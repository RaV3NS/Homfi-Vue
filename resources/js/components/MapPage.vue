<template>
    <div>
        <header-vue ref="header"></header-vue>
        <filter-bar-map :advertsCount="advertsCount" v-on:updateQuery="updateQuery" ref="bar"></filter-bar-map>
        <div class="filter-bar-footer">
            <div class="sort">
                <span class="sort-title">Сортировать</span>
                <div>
                    <b-dropdown variant="link" toggle-class="text-decoration-none">
                        <template v-slot:button-content>
                            <span style="color: #000">{{ sortType }}</span>
                        </template>
                        <b-dropdown-item @click="sortBy('highest_price')">От дорогих к дешевым</b-dropdown-item>
                        <b-dropdown-item @click="sortBy('lowest_price')">От дешевых к дорогим</b-dropdown-item>
                        <b-dropdown-item @click="sortBy('newest')">Сначала самые новые</b-dropdown-item>
                    </b-dropdown>
                </div>
            </div>

            <div class="map-toggler">
                <span style="margin-right: 0.5rem">Показать карту</span>
                <toggle-button v-model="mapToggle" color="var(--blue-400)" :labels="false"/>
            </div>
        </div>

        <div class="flex map_cont map-mobile" v-bind:class="{'map_toggle': !mapToggle}" v-if="advertsCount > 0">
            <div class="left">
                <div class="list disable-scrollbars">
                    <Advert
                        v-for="advert in adverts"
                        :advert="advert"
                        :key="advert.id"
                    />
                </div>
            </div>
            <vue-map :_markers="this.mapAdverts" ref="map"></vue-map>
        </div>
        <div v-else>
            <hr>

            <p class="apartments_not_found">
                На жаль, Ваш запит не дав результатів. Змініть параметри пошуку і спробуйте ще раз
                <a :href="'/' + city.translit" class="filter-reset-link">Скинути всі фільтри</a>
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
                mapAdverts: [],
                advertsQuery: [],
                adverts: [],
                advertsCount: 0,
                mapToggle: true,
            };
        },
        props: ['city'],
        mounted() {
            if (this.city)
                this.$store.dispatch("setCity", JSON.parse(this.city));
            let city = JSON.parse(this.city);

            this.getMarkers(null);
            this.getAdverts();

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
                    console.log(result);
                    this.$store.dispatch("setAltFilter", { prop: "publish_date", field: 'value', value: result[0] })
                }
            }

            let fullUrl = window.location.href.split("?");
            let url = fullUrl[0];

            let urlParams = url.split("-");

            if (urlParams.length === 2) {
                let arr = urlParams[1].split(",");
                let filters = this.$store.state.type_filter;

                let types = ['kvartiry', 'komnaty', 'doma', 'poldoma'];
                let found = arr.some( ai => types.includes(ai) );

                if (found) {
                    arr.map((el) => {
                        let filter = filters.filter(entry => {
                            return entry.slug === el;
                        });

                        this.$refs.bar.$refs.typeFilter.selected.push(filter[0].text);
                        this.$store.dispatch("setFilterType", { id: filter[0].id, value: true });
                    });
                }
            }

            if (urlParams.length === 3) {
                let arr = urlParams[2].split(",");
                let filters = this.$store.state.type_filter;

                arr.map((el) => {
                    let filter =  filters.filter(entry => {
                        return entry.slug === el;
                    });

                    this.$refs.bar.$refs.typeFilter.selected.push(filter[0].text);
                    this.$store.dispatch("setFilterType", { id: filter[0].id, value: true });
                });
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
            getMarkers: function(filters) {
                let city = JSON.parse(this.city);
                let filter_query = "&filter=";
                if (filters === null) filter_query = "";
                else filter_query += filters;
                axios.get('http://localhost:8000/api/adverts/coordinates?city_id=' + city.id + filter_query).then((response) => {
                    this.mapAdverts = response.data;
                });
            },
            getLatLng: function(object) {
                return [object.lat, object.lng];
            },
            getAdverts() {
                let href = window.location.href;

                axios.get("http://localhost:8000/api/adverts-url?url=" + escape(href)).then((response) => {
                    this.advertsQuery = response.data;
                    this.adverts = this.advertsQuery.result.data;
                    this.advertsCount = this.advertsQuery.result.total;

                    let filters = this.advertsQuery.filter;

                    delete filters.geoObject;
                    delete filters.city;

                    if (filters.query === null)
                        delete filters.query;

                    this.getMarkers(JSON.stringify(filters));
                });
            },
            updateQuery(payload) {
                let url = window.location.href.split("?")[0];
                let params = new URLSearchParams();

                payload.data.map((el) => {
                    params.set(el.prop, el.value);
                });

                //console.log(params.toString());

                let query = params.toString().length > 0 ? "?" + params.toString() : "";
                window.location = url + query;
            },
            sortBy(sort) {
                this.updateQuery({ data: [{ prop: "order", value: sort }] });
            },
            toggleMap() {
                this.$refs.map.visibleMap = true;
                this.$refs.header.active = false;
                this.$refs.map.setSize();
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "./assets/computed.css";

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
