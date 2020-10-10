<template>
    <div class="map" v-bind:class="{ 'visible_map': visibleMap }">
        <l-map :zoom="zoom" :center="center" ref="map">
            <l-tile-layer :url="url"></l-tile-layer>
            <l-marker
                v-for="marker in _markers"
                :lat-lng="getLatLng(marker)"
                v-on:click="getAdvert(marker)"
                :key="marker.id"
                :icon="getIcon()"
            >
                <l-popup>
                    <div v-if="isAdvertLoading">
                        <div style="padding: 3rem 1rem; display: flex; justify-content: center">
                            <Preloader></Preloader>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="activeMapAdvert">

                            <carousel :data="carouselData" :autoplay="false" indicator-type="disc"></carousel>
                            <div class="advertInfo">
                                <div class="title_wrap">
                                    <a :href="`/${activeMapAdvert.city.translit}/${activeMapAdvert.id}`"
                                       class="advert-map-title">{{ activeMapAdvert.title.uk }}</a>
                                </div>
                                <div class="row">
                                    <div class="price-box">
                                        <span class="price">{{ activeMapAdvert.price_month }}</span>
                                        <span class="currency">грн/</span>
                                        <span class="month">мес</span>
                                    </div>
                                    <div class="body-meta">{{ getDate(activeMapAdvert) }}</div>
                                </div>
                                <div class="row">
                                    <span class="body-features">{{ getArea(activeMapAdvert) }}</span>
                                    <span class="body-features">{{ getFloor(activeMapAdvert) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </l-popup>
            </l-marker>

            <l-marker
                v-if="hover"
                :lat-lng="hover_coords"
                :icon="getHoverIcon()"
            >

            </l-marker>
        </l-map>

        <div class="close-map" v-if="visibleMap" @click="closeMap">
            <img src="/icons/close.svg" alt="close_map">
        </div>

        <div class="more-filters" v-if="visibleMap" @click="moreFilters">
            <svg width="13.33" height="20">
                <use xlink:href="/icons/sprite.svg#all-filters"></use>
            </svg>
        </div>
    </div>
</template>

<script>
import { LMap, LTileLayer, LMarker, LPopup } from 'vue2-leaflet';
import VueCarousel from "./assets/VueCarousel";
import Preloader from "./Partials/Preloader";

export default {
    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup,
        VueCarousel,
        Preloader
    },
    data () {
        return {
            url: "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png",
            zoom: 12,
            center: [50.45466, 30.5238],
            coords: null,
            markers: null,
            activeMapAdvert: null,
            isAdvertLoading: false,
            visibleMap: false,
            moreFiltersModal: false,

            hover: false,
            hover_coords: [],

            carouselData: [
                '<div class="slide"><img class="slide_img" src="https://i1.wp.com/itc.ua/wp-content/uploads/2019/11/google-photos.jpg?fit=1000%2C600&quality=100&strip=all&ssl=1" alt="img"></div>',
                '<div class="slide"><img class="slide_img" src="https://www.ixbt.com/img/n1/news/2019/11/3/5cc86f31768b3e05177244e3.jpg"></div>'
            ]
        };
    },
    mounted() {
        this.center = [this.city.lat, this.city.lng];
        //this.markers = JSON.parse(this._markers);
        this.markers = this._markers;
    },
    props: ['_markers', 'city'],
    methods: {
        getLatLng: function(object) {
            return [object.lat, object.lng];
        },
        getAdvert: function (marker) {
            this.isAdvertLoading = true;

            axios.get(window.backend_url + 'api/adverts/' + marker.id).then((response) => {
                this.activeMapAdvert = response.data.advert;
                this.isAdvertLoading = false;
                this.center = this.getLatLng(marker);

                let images = response.data.advert.images;
                this.carouselData = [];

                images.map((el) => {
                    this.carouselData.push(`<div class="slide" style="background-image: url(${el.card})"></div>`);
                })
            });
        },
        getAdvertParameter(advert, key) {
            let obj = advert.parameters.filter(obj => {
                return obj.key === key
            });

            if (obj.length > 0) return obj[0];
            else return null;
        },
        getArea: function (advert) {
            let totalArea = this.getAdvertParameter(advert, 'total_space');
            let livingArea = this.getAdvertParameter(advert, 'living_space');
            let kitchenArea = this.getAdvertParameter(advert, 'kitchen_space');

            let kitchenAreaLabel = kitchenArea && kitchenArea.value? ' / ' + kitchenArea.value.key + ' ' + kitchenArea.unit.ru : '';
            let totalAreaLabel = totalArea && totalArea.value ? totalArea.value.key  + ' ' + totalArea.unit.ru : '';
            let livingAreaLabel = livingArea && livingArea.value ? ' / ' + livingArea.value.key  + ' ' + livingArea.unit.ru : '';

            return totalAreaLabel + livingAreaLabel + kitchenAreaLabel;
        },
        getDate: function (advert) {
            let date = new Date(advert.publish_date);
            return this.$moment(date).format("DD.MM.YYYY");

        },
        getFloor: function (advert) {
            let floor = this.getAdvertParameter(advert, 'floor');
            let total_floor = this.getAdvertParameter(advert, 'total_floors');
            let floorLabel = floor ? floor.value.key + ' этаж' : '';
            if (total_floor && total_floor.value)
                floorLabel += ' из ' + total_floor.value.key;

            return floorLabel;
        },
        closeMap() {
            this.visibleMap = false;
            this.$parent.$refs.header.active = true;
        },
        setSize() {
            setTimeout(function() { window.dispatchEvent(new Event('resize')) }, 250);
        },
        getIcon() {
            return L.divIcon({
                className: "marker-homfi",
                html: `<img src="/icons/marker-icon.svg" alt="marker">`
            });
        },
        getHoverIcon() {
            return L.divIcon({
                className: "marker-homfi-hovered",
                html: `<img src="/icons/marker-icon.svg" alt="marker">`
            });
        },
        moreFilters() {
            this.$modal.show('more-filters-map');
            this.moreFiltersModal = true;
            this.visibleMap = false;
            this.$parent.$refs.header.active = true;
        },
        closeModal() {
            this.moreFiltersModal = false;
            this.visibleMap = true;
            this.$parent.$refs.header.active = false;
        }
    }
}
</script>

<style>
    .more-filters {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        z-index: 99999;
        cursor: pointer;
        background: #fff;
        border-radius: 50px;
        padding: 0.7rem;
        border: 1px solid darkgray;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
    }

    .marker-homfi {
        margin-left: -12px;
        margin-top: -41px;
        width: 25px;
        height: 41px;
        transform: translate3d(565px, 74px, 0px);
        z-index: 74;
        outline: none;
    }

    .marker-homfi img {
        width: 25px;
        height: 42px;
        left: -6px;
        position: relative;
    }

    .marker-homfi-hovered img {
        width: 50px;
        height: 50px;
        position: relative;
        left: -16px;
        top: -5px;
    }

    .close-map {
        position: absolute;
        top: 1.5rem;
        left: 1.5rem;
        z-index: 99999;
        cursor: pointer;
        background: #fff;
        border-radius: 50px;
        padding: 0.5rem;
        border: 1px solid darkgray;
    }

    .slide {
        align-items: center;
        background-color: transparent;
        color: #999;
        display: flex;
        font-size: 1.5rem;
        justify-content: center;
        min-height: 10rem;
    }

    .slide_img {
        max-width: 100%;
    }

    .advertInfo {
        padding: 1rem;
    }

    .title_wrap {
        grid-column: 1/span 2;
        padding-bottom: 4px;
        width: 100%;
        font-size: 12px;
        line-height: 24px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .title_wrap a {
        color: var(--blue-900);
        text-decoration: none;
        font-size: 16px;
    }

    .price_box {
        color: var(--blue-900);
    }

    .price {
        display: inline-block;
        padding-right: 8px;
        font-size: 18px;
        line-height: 30px;
        color: var(--blue-900);
    }

    .currency, .month {
        font-size: 20px;
        line-height: 30px;
        color: var(--blue-900);
    }

    .body-meta {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: flex-end;
        -moz-box-pack: end;
        justify-content: flex-end;
        color: var(--greyish-blue);

        font-size: 12px;
        line-height: 16px;
    }

    .row {
        display: flex;
        justify-content: space-between;
        padding: 0 1rem;
        align-items: center;
    }

    .body-features {
        padding-top: 8px;
        font-size: 12px;
        line-height: 16px;
    }

</style>
