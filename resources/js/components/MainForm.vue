<template>
    <div>
        <header-vue></header-vue>
        <div class="main-block">
            <img src="/images/main_top-bg.jpg" alt="background" class="section-background">
            <div class="main-block-container">
                <h1>Аренда недвижимости в Украине</h1>
                <p class="main-text mb-20">Огромный выбор долгосрочной аренды жилья без посредников</p>
                <div class="filters mt-5" v-if="windowWidth > 768">
                    <div class="filters-top">
                        <v-autocomplete
                            solo
                            v-model="selectedCity"
                            placeholder="Город, область"
                            :items="cities"
                            :loading="citiesLoading"
                            :search-input.sync="citySearch"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-autocomplete city-filter"
                            :class="{'has-error': cityError}"
                            return-object
                            clearable
                        >
                            <template slot="prepend-inner">
                                <img src="/icons/search.svg" alt="search" class="autocomplete-search-icon">
                            </template>

                            <template slot="item" slot-scope="data">
                                <div class="option-content" :class="{'option-content-low': !data.item.count}">
                                <span class="option-header">
                                        <img src="/icons/geo-tag.svg" alt="geo" style="margin-right: 12px;">
                                        <span>{{ data.item.text }}</span>
                                    </span>
                                    <b class="option-bold" v-if="data.item.count">{{ data.item.count }} объявлений</b>
                                </div>
                            </template>
                        </v-autocomplete>

                        <v-autocomplete
                            solo
                            v-model="selectedStreet"
                            placeholder="Район, метро, улица"
                            :items="streets"
                            :loading="streetLoading"
                            :search-input.sync="streetSearch"
                            :disabled="streetDisabled"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-autocomplete street-filter"
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
                    <div class="filters-bottom">
                            <FilterButtonMain text="Тип жилья" store_p="type_filter" store_a="setFilterType" :mobile="false"></FilterButtonMain>
                            <FilterButtonMain text="Кол-во комнат" store_p="room_filter" store_a="setRoomType" :mobile="false"></FilterButtonMain>
                            <FilterButtonRangeMain text="Цена, грн" store_prop="price_filter" :mobile="false"></FilterButtonRangeMain>

                        <button class="btn-apply" @click="applyFilters">Применить</button>
                    </div>
                </div>
                <div class="filters-mobile" v-else>
                    <v-autocomplete
                            solo
                            v-model="selectedCity"
                            placeholder="Город, область"
                            :items="cities"
                            :loading="citiesLoading"
                            :search-input.sync="citySearch"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-autocomplete city-filter"
                            :class="{'has-error': cityError}"
                            return-object
                            clearable
                        >
                            <template slot="prepend-inner">
                                <img src="/icons/search.svg" alt="search" class="autocomplete-search-icon">
                            </template>

                            <template slot="item" slot-scope="data">
                                <div class="option-content" :class="{'option-content-low': !data.item.count}">
                                <span class="option-header">
                                        <img src="/icons/geo-tag.svg" alt="geo" style="margin-right: 12px;">
                                        <span>{{ data.item.text }}</span>
                                    </span>
                                    <b class="option-bold" v-if="data.item.count">{{ data.item.count }} объявлений</b>
                                </div>
                            </template>
                        </v-autocomplete>

                        <v-autocomplete
                            solo
                            v-model="selectedStreet"
                            placeholder="Район, метро, улица"
                            :items="streets"
                            :loading="streetLoading"
                            :search-input.sync="streetSearch"
                            :disabled="streetDisabled"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-autocomplete street-filter"
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

                        <FilterButtonMain text="Тип жилья" store_p="type_filter" store_a="setFilterType" :mobile="true" class="flat-filter"></FilterButtonMain>
                        <FilterButtonMain text="Кол-во комнат" store_p="room_filter" store_a="setRoomType" :mobile="true"></FilterButtonMain>
                        <FilterButtonRangeMain text="Цена, грн" store_prop="price_filter" :mobile="true"></FilterButtonRangeMain>

                        <button class="btn-apply btn-apply-mobile" @click="applyFilters">Применить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import FilterButtonMain from './Partials/Filters/FilterButtonMain';
    import FilterButtonRangeMain from './Partials/Filters/FilterButtonRangeMain';

    export default {
        components: { FilterButtonMain, FilterButtonRangeMain },
        data: function() {
            return {
                selectedCity: null,
                cities: [],
                citiesLoading: false,
                citySearch: null,
                cityError: false,

                selectedStreet: null,
                streets: [],
                streetLoading: false,
                streetSearch: null,
                streetDisabled: true,
                windowWidth: window.innerWidth,
            }
        },
        mounted() {
            this.getMainCities();
            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            });
        },
        methods: {
            onResize() {
                this.windowWidth = window.innerWidth;
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
                            if (category[1] && category[1].length > 0)
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
            getMainCities() {
                axios.get(window.backend_url + 'api/geo/main-cities').then((response) => {
                    let data = response.data;
                    data.map((el) => {
                        this.cities.push({
                            text: el.name_ru,
                            value: el.id,
                            slug: el.translit
                        })
                    });
                });
            },
            applyFilters() {
                if (!this.selectedCity) {
                    this.cityError = true;
                     return false;
                }

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

                let get_params = new URLSearchParams();

                if (this.$store.state.price_filter.from)
                    get_params.set('pricemonth_min', this.$store.state.price_filter.from);
                if (this.$store.state.price_filter.to)
                    get_params.set('pricemonth_max', this.$store.state.price_filter.to);

                let flat_query = all_filters.length > 0 ? "-" + all_filters.join('-') : '';
                let params_string = get_params.toString().length > 0 ? '?' + get_params.toString() : '';

                window.location = base_url + flat_query + params_string;
            },
        },
        watch: {
            selectedCity: function(value) {
                if (value)
                    this.cityError = false;

                this.streetDisabled = false;
                
                if (this.selectedStreet) {
                    this.selectedStreet = null;
                    this.streets = [];
                }    
            },
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
        }
    }
