<template>
    <div>
        <div class="content-wrapper">
            <h1>Мой профиль</h1>

            <div class="profile-section">
                <div class="section-header">
                    <h4>ФИО</h4>
                    <button class="btn-link" @click="nameEdit = true" v-if="!nameEdit">Редактировать</button>
                    <button class="btn-link" @click="refresh('name')" v-else>Отменить</button>
                </div>
                <div class="section-content" v-if="!nameEdit">
                    <p v-if="userName !== ''">{{ userName }}</p>
                    <div class="no-data" v-else>Нет данных</div>
                </div>
                <div class="section-content" v-else>
                    <v-row>
                        <v-col cols="12" md="4">
                            <label>Фамилия</label>
                            <v-text-field
                                placeholder="Фамилия"
                                v-model="last_name"
                                outlined
                                dense
                                class="advert-input"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <label>Имя</label>
                            <v-text-field
                                placeholder="Имя"
                                v-model="first_name"
                                outlined
                                dense
                                class="advert-input"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <label>Отчество</label>
                            <v-text-field
                                placeholder="Отчество"
                                v-model="patronymic"
                                outlined
                                dense
                                class="advert-input"
                            ></v-text-field>
                        </v-col>
                        <v-col>
                            <v-btn
                                color="primary"
                                elevation="1"
                                class="profile-save"
                                style="background-color: var(--btn-background-color) !important;"
                                :disabled="btnLock"
                                @click="save('name')"
                                :loading="btnLock"
                            >
                                Сохранить
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>
            </div>
            <div class="profile-section">
                <div class="section-header">
                    <h4>Номер телефона</h4>
                    <button class="btn-link" @click="phoneEdit = true" v-if="!phoneEdit">Редактировать</button>
                    <button class="btn-link" @click="refresh('phone')" v-else>Отменить</button>
                </div>
                <div class="section-content" v-if="!phoneEdit">
                    <div v-if="user && user.phones.length > 0">
                        <p v-for="phone in user.phones">{{ formatNumber(phone.number) }}</p>
                    </div>
                    <div class="no-data" v-else>Нет данных</div>
                </div>
                <div class="section-content" v-else>
                    <v-form ref="numbers">
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
                    </v-form>

                    <v-btn
                        text
                        @click="addNumber"
                        style="margin-top: -25px;"
                    >+ Добавить номер</v-btn>
                    <div>
                        <v-btn
                            color="primary"
                            elevation="1"
                            class="profile-save"
                            style="background-color: var(--btn-background-color) !important; margin-top: 0.5rem"
                            :disabled="btnLock"
                            @click="save('phones')"
                            :loading="btnLock"
                        >
                            Сохранить
                        </v-btn>
                    </div>
                </div>
            </div>
            <div class="profile-section">
                <div class="section-header">
                    <h4>Дополнительные каналы связи</h4>
                    <button class="btn-link" @click="socialEdit = true" v-if="!socialEdit">Редактировать</button>
                    <button class="btn-link" @click="refresh('social')" v-else>Отменить</button>
                </div>
                <div class="section-content" v-if="!socialEdit">
                    <div v-if="isSocialLinks">
                        <div class="contact-row" v-if="skype">
                            <img src="/icons/socials/skype.svg" alt="skype">
                            <span>{{ skype }}</span>
                        </div>
                        <div class="contact-row" v-if="instagram">
                            <img src="/icons/socials/instagram.svg" alt="instagram">
                            <span>{{ instagram }}</span>
                        </div>
                        <div class="contact-row" v-if="facebook">
                            <img src="/icons/socials/facebook.svg" alt="facebook">
                            <span>{{ facebook }}</span>
                            <img src="/icons/socials/messenger.svg" alt="messenger" v-if="facebook_messenger">
                        </div>
                    </div>
                    <div class="no-data" v-else>Нет данных</div>
                </div>
                <div class="section-content" v-else>
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

                    <v-btn
                        color="primary"
                        elevation="1"
                        class="profile-save"
                        style="background-color: var(--btn-background-color) !important; margin-top: 0.5rem"
                        :disabled="btnLock"
                        @click="save('socials')"
                        :loading="btnLock"
                    >
                        Сохранить
                    </v-btn>
                </div>
            </div>
            <div class="profile-section">
                <div class="section-header">
                    <h4>Email (указанный при регистрации)</h4>
                    <button class="btn-link" @click="emailEdit = true" v-if="!emailEdit">Изменить</button>
                    <button class="btn-link" @click="refresh('email')" v-else>Закрыть</button>
                </div>
                <div class="section-content" v-if="!emailEdit">
                    <p v-if="user && user.email">{{ user.email }}</p>
                    <div class="no-data" v-else>Нет данных</div>
                </div>
                <div class="section-content" v-else>
                    <v-form ref="emailReset">
                        <v-row>
                            <v-col cols="12" md="6">
                                <label>Текущий пароль*</label>
                                <v-text-field
                                    v-model="password"
                                    outlined
                                    dense
                                    class="advert-input"
                                    type="password"
                                    :rules="passwordRules"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="6">
                                <label>Email*</label>
                                <v-text-field
                                    v-model="email"
                                    outlined
                                    dense
                                    class="advert-input"
                                    type="email"
                                    required
                                    :rules="emailRules"
                                ></v-text-field>
                            </v-col>

                            <v-col>
                                <v-btn
                                    color="primary"
                                    elevation="1"
                                    class="profile-save"
                                    style="background-color: var(--btn-background-color) !important;"
                                    :disabled="btnLock"
                                    @click="save('email')"
                                    :loading="btnLock"
                                >
                                    Сохранить
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </div>
            </div>
            <div class="profile-section">
                <div class="section-header">
                    <h4>Пароль</h4>
                    <button class="btn-link" @click="passwordEdit = true" v-if="!passwordEdit">Изменить</button>
                    <button class="btn-link" @click="refresh('password')" v-else>Закрыть</button>
                </div>
                <div class="section-content" v-if="passwordEdit">
                    <v-form ref="passwordReset">
                        <v-row>
                            <v-col cols="12">
                                <label>Текущий пароль*</label>
                                <v-text-field
                                    v-model="current_password"
                                    outlined
                                    dense
                                    class="advert-input"
                                    type="password"
                                    required
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <label>Новый пароль*</label>
                                <v-text-field
                                    v-model="new_password"
                                    outlined
                                    dense
                                    class="advert-input"
                                    type="password"
                                    required
                                    :rules="passwordRules"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12">
                                <label>Подтвердите пароль*</label>
                                <v-text-field
                                    v-model="confirm_password"
                                    outlined
                                    dense
                                    class="advert-input"
                                    type="password"
                                    required
                                    :rules="passwordRules"
                                ></v-text-field>
                            </v-col>

                            <v-col>
                                <v-btn
                                    color="primary"
                                    elevation="1"
                                    class="profile-save"
                                    style="background-color: var(--btn-background-color) !important;"
                                    :disabled="btnLock"
                                    @click="save('password')"
                                    :loading="btnLock"
                                >
                                    Сохранить
                                </v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
                </div>
            </div>
            <div class="profile-section">
                <button class="btn-link" @click="deleteAccount">Удалить аккаунт</button>
            </div>
        </div>

        <modal name="email-reset" width="500" height="auto" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('email-reset')">
            <div class="modal-success-wrapper">
                <img class="mb-10 mb-md-20" src="/icons/success-icon.svg" alt="" decoding="async">
                <p>На Ваш Email отправлено письмо для подтверждения электронной почты.</p>
            </div>
        </modal>

        <modal name="delete-account" width="400" height="auto" :adaptive="true">
            <img src="/icons/close.svg" alt="close" class="close_modal_btn" @click="$modal.hide('delete-account')">
            <div class="modal-success-wrapper">
                <h4 class="mb-3 modal-h4">Вы уверены что хотите удалить свой аккаунт ?</h4>
                <div>
                    <label>Текущий пароль*</label>
                    <v-text-field
                        v-model="confirmDeletePassword"
                        outlined
                        dense
                        class="advert-input"
                        type="password"
                        required
                    ></v-text-field>
                </div>
                <v-flex class="action-container">
                    <button class="btn-link">Отменить</button>
                    <v-btn
                        color="primary"
                        elevation="1"
                        class="profile-save"
                        style="background-color: var(--btn-background-color) !important;"
                        :disabled="btnLock"
                        @click="confirmDelete"
                        :loading="btnLock"
                    >
                        Да
                    </v-btn>
                </v-flex>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                user_id: window.user.id,
                user: null,

                first_name: null,
                last_name: null,
                patronymic: null,
                nameEdit: false,

                phoneEdit: false,
                phones: [
                    { number: '', messengers: [] }
                ],

                phoneRules: [
                    v => !!v || 'Телефон обязательное поле для заполнения',
                    v => v && v.length === 17 || 'Неверный формат телефона',
                ],
                emailRules: [
                    v => !!v || 'E-mail обязательное поле для заполнения',
                    v => /.+@.+/.test(v) || 'Неверный формат e-mail адреса',
                ],
                passwordRules: [
                    v => !!v || 'Пароль обязательное поле для заполнения',
                    v => v && v.length > 8 || 'Пароль должен содержать минимум 8 символов'
                ],

                socialEdit: false,
                facebook: null,
                facebook_messenger: null,
                instagram: null,
                skype: null,

                emailEdit: false,
                email: null,
                password: null,

                passwordEdit: false,
                current_password: null,
                new_password: null,
                confirm_password: null,
                confirmDeletePassword: null,

                btnLock: false,
            }
        },
        mounted() {
            this.getUser(this.user_id);
        },
        computed: {
            userName: function() {
                let name = '';

                if (this.last_name)
                    name += this.last_name + ' ';
                if (this.first_name)
                    name += this.first_name + ' ';
                if (this.patronymic)
                    name += this.patronymic + ' ';

                return name;
            },
            isSocialLinks: function() {
                if (this.user &&
                    this.user.social_links &&
                    this.user.social_links.length > 0)
                    return true;
                else
                    return false;
            }
        },
        methods: {
            addNumber() {
                this.phones.push({ number: "", messengers: [] });
            },
            deleteAccount() {
                this.$modal.show('delete-account');
            },
            confirmDelete() {
                this.btnLock = true;
                axios.delete( window.backend_url + 'api/user/' + this.user_id).then((response) => {
                    if (response.status === 200)
                        this.$modal.hide('delete-account');
                });
            },
            deleteNumber(index) {
                this.phones.splice(index, 1);
            },
            getUser() {
                axios.get( window.backend_url + 'api/user/' + this.user_id).then((response) => {
                    this.user = response.data;
                });
            },
            updateUser(request, type) {
                axios.put( window.backend_url + 'api/user/' + this.user_id, request).then((response) => {
                    this.$toast.info( 'Данные успешно обновлены' , {
                        position: 'top-right',
                        duration: 3000,
                        dismissible: false
                    });

                    if (type === 'name') this.nameEdit = false;
                    if (type === 'phones') this.phoneEdit = false;
                    if (type === 'socials') this.socialEdit = false;

                    this.btnLock = false;
                    this.getUser();
                });
            },
            save(type) {
                let request = {};
                this.btnLock = true;

                if (type === 'name') {
                    request.first_name = this.first_name;
                    request.last_name = this.last_name;
                    request.patronymic = this.patronymic;
                }

                if (type === 'phones') {
                    if (!this.$refs.numbers.validate()) {
                        this.btnLock = false;
                        return false;
                    }

                    let phones = _.cloneDeep(this.phones);
                    phones.map((el) => {
                        var replaced = el.number.replace(/ /g, '').replace(/\+/g, '');
                        el.number = replaced;
                        return el;
                    });

                    request.phones = phones;
                }

                if (type === 'socials') {
                    request.social_links = {};
                    if (this.skype) request.social_links.skype = this.skype;
                    if (this.facebook) request.social_links.facebook = this.facebook;
                    if (this.instagram) request.social_links.instagram = this.instagram;
                    if (this.facebook_messenger) request.social_links.facebook_messenger = 1;
                }

                if (type === 'email') {
                    if (!this.$refs.emailReset.validate()) {
                        this.btnLock = false;
                        return false;
                    }

                    let request = { email: this.email, password: this.password };

                    axios.post( window.backend_url + `api/user/${this.user_id}/reset-email`, request)
                        .then((response) => {
                            if (response.status === 200) {
                                this.$modal.show('email-reset');
                                this.emailEdit = false;
                                this.getUser();
                            }
                        })
                        .catch(error => {
                            if (error.response.data.error && error.response.data.error.email) {
                                this.$toast.error( error.response.data.error.email[0] , {
                                    position: 'top-right',
                                    duration: 3000,
                                    dismissible: false
                                });
                            }

                            if (error.response.data.error && error.response.data.error === 'Can\'t reset email') {
                                this.$toast.error( 'Неверный пароль' , {
                                    position: 'top-right',
                                    duration: 3000,
                                    dismissible: false
                                });
                            }
                        });

                    this.btnLock = false;
                    return false;
                }

                if (type === 'password') {
                    if (!this.$refs.passwordReset.validate()) {
                        this.btnLock = false;
                        return false;
                    }

                    let request = {
                        old_password: this.current_password,
                        new_password: this.new_password,
                        confirm_password: this.confirm_password
                    };

                    axios.post( window.backend_url + `api/change-password`, request)
                        .then((response) => {
                            if (response.data.status === 200) {
                                this.$toast.success( 'Данные обновлены' , {
                                    position: 'top-right',
                                    duration: 3000,
                                    dismissible: false
                                });

                                this.passwordEdit = false;
                            } else {
                                this.$toast.error( response.data.message , {
                                    position: 'top-right',
                                    duration: 3000,
                                    dismissible: false
                                });
                            }
                        })
                        .catch(error => {
                            this.$toast.error( 'Ошибка! Что-то пошло не так' , {
                                position: 'top-right',
                                duration: 3000,
                                dismissible: false
                            });
                        });

                    this.btnLock = false;
                    return false;
                }

                this.updateUser(request, type);
            },
            refresh(type) {
                if (type === 'name') {
                    this.first_name = this.user.first_name;
                    this.last_name = this.user.last_name;
                    this.patronymic = this.user.patronymic;

                    this.nameEdit = false;
                }

                if (type === 'phone') {
                    if (this.user.phones.length > 0) this.phones = [];
                    this.user.phones.map((el) => {
                        this.phones.push({ number: this.formatNumber(el.number), messengers: el.messengers });
                    });

                    this.phoneEdit = false;
                }

                if (type === 'social') {
                    if (this.user.social_links.facebook)
                        this.facebook = this.user.social_links.facebook;
                    else this.facebook = null;
                    if (this.user.social_links.skype)
                        this.facebook = this.user.social_links.skype;
                    else this.skype = null;
                    if (this.user.social_links.facebook_messenger)
                        this.facebook = "messenger";
                    else this.facebook_messenger = null;
                    if (this.user.social_links.instagram)
                        this.facebook = this.user.social_links.instagram;
                    else this.instagram = null;

                    this.socialEdit = false;
                }

                if (type === 'email') {
                    this.email = this.user.email;
                    this.password = null;
                    this.emailEdit = false;
                }

                if (type === 'password') {
                    this.current_password = null;
                    this.new_password = null;
                    this.confirm_password = null;

                    this.passwordEdit = false;
                }
            },
            formatNumber(number) {
                let parts = [number.substr(0, 3), number.substr(3, 2), number.substr(5, 3), number.substr(8, 2), number.substr(9, 2)];
                return '+' + parts.join(' ');
            },
        },
        watch: {
            user: function(value) {
                this.first_name = this.user.first_name;
                this.last_name = this.user.last_name;
                this.patronymic = this.user.patronymic;

                if (this.user.phones.length > 0) this.phones = [];
                this.user.phones.map((el) => {
                    this.phones.push({ number: this.formatNumber(el.number), messengers: el.messengers });
                });

                if (value.social_links) {
                    if (value.social_links.facebook) this.facebook = value.social_links.facebook;
                    if (value.social_links.skype) this.skype = value.social_links.skype;
                    if (value.social_links.facebook_messenger) this.facebook_messenger = "messenger";
                    if (value.social_links.instagram) this.instagram = value.social_links.instagram;
                }

                this.email = this.user.email;

                this.$parent.loading = false;
            }
        }
    }
</script>
