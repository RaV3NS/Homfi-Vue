<template>
    <div>
        <div class="search-settings_header">
            <h1 class="search_title search_title-wrap">Оренда квартир, Київ - {{ advertsCount }} об'яв</h1>
            <div class="search-bar">
                <v-select
                    @search="onCitySearch"
                    label="name"
                    :filterable="false"
                    :options="cities"
                    v-model="selectedCity"
                    id="citySearch"
                    :placeholder="this.city_placeholder"
                    class="autocomplete"
                >

                    <template slot="no-options">
                        Начните вводить название...
                    </template>
                    <template slot="option" slot-scope="option">
                        <div class="d-center">
                            {{ option.name_ru }}
                        </div>
                    </template>
                    <template slot="selected-option" slot-scope="option">
                        <img class="autocomplete__search-icon" src="/icons/search.svg" decoding="async" alt="Search Icon">
                        <div class="selected d-center">
                            {{ option.name_ru }}
                        </div>
                    </template>
                </v-select>

                <v-select
                    @search="onStreetSearch"
                    label="name"
                    :filterable="false"
                    :options="streets"
                    v-model="selectedStreet"
                    id="streetFilter"
                    :placeholder="this.street_placeholder"
                    :disabled="street_active"
                    class="autocomplete"
                >

                    <template slot="no-options">
                        Начните вводить название...
                    </template>
                    <template slot="option" slot-scope="option">
                        <div class="d-center">
                            {{ option.name_ru }}
                        </div>
                    </template>
                    <template slot="selected-option" slot-scope="option">
                        <img class="autocomplete autocomplete__search-icon" src="/icons/search.svg" decoding="async" alt="Search Icon">
                        <div class="selected d-center">
                            {{ option.name_ru }}
                        </div>
                    </template>
                </v-select>
            </div>

            <div class="filters">
                <FilterButton text="Тип жилья" store_p="type_filter" store_a="setFilterType"></FilterButton>
                <FilterButton text="Кол-во комнат" store_p="room_filter" store_a="setRoomType"></FilterButton>

                <FilterButtonRange text="Цена, грн" store_prop="price_filter"></FilterButtonRange>
                <div>
                    <a href="#" class="btn btn-filter" @click="openModal">Еще фильтры</a>
                </div>
            </div>

            <modal name="more-filters" width="800" height="auto" :adaptive="true">
                <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('more-filters')">
                <div class="wrapper">
                    <span class="more-filters-title">Поиск по ключевым словам</span>
                    <div class="more-filters-field">
                        <input type="text" class="more-filters-input" v-model="altSearch">
                    </div>

                    <div class="grid">
                        <FormRangeFieldFilters name="all_space" label="Общая кухни" unit="м²" />
                        <FormRangeFieldFilters name="kitchen_space" label="Площадь кухни" unit="м²" />
                        <FormRangeFieldFilters name="living_space" label="Жилая площадь" unit="м²" />

                        <FormRangeFieldFilters name="build_year" label="Год постройки" unit="г." />
                        <FormRangeFieldFilters name="wall_height" label="Высота потолков" unit="м" />

                        <div class="item">
                            <div class="subitem">
                                <span class="more-filters-title" style="margin-bottom: 0;">Дата публикации</span>
                                <div class="more-filters-field">
                                    <v-select
                                        :options="this.$store.state.alt_filters.publish_date.options"
                                        v-model="selectedDate"
                                        id="date"
                                        placeholder="Выбрать"
                                    >
                                        <template slot="option" slot-scope="option">
                                            <div class="d-center">
                                                {{ option.name }}
                                            </div>
                                        </template>
                                        <template slot="selected-option" slot-scope="option">
                                            <div class="selected d-center">
                                                {{ option.name }}
                                            </div>
                                        </template>
                                    </v-select>
                                </div>
                            </div>
                        </div>

                        <FormRangeFieldFilters name="floor" label="Этаж" />
                        <FormRangeFieldFilters name="total_floor" label="Этажность" />

                        <div class="item"></div>

                        <CheckboxAltFilter text="Совместная аренда" name="coop"></CheckboxAltFilter>
                        <CheckboxAltFilter text="Не первая сдача" name="nofirst"></CheckboxAltFilter>
                        <CheckboxAltFilter text="Не последняя сдача" name="nolast"></CheckboxAltFilter>
                    </div>

                    <hr class="modal_hr">

                    <div class="bottom">
                        <a href="#" class="link link-info" @click="resetAltFilters">Сбросить</a>
                        <a href="#" class="btn btn-primary">Продолжить</a>
                    </div>
                </div>
            </modal>
        </div>
    </div>
</template>

<script>

import FilterButton from "../Partials/Filters/FilterButton";
import FilterButtonRange from "../Partials/Filters/FilterButtonRange";
import FormRangeFieldFilters from "../Partials/Filters/FormRangeFieldFilters";
import CheckboxAltFilter from "../Partials/Forms/CheckboxAltFilter";

