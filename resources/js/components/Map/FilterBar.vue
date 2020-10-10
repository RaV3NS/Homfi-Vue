<template>
    <div>
        <div class="search-settings_header">
            <div class="title_wrap">
                <h1 class="search_title search_title-wrap">{{ h1 || 'Аренда квартир' }}</h1>

                <button type="button" class="group_sort" @click="showSortModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4.72374 7.79066C5.11426 8.18119 5.74743 8.18119 6.13795 7.79066L8.04259 5.88602V21.5806C8.04259 22.1328 8.49031 22.5806 9.04259 22.5806H9.06642C9.6187 22.5806 10.0664 22.1328 10.0664 21.5806V1L4.70705 6.35982C4.31655 6.75035 4.31656 7.38349 4.70708 7.774L4.72374 7.79066Z" fill="#0C2455"></path><path d="M19.1654 16.2093C18.7748 15.8188 18.1417 15.8188 17.7512 16.2093L15.8465 18.114V2.41944C15.8465 1.86715 15.3988 1.41943 14.8465 1.41943H14.8227C14.2704 1.41943 13.8227 1.86715 13.8227 2.41943V23L19.1821 17.6402C19.5726 17.2497 19.5725 16.6165 19.182 16.226L19.1654 16.2093Z" fill="#0C2455"></path></svg>
                </button>

                <div class="sort-modal" v-if="sortModalActive" v-click-outside="hideModal">
                    <div id="highest_price" class="group_sort__item" @click="$parent.sortBy('highest_price')">От дорогих к дешевым</div>
                    <div id="lowest_price" class="group_sort__item" @click="$parent.sortBy('lowest_price')">От дешевых к дорогим</div>
                    <div id="newest" class="group_sort__item" @click="$parent.sortBy('newest')">Сначала самые новые</div>
                </div>
            </div>
            <div class="search-bar">
                <v-autocomplete
                    dense
                    outlined
                    v-model="selectedCity"
                    :placeholder="this.city_placeholder"
                    :items="cities"
                    :loading="citiesLoading"
                    :search-input.sync="citySearch"
                    no-data-text="Мы не нашли результатов по вашему запросу"
                    class="advert-input-city"
                    return-object
                >
                    <template slot="prepend-inner">
                        <img src="/icons/search.svg" alt="search" class="autocomplete-search-icon">
                    </template>

                    <template slot="item" slot-scope="data">
                        <div class="option-content">
                           <span class="option-header">
                                <img src="/icons/geo-tag.svg" alt="geo" style="margin-right: 12px;">
                                <span>{{ data.item.text }}</span>
                            </span>
                            <b class="option-bold">{{ data.item.count }} объявлений</b>
                        </div>
                    </template>
                </v-autocomplete>

                <v-autocomplete
                    dense
                    outlined
                    v-model="selectedStreet"
                    :placeholder="this.street_placeholder"
                    :items="streets"
                    :loading="streetLoading"
                    :search-input.sync="streetSearch"
                    no-data-text="Мы не нашли результатов по вашему запросу"
                    class="advert-input-street"
                    return-object
                    clearable
                >
                    <template slot="prepend-inner">
                        <img src="/icons/search.svg" alt="search" class="autocomplete-search-icon">
                    </template>

                    <template slot="item" slot-scope="data">
                        <div class="option-content">
                           <span class="option-header">
                                <img src="/icons/geo-tag.svg" alt="geo" style="margin-right: 12px;">
                                <span>{{ data.item.text }}</span>
                            </span>
                            <b class="option-bold">{{ data.item.count }} объявлений</b>
                        </div>
                    </template>
                </v-autocomplete>
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
                <div class="modal-header">
                    <p class="mb-0">Расширенные фильтры</p>
                    <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('more-filters')">
                </div>
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
                                        v-model="selectedDate"
                                        :items="this.$store.state.alt_filters.publish_date.options"
                                        outlined
                                        dense
                                        placeholder="Выбрать"
                                        class="date-select"
                                        clearable
                                    ></v-select>
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
            <modal name="more-filters-map" width="800" height="auto" :adaptive="true" @before-close="morefiltersClose">
                <div class="modal-header">
                    <p class="mb-0">Расширенные фильтры</p>
                    <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('more-filters-map')">
                </div>
                <div class="wrapper">
                    <div class="flat-filters">
                        <FilterToggle text="Тип жилья" store_p="type_filter" store_a="setFilterType" v-on:setFilters="updateFlatFilters" ref="typeFilterToggle"></FilterToggle>
                        <FilterToggle text="Кол-во комнат" store_p="room_filter" store_a="setRoomType" v-on:setFilters="updateFlatFilters" ref="roomFilterToggle"></FilterToggle>
                        <FilterToggleRange text="Цена, грн" store_prop="price_filter" v-on:filterChange="handleFilterChange" ref="priceFilterToggle"></FilterToggleRange>
                    </div>

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
                                        v-model="selectedDate"
                                        :items="this.$store.state.alt_filters.publish_date.options"
                                        outlined
                                        dense
                                        placeholder="Выбрать"
                                        class="date-select"
                                        clearable
                                    ></v-select>
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
                        <a href="#" class="link link-info" v-on:click.stop.prevent="resetAllFilters">Сбросить</a>
                        <a href="#" class="btn btn-primary" v-on:click.stop.prevent="setAllFilters">Продолжить</a>
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
import FilterToggle from "../Partials/Filters/FilterToggle";
import FilterToggleRange from "../Partials/Filters/FilterToggleRange";