</script>

<style lang="scss" scoped>

    .button-group {
        background-color: var(--white);
        border-radius: .375rem;
        border: 1px solid var(--gray-300);
        margin-bottom: 1.25rem;
    }

    .btn-apply {
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border: none;
        border-radius: .375rem;
        padding: var(--btn-padding,9px 1.25rem);
        font-family: inherit;
        font-size: var(--btn-font-size,1rem);
        font-weight: var(--btn-font-weight,400);
        line-height: var(--btn-line-height,1.375rem);
        text-decoration: none;
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);

        --btn-background-color: var(--blue-400);
        --btn-background-color-hover: var(--blue-600);
        --btn-color: var(--white);

        margin-left: .5rem;
    }

    .filter-toggle {
        width: 100%;
        height: 100%;
        padding: 0 1.25rem;
        background-color: transparent;
        border: none;
        color: var(--greyish-blue);
        text-align: left;
        outline: none;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        cursor: pointer;
        border-radius: var(--border-radius);
        -webkit-transition: background-color .2s ease;
        transition: background-color .2s ease;
        will-change: background-color;
    }

    .filter-button {
        --border-radius: 6px;
        position: relative;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 1px solid var(--gray-300);
        background-color: var(--white);
        text-align: left;
        min-width: auto;
        height: 100%;
        color: #000;
    }

    .filters-bottom {
        display: grid;
        grid-template-columns: repeat(4,1fr);
        margin-top: -10px;
        height: 4.125rem;
    }

    .filters .filters-top {
        display: grid;
        grid-template-columns: repeat(4,1fr);
    }

    .filters {
        font-weight: 400;
    }

    .main-block-container {
        width: 100%;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
        padding: 1.875rem 1.25rem;
        color: var(--white);
        position: relative;
        z-index: 2;
        height: 100%;
    }

    .main-block {
        height: -webkit-calc(100vh - var(--header-height));
        height: calc(100vh - var(--header-height));
        position: relative;
        z-index: 10;
        background-color: #000;
        text-align: center;
    }

    .main-block-container h1 {
        font-size: 1.625rem;
        line-height: 1.875rem;
        margin-bottom: 1rem;
        font-weight: 400;
    }

    .section-background {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 1;
        opacity: .7;
        object-fit: cover;
        display: block;
    }

    .main-text {
        line-height: 1.25rem;
        color: #fff;
    }

    .filters-top .city-filter {
        grid-column: 1/3;
    }

    .filters-top .street-filter {
        grid-column: 3/-1;
    }

    @media only screen and (min-width: 768px) {
        .main-block {
            display: -webkit-flex;
            display: -moz-box;
            display: flex;
            -webkit-flex-direction: column;
            -moz-box-orient: vertical;
            -moz-box-direction: normal;
            flex-direction: column;
            height: 25rem;
        }

        .main-block-container {
            padding: 3.75rem 0.5rem;
        }

        .main-block-container h1 {
            margin-bottom: 1.25rem;
        }

        .filter-button:not(:first-of-type) {
            border-left: none;
        }

        .filter-button:first-of-type {
            border-top-left-radius: var(--border-radius);
            border-bottom-left-radius: var(--border-radius);
        }

        .filter-button:last-of-type {
            border-top-right-radius: var(--border-radius);
            border-bottom-right-radius: var(--border-radius);
        }
    }


    @media only screen and (min-width: 1024px) {
        .filters-top .city-filter {
            grid-column: 1/1;
        }

        .filters-top .street-filter {
            grid-column: 2/-1;
        }
    }

    @media only screen and (max-width: 768px) {
        .flat-filter, .street-filter {
            margin-top: -2rem;
        }

        .btn-apply-mobile {
            width: 100%;
            margin: 0;
            margin-top: 2rem;
            height: 55px;
        }
    }

</style>
