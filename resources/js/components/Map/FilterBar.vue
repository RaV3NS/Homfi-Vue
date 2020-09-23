<template>
    <div>
        <div class="search-settings_header">
            <div class="title_wrap">
                <h1 class="search_title search_title-wrap">Оренда квартир, Київ - {{ advertsCount }} об'яв</h1>
                <button type="button" class="group_sort" @click="sortModalActive = !sortModalActive">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.72374 7.79066C5.11426 8.18119 5.74743 8.18119 6.13795 7.79066L8.04259 5.88602V21.5806C8.04259 22.1328 8.49031 22.5806 9.04259 22.5806H9.06642C9.6187 22.5806 10.0664 22.1328 10.0664 21.5806V1L4.70705 6.35982C4.31655 6.75035 4.31656 7.38349 4.70708 7.774L4.72374 7.79066Z" fill="#0C2455"></path><path d="M19.1654 16.2093C18.7748 15.8188 18.1417 15.8188 17.7512 16.2093L15.8465 18.114V2.41944C15.8465 1.86715 15.3988 1.41943 14.8465 1.41943H14.8227C14.2704 1.41943 13.8227 1.86715 13.8227 2.41943V23L19.1821 17.6402C19.5726 17.2497 19.5725 16.6165 19.182 16.226L19.1654 16.2093Z" fill="#0C2455"></path></svg>
                </button>

                <div class="sort-modal" v-click-outside="console.log(1)" v-if="sortModalActive">
                    <div id="highest_price" class="group_sort__item">От дорогих к дешевым</div>
                    <div id="lowest_price" class="group_sort__item">От дешевых к дорогим</div>
                    <div id="newest" class="group_sort__item">Сначала самые новые</div>
                </div>
            </div>
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
                <FilterButton text="Тип жилья" store_p="type_filter" store_a="setFilterType" v-on:setFilters="updateFlatFilters" ref="typeFilter"></FilterButton>
                <FilterButton text="Кол-во комнат" store_p="room_filter" store_a="setRoomType" v-on:setFilters="updateFlatFilters" ref="roomFilter"></FilterButton>

                <FilterButtonRange text="Цена, грн" store_prop="price_filter" v-on:filterChange="handleFilterChange"></FilterButtonRange>

                <div>
                    <a href="#" class="btn btn-filter" v-on:click.stop.prevent="openModal">Еще фильтры</a>
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
                        <FormRangeFieldFilters name="all_space" label="Общая площадь" unit="м²" v-on:filterChange="handleFilterChange" />
                        <FormRangeFieldFilters name="kitchen_space" label="Площадь кухни" unit="м²" v-on:filterChange="handleFilterChange" />
                        <FormRangeFieldFilters name="living_space" label="Жилая площадь" unit="м²" v-on:filterChange="handleFilterChange" />

                        <FormRangeFieldFilters name="build_year" label="Год постройки" unit="г." v-on:filterChange="handleFilterChange" />
                        <FormRangeFieldFilters name="wall_height" label="Высота потолков" unit="м" v-on:filterChange="handleFilterChange" />

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
                        <CheckboxAltFilter text="Не первый этаж" name="nofirst"></CheckboxAltFilter>
                        <CheckboxAltFilter text="Не последний этаж" name="nolast"></CheckboxAltFilter>
                    </div>

                    <hr class="modal_hr">

                    <div class="bottom">
                        <a href="#" class="link link-info" v-on:click.stop.prevent="resetAltFilters">Сбросить</a>
                        <a href="#" class="btn btn-primary" v-on:click.stop.prevent="setAltFilters">Продолжить</a>
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
import ClickOutside from 'vue-click-outside';

