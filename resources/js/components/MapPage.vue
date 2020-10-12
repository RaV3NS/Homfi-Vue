<template>
    <div>
        <pageloader v-if="loading"></pageloader>
        <header-vue ref="header"></header-vue>
        <filter-bar-map :advertsCount="advertsCount" v-on:updateQuery="updateQuery" ref="bar"></filter-bar-map>
        <div class="filter-bar-footer">
            <div class="sort">
                <span class="sort-title">Сортировать</span>
                <div>
                    <v-menu
                        offset-y
                        transition="scale-transition"
                    >
                        <template v-slot:activator="{ on }">
                            <a class="sort-label" v-on="on">{{ sortType }}</a>
                            <svg fill="#0C2455" width="12" height="7.02" class="arrow"><use xlink:href="/icons/sprite.svg#arrow"></use></svg>
                        </template>

                        <v-card width="300px">
                            <v-list>
                                <v-list-item-group color="primary">
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title @click="sortBy('highest_price')" v-text="'От дорогих к дешевым'"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title @click="sortBy('lowest_price')" v-text="'От дешевых к дорогим'"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                    <v-list-item>
                                        <v-list-item-content>
                                            <v-list-item-title @click="sortBy('newest')" v-text="'Сначала самые новые'"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-card>
                    </v-menu>
                </div>
            </div>

            <div class="map-toggler">
                <span style="margin-right: 0.5rem">Показать карту</span>
                <toggle-button v-model="mapToggle" color="var(--blue-400)" :labels="false"/>
            </div>
        </div>

        <div class="flex map_cont map-mobile" v-bind:class="{'map_toggle': !mapToggle}" v-if="pageAdvertsCount > 0">
            <div class="left" id="left">
                <div class="list disable-scrollbars">
                    <Advert
                        v-for="advert in adverts"
                        :advert="advert"
                        :key="advert.id"
                    />

                    <v-pagination
                        class="pagination"
                        v-model="page"
                        :length="pages"
                        circle
                    ></v-pagination>
                </div>
            </div>

            <vue-map :_markers="this.mapAdverts" :city="JSON.parse(this.city)" ref="map"></vue-map>
        </div>
        <div v-else>
            <hr>

            <p class="apartments_not_found">
                На жаль, Ваш запит не дав результатів. Змініть параметри пошуку і спробуйте ще раз
                <a @click="resetAllFilters" class="filter-reset-link">Скинути всі фільтри</a>
            </p>
        </div>

        <a href="#" class="map_call-mobile" v-on:click.stop.prevent="toggleMap">
            <img src="/icons/pin.png" alt="png">
        </a>
    </div>
</template>

