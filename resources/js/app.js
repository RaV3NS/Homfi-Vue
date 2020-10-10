/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Leaflet css + icons pack
import L from 'leaflet';
import vSelect from 'vue-select'
import Vuex from 'vuex';
import VModal from 'vue-js-modal/dist/index.nocss.js'

import ToggleButton from 'vue-js-toggle-button'
import moment from 'moment';
import VueSlideToggle from 'vue-slide-toggle';
import VueCarousel from '@chenfengyuan/vue-carousel';
import VueAwesomeSwiper from 'vue-awesome-swiper';
import VueToast from 'vue-toast-notification';

import 'leaflet/dist/leaflet.css';
import "vue-select/src/scss/vue-select.scss";
import 'vue-js-modal/dist/styles.css'
import 'swiper/swiper-bundle.css'
import 'vuetify/dist/vuetify.min.css'
import 'vue-toast-notification/dist/theme-default.css';

delete L.Icon.Default.prototype._getIconUrl;

// Prototypes
Vue.prototype.$moment = moment;

// Set open indicator invisible
vSelect.props.components.default = () => ({
    OpenIndicator: {
        render: createElement => createElement('span', ''),
    },
});

Vue.use(VueToast);
Vue.use(VueCarousel);
Vue.use(Vuex);
Vue.use(VModal);
Vue.use(ToggleButton);
Vue.use(VueSlideToggle);
Vue.use(VueAwesomeSwiper, /* { default options with global component } */);

// Directives
import { VueMaskDirective } from 'v-mask'
Vue.directive('mask', VueMaskDirective);

// Reset the main icons
L.Icon.Default.mergeOptions({
    iconRetinaUrl: require('./components/assets/icons/marker-icon.svg'),
    iconUrl: require('./components/assets/icons/marker-icon.svg'),
    shadowUrl: require('./components/assets/icons/marker-icon.svg'),
    shadowSize: [0,0]
});

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-vue', require('./components/header.vue').default);
Vue.component('map-page', require('./components/MapPage.vue').default);
Vue.component('vue-map', require('./components/Map.vue').default);
Vue.component('advert-page', require('./components/AdvertPage.vue').default);
Vue.component('preview-page', require('./components/PreviewPage.vue').default);
Vue.component('create-advert', require('./components/CreateAdvert/CreateAdvert.vue').default);
Vue.component('edit-advert', require('./components/CreateAdvert/EditAdvert.vue').default);
Vue.component('pageloader', require('./components/Partials/Pageloader.vue').default);
Vue.component('profile', require('./components/Profile.vue').default);
Vue.component('filter-bar-map', require('./components/Map/FilterBar.vue').default);
Vue.component('vue-select', vSelect);

Vue.component('settings', require('./components/Settings.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

window.backend_url = "http://localhost:8000/";
window.user = {
    first_name: 'Александр',
    last_name: 'Франчук',
    email: 'archyzero@gmail.com',
    id: 193
};
window.authUser = {};
// axios.get( window.backend_url + 'api/user/193').then((response) => {
//    window.authUser = response.data;
// });
window.home_url_adverts = '/';

export const store = new Vuex.Store({
    state: {
        city: null,
        sortBy: {
            value: 'newest'
        },
        type_filter: [
            {
                id: 0,
                text: 'Квартира',
                checked: false,
                slug: 'kvartiry',
                prop: 'flat'
            },
            {
                id: 1,
                text: 'Комната',
                checked: false,
                slug: 'komnaty',
                prop: 'room'
            },
            {
                id: 2,
                text: 'Дом',
                checked: false,
                slug: 'doma',
                prop: 'house'
            },
            {
                id: 3,
                text: 'Часть дома',
                checked: false,
                slug: 'poldoma',
                prop: 'half-house'
            },
        ],
        type_filter_value: {
            value: []
        },
        room_filter: [
            {
                id: 0,
                text: '1 комната',
                checked: false,
                slug: 'odnokomnatnyie',
                prop: 1
            },
            {
                id: 1,
                text: '2 комнаты',
                checked: false,
                slug: 'dvuhkomnatnyie',
                prop: 2
            },
            {
                id: 2,
                text: '3 комнаты',
                checked: false,
                slug: 'trehkomnatnyie',
                prop: 3
            },
            {
                id: 3,
                text: '4+ комнат',
                checked: false,
                slug: 'chetyrehkomnatnyie',
                prop: 4
            },
        ],
        room_filter_value: {
            value: []
        },
        price_filter: {
            from: null,
            to: null
        },
        alt_filters: {
            search: {
                value: null
            },
            all_space: {
                from: null,
                to: null
            },
            kitchen_space: {
                from: null,
                to: null
            },
            living_space: {
                from: null,
                to: null
            },
            build_year: {
                from: null,
                to: null
            },
            wall_height: {
                from: null,
                to: null
            },
            publish_date: {
                value: null,
                options: [
                    {text: "Сегодня", value: 'today'},
                    {text: "2 дня", value: '2_days_ago'},
                    {text: "3 дня", value: '3_days_ago'},
                    {text: "Неделя", value: "week_ago"},
                    {text: "2 недели", value: "2_weeks_ago"},
                    {text: "Месяц", value: "month_ago"},
                ]
            },
            floor: {
                from: null,
                to: null
            },
            total_floor: {
                from: null,
                to: null
            },
            coop: {
                value: false
            },
            nofirst: {
                value: false
            },
            nolast: {
                value: false
            }
        }
    },
    mutations: {
        setCity(state, city) {
            state.city = city;
        },
        setFilterType(state, newValue) {
            state.type_filter[newValue.id].checked = newValue.value;

            if (newValue.value)
                state.type_filter_value.value.push(state.type_filter[newValue.id].slug);
            else
                state.type_filter_value.value = state.type_filter_value.value.filter(el => el !== state.type_filter[newValue.id].slug);
        },
        setRoomType(state, newValue) {
            state.room_filter[newValue.id].checked = newValue.value;

            if (newValue.value)
                state.room_filter_value.value.push(state.room_filter[newValue.id].slug);
            else
                state.room_filter_value.value = state.room_filter_value.value.filter(el => el !== state.room_filter[newValue.id].slug);
        },
        setFilter(state, obj) {
            state[obj.prop][obj.field] = obj.value;
        },
        setAltFilter(state, obj) {
            state.alt_filters[obj.prop][obj.field] = obj.value;
        }
    },
    actions: {
        setCity({ commit }, city) {
            commit("setCity", city);
        },
        setFilterType({ commit }, newValue) {
            commit("setFilterType", newValue);
        },
        setRoomType({ commit }, newValue) {
            commit("setRoomType", newValue);
        },
        setFilter({ commit }, newValue) {
            commit("setFilter", newValue);
        },
        setAltFilter({ commit }, newValue) {
            commit("setAltFilter", newValue);
        }
    }
});

import vuetify from './vuetify'

const app = new Vue({
    el: '#app',
    store,
    vuetify
});