export default {
    components: { FilterButton, FilterButtonRange, FormRangeFieldFilters, CheckboxAltFilter },
    data: function () {
        return {
            cities: [],
            selectedCity: null,
            fetch_city_url: "http://localhost:8000/api/geo/cities?query=",
            city_placeholder: "Город, область",

            streets: [],
            selectedStreet: null,
            fetch_street_url: "http://localhost:8000/api/geo/search?city_id=9744&query=",
            street_active: false,
            street_placeholder: "Район, метро, улица",

            type_data: null,
        }
    },
    mounted() {
        this.selectedCity = JSON.parse(this.$parent.city);
        if (this.selectedCity)
            this.fetch_street_url = "http://localhost:8000/api/geo/search?city_id=" + this.selectedCity.id + "&query=";
        else
            this.street_active = false;

        this.type_data = this.$store.state.type_filter;
    },
    methods: {
        onCitySearch(search, loading) {
            loading(true);
            this.search(loading, search, this, this.fetch_city_url, 'city');
        },
        onStreetSearch(search, loading) {
            loading(true);
            this.search(loading, search, this, this.fetch_street_url, 'street');
        },
        search: _.debounce((loading, search, vm, url, type) => {
            if (search !== '' && search !== undefined) {
                axios.get(url + search)
                    .then((response) => {
                        type === 'city' ? vm.cities = response.data : vm.streets = response.data;
                        loading(false);
                    })
            } else {
                loading(false);
            }

        }, 350),
        openModal() {
            this.$modal.show('more-filters');
        },
        resetAltFilters() {
            let data = this.$store.state.alt_filters;
            Object.entries(data).map((el) => {
                if (el[1].value)
                    this.$store.dispatch("setAltFilter", { prop: el[0], field: "value", value: null });

                if (el[1].from) {
                    this.$store.dispatch("setAltFilter", { prop: el[0], field: "from", value: null });
                    this.$store.dispatch("setAltFilter", { prop: el[0], field: "to", value: null });
                }
            })
        }
    },
    props: ['advertsCount'],
    computed: {
        selectedDate: {
            get() {
                return this.$store.state.alt_filters.publish_date.value
            },
            set(value) {
                this.$store.dispatch("setAltFilter", { prop: "publish_date", field: "value", value: value });
            }
        },
        altSearch: {
            get() {
                return this.$store.state.alt_filters.search.value
            },
            set(value) {
                this.$store.dispatch("setAltFilter", { prop: "search", field: "value", value: value });
            }
        },
    }
}
</script>


<style lang="scss" scoped>
    @import '../../../sass/header.scss';
    @import '../../../sass/base.scss';

    .bottom {
        display: flex;
        justify-content: space-between;
    }

    .modal_hr {
        margin: 30px 0;
        border: none;
        border-top: 1px solid var(--gray-300);
    }

    .close_modal_btn {
        margin-left: auto;
        padding: 1rem;
        padding-bottom: 0;
        cursor: pointer;
    }

    .wrapper {
        padding: 2rem;
        padding-top: 0;
    }

    .more-filters-input, .more-filters-input:focus {
        height: 44px;
        padding: 0 12px 0 20px;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        font-weight: 300;
        font-size: 16px;
        line-height: 20px;
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        color: var(--blue-900);
        outline-color: var(--gray-300);
        width: 100%;
    }

    .more-filters-title {
        color: var(--blue-900);
        font-size: 16px;
        line-height: 22px;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
    }

    input {
        -webkit-writing-mode: horizontal-tb !important;
        text-rendering: auto;
        color: -internal-light-dark(black, white);
        letter-spacing: normal;
        word-spacing: normal;
        text-transform: none;
        text-indent: 0px;
        text-shadow: none;
        display: inline-block;
        text-align: start;
        appearance: textfield;
        background-color: -internal-light-dark(rgb(255, 255, 255), rgb(59, 59, 59));
        -webkit-rtl-ordering: logical;
        cursor: text;
        margin: 0em;
        font: 400 13.3333px Arial;
        padding: 1px 2px;
        border-width: 2px;
        border-style: inset;
        border-color: -internal-light-dark(rgb(118, 118, 118), rgb(195, 195, 195));
        border-image: initial;
    }

    .btn-filter {
        --border-radius: 6px;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 1px solid var(--gray-300);
        background-color: var(--white);
        text-align: left;
        height: 45px;
        color: var(--greyish-blue);
        width: fit-content;
        padding: 0.6rem 1.2rem;
        justify-content: center;
        margin: 0.5rem 0;
        min-width: 144px;
        margin-left: 0.5rem;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(3,1fr);
        grid-column-gap: 20px;
        grid-row-gap: 30px;
        margin-bottom: 45px;
        margin-top: 2rem;
    }
</style>


