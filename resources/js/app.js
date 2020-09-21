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
import { BootstrapVue } from 'bootstrap-vue'
import VModal from 'vue-js-modal/dist/index.nocss.js'
import VueCarousel from '@chenfengyuan/vue-carousel';

import 'leaflet/dist/leaflet.css';
import "vue-select/src/scss/vue-select.scss";
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-js-modal/dist/styles.css'

delete L.Icon.Default.prototype._getIconUrl;

// Set open indicator invisible
vSelect.props.components.default = () => ({
    OpenIndicator: {
        render: createElement => createElement('span', ''),
    },
});

Vue.use(Vuex);
Vue.use(BootstrapVue);
Vue.use(VModal);
Vue.use(VueCarousel);

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

Vue.component('filter-bar-map', require('./components/Map/FilterBar.vue').default);
Vue.component('v-select', vSelect)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export const store = new Vuex.Store({
    state: {
        city: null,
        type_filter: [
            {
                text: 'Квартира',
                checked: false
            },
            {
                text: 'Комната',
                checked: false
            },
            {
                text: 'Дом',
                checked: false
            },
            {
                text: 'Часть дома',
                checked: false
            },
        ],
        room_filter: [
            {
                text: '1 комната',
                checked: false
            },
            {
                text: '2 комнаты',
                checked: false
            },
            {
                text: '3 комнаты',
                checked: false
            },
            {
                text: '4+ комнат',
                checked: false
            },
        ],
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
                    {name: "Сегодня", label: "Сегодня"},
                    {name: "2 дня", label: "Сегодня"},
                    {name: "3 дня", label: "Сегодня"},
                    {name: "Неделя", label: "Сегодня"},
                    {name: "2 недели", label: "Сегодня"},
                    {name: "Месяц", label: "Сегодня"},
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
        },
        setRoomType(state, newValue) {
            state.room_filter[newValue.id].checked = newValue.value;
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

const app = new Vue({
    el: '#app',
    store
});