<template>
    <div>
        <pageloader v-if="loading"></pageloader>
        <v-stepper v-model="step" class="no-shadow">
            <v-stepper-header>
                <v-stepper-step
                    :complete="step > 1"
                    step="1"
                    class="step-click"
                    @click="gotoStep(1)"
                >
                    Адрес
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    :complete="step > 2"
                    step="2"
                    class="step-click"
                    @click="gotoStep(2)"
                >
                    Характеристики
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    :complete="step > 3"
                    step="3"
                    class="step-click"
                    @click="gotoStep(3)"
                >
                    Удобства
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    :complete="step > 4"
                    step="4"
                    class="step-click"
                    @click="gotoStep(4)"
                >
                    Фотографии
                </v-stepper-step>

                <v-divider></v-divider>

                <v-stepper-step
                    step="5"
                    class="step-click"
                    @click="gotoStep(5)"
                >
                    Контактные данные
                </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content step="1">
                    <v-form
                        ref="geoform"
                        lazy-validation
                    >
                        <v-container class="create-advert-600">
                        <label>Область *</label>
                        <v-autocomplete
                            solo
                            v-model="district"
                            :items="districts"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            placeholder="Область"
                            class="advert-input"
                            dense
                            :rules="[v => !!v || 'Область обязательное поле для заполнения']"
                        ></v-autocomplete>

                        <label>Город *</label>
                        <v-autocomplete
                            solo
                            v-model="city"
                            placeholder="Город"
                            :items="cities"
                            :disabled="cityDisabled"
                            :search-input.sync="citySearch"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-input"
                            dense
                            return-object
                            :rules="[v => !!v || 'Город обязательное поле для заполнения']"
                        ></v-autocomplete>

                        <label>Административный район</label>
                        <v-autocomplete
                            solo
                            v-model="administrative"
                            label="Административный район"
                            :items="administratives"
                            :disabled="administrativeDisabled"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-input"
                            dense
                        ></v-autocomplete>

                        <label>Улица *</label>
                        <v-autocomplete
                            solo
                            v-model="street"
                            label="Улица"
                            :items="streets"
                            :disabled="streetsDisabled"
                            :search-input.sync="streetsSearch"
                            return-object
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            class="advert-input"
                            dense
                            :rules="[v => !!v || 'Улица обязательное поле для заполнения']"
                        ></v-autocomplete>

                        <label>Номер дома</label>
                        <v-text-field placeholder="Номер дома" v-model="houseNumber" outlined dense class="advert-input"></v-text-field>

                        <label>Станция метро</label>
                        <v-autocomplete
                            solo
                            v-model="subway"
                            label="Станция метро"
                            :items="subways"
                            :disabled="subwayDisabled"
                            no-data-text="Мы не нашли результатов по вашему запросу"
                            dense
                            class="advert-input"
                        ></v-autocomplete>

                        <v-btn elevation="2" class="map-btn" @click="getMapCoords">Проверить на карте</v-btn>

                        <div class="loading-block" v-if="mapLoading">
                            <Preloader></Preloader>
                            <span>Подождите, координаты дома проверяются на карте.</span>
                        </div>

                        <div class="map" v-if="mapLatLng.length > 0">
                            <span class="map-text">Обратите внимание, что данная информация будет доступна всем пользователям
                                и они получат возможность проложить маршрут к вашему объекту недвижимости.</span>

                            <l-map :zoom="15" :center="mapLatLng" ref="map">
                                <l-tile-layer url="https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png"></l-tile-layer>
                                <l-marker
                                    :lat-lng="mapLatLng"
                                    :icon="getMarkerIcon()"
                                >
                                </l-marker>
                            </l-map>
                        </div>

                        <v-btn
                            color="primary"
                            elevation="1"
                            large
                            @click="validateStep1"
                            :class="{mapActive: mapLatLng.length > 0, 'btn-next': 1}"
                        >Далее</v-btn>
                    </v-container>
                    </v-form>
                </v-stepper-content>
                <v-stepper-content step="2">
                    <v-container class="create-advert-800">
                        <div class="characteristics flex justify-space-between">
                            <v-form
                                ref="form"
                                lazy-validation
                            >
                            <v-row>
                                <v-col cols="12" sm="5">
                                    <label>Цена, грн*</label>
                                    <v-text-field
                                        placeholder="Цена, грн"
                                        v-model="price"
                                        outlined
                                        dense
                                        class="advert-input"
                                        :rules="price_rules"
                                        type="number"
                                    ></v-text-field>

                                    <label>Тип*</label>
                                    <v-select
                                        :items="house_types"
                                        outlined
                                        dense
                                        placeholder="Тип"
                                        v-model="type"
                                        class="advert-input"
                                        :rules="[v => !!v || 'Тип обязательное поле для заполнения']"
                                    ></v-select>

                                    <label>Количество комнат*</label>
                                    <v-select
                                        :items="room_types"
                                        outlined
                                        dense
                                        placeholder="Количество комнат"
                                        v-model="rooms_count"
                                        class="advert-input"
                                        :rules="[v => !!v || 'Кол-во комнат обязательное поле для заполнения']"
                                    ></v-select>

                                    <label>Общая площадь, м²*</label>
                                    <v-text-field
                                        placeholder="Общая площадь, м²"
                                        v-model="total_space"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                        :rules="[v => !!v || 'Общая площадь обязательное поле для заполнения']"
                                    ></v-text-field>

                                    <label>Жилая площадь, м²</label>
                                    <v-text-field
                                        placeholder="Жилая площадь, м²"
                                        v-model="living_space"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                    ></v-text-field>

                                    <label>Площадь кухни, м²</label>
                                    <v-text-field
                                        placeholder="Площадь кухни, м²"
                                        v-model="kitchen_space"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                    ></v-text-field>

                                    <label>Высота потолков, м</label>
                                    <v-text-field
                                        placeholder="Высота потолков, м"
                                        v-model="height"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                    ></v-text-field>
                                </v-col>

                                <v-col cols="12" offset-sm="2" sm="5">
                                    <label>Этажность*</label>
                                    <v-text-field
                                        placeholder="Этажность"
                                        v-model="total_floors"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                        :rules="[v => !!v || 'Этажность обязательное поле для заполнения']"
                                    ></v-text-field>

                                    <label>Этаж*</label>
                                    <v-text-field
                                        placeholder="Этаж"
                                        v-model="floor"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                        :rules="[v => !!v || 'Этаж обязательное поле для заполнения']"
                                    ></v-text-field>

                                    <label>Отопление</label>
                                    <v-select
                                        :items="heating_types"
                                        outlined
                                        dense
                                        placeholder="Отопление"
                                        v-model="heating"
                                        class="advert-input"
                                    ></v-select>

                                    <label>Год постройки</label>
                                    <v-text-field
                                        placeholder="Год постройки"
                                        v-model="build_year"
                                        outlined
                                        dense
                                        class="advert-input"
                                        type="number"
                                    ></v-text-field>

                                    <label>Коммунальные платежи</label>
                                    <v-select
                                        :items="communals_types"
                                        outlined
                                        dense
                                        placeholder="Коммунальные платежи"
                                        v-model="communals"
                                        class="advert-input"
                                    ></v-select>

                                    <div v-if="communals && communals === 'non-included'">
                                        <label>Средняя стоимость</label>
                                        <div class="flex justify-content-between">
                                            <v-text-field
                                                placeholder="Зима"
                                                v-model="communals_winter"
                                                outlined
                                                dense
                                                class="mr-5 advert-input"
                                                type="number"
                                            ></v-text-field>
                                            <v-text-field
                                                placeholder="Лето"
                                                v-model="communals_summer"
                                                outlined
                                                dense
                                                class="advert-input"
                                                type="number"
                                            ></v-text-field>
                                        </div>
                                    </div>

                                    <label>Дата сдачи</label>
                                    <v-menu

                                        v-model="fromDateMenu"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        max-width="290px"
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on }">
                                            <v-text-field
                                                readonly
                                                :value="rent_date"
                                                v-on="on"
                                                outlined
                                                dense
                                                placeholder="Дата сдачи"
                                                class="advert-input"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            locale="ru-in"
                                            v-model="fromDateVal"
                                            no-title
                                            @input="fromDateMenu = false"
                                        ></v-date-picker>
                                    </v-menu>

                                    <label>Срок сдачи, мес</label>
                                    <div class="flex justify-content-between">
                                        <v-text-field
                                            placeholder="Срок сдачи от"
                                            v-model="rent_from"
                                            outlined
                                            dense
                                            class="mr-5 advert-input"
                                            type="number"
                                        ></v-text-field>
                                        <v-text-field
                                            placeholder="до"
                                            v-model="rent_to"
                                            outlined
                                            dense
                                            class="advert-input"
                                            type="number"
                                        ></v-text-field>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <div class="flex justify-space-between">
                                        <label>Описание</label>
                                        <label>{{ blength }} / 3000</label>
                                    </div>

                                    <v-textarea
                                        outlined
                                        name="input-7-4"
                                        v-model="body"
                                        class="advert-input"
                                        :rules="body_rules"
                                    ></v-textarea>
                                </v-col>
                            </v-row>
                            </v-form>
                        </div>

                        <div class="action-container">
                            <v-btn
                                elevation="1"
                                large
                                text
                                class="back-btn"
                                @click="gotoStep(1)"
                            >Назад</v-btn>

                            <v-btn
                                color="primary"
                                elevation="1"
                                large
                                @click="validateStep2"
                            >Далее</v-btn>
                        </div>
                    </v-container>
                </v-stepper-content>
                <v-stepper-content step="3">
                    <v-container class="create-advert-800">
                        <v-row>
                            <v-col cols="12" md="6">
                                <h6>Удобства</h6>

                                <v-checkbox
                                    v-for="item in getOptionsByCategory('additional')"
                                    v-model="selected_options"
                                    :label="item.name_ru"
                                    :value="item.id"
                                    :key="item.id"
                                ></v-checkbox>
                            </v-col>
                            <v-col cols="12" offset-md="1" md="5">
                                <div class="mb-7">
                                    <h6>Инфраструктура</h6>

                                    <v-checkbox
                                        v-for="item in getOptionsByCategory('infrastructure')"
                                        v-model="selected_options"
                                        :label="item.name_ru"
                                        :value="item.id"
                                        :key="item.id"
                                    ></v-checkbox>
                                </div>

                                <div class="mb-7">
                                    <h6>Бытовые удобства</h6>

                                    <v-checkbox
                                        v-for="item in getOptionsByCategory('household')"
                                        v-model="selected_options"
                                        :label="item.name_ru"
                                        :value="item.id"
                                        :key="item.id"
                                    ></v-checkbox>
                                </div>

                                <div>
                                    <h6>Другое</h6>

                                    <v-checkbox
                                        v-for="item in getOptionsByCategory('other')"
                                        v-model="selected_options"
                                        :label="item.name_ru"
                                        :value="item.id"
                                        :key="item.id"
                                    ></v-checkbox>
                                </div>
                            </v-col>
                        </v-row>

                        <div class="action-container">
                            <v-btn
                                elevation="1"
                                large
                                text
                                class="back-btn"
                                @click="gotoStep(2)"
                            >Назад</v-btn>

                            <v-btn
                                color="primary"
                                elevation="1"
                                large
                                @click="step = 4"
                            >Далее</v-btn>
                        </div>
                    </v-container>
                </v-stepper-content>
                <v-stepper-content step="4">
                    <v-container class="create-advert-800 photo-wrap-container">
                        <div class="no-photo" v-if="photos.length === 0">
                            <div class="photo-upload-container">
                                <div class="photo-upload-block">
                                    <p class="title">Доступно 24 фото для загрузки</p>
                                    <p class="text">Форматы: jpeg, jpg, png.</p>
                                    <p class="text">Делайте качественные снимки вашего жилья. Помните, что первое фото будут видеть ваши гости при поиске жилья. По каждому из объектов обязательно должны быть представлены общие фотографии всех комнат, кухни и санузла.</p>

                                    <upload-btn
                                        @file-update="onFilesLoaded"
                                        multiple
                                        noTitleUpdate
                                        large
                                        title="Загрузить фотографии"
                                        class="vue-upload-btn mt-5"
                                    ></upload-btn>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <div class="flex flex-wrap photo-block" v-if="step === 4">
                                <draggable v-model="photos" @change="photosUpdated = true">
                                    <vue-photo
                                        v-for="(photo, index) in photos"
                                        :src="getSrc(photo)"
                                        :id="index"
                                        :key="index"
                                        v-on:remove="removePhoto"
                                        v-on:rotate="updatePhotoRotation"
                                    ></vue-photo>
                                </draggable>

                                <upload-btn
                                    @file-update="onFilesLoaded"
                                    multiple
                                    noTitleUpdate
                                    large
                                    title="Загрузить больше фотографий"
                                    class="upload-area"
                                ></upload-btn>
                            </div>
                        </div>

                        <div class="action-container">
                            <v-btn
                                elevation="1"
                                large
                                text
                                class="back-btn"
                                @click="gotoStep(3)"
                            >Назад</v-btn>

                            <v-btn
                                color="primary"
                                elevation="1"
                                large
                                @click="validateStep4"
                                :disabled="!navEnabled"
                            >Далее</v-btn>
                        </div>
                    </v-container>
                </v-stepper-content>
                <v-stepper-content step="5">
                    <v-form
                        ref="contacts_form"
                    >
                        <v-container class="create-advert-600">
                        <label>Фамилия</label>
                        <v-text-field
                            placeholder="Фамилия"
                            v-model="last_name"
                            outlined
                            dense
                            class="advert-input"
                        ></v-text-field>

                        <label>Имя</label>
                        <v-text-field
                            placeholder="Имя"
                            v-model="first_name"
                            outlined
                            dense
                            class="advert-input"
                        ></v-text-field>

                        <div class="number" v-for="(phone, index) in phones">
                            <label>Номер телефона</label>
                            <v-row class="number-row">
                                <v-col cols="12" sm="6" class="number-col">
                                    <v-text-field
                                        placeholder="+380 --- -- --"
                                        outlined
                                        dense
                                        class="advert-input"
                                        :rules="phoneRules"
                                        v-model="phone.number"
                                        v-mask="'+380 ## ### ## ##'"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3" sm="1" class="number-col">
                                    <v-checkbox value="viber" v-model="phone.messengers">
                                        <template v-slot:label>
                                            <div>
                                                <img src="/icons/socials/viber.svg" alt="viber">
                                            </div>
                                        </template>
                                    </v-checkbox>
                                </v-col>
                                <v-col cols="3" sm="1" class="number-col">
                                    <v-checkbox value="whatsapp" v-model="phone.messengers">
                                        <template v-slot:label>
                                            <div>
                                                <img src="/icons/socials/whatsapp.svg" alt="whatsapp">
                                            </div>
                                        </template>
                                    </v-checkbox>
                                </v-col>
                                <v-col cols="3" sm="1" class="number-col">
                                    <v-checkbox value="telegram" v-model="phone.messengers">
                                        <template v-slot:label>
                                            <div>
                                                <img src="/icons/socials/telegram.svg" alt="telegram">
                                            </div>
                                        </template>
                                    </v-checkbox>
                                </v-col>
                                <v-col cols="3" sm="1" v-if="phones.length > 1" class="number-col">
                                    <img
                                        src="/icons/close.svg"
                                        alt="close"
                                        class="close-btn"
                                        @click="deleteNumber(index)"
                                    >
                                </v-col>
                            </v-row>
                        </div>

                        <v-btn
                            text
                            @click="addNumber"
                        >+ Добавить номер</v-btn>

                        <label class="mt-5">Email*</label>
                        <v-text-field
                            placeholder="example@gmail.com"
                            v-model="email"
                            outlined
                            dense
                            :rules="emailRules"
                            class="advert-input"
                        ></v-text-field>

                        <label>Gmail</label>
                        <v-text-field
                            placeholder="example@gmail.com"
                            v-model="gmail"
                            outlined
                            dense
                            class="advert-input"
                        ></v-text-field>

                        <label>Skype</label>
                        <v-text-field
                            placeholder="Укажите свой логин"
                            v-model="skype"
                            outlined
                            dense
                            class="advert-input"
                        ></v-text-field>

                        <label>Facebook</label>
                        <div class="flex justify-space-between">
                            <v-text-field
                                placeholder="Ссылка на профиль"
                                v-model="facebook"
                                outlined
                                dense
                                class="advert-input mr-5"
                            ></v-text-field>

                            <v-checkbox value="messenger" class="messenger-box" v-model="facebook_messenger">
                                <template v-slot:label>
                                    <div>
                                        <img src="/icons/socials/messenger.svg" alt="messenger">
                                    </div>
                                </template>
                            </v-checkbox>
                        </div>

                        <label>Instagram</label>
                        <v-text-field
                            placeholder="Ссылка на профиль"
                            v-model="instagram"
                            outlined
                            dense
                            class="advert-input"
                        ></v-text-field>

                        <label>Показывать контакты</label>
                        <v-radio-group
                            v-model="show"
                            row
                        >
                            <v-radio
                                label="Всем желающим"
                                value="1"
                            ></v-radio>

                            <v-radio
                                label="Зарегистрированым пользователям"
                                value="0"
                            ></v-radio>
                        </v-radio-group>

                        <div class="action-container">
                            <v-btn
                                elevation="1"
                                large
                                text
                                class="back-btn"
                                @click="gotoStep(4)"
                            >Назад</v-btn>

                            <v-btn
                                color="primary"
                                elevation="1"
                                large
                                @click="validateStep5"
                                :disabled="!navEnabled"
                            >Далее</v-btn>
                        </div>
                    </v-container>
                    </v-form>
                </v-stepper-content>
            </v-stepper-items>

        </v-stepper>
    </div>
