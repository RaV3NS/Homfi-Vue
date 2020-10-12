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
                            <div style="position: relative">
                                <carousel :data="carouselData" :autoplay="false" indicator-type="disc"></carousel>
                                <button type="button" class="btn-favourites" @click="favourites(activeMapAdvert)">
                                    <svg v-if="!isFavourite(activeMapAdvert)" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d)"><path d="M11.9029 19.0207L11.8813 18.9991L11.8584 18.9788C9.16904 16.5979 6.93836 14.6188 5.36799 12.7092C3.80984 10.8144 3 9.10239 3 7.27308C3 4.30715 5.30623 2 8.26799 2C9.74942 2 11.2282 2.62176 12.2929 3.68646L13 4.39352L13.7071 3.68646C14.7718 2.62176 16.2506 2 17.732 2C20.6938 2 23 4.30715 23 7.27308C23 9.10361 22.189 10.8168 20.6288 12.7131C19.0563 14.6244 16.8228 16.6053 14.1304 18.989L14.1074 19.0094L14.0857 19.0311L12.9998 20.1184L11.9029 19.0207Z" stroke="white" stroke-width="2"></path></g><defs><filter id="filter0_d" x="0" y="0" width="26" height="24.5333" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset dy="1"></feOffset><feGaussianBlur stdDeviation="1"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.416476 0 0 0 0 0.484595 0 0 0 0 0.620833 0 0 0 0.5 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
                                    <svg v-else viewBox="0 0 22 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9.19558 18.7276L11 20.5333L12.7933 18.7378C18.1382 14.0057 22 10.5867 22 6.27308C22 2.75565 19.2469 0 15.732 0C13.9789 0 12.2477 0.731706 11 1.97934C9.75231 0.731706 8.02106 0 6.26799 0C2.75315 0 0 2.75565 0 6.27308C0 10.5838 3.85644 14.0008 9.19558 18.7276Z" fill="#FF8C42"></path></svg>
                                </button>
                            </div>
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
            favouritesList: [],

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
        this.favouritesList = this.$store.state.favourites;
    },
    props: ['_markers', 'city'],
    methods: {
        favourites(advert) {
            if (this.isFavourite(advert)) {
                axios.delete(window.backend_url + `api/user/${window.user.id}/favorites/${advert.id}`).then((response) => {
                    if (response.status === 200) {
                        let favourites = this.$store.state.favourites;
                        let index = this.favouritesList.indexOf(advert.id);
                        if (index !== -1)
                            favourites.splice(index, 1);
                        this.$store.dispatch("setFavourites", favourites);
                        
                        this.$toast.warning( 'Объявление ' + advert.id + ' удалено из избранного' , {
                            position: 'top-right',
                            duration: 3000,
                            dismissible: true
                        });
                    }
                });
            } else {
                axios.post(window.backend_url + `api/user/${window.user.id}/favorites`, { advert_id: advert.id }).then((response) => {
                    if (response.status === 200) {
                        this.$toast.info( 'Объявление ' + advert.id + ' добавлено в избранное' , {
                            position: 'top-right',
                            duration: 3000,
                            dismissible: true
                        });

                        let favourites = this.$store.state.favourites;
                        favourites.push(advert.id);
                        this.$store.dispatch("setFavourites", favourites);
                    };   
                });
            }
        },
        getLatLng: function(object) {
            return [object.lat, object.lng];
        },
        isFavourite(advert) {
            if (advert)
                return this.favouritesList.includes(advert.id);
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
    .btn-favourites {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
        display: -webkit-inline-flex;
        display: -moz-inline-box;
        display: inline-flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        margin: 0;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: none;
        outline: none;
        cursor: pointer;
    }

    .btn-favourites:hover {
        background: hsla(0,0%,100%,.5);
        -webkit-filter: drop-shadow(0 0 20px rgba(100,110,148,.2));
        filter: drop-shadow(0 0 20px rgba(100,110,148,.2))
    }

    .btn-favourites svg {
        width: 22px;
        height: 21px;
    }

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
