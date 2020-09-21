<template>
    <div>
        <filter-bar-map :advertsCount="advertsCount"></filter-bar-map>

        <div class="flex map_cont map-mobile">
            <div class="left">
                <div class="list disable-scrollbars">
                    <Advert
                        v-for="advert in adverts"
                        :advert="advert"
                        :key="advert.id"
                    />
                </div>
            </div>
            <vue-map :_markers="this.mapAdverts"></vue-map>
        </div>

        <a href="#" class="map_call-mobile">
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
                adverts: []
            };
        },
        props: ['city'],
        mounted() {
            if (this.city)
                this.$store.dispatch("setCity", JSON.parse(this.city));
            let city = JSON.parse(this.city);

            axios.get('http://localhost:8000/api/adverts/coordinates?city_id=' + city.id).then((response) => {
                this.mapAdverts = response.data;
            });

            this.getAdverts();

            let params = new URLSearchParams(window.location.search);
            for (var p of params.entries()) {
                if (p[0] == "pricemonth_min") {
                    this.$store.dispatch("setFilter", { prop: 'price_filter', field: 'from', value: parseFloat(p[1]) })
                }

                if (p[0] == "pricemonth_max") {
                    this.$store.dispatch("setFilter", { prop: 'price_filter', field: 'to', value: parseFloat(p[1]) })
                }
            }
        },
        computed: {
            advertsCount: function() {
                return this.adverts.length;
            }
        },
        methods: {
            getLatLng: function(object) {
                return [object.lat, object.lng];
            },
            getAdverts() {
                let href = window.location.href;

                axios.get("http://localhost:8000/api/adverts-url?url=" + escape(href)).then((response) => {
                    this.advertsQuery = response.data;
                    this.adverts = this.advertsQuery.result.data;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import "./assets/computed.css";

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




//в map_cont по нажатию на чекбокс добавляем/убираем класс map_toggle

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
            }
        }
        .map_call-mobile {
            display: block;
        }

    }
</style>