</template>

<script>
    import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';
    import UploadButton from 'vuetify-upload-button';
    import Photo from "../Partials/Photo";
    import draggable from 'vuedraggable'
    import Preloader from "../Partials/Preloader";

    export default {
        components: {
            LMap,
            LTileLayer,
            LMarker,
            'upload-btn': UploadButton,
            'vue-photo': Photo,
            draggable,
            Preloader
        },
        data: function() {
            return {
                advert: null,
                photos: [],
                photos_request: [],
                photosUpdated: false,
                step: 1,

                districts: [],
                district: null,
                navEnabled: true,

                cities: [],
                city: null,
                cityDisabled: true,
                citySearch: null,
                citiesLoading: false,

                administrative: null,
                administratives: [],
                administrativeDisabled: true,

                streets: [],
                streetsLoading: false,
                streetsDisabled: true,
                streetsSearch: null,
                street: null,

                houseNumber: null,

                subway: null,
                subways: [],
                subwayDisabled: true,

                mapLoading: false,
                mapLatLng: [],

                // Characteristics
                house_types: [
                    { text: 'Квартира', value: 'flat' },
                    { text: 'Дом', value: 'house' },
                    { text: 'Полдома', value: 'half-house' },
                    { text: 'Комната', value: 'room' },
                ],
                room_types: [
                    { text: '1 комната', value: '1' },
                    { text: '2 комнаты', value: '2' },
                    { text: '3 комнаты', value: '3' },
                    { text: '4+ комнат', value: '4' },
                ],
                heating_types: [
                    { text: 'Без отопления', value: "no" },
                    { text: 'Централизованное', value: "central" },
                    { text: 'Автономное', value: "autonomous" },
                ],
                communals_types: [
                    { text: 'Включены', value: "included" },
                    { text: 'Оплачиваются дополнительно', value: "non-included" },
                ],

                heating: null,
                rooms_count: null,
                type: null,
                price: null,
                floor: null,
                total_floors: null,
                total_space: null,
                living_space: null,
                kitchen_space: null,
                communals: null,
                communals_summer: null,
                communals_winter: null,
                build_year: null,
                height: null,
                rent_from: null,
                rent_to: null,

                // Datepicker
                fromDateMenu: false,
                fromDateVal: null,

                body: '',

                // Rules
                body_rules: [
                    v => (v.length <= 3000) || 'Поле описание может вмещать в себя максимум 3000 символов',
                ],
                price_rules: [
                    v => !!v || 'Цена обязательное поле для заполнения',
                    v => (v > 0) || 'Некорректная цена'
                ],

                // Options
                options: [],
                selected_options: [],

                // Contacts
                first_name: window.user.first_name,
                last_name: window.user.last_name,
                phones: [],
                emailRules: [
                    v => !!v || 'E-mail обязательное поле для заполнения',
                    v => /.+@.+/.test(v) || 'Неверный формат e-mail адреса',
                ],
                phoneRules: [
                    v => !!v || 'Телефон обязательное поле для заполнения',
                    v => v && v.length === 17 || 'Неверный формат телефона',
                ],
                email: window.user.email,
                gmail: null,
                skype: null,
                facebook: null,
                instagram: null,
                show: "1",
                facebook_messenger: false,

                request: {},
                loading: true
            }
        },
        props: ["advert_id"],
        computed: {
            rent_date() {
                if (this.fromDateVal)
                    return this.$moment(this.fromDateVal).format("DD.MM.YYYY");
                else
                    return null;
            },
            blength() {
                return this.body ? this.body.length : 0;
            }
        },
        mounted() {
            this.getDistricts();
            this.getAdvert(this.advert_id);

            if (localStorage.getItem('advert')) {
                let item = JSON.parse(localStorage.getItem('advert'));
                if (parseInt(this.advert_id) === parseInt(item.id))
                    this.step = item.step;
            }
        },
        methods: {
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
                                    value: el.id
                                });
                            })

                            vm[param + 'Loading'] = false;
                        })
            }, 350),
            getAdvert(id) {
                axios.get(window.backend_url + `api/user/${window.user.id}/adverts/${id}`)
                .then((response) => {
                    this.advert = response.data;
                    this.setValues();
                });
            },
            setValues() {
               this.district = this.advert.city.region.district_id;
               this.houseNumber = this.advert.address;
               if (this.advert.subway)
                  this.subway = this.advert.subway.id;
               this.getAdvertParameters();

               this.advert.options.map((option) => {
                  this.selected_options.push(option.id);
               });

               this.advert.images.map((el) => {
                   this.photos.push({ file: null, src: el['720p'], id: el.id, rotation: 0 });
               })

               this.first_name = this.advert.first_name;
               this.last_name = this.advert.last_name;

               this.advert.phones.map((el) => {
                   this.phones.push({ number: el.number, messengers: el.messengers })
               })

               this.gmail = this.advert.social_links.email;
               this.facebook = this.advert.social_links.facebook;
               this.facebook_messenger = this.advert.social_links.facebook_messenger;
               this.instagram = this.advert.social_links.instagram;
               this.skype = this.advert.social_links.skype;

               this.show = this.advert.show_contacts.toString();

               setTimeout(() => {
                   this.loading = false;
               }, 1500);
            },
            getDistricts() {
                axios.get(window.backend_url + 'api/geo/districts').then((response) => {
                    let data = response.data;
                    this.districts = [];
                    data.map((el) => {
                        this.districts.push({
                           text: el.name_ru,
                           value: el.id
                        });
                    })
                })
            },
            getAdministratives(id) {
                axios.get(window.backend_url + `api/geo/search?city_id=${id}&type=administratives`)
                    .then((response) => {
                        let data = response.data;
                        this.administratives = [];
                        data.map((el) => {
                            this.administratives.push({
                               text: el.name_ru,
                               value: el.id
                            });
                        })

                        if (this.administratives.length > 0)
                            this.administrativeDisabled = false;
                        else
                            this.administrativeDisabled = true;

                        if (this.city.value === this.advert.city.id && this.district === this.advert.city.region.district_id)
                            this.administrative = this.advert.administrative_id;
                    });
            },
            getSubways(id) {
                axios.get(window.backend_url + `api/geo/search?city_id=${id}&type=subways`)
                    .then((response) => {
                        let data = response.data;
                        this.subways = [];

                        data.map((el) => {
                            this.subways.push({
                                text: el.name_ru,
                                value: el.id
                            });
                        })

                        this.subways.sort(function(a, b){
                            if(a.text < b.text) { return -1; }
                            if(a.text > b.text) { return 1; }
                            return 0;
                        })

                        if (this.subways.length > 0)
                            this.subwayDisabled = false;
                        else
                            this.subwayDisabled = true;
                    });
            },
            getMapCoords() {
                this.mapLatLng = [];

                if (this.district && this.city && this.street) {
                    this.mapLoading = true;

                    let data = {
                       city_id: this.city.value,
                       street_id: this.street.value,
                       district_id: this.district
                    };

                    if (this.houseNumber) data.address = this.houseNumber;

                    axios.post(window.backend_url + `api/user/${window.user.id}/adverts/address`, data).then((response) => {
                        this.mapLatLng = [response.data.lat, response.data.lng];
                        this.mapLoading = false;
                    });
                }
            },
            saveMapCoords() {
               if (this.mapLatLng.length > 0) {
                   let request = { lat: this.mapLatLng[0], lng: this.mapLatLng[1] };
                   this.updateAdvert(request);
               } else {
                   if (this.district && this.city && this.street) {
                       this.mapLoading = true;

                       let data = {
                           city_id: this.city.value,
                           street_id: this.street.value,
                           district_id: this.district
                       };

                       if (this.houseNumber) data.address = this.houseNumber;

                       axios.post(window.backend_url + `api/user/${window.user.id}/adverts/address`, data).then((response) => {
                           this.mapLatLng = [response.data.lat, response.data.lng];
                           this.mapLoading = false;

                           let request = {lat: response.data.lat, lng: response.data.lng};
                           this.updateAdvert(request, 'geo');
                       });
                   }
               }
            },
            getMarkerIcon() {
                return L.divIcon({
                    className: "marker-house",
                    html: `<img src="/icons/pin-home.svg" alt="marker">`
                });
            },
            validateStep1(next = true) {
                if (this.$refs.geoform.validate())
                    if (next)
                        this.step = 2;
                    else
                        return true;
            },
            validateStep2(next = true) {
                if (this.$refs.form.validate()) {
                    if (next)
                        this.step = 3;
                    else
                        return true;
                }
            },
            getSrc(photo) {
               if (photo.file)
                   return this.blob(photo.file);
               if (photo.src)
                   return photo.src;
            },
            getOptions() {
                axios.get(window.backend_url + 'api/adverts/options').then((response) => {
                    this.options = response.data;
                });
            },
            getOptionsByCategory(category) {
                return this.options.filter(el => el.category === category);
            },
            onFilesLoaded(files) {
                Array.from(files).map((el) => {
                   if (el.type === 'image/jpeg' || el.type === 'image/webp' || el.type === 'image/png') {
                       this.photos.push({ file: el, rotation: 0 });
                   } else {
                       let text = `Ошибка при загрузке файла ${el.name}. Недопустимый формат файла`;
                       this.$toast.error( text , {
                           position: 'top-right',
                           duration: 5000,
                           dismissible: true
                       })
                   }
                });
            },
            updatePhotoRotation(payload) {
                this.photos[payload.id].rotation = payload.rotation;
            },
            blob(file) {
                return URL.createObjectURL(file);
            },
            removePhoto(payload) {
                let server_id = this.photos[payload.id].id;
                if (server_id) {
                    this.photosUpdated = true;
                    let index = this.photos_request.findIndex(x => x.id === server_id);
                    this.photos_request.splice(index, 1);
                }
                this.photos.splice(payload.id, 1);
            },
            addNumber() {
                this.phones.push({ number: "", messengers: [] });
            },
            deleteNumber(index) {
                this.phones.splice(index, 1);
            },
            updatePhotos(next = true) {
                let html = "<div class='toast-flex'><img src='/icons/spinner.svg'> Подождите, фотографии обновляются</div>";
                let loader = this.$toast.default( html , {
                    position: 'top-right',
                    duration: 100000,
                    dismissible: false
                })

                let request = { photos: this.photos_request };
                axios.put(window.backend_url + `api/user/${window.user.id}/adverts/${this.advert.id}/photo`, request)
                    .then((response) => {
                        this.navEnabled = true;
                        this.photosUpdated = false;
                        loader.close();

                        if (next)
                            this.step = 5;
                        else
                            return true;
                    });
            },
            validateCurrentStep() {
                if (this.step === 1)
                    return this.validateStep1(false);

                if (this.step === 2)
                    return this.validateStep2(false);

                if (this.step === 4)
                    return this.validateStep4(false);

                return true;

            },
            gotoStep(step) {
               if (this.navEnabled)
                   if (this.validateCurrentStep())
                        this.step = step;
            },
            validateStep4(next = true) {
                let form = new FormData();
                let files = [];
                let rotation = [];
                let newPhotos = 0;

                this.photos.map((el) => {
                    if (el.file) {
                        this.photosUpdated = true;
                        newPhotos++;
                        form.append("photos[]", el.file);
                        form.append("rotation[]", el.rotation);
                    }
                });

                if (this.photosUpdated === true) {
                    // preload image upload
                    this.navEnabled = false;
                    let html = "<div class='toast-flex'><img src='/icons/spinner.svg'> Подождите, фотографии загружаются</div>";
                    let loader = this.$toast.default( html , {
                        position: 'top-right',
                        duration: 100000,
                        dismissible: false
                    })

                    if (newPhotos > 0) {
                        axios.post(window.backend_url + `api/user/${window.user.id}/adverts/` + this.advert_id + '/photo', form, {
                            headers: {
                                "Content-Type": "multipart/form-data"
                            }
                        }).then((response) => {
                            if (response.data) {

                                let index = 0;
                                this.photos.map((el) => {
                                   if (el.id)
                                       this.photos_request.push({ id: el.id, rotation: el.rotation });
                                   else {
                                       if (response.data[index]) {
                                           this.photos[index].id = response.data[index].id;
                                           this.photos[index].rotation = response.data[index].rotation;
                                           this.photos_request.push(response.data[index]);
                                           index++;
                                       }
                                   }
                                });

                                loader.close();

                                if (next)
                                    this.updatePhotos();
                                else
                                    this.updatePhotos(false);
                            }
                        });
                    } else {
                        this.photos.map((el) => {
                            if (el.id)
                                this.photos_request.push({id: el.id, rotation: el.rotation});
                        });

                        this.updatePhotos();
                        loader.close();
                    }
                } else {
                    if (next)
                        this.step = 5;
                    else
                        return true;
                }
            },
            validateStep5() {
                if (this.$refs.contacts_form.validate()) {
                    let request = {};

                    request.email = this.email;
                    request.first_name = this.first_name;
                    request.last_name = this.last_name;
                    request.phones = this.phones;
                    request.show_contacts = this.show ? 1 : 0;
                    request.social_links = {
                        email: this.gmail,
                        facebook: this.facebook,
                        facebook_messenger: this.facebook_messenger ? 1 : 0,
                        instagram: this.instagram,
                        skype: this.skype
                    };

                    this.navEnabled = false;
                    this.updateAdvert(request, 'contacts');
                }
            },
            setParameters() {

                let parameters = [];
                let aliases = [
                    { id: 1, key: "type", value: this.type },
                    { id: 2, key: "room_count", value: this.rooms_count },
                    { id: 3, key: "total_space", value: this.total_space },
                    { id: 4, key: "living_space", value: this.living_space },
                    { id: 5, key: "kitchen_space", value: this.kitchen_space },
                    { id: 6, key: "height", value: this.height },
                    { id: 7, key: "floor", value: this.floor },
                    { id: 8, key: "total_floors", value: this.total_floors },
                    { id: 9, key: "heating", value: this.heating },
                    { id: 10, key: "build_year", value: this.build_year },
                    { id: 11, key: "communals", value: this.communals },
                    { id: 12, key: "rent_date", value: this.rent_date },
                    { id: 13, key: "rent_term_min", value: this.rent_from },
                    { id: 15, key: "rent_term_max", value: this.rent_to },
                    { id: 16, key: "communals_winter", value: this.communals_winter },
                    { id: 17, key: "communals_summer", value: this.communals_summer },
                ];

                aliases.map((el) => {
                    parameters.push({ id: el.id, key: el.key, value: el.value });
                })

                return parameters;
            },
            getAdvertParameters() {
                let parameters = this.advert.parameters;
                let aliases = [
                    { id: 2, key: "room_count", value: 'rooms_count' },
                    { id: 13, key: "rent_term_min", value: 'rent_from' },
                    { id: 15, key: "rent_term_max", value: 'rent_to' },
                ];

                parameters.map((el) => {
                    let found = aliases.filter(alias => el.key === alias.key);
                    if (found[0]) {
                        this[found[0].value] = el.value.key;
                    } else
                        this[el.key] = el.value.key;
                })

                this.price = this.advert.price_month;
                this.body = this.advert.body;
            },
            updateAdvert(request, step = 'initial') {

                if (step === 'parameter')
                    axios.post(window.backend_url + `api/user/${window.user.id}/adverts/${this.advert.id}/parameter`, request)
                        .then((response) => {
                            if (step === 'geo')
                                this.saveMapCoords();

                            if (step === 'contacts')
                                window.location = '/adverts/preview/' + this.advert.id;
                        });
                if (step === 'initial')
                    axios.put(window.backend_url + `api/user/${window.user.id}/adverts/` + this.advert_id, request)
                        .then((response) => {
                            if (step === 'geo')
                                this.saveMapCoords();

                            if (step === 'contacts')
                                window.location = '/adverts/preview/' + this.advert.id;
                        });
            }
        },
        watch: {
            district: function(id) {
                this.cityDisabled = false;

                if (id === this.advert.city.region.district_id) {
                    this.city = { text: this.advert.city.name_ru, value: this.advert.city.id };

                    this.getAdministratives(this.advert.city.id);

                    this.streets.push({
                       text: this.advert.street.name_ru,
                       value: this.advert.street.id
                    });

                    this.street = {
                        text: this.advert.street.name_ru,
                        value: this.advert.street.id
                    };

                    this.cities.push({
                        text: this.advert.city.name_ru,
                        value: this.advert.city.id
                    });

                } else {
                    this.city = null;
                    this.street = null;
                    this.administrative = null;

                    this.cities = [];
                    this.streets = [];
                    this.administratives = [];
                }
            },
            citySearch: function(value) {
                this.citiesLoading = true;
                let url = window.backend_url + 'api/geo/cities?district_id=' + this.district + '&query=' + encodeURI(value);
                if (value && value.trim() !== '')
                    this.search(this, url, 'cities', 'city');
                else
                    this.citiesLoading = false;
            },
            city: function(city) {
                if (city === null) {
                    this.administratives = [];
                    this.subways = [];
                } else {
                    this.getAdministratives(city.value);
                    this.getSubways(city.value);
                }

                this.streetsDisabled = false;

                if (city.value !== this.advert.city.id) {
                    this.street = null;
                    this.streets = [];
                    this.administrative = null;
                    this.subway = null;
                }
            },
            streetsSearch: function(value) {
                this.streetsLoading = true;
                let url = window.backend_url + 'api/geo/search?city_id=' + this.city.value + '&type=streets&query=' + encodeURI(value);
                if (value && value.trim() !== '')
                    this.search(this, url, 'streets', 'street');
                else
                    this.streetsLoading = false;
            },
            step: function(newValue, value) {
                let request = {};

                if (this.advert_id) {
                    localStorage.setItem('advert', JSON.stringify({ id: this.advert_id, step: newValue }));
                }

                switch (newValue) {
                    case 1:
                        // Leaflet Rerender
                        window.dispatchEvent(new Event('resize'));
                    break;
                    case 3:
                        this.getOptions();
                    break;
                }

                switch (value) {
                    case 1:

                        request.city_id = this.city.value;
                        request.district_id = this.district;
                        request.street_id = this.street.value;
                        request.address = this.houseNumber;
                        request.subway_id = this.subway;
                        request.administrative_id = this.administrative;

                        this.updateAdvert(request, 'geo');
                    break;

                    case 2:

                        request.price_month = this.price;
                        request.body = this.body;
                        request.parameters = this.setParameters();

                        this.updateAdvert(request, 'parameter');
                    break;

                    case 3:
                        request.options = this.selected_options;
                        this.updateAdvert(request);
                    break;
                }

                }
            },
            mapLatLng: function(value) {
                if (value[0] && value[1]) {
                    this.request.lat = value[0];
                    this.request.lng = value[1];
                }
            }
        }