export default {
    components: { FilterToggleRange, FilterToggle, FilterButton, FilterButtonRange, FormRangeFieldFilters, CheckboxAltFilter },
    data: function () {
        return {
            cities: [],
            selectedCity: null,
            city_placeholder: "Город, область",
            sortModalActive: false,

            streets: [],
            selectedStreet: null,
            street_active: false,
            street_placeholder: "Район, метро, улица",

            type_data: null,
            citiesLoading: false,
            citySearch: null,
            streetSearch: null,
            streetLoading: false,
            streetUrlValue: null,
            cityFromUrl: null,
            h1: null,
            windowWidth: window.innerWidth,
        }
    },
    mounted() {
        this.type_data = this.$store.state.type_filter;
        this.$nextTick(() => {
            window.addEventListener('resize', this.onResize);
        })
    },
    methods: {
        onResize() {
            this.windowWidth = window.innerWidth;
        },
        showSortModal() {
            this.sortModalActive = true;
        },
        hideModal() {
            if (this.sortModalActive == true)
               this.sortModalActive = false;
        },
        search: _.debounce((vm, url, param, param_value) => {
            axios.get(url)
                .then((response) => {
                    let data = response.data;

                    if (vm[param_value])
                        vm[param].push(vm[param_value])
                    else
                        vm[param] = [];

                    data.map((el) => {
                        vm[param].push({
                            text: el.name_ru,
                            value: el.id,
                            slug: el.translit,
                            count: el.adverts_count
                        });
                    })

                    vm[param + 'Loading'] = false;
                })
        }, 350),
        searchStreets: _.debounce((vm, url, param, param_value) => {
            axios.get(url)
                .then((response) => {
                    let data = response.data;

                    if (vm[param_value])
                        vm[param].push(vm[param_value])
                    else
                        vm[param] = [];

                    Object.entries(data).reverse().map((category) => {
                        if (category[1].length > 0)
                            category[1].map((el) => {
                                vm[param].push({
                                    text: el.name_ru,
                                    value: el.translit,
                                    count: el.advert_count
                                });
                            });
                    });

                    vm['streetLoading'] = false;
                })
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
        morefiltersClose() {
           if (this.$parent.$refs.map.moreFiltersModal)
               this.$parent.$refs.map.closeModal();
        },
        setAltFilters(redirect = true) {
            let altFilters = this.$store.state.alt_filters;
            let data = [];

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
                            data.push({ prop: alias.name, value: el[1].value });
                        else
                            data.push({ prop: alias.name, value: null });
                    }

                    if (alias.type === 'value') {
                        data.push({ prop: alias.name, value: el[1].value });
                    }
                }
            });

            if (redirect)
                this.$emit("updateQuery", { data: data });
            else
                return data;
        },
        updateCity(city, count) {
            this.cityFromUrl = city.translit;
            this.cities.push({ text: city.name_ru, value: city.id, slug: city.translit, count: count });
            this.selectedCity = { text: city.name_ru, value: city.id, slug: city.translit, count: count };
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

            if (this.selectedStreet)
                all_filters.push(this.selectedStreet.value);

            let city_url = fullUrl[0].split("/");
            city_url[3] = this.selectedCity.slug;
            city_url = city_url.join("/");

            base_url = city_url;

            let get_params = fullUrl[1] ? "?" + fullUrl[1] : "";
            let flat_query = all_filters.length > 0 ? "-" + all_filters.join('-') : '';
            let getParams = new URLSearchParams(get_params);
            getParams.delete("page");

            window.location = base_url + flat_query + getParams.toString();
        },
        resetAllFilters() {
           this.resetAltFilters();
           this.$refs.typeFilterToggle.handleRefresh();
           this.$refs.roomFilterToggle.handleRefresh();
           this.$refs.priceFilterToggle.handleRefresh();
        },
        setAllFilters() {
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

            if (this.selectedStreet)
                all_filters.push(this.selectedStreet.value);

            let city_url = fullUrl[0].split("/");
            city_url[3] = this.selectedCity.slug;
            city_url = city_url.join("/");

            base_url = city_url;

            let get_params = fullUrl[1] ? "?" + fullUrl[1] : "";
            let flat_query = all_filters.length > 0 ? "-" + all_filters.join('-') : '';
            let getParams = new URLSearchParams(get_params);
            getParams.delete("page");

            // Alt Filters Set
            let data = this.setAltFilters(false);
            let request = this.$parent.updateQuery({ data: data }, false);

            window.location = base_url + flat_query + request;
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
    watch: {
        citySearch: function(value) {
            this.citiesLoading = true;
            let url = window.backend_url + 'api/geo/cities?query=' + encodeURI(value);
            if (value && value.trim() !== '')
                this.search(this, url, 'cities', 'selectedCity');
            else
                this.citiesLoading = false;
        },
        streetSearch: function(value) {
            this.streetLoading = true;
            let url = window.backend_url + `api/geo/search?city_id=${this.selectedCity.value}&query=` + encodeURI(value);
            if (value && value.trim() !== '')
                this.searchStreets(this, url, 'streets', 'selectedStreet');
            else
                this.streetLoading = false;
        },
        selectedStreet: function(obj, oldObj) {
            if (oldObj && !obj)
                this.updateFlatFilters();
            if (obj.value !== this.streetUrlValue)
                this.updateFlatFilters();
        },
        selectedCity: function (value) {
            if (value && this.cityFromUrl !== value.slug)
                this.updateFlatFilters();
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

    .modal-header p {
        font-weight: 400;
        font-size: 20px;
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
    }

    .flat-filters {
        margin: 1rem 0;
    }

    .option-header {
        display: flex;
        font-family: Rubik;
        color: rgb(12, 36, 85);
    }

    .option-bold {
        font-weight: 600;
        font-family: Rubik;
        margin-left: 23px;
        margin-top: 3px;
        display: block;
        color: rgb(12, 36, 85);
    }

    .autocomplete-search-icon {
        position: relative;
        top: -1px;
        margin-right: 5px;
    }

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
        font-weight: 400;
        font-size: 16px;
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
        align-items: center;
    }

    .modal_hr {
        margin: 30px 0;
        border: none;
        border-top: 1px solid var(--gray-300);
    }

    .close_modal_btn {
        cursor: pointer;
        padding: 0;
        margin: 0;
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

    @media only screen and (max-width: 652px) {
        .filters > div {
            margin-right: 0.6rem;
        }

        .btn-filter {
            height: 43px !important;
        }
    }

    @media only screen and (max-width: 1280px) {
        .group_sort {
            display: block;
        }
    }
</style>