export default {
    components: { FilterButton, FilterButtonRange, FormRangeFieldFilters, CheckboxAltFilter },
    data: function () {
        return {
            cities: [],
            selectedCity: null,
            fetch_city_url: "http://localhost:8000/api/geo/cities?query=",
            city_placeholder: "Город, область",
            sortModalActive: false,

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

                if (el[1].from || el[1].to) {
                    this.$store.dispatch("setAltFilter", { prop: el[0], field: "from", value: null });
                    this.$store.dispatch("setAltFilter", { prop: el[0], field: "to", value: null });
                }
            })
        },
        handleFilterChange(payload) {
            let data = [];

            if (payload.prop === "price_filter") {
                data.push({ prop: 'pricemonth_max', value: payload.data.to });
                data.push({ prop: 'pricemonth_min', value: payload.data.from });

                this.$emit("updateQuery", { data: data });
            }
        },
        setAltFilters() {
            let altFilters = this.$store.state.alt_filters;
            let data = [];

            console.log(this.$store.state.alt_filters);

            let aliases = {
               'search': { type: 'value', name: 'query' },
               'all_space': { type: 'min-max', name: 'total_space' },
               'kitchen_space': { type: 'min-max', name: 'kitchen_space' },
               'living_space': { type: 'min-max', name: 'living_space' },
               'build_year': { type: 'min-max', name: 'build_year' },
               'wall_height': { type: 'min-max', name: 'height' },
               'publish_date': { type: 'select', name: 'publish_date' },
               'floor': { type: 'min-max', name: 'floor' },
               'total_floor': { type: 'min-max', name: 'total_floors' },
               'coop': { type: 'value', name: 'joint_rent' },
               'nofirst': { type: 'value', name: 'not_first_floor' },
               'nolast': { type: 'value', name: 'not_last_floor' },
            };

            Object.entries(altFilters).map((el) => {
                let alias = aliases[el[0]];
                if (alias) {
                    if (alias.type === 'min-max') {
                       data.push({ prop: alias.name + '_min', value: el[1].from });
                       data.push({ prop: alias.name + '_max', value: el[1].to });
                    }

                    if (alias.type === 'select') {
                        if (el[1].value)
                            data.push({ prop: alias.name, value: el[1].value.value });
                        else
                            data.push({ prop: alias.name, value: null });
                    }

                    if (alias.type === 'value') {
                        data.push({ prop: alias.name, value: el[1].value });
                    }
                }
            });

            this.$emit("updateQuery", { data: data });
        },
        updateFlatFilters() {
            let fullUrl = window.location.href.split("?");
            let url = fullUrl[0];

            let params = url.split("-");
            let base_url = params[0];
            let types = this.$store.state.type_filter;
            let all_filters = [];

            let flat_filter = [];
            types.map((el) => {
               if (el.checked)
                   flat_filter.push(el.slug);
            });

            let rooms = this.$store.state.room_filter;

            let room_filter = [];
            rooms.map((el) => {
                if (el.checked)
                    room_filter.push(el.slug);
            });

            if (flat_filter.length > 0)
                all_filters.push(flat_filter.join(","));

            if (room_filter.length > 0)
                all_filters.push(room_filter.join(","));

            let get_params = fullUrl[1] ? "?" + fullUrl[1] : "";
            let flat_query = all_filters.length > 0 ? "-" + all_filters.join('-') : '';

            window.location = base_url + flat_query + get_params;
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
    },
    directives: {
        ClickOutside
    }
}
</script>


<style lang="scss" scoped>
    @import '../../../sass/header.scss';
    @import '../../../sass/base.scss';


    .sort-modal {
        position: absolute;
        right: 10px;
        top: 56px;
        min-width: 240px;
        padding-top: 20px;
        padding-bottom: 20px;
        max-height: -webkit-calc(100vh - 152px);
        max-height: calc(100vh - 152px);
        z-index: 10;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 1px solid #d5dbe5;
        box-shadow: 0 0 20px rgba(100,110,148,.2);
        border-radius: 6px;
        background: var(--white);
    }

    .group_sort__item {
        padding: 6px 20px;
        color: var(--greyish-blue);
        cursor: pointer;
    }

    .title_wrap {
        display: flex;
        justify-content: space-between;
    }

    .group_sort {
        display: none;
        font-weight: 400;
        grid-row-start: 3;

        background: none;
        border: none;
    }

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

    .search-settings_header {
        padding-bottom: 0;
    }

    @media only screen and (max-width: 1280px) {
        .group_sort {
            display: block;
        }
    }
</style>