</script>

<style lang="scss" scoped>
    .step-click {
        cursor: pointer;
    }

    .messenger-box {
        position: relative;
        top: 5px;
    }

    .number-row {
        padding: 0 0 1rem 0 !important;
    }

    .close-btn {
        cursor: pointer;
        width: 30px;
        position: relative;
        top: -8px;
        height: 30px;
    }

    .photo-upload-block .text {
        font-size: 14px;
        font-weight: 300;
        color: var(--blue-900);
        margin-bottom: 10px;
    }

    .photo-upload-block .title {
        font-size: 16px;
        font-weight: 400;
        color: var(--blue-900);
        margin-bottom: 10px;
    }

    .photo-upload-block {
        background: var(--beige-400);
        border-radius: 6px;
        padding: 40px 50px 120px;
    }

    .photo-upload-container {
        padding: 20px;
        border: 2px dashed var(--gray-300);
        border-radius: 6px;
        line-height: 22px;
        text-align: center;
    }

    h6 {
        font-size: 16px;
        line-height: 1.4em;
        font-weight: 400;
        margin-bottom: 20px;
    }

    label {
        margin-bottom: 5px;
        font-size: 16px;
        line-height: 22px;
        font-weight: 400;
        color: var(--blue-900);
        display: block;
    }

    .characteristics .left, .characteristics .right {
        flex-basis: 45%;
    }

    .back-btn {
        box-shadow: none !important;
        border: 1px solid #eaeaea;
    }

    .action-container {
        margin-top: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .mapActive {
        margin-top: 6rem;
    }

    .map-text {
        font-size: 14px;
        line-height: 18px;
        font-weight: 300;
        color: var(--greyish-blue);
        display: block;
        margin-bottom: 1rem;
    }

    .btn-next {
        display: block;
        margin-left: auto;
    }

    .no-shadow {
        box-shadow: none;
    }

    .create-advert-800 {
        max-width: 800px;
    }

    .create-advert-600 {
        max-width: 600px;
    }

    .map {
        height: 360px;
        margin-bottom: 1rem;
    }

    .map-btn {
        border: 1px solid #eaeaea;
        display: block;
        margin-bottom: 2rem;
    }

    .loading-block span {
        margin-left: 1rem;
    }

    .loading-block {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
        border: 1px solid #eaeaea;
        border-radius: 10px;
        background-color: #fff;
        padding: 1.5rem 2rem;
        box-shadow: 0px 0px 7px #eaeaea;
    }

    .step-label .border {
        height: var(--border-height);
        border-radius: 8px;
        margin: auto 0 -6px;
        background: transparent;
    }

    .step-label .active {
        background: var(--blue-400);
    }

    .step-label .active-text {
        font-weight: 400;
    }

    .step-label .text {
        webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        height: -webkit-calc(100% - 2px);
        height: calc(100% - 2px);
        background: var(--white);
        color: var(--blue-900);
        cursor: pointer;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
    }

    .step .step-label {
        -webkit-flex-direction: column;
        -moz-box-orient: vertical;
        -moz-box-direction: normal;
        flex-direction: column;
        height: 100%;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
    }

    .create-advert-stepper .step {
        font-size: 18px;
        line-height: 30px;
        text-align: center;
        height: 100%;
        width: 20%;
    }

    .create-advert-stepper {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        position: sticky;
        top: 0;
        height: 56px;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        background: var(--white);
        box-shadow: inset 0 -1px var(--gray-300);
        border-bottom: 6px solid var(--beige-400);
        z-index: 9;
        --border-height: 6px;
    }

    @media (min-width: 600px) {
        .number-row {
            justify-content: start;
            margin-bottom: -35px;
        }

        .number-col {
            margin-right: 1rem;
        }
    }

    @media (min-width: 1280px) {
        .create-advert-stepper .step {
            width: 228px;
            margin: 0 10px;
        }

        .step-label .text {
            position: static;
        }

        .photo-wrap-container {
            padding: 0 5rem;
        }
    }
</style>
