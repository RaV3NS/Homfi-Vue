<template>
    <div>
        <pageloader v-if="loading"></pageloader>
        <title>{{ h1 }}</title>
        <header-vue></header-vue>

        <div class="breadcrumbs-header">
            <div class="breadcrumbs-wrapper">
                <a href="/" class="breadcrumbs breadcrumbs__link">Главная</a>
                <span> / </span>
                <a href="/" class="breadcrumbs breadcrumbs__link">Хочу снять</a>
                <span class="city">({{ city_name }})</span>
            </div>

            <div class="page-title">
                {{ h1 }}
            </div>
        </div>

        <div class="gallery-container">
            <div class="gallery" v-if="windowWidth > 1280">
                <div v-for="(img, index) in main_images" :class="`gallery-item gallery-item-${index + 1}`">
                    <img class="gallery-image" :src="img.src" decoding="async" loading="lazy" :alt="img.alt" @click="openCarouselModal(index)">
                    <button type="button" class="btn-favourites" v-if="index === 0">
                        <svg viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d)"><path d="M11.9029 19.0207L11.8813 18.9991L11.8584 18.9788C9.16904 16.5979 6.93836 14.6188 5.36799 12.7092C3.80984 10.8144 3 9.10239 3 7.27308C3 4.30715 5.30623 2 8.26799 2C9.74942 2 11.2282 2.62176 12.2929 3.68646L13 4.39352L13.7071 3.68646C14.7718 2.62176 16.2506 2 17.732 2C20.6938 2 23 4.30715 23 7.27308C23 9.10361 22.189 10.8168 20.6288 12.7131C19.0563 14.6244 16.8228 16.6053 14.1304 18.989L14.1074 19.0094L14.0857 19.0311L12.9998 20.1184L11.9029 19.0207Z" stroke="white" stroke-width="2"></path></g><defs><filter id="filter0_d" x="0" y="0" width="26" height="24.5333" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset dy="1"></feOffset><feGaussianBlur stdDeviation="1"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.416476 0 0 0 0 0.484595 0 0 0 0 0.620833 0 0 0 0.5 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
                    </button>
                </div>
                <button type="button" class="btn-light btn-wishlist" @click="openCarouselModal(0)"> Все фотографии </button>
            </div>
            <div v-else>
                <carousel :autoplay="false" :data="carousel_images" indicator-type="disc" ref="carousel_modal"></carousel>
                <button type="button" class="btn-mobile" @click="openCarouselModal(0)"> Все фотографии </button>
            </div>
        </div>

        <div class="ad-details-wrapper">
            <div class="ad-content">
                <div class="section">
                    <div class="ad-details-title">Адрес</div>
                    <p class="ad-details-text">{{ getAddress() }}</p>

                    <div class="rent-terms">
                        <p v-if="getRentDate()"><b>Дата сдачи</b> {{ getRentDate() }}</p>
                        <p v-if="getRentTerm()"><b>Срок сдачи</b> {{ getRentTerm() }}</p>
                    </div>
                </div>

                <div class="section">
                    <div class="ad-details-title">Характеристики</div>

                    <div class="chars">
                        <ul>
                            <li class="list-item" v-for="item in chars_left">
                                <img :src="`/icons/${item.icon}.svg`" decoding="async" loading="lazy" alt="characteristics icon">
                                <span>{{ item.value }}</span>
                            </li>
                        </ul>

                        <ul>
                            <li class="list-item" v-for="item in chars_right">
                                <img :src="`/icons/${item.icon}.svg`" decoding="async" loading="lazy" alt="characteristics icon">
                                <span>{{ item.value }}</span>
                            </li>

                            <div class="chars-flex">
                                <li class="list-item" v-for="item in chars_communals">
                                    <img :src="`/icons/${item.icon}.svg`" decoding="async" loading="lazy" alt="characteristics icon">
                                    <span>{{ item.value }}</span>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>

                <div class="section">
                    <div class="ad-details-title">Описание</div>
                    <p class="ad-details-text">{{ body }}</p>
                </div>

                <div class="section">
                    <div class="ad-details-title">Удобства</div>

                    <ul class="main-facilities">
                        <li class="list-item" v-for="option in options_main">
                            <img src="/icons/check-circle.svg" decoding="async" loading="lazy" alt="facilities icon">
                            <span>{{ option.name_ru }}</span>
                        </li>
                    </ul>

                    <div class="extra-toggle">
                        <button class="extra-toggle-button" @click="toggleExtra" v-if="!extraOpen">Показать дополнительные удобства</button>
                    </div>

                    <VueSlideToggle :open="extraOpen" tag="section" :duration="500">
                        <div class="extra-facilities">
                            <div class="facilities-group" v-if="options_additional.length > 0">
                                <h6 class="title">Дополнительные удобства</h6>
                                <ul class="list">
                                    <li class="list-item" v-for="option in options_additional">{{ option.name_ru }}</li>
                                </ul>
                            </div>

                            <div class="facilities-group">
                                <div class="list-wrap" v-if="options_infrastructure.length > 0">
                                    <h6 class="title">Инфраструктура</h6>
                                    <ul class="list">
                                        <li class="list-item" v-for="option in options_infrastructure">{{ option.name_ru }}</li>
                                    </ul>
                                </div>

                                <div class="list-wrap"  v-if="options_household.length > 0">
                                    <h6 class="title">Бытовые удобства</h6>
                                    <ul class="list">
                                        <li class="list-item" v-for="option in options_household">{{ option.name_ru }}</li>
                                    </ul>
                                </div>

                                <div class="list-wrap"  v-if="options_other.length > 0">
                                    <h6 class="title">Другое</h6>
                                    <ul class="list">
                                        <li class="list-item" v-for="option in options_other">{{ option.name_ru }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </VueSlideToggle>

                    <div class="extra-toggle">
                        <button class="extra-toggle-button" @click="toggleExtra" v-if="extraOpen">Свернуть дополнительные удобства</button>
                    </div>

                    <div class="complaint-mobile" v-if="windowWidth < 1024">
                        <button @click="openComplain">Пожаловаться на объявление</button>

                        <div class="date">Дата публикации: <br> {{ getDate() }}</div>
                    </div>
                </div>

                <div class="section">
                    <div class="title-wrapper">
                        <div class="ad-details-title">Расположение на карте</div>
                        <div class="ad-details_map-title__text-wrapper">
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`" class="ad-details-link" target="_blank">
                                <p class="ad-details_map-title__text">Показать на <span>Google Maps</span></p>
                            </a>
                            <a :href="`https://www.google.com/maps/search/?api=1&query=${lat},${lng}`" class="ad-details-link" target="_blank">
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21.5181 16.093L18.4045 20.6909L13.8003 25.2721C12.088 23.0958 10.2181 20.9877 9.07812 18.4378L11.5598 14.6361L15.3662 10.9639C14.094 12.5361 14.189 14.8485 15.6512 16.3106C17.2155 17.875 19.7511 17.875 21.3154 16.3106C21.3859 16.2402 21.454 16.1673 21.5181 16.093Z" fill="#ECB72B"></path><path d="M21.5882 16.0041C22.8715 14.4311 22.7805 12.1108 21.3135 10.6447C19.7492 9.08033 17.2136 9.08033 15.6493 10.6447C15.5836 10.7103 15.5211 10.7777 15.4609 10.8465L18.0867 6.57787L21.6492 3.4834C24.3099 4.32568 26.5107 6.20109 27.7813 8.63863L25.1824 12.8293L21.5882 16.0041Z" fill="#5085F7"></path><path d="M15.3639 10.9661L9.07567 18.4402C8.45202 17.0465 8.04658 15.521 8.00475 13.78C8.00158 13.681 8 13.5813 8 13.4815C8 10.9198 8.9191 8.57172 10.4462 6.75098L15.3639 10.9661Z" fill="#DA2F2A"></path><path d="M15.3632 10.9663L10.4453 6.75082C12.3682 4.4582 15.2538 3 18.4805 3C19.5848 3 20.6492 3.17087 21.6486 3.48753L15.3632 10.9663Z" fill="#4274EB"></path><path d="M28.9614 13.4793C28.9614 11.7343 28.5349 10.0889 27.7806 8.6416L13.7969 25.2731C15.3856 27.294 16.8373 29.374 17.4406 31.9212C17.46 32.0028 17.4808 32.0981 17.5029 32.2053C17.7217 33.2651 19.2392 33.2651 19.4579 32.2053C19.4801 32.0981 19.5009 32.0028 19.5202 31.9212C21.1286 25.1302 28.7673 21.6597 28.9569 13.7784C28.9596 13.679 28.9614 13.5793 28.9614 13.4793Z" fill="#60A850"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div class="map" v-if="this.lat && this.lng">
                        <l-map :zoom="15" :center="[this.lat, this.lng]" ref="map">
                            <l-tile-layer url="https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png"></l-tile-layer>
                            <l-marker
                                :lat-lng="[this.lat, this.lng]"
                                :icon="getMarkerIcon()"
                            >
                            </l-marker>
                        </l-map>
                    </div>
                </div>
            </div>
            <div class="ad-sidebar">
                <div class="ad-details-sidebar-container">
                    <div class="ad-details-sidebar-title">
                        <div class="price">
                            <span class="value">{{ getPrice() }}</span>
                            <span class="units"> грн/мес</span>
                        </div>

                        <div class="date">
                            <span>Дата публикации <br> {{ getDate() }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="ad-details-bottom">
                        <div class="ad-details-owner">
                            {{ getName() }}
                        </div>

                        <button class="ad-details-btn button-primary" @click="openContacts">Показать контакты</button>
                    </div>
                </div>
                <div class="sidebar-complaint">
                    <button @click="openComplain">Пожаловаться на объявление</button>
                </div>
            </div>
        </div>

        <div class="bottom-panel" v-if="windowWidth < 1024">
            <div class="price-box">
                <div class="price">{{ price }}</div>
                <div class="units">грн/мес</div>
            </div>

            <button class="btn button-primary" @click="openContacts">Показать контакты</button>
        </div>

        <modal name="show-contacts" width="320" height="auto" :adaptive="true">
            <div v-if="contactsLoading">
                <div class="preload-wrapper">
                    <Preloader></Preloader>
                </div>

            </div>

            <div v-else>
                <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('show-contacts')">
                <div class="contacts-wrapper">
                    <div class="modal-contacts-name">{{ getName() }}</div>
                    <hr>
                    <div v-if="advert && advert.email">
                        {{ advert.email }}
                        <hr>
                    </div>
                    <div v-if="phones.length > 0">
                        <div class="phone-line" v-for="phone in phones">
                            <a :href="`tel:${phone.number}`">{{ phone.number }}</a>
                            <img v-for="img in phone.messengers" :src="`/icons/socials/${img}.svg`" :alt="img">
                        </div>
                    </div>
                    <div v-if="social_links.length > 0">
                        <hr>
                        <div class="contacts-extra-title">Дополнительные каналы связи</div>
                        <div class="extra-flex">
                            <a v-for="link in social_links" :href="getContactsHrefTemplate(link)" target="_blank">
                                <img :src="`/icons/socials/${getContactsImg(link)}.svg`" alt="social link">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </modal>

        <modal name="carousel-modal" width="100%" height="100%" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn_carousel" @click="$modal.hide('carousel-modal')">

            <div class="carousel-container">
                <carousel :autoplay="false" :data="carousel_images" indicator-type="disc" ref="carousel_modal"></carousel>
            </div>

            <div class="thumbn-container" v-if="windowWidth > 768">
                <swiper ref="swiper" :options="swiperOptions">
                    <swiper-slide v-for="(thumb, index) in thumb_images" :key="index"><img class="thumb-img" :key="index" :src="thumb" @click="changeSlide(index)"></swiper-slide>
                </swiper>
            </div>
        </modal>

        <modal name="complain-modal" width="500" height="auto" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('complain-modal')">
            <div class="complain-wrapper">
                <div class="complain-modal-title">Причина жалобы</div>

                <label class="radio-text-label" v-for="reason in complain_reasons">
                    <input type="radio" class="radio-input" name="reason" :value="reason.ru" v-model="complain_reason">
                    <span class="radio-text">{{ reason.ru }}</span>
                </label>

                <div class="action-container">
                    <button class="button-primary" @click="complainStep2">Далее</button>
                </div>
            </div>
        </modal>

        <modal name="complain-modal-2" width="500" height="auto" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('complain-modal-2')">
            <div class="complain-wrapper">
                <div class="complain-modal-title">{{ complain_reason }}</div>

                <div class="form-container">
                    <label for="comment">Комментарий *</label>
                    <textarea
                        name="comment"
                        id="comment"
                        placeholder="Ваш комментарий"
                        v-model="complain_comment"
                        :class="{ error: error_comment }"
                    ></textarea>
                    <span class="form-error">{{ error_comment }}</span>
                </div>

                <div class="form-container">
                    <label for="email">Email</label>
                    <input
                        type="text"
                        id="email"
                        placeholder="example@gmail.com"
                        v-model="complain_email"
                        :class="{ error: error_email }"></input>
                    <span class="form-error">{{ error_email }}</span>
                </div>

                <div class="form-container">
                    <label for="phone">Номер телефона</label>
                    <input
                        type="text"
                        id="phone"
                        v-mask="'+380 ## ### ## ##'"
                        placeholder="+380 --- -- --"
                        v-model="complain_phone"
                        :class="{ error: error_phone }"></input>
                    <span class="form-error">{{ error_phone }}</span>
                </div>

                <div class="complain-step-2-container">
                    <button class="btn-link" @click="backToStep1">Назад</button>
                    <button class="button-primary" @click="sendComplain">Далее</button>
                </div>
            </div>
        </modal>

        <modal name="complain-modal-3" width="400" height="auto" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('complain-modal-3')">
            <div class="modal-success-wrapper">
                <img class="mb-10 mb-md-20" src="/icons/success-icon.svg" alt="" decoding="async">
                <p>Спасибо, что сообщили нам о проблеме, мы постараемся быстро все уладить</p>
            </div>
        </modal>
    </div>
</template>

<script>
    import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';
    import Preloader from "./Partials/Preloader";

    export default {
        components: {
            LMap,
            LTileLayer,
            LMarker,
            Preloader
        },
        data: function() {
            return {
                swiperOptions: {
                    slidesPerView: 5,
                },

                advert: null,
                seo: null,
                h1: null,
                city_name: null,

                chars_left: [],
                chars_right: [],
                chars_communals: [],

                options_main: [],
                options_additional: [],
                options_household: [],
                options_other: [],
                options_infrastructure: [],

                extraOpen: false,
                body: '',
                lat: '',
                lng: '',

                main_images: [],
                carousel_images: [],
                thumb_images: [],
                phones: [],
                social_links: [],

                windowWidth: window.innerWidth,
                complain_reason: null,
                complain_reasons: [
                    { ru: 'Взимание платы за посреднические услуги' },
                    { ru: 'Мошенничество' },
                    { ru: 'Объект уже сдан' },
                    { ru: 'Я являюсь собственником' },
                    { ru: 'Неверная цена' },
                    { ru: 'Неверный адрес' },
                    { ru: 'Невозможно связаться с владельцем' },
                    { ru: 'Другое' }
                ],
                complain_phone: '',
                complain_comment: '',
                complain_email: '',

                error_comment: null,
                error_phone: null,
                error_email: null,
                loading: true,
                contactsLoading: true
            }
        },
        props: ["id"],
        mounted() {
            this.getAdvert();

            this.$nextTick(() => {
                window.addEventListener('resize', this.onResize);
            })
        },
        methods: {
            onResize() {
                this.windowWidth = window.innerWidth;
            },
            getAdvert: function() {
                axios.get(window.backend_url + 'api/adverts/' + this.id).then((response) => {
                    this.advert = response.data.advert;
                    this.seo = response.data.seo;

                    this.h1 = response.data.seo.h1;
                    this.city_name = response.data.advert.city.name_ru;

                    this.loading = false;
                });
            },
            getPrice() {
                if (this.advert)
                    return this.price;
            },
            getDate() {
               if (this.advert) {
                   let date = new Date(this.advert.updated_at);
                   return this.$moment(date).format("DD.MM.YYYY / HH:mm");
               }
            },
            getName() {
                if (this.advert)
                    return this.advert.first_name + " " + this.advert.last_name;
            },
            getAddress() {
                if (this.advert)
                    return this.advert.full_address.ru;
            },
            getAdvertParameter(option) {
                if (this.advert) {
                    let options = this.advert.parameters;
                    let found = options.filter(el => el.key === option);
                    if (found[0] && found[0].value.key) return found[0];
                    else return null;
                }
            },
            getRentTerm() {
                let from = this.getAdvertParameter('rent_term_min');
                let to = this.getAdvertParameter('rent_term_max');

                if (from && !to)
                    return "от " + from.value.key + " мес";

                if (!from && to)
                    return "до " + to.value.key + " мес";

                if (from && to)
                    return from.value.key + "-" + to.value.key + " мес";
            },
            getRentDate() {
                let date = this.getAdvertParameter('rent_date');

                if (date) {
                    return this.$moment(date.value.key).format("DD.MM.YYYY");
                }
            },
            getArea: function () {
                let totalArea = this.getAdvertParameter('total_space');
                let livingArea = this.getAdvertParameter('living_space');
                let kitchenArea = this.getAdvertParameter('kitchen_space');

                let kitchenAreaLabel = kitchenArea && kitchenArea.value? ' / ' + kitchenArea.value.key + ' ' + kitchenArea.unit.ru : '';
                let totalAreaLabel = totalArea && totalArea.value ? totalArea.value.key  + ' ' + totalArea.unit.ru : '';
                let livingAreaLabel = livingArea && livingArea.value ? ' / ' + livingArea.value.key  + ' ' + livingArea.unit.ru : '';

                return totalAreaLabel + livingAreaLabel + kitchenAreaLabel;
            },
            getFloor: function () {
                let floor = this.getAdvertParameter('floor');
                let total_floor = this.getAdvertParameter('total_floors');
                let floorLabel = floor ? floor.value.key + ' этаж' : '';
                if (total_floor && total_floor.value)
                    floorLabel += ' из ' + total_floor.value.key;

                return floorLabel;
            },
            getCharacteristics(list) {
                if (list === 'left') {
                    this.chars_left = [];

                    let type = this.getAdvertParameter('type');
                    if (type) this.chars_left.push({ value: type.value.value_ru, icon: 'type' });

                    let room_count = this.getAdvertParameter('room_count');
                    if (room_count) this.chars_left.push({ value: room_count.value.value_ru, icon: 'rooms' });

                    let space = this.getArea();
                    if (space && space !== '') this.chars_left.push({ value: space, icon: 'area' });

                    let floors = this.getFloor();
                    if (floors && floors !== '') this.chars_left.push({ value: floors, icon: 'floor' });
                }

                if (list === 'right') {
                    this.chars_right = [];

                    let height = this.getAdvertParameter('height');
                    if (height) this.chars_right.push({ value: height.name_ru + ' ' + height.value.value_ru + ' м', icon: 'ceiling' });

                    let build_year = this.getAdvertParameter('build_year');
                    if (build_year) this.chars_right.push({ value: build_year.name_ru + ' ' + build_year.value.value_ru + ' г.', icon: 'year' });

                    let heating = this.getAdvertParameter('heating');
                    if (heating) this.chars_right.push({ value: heating.value.value_ru, icon: 'heating' });

                    let communals = this.getAdvertParameter('communals');
                    if (communals) this.chars_right.push({ value: communals.value.value_ru, icon: 'payments' });

                    if (communals && communals.value.key === 'non-included') {
                        let communals_summer = this.getAdvertParameter('communals_summer');
                        if (communals_summer) {
                            let value = 'Зима ' + communals_summer.value.key + ' ' + communals_summer.unit.ru
                            this.chars_communals.push({ value: value, icon: 'summer' });
                        }

                        let communals_winter = this.getAdvertParameter('communals_winter');
                        if (communals_winter) {
                            let value = 'Лето ' + communals_winter.value.key + ' ' + communals_winter.unit.ru
                            this.chars_communals.push({ value: value, icon: 'winter' });
                        }
                    }
                }
            },
            getOptions(category) {
                return this.advert.options.filter(el => el.category === category);
            },
            toggleExtra() {
                this.extraOpen = !this.extraOpen;
                this.extraLabel = this.extraOpen ? 'Свернуть дополнительные удобства' : 'Показать дополнительные удобства';
            },
            getMarkerIcon() {
                return L.divIcon({
                    className: "marker-house",
                    html: `<img src="/icons/pin-home.svg" alt="marker">`
                });
            },
            getImages() {
                this.main_images = [];
                for (let i = 0; i < 6; i++) {
                    if (this.advert.images[i]) {
                        let alt = this.seo.images[i].alt.replace('parameter_values.type.', 'Квартира');
                        this.main_images.push({ src: this.advert.images[i]['720p'], alt: alt });
                    } else
                        this.main_images.push({ src: "/images/gallery-placeholders/card.svg", alt: 'no-image'});
                }

                this.carousel_images = [];
                this.advert.images.map((el, index) => {
                    let alt = this.seo.images[index].alt.replace('parameter_values.type.', 'Квартира');
                    //this.carousel_images.push({ src: el['720p'], alt: alt })
                    this.carousel_images.push(`<div class="slide" style="background-image: url(${el['720p']})"></div>`)
                    this.thumb_images.push(el['thumb']);
                    //this.carousel_images.push(`<img class="carousel-img" src="${el['720p']}" alt="${alt}">`)
                });
            },
            getPhones() {
                axios.post(window.backend_url + "api/adverts/phone/" + this.advert.phone).then((response) => {
                   this.phones = response.data.data;
                   this.contactsLoading = false;
                });
            },
            getSocialLinks() {
                this.social_links = Object.entries(this.advert.social_links);
            },
            getContactsHrefTemplate(link) {
                switch (link[0]) {
                    case "email":
                    case "gmail":
                        return 'mailto:' + link[1];
                    case "skype":
                        return 'skype:' + link[1] + '?chat';
                    case "facebook_messenger":
                        return "https://m.me" + link[1];
                    default:
                        return link[1];
                }
            },
            getContactsImg(link) {
                switch (link[0]) {
                    case 'email':
                        return 'gmail';
                    case 'facebook_messenger':
                        return 'messenger';
                    default:
                        return link[0];
                }
            },
            openContacts() {
                this.getPhones();
                this.getSocialLinks();
                this.$modal.show('show-contacts');
            },
            openCarouselModal(index) {
                this.$modal.show('carousel-modal');
                setTimeout(() => {
                    this.$refs.swiper.$swiper.slideTo(index, 0);
                    this.$refs.carousel_modal.slideTo(index);
                }, 100);
            },
            changeSlide(index) {
                this.$refs.swiper.$swiper.slideTo(index, 0);
                this.$refs.carousel_modal.slideTo(index);
            },
            openComplain() {
                this.$modal.show('complain-modal');
            },
            complainStep2() {
                if (this.complain_reason) {
                    this.$modal.hide('complain-modal');
                    this.$modal.show('complain-modal-2');
                }
            },
            backToStep1() {
                this.$modal.hide('complain-modal-2');
                this.$modal.show('complain-modal');
            },
            validateComplain() {
                if (!this.complain_comment || this.complain_comment.trim() === '') {
                    this.error_comment = 'Комментарий - обязательное поле для заполнения';
                } else
                    this.error_comment = null;

                const regx_email = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (!regx_email.test(this.complain_email)) {
                    this.error_email = 'Неверный формат e-mail адреса';
                } else {
                    this.error_email = null;
                }

                if (this.complain_phone && this.complain_phone.length < 17 ) {
                    this.error_phone = 'Неверный формат телефона';
                } else {
                    this.error_phone = null;
                }

                if (this.complain_phone.trim() === '' && this.complain_email.trim() === '') {
                    this.error_email = 'Поле E-mail адрес является обязательным для заполнения, если телефон не указан.';
                    this.error_phone = 'Поле Телефон является обязательным для заполнения, если E-mail адрес не указан.';
                } else if (!this.error_email || this.complain_email.trim() === '') {
                    this.error_email = null;
                } else if (!this.error_phone || this.complain_phone.trim() === '') {
                    this.error_phone = null;
                }

                if (this.error_email || this.error_phone || this.error_comment)
                    return false;

                return true;
            },
            sendComplain() {
                if (this.validateComplain()) {
                    let data = {
                       body: this.complain_comment,
                       reason: this.complain_reason
                    };

                    if (this.complain_phone) data.phone = this.complain_phone
                        .replace(/\s/g, "")
                        .replace(/\+/g, "");
                    if (this.complain_email) data.email = this.complain_email;

                    this.$modal.hide('complain-modal-2');

                    axios.post(window.backend_url + 'api/adverts/' + this.id + '/complain', data).then((response) => {
                        this.$modal.show('complain-modal-3');
                    });

                }
            }
        },
        computed: {
            price: function() {
                return this.advert ? this.advert.price_month : 0;
            }
        },
        watch: {
            advert: function(newValue) {
                // On Advert Load

                // Parameters List
                this.getCharacteristics('left');
                this.getCharacteristics('right');

                // Options
                this.options_main = this.getOptions('main');
                this.options_additional = this.getOptions('additional');
                this.options_household = this.getOptions('household');
                this.options_infrastructure = this.getOptions('infrastructure');
                this.options_other = this.getOptions('other');

                // Other
                this.body = this.advert.body;
                this.lat = this.advert.lat;
                this.lng = this.advert.lng;

                // Images
                this.images = this.getImages();
            }
        }
    }
</script>

<style lang="scss" scoped>
    .preload-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem 2rem;
    }

    .modal-success-wrapper {
        display: flex;
        justify-content: center;
        padding: 0 2rem 2rem;
        flex-direction: column;
    }

    .form-error {
        font-size: 14px;
        line-height: 20px;
        font-weight: 300;
        color: var(--red-700);
    }

    .complain-step-2-container {
        display: flex;
        justify-content: space-between;
        margin: 2rem 0;
    }

    .form-container .error {
        --tf-input-border-color: var(--red-700);
    }

    .form-container input {
        --tf-input-padding: 11px 1.25rem;
        line-height: var(--tf-line-height);
        font-weight: 300;
        color: var(--blue-900);
        padding: var(--tf-input-padding);
        font-family: inherit;
        font-size: 16px;
        width: 100%;
        border: 1px solid var(--tf-input-border-color);
        border-radius: .375rem;
        background-color: var(--white);
        -webkit-transition: var(--tf-input-transition);
        transition: var(--tf-input-transition);
    }

    .form-container textarea {
        display: block;
        width: 100%;
        resize: none;
        font-family: Rubik,sans-serif;
        font-size: var(--tf-font-size);
        font-weight: 300;
        line-height: var(--tf-line-height);
        color: var(--blue-900);
        padding: var(--tf-input-padding);
        border: 1px solid var(--tf-input-border-color);
        border-radius: 6px;
        background-color: var(--white);
    }

    .form-container label {
        display: block;
        margin-bottom: 5px;
    }

    .form-container {
        margin-bottom: 1rem;
        --tf-input-padding: 20px;
        --tf-input-border-color: var(--gray-300);
        --tf-input-border-color-focus: var(--blue-900);
        --tf-font-size: 16px;
        --tf-line-height: 20px;
    }

    .action-container button {
        margin-left: auto;
        display: block;
    }

    .action-container {
        margin: 2rem 0;
    }

    .radio-input:checked {
        border: 4px solid var(--blue-400);
    }

    .radio-text-label {
        padding: 16px 0;
        border-bottom: 1px solid var(--gray-300);
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        cursor: pointer;
    }

    .radio-input {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0 20px 0 0;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 1px solid var(--blue-900);
        background: var(--white);
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        outline: none;
    }

    .radio-text {
        font-size: 16px;
        line-height: 22px;
        font-weight: 400;
        color: var(--blue-900);
    }

    hr {
        margin: 20px 0;
        border: none;
        border-bottom: 1px solid var(--gray-300);
    }

    .complain-wrapper {
        padding: 0 2rem;
    }

    .complain-modal-title {
        font-size: 20px;
        line-height: 28px;
        font-weight: 400;
        margin-bottom: 20px;
    }

    .complaint-mobile {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        padding: 16px 20px;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background: var(--beige-400);
        border: 1px solid var(--gray-300);
        border-radius: 6px;
    }

    .complaint-mobile button {
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border: none;
        border-radius: .375rem;
        padding: var(--btn-padding,9px 1.25rem);
        font-family: inherit;
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);
        --btn-padding: 0;
        --btn-background-color: none;
        --btn-color: var(--blue-900);
        text-decoration: underline;
        font-weight: 300;
        font-size: 14px;
        line-height: 18px;
    }

    .complaint-mobile .date {
        font-size: 12px;
        line-height: 18px;
        color: var(--greyish-blue);
        margin-bottom: 10px;
    }

    .price-box {
        display: flex;
        flex-direction: column;
    }

    .sidebar-complaint {
        margin-top: 20px;
        text-align: center;
    }

    .sidebar-complaint button, .btn-link {
        outline: none;
        --btn-padding: 0;
        --btn-background-color: none;
        --btn-color: var(--blue-900);
        text-decoration: underline;
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border: none;
        border-radius: .375rem;
        padding: var(--btn-padding,9px 1.25rem);
        font-family: inherit;
        font-size: var(--btn-font-size,1rem);
        font-weight: var(--btn-font-weight, 300);
        line-height: var(--btn-line-height,1.375rem);
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);
    }

    .bottom-panel {
        width: 100%;
        padding: 10px 20px;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: var(--white);
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        box-shadow: 0 0 20px rgba(100,110,148,.2);
        z-index: 1001;
    }

    .bottom-panel .price {
        font-size: 26px;
        line-height: 30px;
        margin-right: 10px;
    }

    .bottom-panel .units {
        font-size: 16px;
        line-height: 22px;
    }

    .thumbn-container {
        overflow: visible;
        margin: 1rem auto;
        width: 775px;
    }

    .thumb-img {
        width: 130px;
        height: 100px;
        border-radius: 10px;
    }

    .carousel-container {
        padding: 0 2rem;
        position: relative;
        overflow: hidden;
        border-radius: 6px;
        margin-left: auto;
        margin-right: auto;
    }

    .close_modal_btn_carousel {
        cursor: pointer;
        margin-left: auto;
        width: 115px;
        padding: 2rem;
    }

    .phone-line {
        display: flex;
        align-items: center;
        font-size: 16px;
        line-height: 22px;
        font-weight: normal;
        justify-content: center;
    }

    .contacts-extra-title {
        font-size: 15px;
        line-height: 20px;
        color: var(--greyish-blue);
        margin-bottom: 12px;
    }

    .extra-flex {
        display: flex;
    }

    .extra-flex > *:not(:last-child) {
        margin-right: 0.5rem;
    }

    .phone-line > *:not(:last-child) {
        margin-right: 10px;
    }

    .phone-line > a {
        color: currentColor;
        text-decoration: none;
    }

    .phone-line:not(:last-child) {
        margin-bottom: 10px;
    }

    .contacts-wrapper {
        padding: 0 3rem;
        margin-bottom: 2rem;
    }

    .contacts-wrapper div {
        text-align: center;
    }

    .contacts-wrapper hr {
        margin-bottom: 1rem;
    }

    .chars-flex {
        display: flex;
        flex-direction: column;
    }

    .chars-flex img {
        width: 28px;
    }

    .chars-flex .list-item {
        position: relative;
        left: 2px;
    }

    .map {
        height: 360px;
    }

    .ad-details_map-title__text-wrapper {
        display: flex;
        align-items: flex-end;
    }

    .ad-details_map-title__text-wrapper svg {
        margin-bottom: 25px
    }

    .ad-details_map-title__text span {
        font-weight: normal;
    }

    .ad-details-link {
        text-decoration: none;
        color: var(--blue-900);
    }

    .title-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .extra-toggle-button {
        font-size: 16px;
        line-height: 22px;
        height: auto;
        --btn-padding: 0;
        --btn-background-color: none;
        --btn-color: var(--blue-600);
        text-decoration: underline;
        font-weight: normal;
        margin-bottom: 20px;

        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color, currentColor);
        cursor: pointer;
        border: none;
        border-radius: 0.375rem;
        padding: var(--btn-padding, 9px 1.25rem);
        font-family: inherit;
        transition: var(--button-transition);

        margin-top: 1rem;
        outline: none;
    }

    .facilities-group .list-wrap:not(:last-child) {
        margin-bottom: 44px;
    }

    .facilities-group .title {
        font-size: 16px;
        line-height: 1.4em;
        font-weight: normal;
        margin-bottom: 20px;
    }

    .main-facilities {
        margin-bottom: 20px;
        list-style: none;
    }

    .chars {
        display: flex;
        flex-direction: column;
    }

    .list-item {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        font-size: 16px;
        line-height: 20px;
        color: currentColor;
        padding: 5px 0;
    }

    .list-item img {
        margin-right: 10px;
        display: block;
    }

    b {
        font-weight: bold;
    }

    .ad-content .section {
        margin-bottom: 40px;
        color: var(--text-color-primary);
    }

    .ad-details-title {
        font-size: 20px;
        line-height: 1.5em;
        font-weight: 400;
        margin-bottom: 20px;
        color: var(--text-color-primary);
    }

    .ad-details-text {
        line-height: 1.3em;
        margin-bottom: .75em;
        color: var(--text-color-primary);
    }

    .modal-contacts-name {
        font-size: 20px;
        line-height: 28px;
        font-weight: 400;
        text-align: center;
        margin-bottom: 1rem;
    }

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

    .btn-wishlist {
        position: absolute;
        left: 20px;
        bottom: 20px;
        -webkit-appearance: button;
    }

    .btn-light:hover {
        background-color: var(--btn-background-color-hover);
    }

    .btn-light {
        --btn-background-color: var(--white);
        --btn-background-color-hover: var(--beige-400);
        --btn-color: var(--blue-900);
        border: 1px solid var(--gray-300);
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border-radius: .375rem;
        padding: var(--btn-padding,9px 1.25rem);
        font-family: inherit;
        font-size: var(--btn-font-size,1rem);
        font-weight: var(--btn-font-weight,400);
        line-height: var(--btn-line-height,1.375rem);
        text-decoration: none;
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);
        outline: none;
    }

    .close_modal_btn {
        padding: 0.5rem;
        margin-left: auto;
        cursor: pointer;
    }

    .gallery-container {
        position: relative;
    }

    .gallery {
        display: grid;
        grid-template-columns: 6fr repeat(6,1fr);
        grid-template-rows: repeat(2,248px);
        grid-gap: 2px;
    }

    .gallery-item {
        position: relative;
    }

    .gallery-item-1 {
        grid-column-start: 1;
        grid-column-end: 2;
        grid-row-start: 1;
        grid-row-end: 3;
    }

    .gallery-item-2 {
        grid-row-start: 1;
        grid-row-end: 2;
        grid-column-start: 2;
        grid-column-end: 5;
    }

    .gallery-item-3 {
        grid-row-start: 1;
        grid-row-end: 2;
        grid-column-start: 5;
        grid-column-end: 8;
    }

    .gallery-item-4 {
        grid-row-start: 2;
        grid-row-end: 3;
        grid-column-start: 2;
        grid-column-end: 4;
    }

    .gallery-item-5 {
        grid-row-start: 2;
        grid-row-end: 3;
        grid-column-start: 4;
        grid-column-end: 6;
    }

    .gallery-item-6 {
        grid-row-start: 2;
        grid-row-end: 3;
        grid-column-start: 6;
        grid-column-end: 8;
    }

    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .breadcrumbs-wrapper {
        margin: 10px 0;
        font-size: 14px;
        line-height: 22px;
        font-weight: 400;
    }

    .breadcrumbs__link {
        color: var(--blue-900);
        text-decoration: none;
    }

    .breadcrumbs__link:hover {
        text-decoration: underline;
    }

    .breadcrumbs-wrapper .city {
        color: var(--greyish-blue);
    }

    .ad-details-sidebar-container hr {
        margin: 0;
        border: none;
        border-bottom: 1px solid var(--gray-300);
    }

    .ad-details-bottom {
        padding-top: 20px;
    }

    .ad-details-owner {
        font-size: 20px;
        line-height: 28px;
        font-weight: 400;
        text-align: center;
        margin-bottom: 20px;
    }

    .ad-details-btn {
        width: 100%;
        height: 50px;
    }

    .breadcrumbs-header {
        padding: 1rem;
    }

    button.button-primary {
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
        outline: none;
    }

    .page-title {
        font-size: 20px;
        line-height: 1.5em;
        font-weight: 400;
        color: var(--text-color-primary);
        margin: 10px 0;
    }

    .ad-details-wrapper {
        width: -webkit-calc(100% - 40px);
        width: calc(100% - 40px);
        margin: 0 auto;
        padding: 10px 0;
    }

    .ad-sidebar {
        display: none;
    }

    .ad-details-sidebar-container {
        width: 360px;
        padding: 40px 30px;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background: var(--beige-400);
        border: 1px solid var(--gray-300);
        border-radius: 6px;
    }

    .ad-details-sidebar-title {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
        padding-bottom: 10px;
    }

    .ad-details-sidebar-title .price {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-align-items: flex-end;
        -moz-box-align: end;
        align-items: flex-end;
        font-weight: 400;
        margin-bottom: 10px;
    }

    .ad-details-sidebar-title .date {
        font-size: 14px;
        line-height: 17px;
        color: var(--greyish-blue);
        margin-bottom: 10px;
    }

    .ad-details-sidebar-title .price .value {
        font-size: 28px;
        margin-right: 6px;
    }

    .ad-details-sidebar-title .price .units {
        font-size: 16px;
        line-height: 1.4em;
    }

    @media (min-width: 768px) {

        .price-box {
            flex-direction: row;
            align-items: center;
        }

        .ad-details-wrapper {
            width: 568px;
            padding: 30px 0;
        }

        .rent-terms {
            display: flex;
        }

        .rent-terms > p:not(:last-child) {
            margin-right: 20px;
        }

        .chars {
            flex-direction: row;
        }

        .chars ul {
            width: calc(50% - 25px);
        }

        .main-facilities {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .main-facilities li {
            flex: calc(50% - 15px) 0;
        }

        .extra-facilities {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .facilities-group {
            flex: calc(50% - 15px) 0;
        }

        .ad-details_map-title__text {
            display: block;
            font-size: 16px;
            line-height: 30px;
            margin-bottom: 20px;
        }

        .chars-flex {
            flex-direction: row;
            width: 115%;
            justify-content: space-between;
        }

        .thumb-container {
            margin-top: 20px;
            height: 72px;
        }
    }

    @media (max-width: 370px) {
        .radio-text {
            line-height: 10px;
            font-size: 10px;
        }
    }

    @media(max-width: 1000px) {
        .ad-details-link > p {
            margin-bottom: 1.5rem;
        }
    }

    @media (min-width: 1024px) {
        .breadcrumbs-header {
            padding: 0;
        }

        .thumb-container {
            height: 68px;
            max-width: 895px;
            margin-right: auto;
            margin-left: auto;
            overflow: hidden;
        }

        .carousel-container {
            max-width: 895px;
            height: 500px;
        }

        .ad-details-wrapper {
            width: 944px;
            display: -webkit-flex;
            display: -moz-box;
            display: flex;
            -webkit-justify-content: space-between;
            -moz-box-pack: justify;
            justify-content: space-between;
            -webkit-align-items: flex-start;
            -moz-box-align: start;
            align-items: flex-start;
        }

        .ad-content {
            width: 570px;
        }

        .ad-sidebar {
            display: block;
            position: sticky;
            top: 30px;
        }

        .breadcrumbs-header {
            width: 852px;
            margin: 0 auto;
        }

        .extra-toggle-button {
            margin-bottom: 36px;
        }
    }

    @media (min-width: 1280px) {
        .thumb-container {
            height: 62px;
        }

        .ad-details-wrapper {
            width: 1140px;
        }

        .close_modal_btn_carousel {
            margin-right: 1rem;
        }
    }

</style>
