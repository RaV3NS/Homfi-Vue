<template>
    <div>
        <div class="content-wrapper">
            <div class="wrapper-head">
                <h1>Мои объявления</h1>

                <div class="right-block">
                    <v-select
                        :items="statuses"
                        v-model="status"
                        solo
                        dense
                        class="status-select"
                    ></v-select>
                    <button class="btn-link" @click="createAdvert">Добавить объявление</button>
                </div>
            </div>


            <div class="adverts-wrapper" v-if="adverts.length > 0">
                <div class="table-head">
                    <v-row>
                        <v-col cols="1"><b>ID</b></v-col>
                        <v-col cols="2">Статус</v-col>
                        <v-col cols="2">Дата</v-col>
                        <v-col cols="5">Название</v-col>
                        <v-col cols="2">Действия</v-col>
                    </v-row>
                </div>
                <v-row v-for="advert in adverts" class="advert-item">
                    <v-col cols="1" lg="1" sm="1">{{ advert.id }}</v-col>
                    <v-col cols="10" lg="2" sm="11" class="no-padding">
                        <span class="unread" :class="advert.status"></span>
                        {{ getStatus(advert) }}
                    </v-col>
                    <v-col cols="12" lg="2" sm="12">{{ getDate(advert) }}</v-col>
                    <v-col cols="12" lg="5" sm="12">{{ advert.title.ru }}</v-col>
                    <v-col cols="12" lg="2" sm="12">
                        <v-icon
                            color="darken-2"
                            @click="action('edit', advert)"
                        >
                            mdi-pencil
                        </v-icon>

                        <v-icon
                            color="darken-2"
                            v-if="advert.status === 'moderate'"
                            @click="action('preview', advert)"
                        >
                            mdi-eye
                        </v-icon>

                        <v-icon
                            color="darken-2"
                            @click="action('delete', advert)"
                        >
                            mdi-delete
                        </v-icon>
                    </v-col>
                </v-row>

                <div class="pagination" v-if="pages > 1">
                    <div class="text-center">
                        <v-pagination
                            v-model="page"
                            :length="pages"
                            circle
                        ></v-pagination>
                    </div>
                </div>
            </div>

            <div class="no-data-block" v-else>
                <div class="no-data">У вас пока нет объявлений</div>
            </div>
        </div>
    </div>
</template>

<script>
    import Advert from "./Profile/Advert";

    export default {
        components: { Advert },
        data: function() {
            return {
                user_id: window.user.id,
                user: null,
                adverts: [],
                status: 'all',
                statuses: [
                    { text: 'Все объявления', value: 'all' },
                    { text: 'Активные', value: 'enabled' },
                    { text: 'Черновик', value: 'draft' },
                    { text: 'Неактивные', value: 'disabled' },
                    { text: 'Отмененные', value: 'rejected' },
                    { text: 'На модерации', value: 'moderate' },
                    { text: 'Заблокировано', value: 'blocked' },
                ],
                page: 1,
                pages: 1
            }
        },
        mounted() {
            this.getUser(this.user_id);
            this.getAdverts();
        },

        methods: {
            getUser() {
                axios.get( window.backend_url + 'api/user/' + this.user_id).then((response) => {
                    this.user = response.data;
                });
            },
            getAdverts() {
                this.adverts = [];
                let url = window.location.href;
                axios.get(window.backend_url + `api/user/${this.user_id}/adverts?${url.split('?')[1]}`).then((response) => {
                    let data = response.data.data;
                    if (data.length > 0)
                        data.map((el) => {
                            this.adverts.push(el);
                        });

                    let params = new URLSearchParams(url.split('?')[1]);

                    if (params) {
                        this.page = params.get('page') ? parseFloat(params.get('page')) : 1;
                        this.status = params.get('status') ? params.get('status') : 'all';
                    }

                    this.pages = response.data.last_page;
                    this.$parent.loading = false;
                });
            },
            getDate(advert) {
                let date = new Date(advert.created_at);
                return this.$moment(date).format("DD.MM.YYYY");
            },
            getStatus(advert) {
                switch (advert.status) {
                    case "enabled":
                        return 'Активно';
                    case "disabled":
                        return 'Неактивно';
                    case "rejected":
                        return 'Отклонено';
                    case "accepted":
                        return 'Принято';
                    case "blocked":
                        return 'Заблокировано';
                    case "moderate":
                        return 'На модерации';
                    case "draft":
                        return 'Черновик';
                }
            },
            createAdvert() {
                if (localStorage.getItem('advert'))
                    localStorage.removeItem('advert');
                window.location.href = '/advert/create';
            },
            action(type, advert) {
                console.log(type);

                switch (type) {
                    case 'edit':
                        window.location.href = '/advert/edit/' + advert.id;
                    break;

                    case 'preview':
                        window.location.href = '/adverts/preview/' + advert.id;
                    break;
                }
            }
        },
        watch: {
            user: function(value) {
                //
            },
            page: function(value) {
                let url = window.location.href;
                let params = new URLSearchParams(url.split('?')[1]);
                if (params.get('page')) {
                    if (parseFloat(params.get('page')) === value) return false;
                }

                params.set('page', this.page);
                window.location.href = url.split('?')[0] + '?' + params.toString();
            },
            status: function(value) {
                let url = window.location.href;
                let params = new URLSearchParams(url.split('?')[1]);
                if (params.get('status')) {
                    this.getAdverts();
                    if (params.get('status') === value) return false;
                }

                if (this.status === 'all')
                    params.delete('status');
                else
                    params.set('status', this.status);
                params.set('page', '1');
                window.location.href = url.split('?')[0] + '?' + params.toString();
            }
        }
    }
</script>

<style>
    .status-select {
        margin-right: 1rem !important;
        height: 40px;
        width: 250px;
    }

    .right-block > * {
        margin: 0 0.5rem;
    }

    .right-block {
        display: flex;
        align-items: center;
    }

    .wrapper-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .v-icon {
        cursor: pointer;
    }

    .table-head {
        box-shadow: 0 5px 4px -5px grey;;
    }

    .adverts {
        box-shadow: inset 0 3px 3px rgba(0,0,0,.1);
    }

    .draft::before {
        background-color: gray;
    }

    .enabled::before,
    .accepted::before{
        background-color: green;
    }

    .blocked::before,
    .rejected::before{
        background-color: crimson;
    }

    .disabled::before {
        background-color: orange;
    }

    .moderate::before {
        background-color: deepskyblue;
    }

    .advert-item {
        border-bottom: 1px solid #eaeaea;
    }

    .table-head {
        font-weight: 400;
    }

    .adverts-wrapper {
        margin-top: 1rem;
    }

    .adverts-table-grid .id {
        grid-column: 1/2;
    }

    .adverts-table-grid .status {
        grid-column: 2/4;
    }

    .adverts-table-grid .date {
        grid-column: 4/5;
    }

    .adverts-table-grid .name {
        grid-column: 5/9;
    }

    .adverts-table-grid {
        display: grid;
        grid-template-columns: repeat(10,1fr);
        grid-gap: .625rem;
        -webkit-align-items: start;
        -moz-box-align: start;
        align-items: start;
    }

    @media only screen and (max-width: 1280px) {
        .table-head {
            display: none;
        }
    }
</style>