<script>
    import Advert from "./Map/Advert";

    export default {
        components: { Advert },
        data () {
            return {
                page: 1,
                pages: 1,
                mapAdverts: [],
                advertsQuery: [],
                adverts: [],
                advertsCount: 0,
                pageAdvertsCount: 0,
                mapToggle: true,
                windowWidth: window.innerWidth,
                loading: true
            };
        },
        props: ['city'],
        mounted() {
            if (this.city)
                this.$store.dispatch("setCity", JSON.parse(this.city));

            this.getMarkers(null);
            this.getFavourites();

            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })

            let params = new URLSearchParams(window.location.search);

            let aliases = {
                'pricemonth_min': {action: "setFilter", prop: 'price_filter', field: 'from'},
                'pricemonth_max': {action: "setFilter", prop: 'price_filter', field: 'to'},
                'total_space_min': {action: "setAltFilter", prop: 'all_space', field: 'from'},
                'total_space_max': {action: "setAltFilter", prop: 'all_space', field: 'to'},
                'kitchen_space_min': {action: "setAltFilter", prop: 'kitchen_space', field: 'from'},
                'kitchen_space_max': {action: "setAltFilter", prop: 'kitchen_space', field: 'to'},
                'living_space_min': {action: "setAltFilter", prop: 'living_space', field: 'from'},
                'living_space_max': {action: "setAltFilter", prop: 'living_space', field: 'to'},
                'build_year_min': {action: "setAltFilter", prop: 'build_year', field: 'from'},
                'build_year_max': {action: "setAltFilter", prop: 'build_year', field: 'to'},
                'height_min': {action: "setAltFilter", prop: 'wall_height', field: 'from'},
                'height_max': {action: "setAltFilter", prop: 'wall_height', field: 'to'},
                'floor_min': {action: "setAltFilter", prop: 'floor', field: 'from'},
                'floor_max': {action: "setAltFilter", prop: 'floor', field: 'to'},
                'total_floors_min': {action: "setAltFilter", prop: 'total_floor', field: 'from'},
                'total_floors_max': {action: "setAltFilter", prop: 'total_floor', field: 'to'},
                'joint_rent': {action: "setAltFilter", prop: 'coop', field: 'value'},
                'not_first_floor': {action: "setAltFilter", prop: 'nofirst', field: 'value'},
                'not_last_floor': {action: "setAltFilter", prop: 'nolast', field: 'value'},
                'order': { action: "setFilter", prop: 'sortBy', field: 'value' },
                'query': {action: "setAltFilter", prop: 'search', field: 'value'},
            };

            for (var p of params.entries()) {
                let alias = aliases[p[0]];

                if (alias)
                    this.$store.dispatch(alias.action, { prop: alias.prop, field: alias.field, value: p[1] })

                if (p[0] === 'publish_date') {
                    let types = this.$store.state.alt_filters.publish_date.options;
                    let result = Object.entries(types).filter(param => param[1].value === p[1]);
                    this.$store.dispatch("setAltFilter", { prop: "publish_date", field: 'value', value: result[0] })
                }
            }
        },
        computed: {
            sortType: function() {
                switch (this.$store.state.sortBy.value) {
                    case "newest":
                        return "Сначала самые новые";

                    case "lowest_price":
                        return "От дешевых к дорогим";

                    case "highest_price":
                        return "От дорогих к дешевым";
                }
            }
        },
        methods: {
            getFavourites() {
                axios.get(window.backend_url + `api/user/${window.user.id}/favorites/ids`).then((response) => {
                    this.$store.dispatch('setFavourites', response.data);
                });

                this.getAdverts();
            },
            onResize() {
                this.windowWidth = window.innerWidth;
            },
            getMarkers: function(filters) {
                let city = JSON.parse(this.city);
                let filter_query = "&filter=";
                if (filters === null) filter_query = "";
                else filter_query += filters;
                axios.get(window.backend_url + 'api/adverts/coordinates?city_id=' + city.id + filter_query).then((response) => {
                    this.mapAdverts = response.data;

                    // убрать/скорректировать на проде
                    setTimeout(() => {
                        this.loading = false;
                    }, 1000);
                });
            },
            getLatLng: function(object) {
                return [object.lat, object.lng];
            },
            getSlugFilters: function(filters) {
                if (filters.type) {
                    let types = this.$store.state.type_filter;
                    filters.type.map((el) => {
                        let found = types.filter(search => search.prop === el);
                        if (found) {
                            this.$store.dispatch("setFilterType", { id: found[0].id, value: true });
                            this.$refs.bar.$refs.typeFilter.selected.push(found[0].text);
                        }
                    });
                }

                if (filters.room_count) {
                    let rooms = this.$store.state.room_filter;
                    filters.room_count.map((el) => {
                        let found = rooms.filter(search => search.prop === el);
                        if (found) {
                            this.$store.dispatch("setRoomType", { id: found[0].id, value: true });
                            this.$refs.bar.$refs.roomFilter.selected.push(found[0].text);
                        }
                    });
                }

                if (filters.geoObject) {
                    this.$refs.bar.streets.push({ text: filters.geoObject.name_ru, value: filters.geoObject.translit });
                    this.$refs.bar.selectedStreet = { text: filters.geoObject.name_ru, value: filters.geoObject.translit };
                    this.$refs.bar.streetUrlValue = filters.geoObject.translit;
                }
            },
            getAdverts() {
                let href = window.location.href;

                axios.get(window.backend_url + "api/adverts-url?url=" + escape(href)).then((response) => {
                    this.advertsQuery = response.data;
                    this.adverts = this.advertsQuery.result.data;
                    this.advertsCount = this.advertsQuery.result.total;
                    this.pageAdvertsCount = this.advertsQuery.result.data.length;

                    this.$refs.bar.h1 = response.data.seo.h1;
                    this.pages = response.data.result.last_page;
                    this.page = response.data.result.current_page;

                    let city = JSON.parse(this.city);
                    this.$refs.bar.updateCity(city, this.advertsCount);

                    let filters = this.advertsQuery.filter;
                    let geoObject = response.data.filter.geoObject;
                    let queryFilters = _.cloneDeep(filters);

                    if (filters.total_floor) {
                        filters.total_floors = filters.total_floor;
                        delete filters.total_floor;
                    }

                    delete queryFilters.city;
                    delete queryFilters.geoObject;

                    if (queryFilters.query === null)
                        delete queryFilters.query;

                    if (geoObject)
                        filters.geoObject = geoObject;

                    this.getSlugFilters(filters);
                    this.getMarkers(JSON.stringify(queryFilters));
                });
            },
            updateQuery(payload, redirect = true) {
                let url = window.location.href.split("?")[0];
                let params = new URLSearchParams(window.location.href.split("?")[1]);

                payload.data.map((el) => {
                    if (el.value)
                        params.set(el.prop, el.value);
                    else
                        params.delete(el.prop);
                });

                let query = params.toString().length > 0 ? "?" + params.toString() : "";

                if (redirect)
                    window.location = url + query;
                else
                    return query;
            },
            sortBy(sort) {
                this.updateQuery({ data: [{ prop: "order", value: sort }] });
            },
            toggleMap() {
                this.$refs.map.visibleMap = true;
                this.$refs.header.active = false;
                this.$refs.map.setSize();
            },
            resetAllFilters() {
               window.location = "/" + JSON.parse(this.city).translit;
            }
        },
        watch: {
            page: function(value) {
                if (value != this.advertsQuery.result.current_page)
                    this.updateQuery({data: [{ prop: "page", value: value }]});
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "./assets/computed.css";

    .pagination {
        grid-column: 1 / -1;
    }

    .sort-label {
        margin-left: 0.7rem;
        margin-right: 0.3rem;
        color: #000;
    }

    .filter-reset-link {
        display: block;
        font-size: 16px;
        line-height: 24px;
        text-align: center;
        -webkit-text-decoration-line: underline;
        -moz-text-decoration-line: underline;
        text-decoration-line: underline;
        border: none;
        background-color: transparent;
        cursor: pointer;
        color: #000;
        font-weight: 400;
        margin-top: 0.3rem;
    }

    .apartments_not_found {
        width: 350px;
        margin: 1rem auto;
        text-align: center;
        color: rgb(12, 36, 85);
    }

    .map-toggler {
        padding: 1rem;
    }

    .map {
        height: 80vh;
        flex-basis: 65%;
        z-index: 0;
    }

    .list {
        display: grid;
        grid-template-columns: repeat(auto-fit,minmax(354px,1fr));
        grid-gap: 20px;
    }

    .vue2leaflet-map {
        z-index: 0 !important;
    }

    .leaflet-control-attribution.leaflet-control {
        display: none;
    }

    .flex {
        display: flex;
    }

    .left {
        flex-basis: 40%;
        padding: 1rem;
    }

    .map_call-mobile {
        display: none;
        position: fixed;
        bottom: 5rem;
        left: 50%;
        background: #4364da;
        border-radius: 50%;
        transform: translate(-50%, 0);
        img {
            height: 42px;
            filter: invert(1);
            padding: 12px;
        }
    }

    .filter-bar-footer {
        padding-left: 1.2rem;
        display: flex;
        justify-content: space-between;
    }

    .sort-title {
        color: var(--greyish-blue);
        font-size: 16px;
        font-weight: 400;
    }

    .sort {
        display: flex;
        align-items: center;
        position: relative;
        top: -10px;
    }

    @media only screen and (max-width: 1280px) {
        .sort {
            display: none;
        }
    }

    @media only screen and (max-width: 1360px) {
        .map-mobile {
            position: relative;
            overflow: hidden;
            >div {
                flex-basis: 100%;
            }
            .map {
                display: none;
                width: calc(99vw - 5px);
                position: absolute;
                z-index: 3;
            }
            .map.visible_map {
                display: block;
                position: fixed;
                height: 100vh;
                width: 100vw;
                top: 0;
            }

        }
        .map_call-mobile {
            display: block;
        }

    }
</style>
