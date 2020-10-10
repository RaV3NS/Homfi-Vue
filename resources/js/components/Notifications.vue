<template>
    <div>
        <div class="content-wrapper">
            <h1>Уведомления</h1>

            <div class="notifications-wrapper" v-if="notifications.length > 0">
                <div class="notify-item" v-for="notify in notifications">
                    <div class="notify-header" @click="read(notify)">
                        <span class="type">
                            <span class="unread" v-if="notify.status === 'new'"></span>
                                <span>
                                {{ getType(notify) }} <br>
                                <span v-if="notify.type === 'advert_blocked'">Причина: </span>{{ notify.title.ru }}
                            </span>
                        </span>
                        <span class="notify-dates">{{ getDate(notify) }}</span>
                    </div>
                </div>

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
                <div class="no-data">У вас пока нет новых уведомлений</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                user_id: window.user.id,
                user: null,
                notifications: [],
                page: 1,
                pages: 1
            }
        },
        mounted() {
            this.getUser(this.user_id);
            this.getNotifications();

            let url = window.location.href;
            let params = new URLSearchParams(url.split('?')[1]);
            if (params.get('page')) {
                this.page = params.get('page');
            }
        },

        methods: {
            getUser() {
                axios.get( window.backend_url + 'api/user/' + this.user_id).then((response) => {
                    this.user = response.data;
                });
            },
            getNotifications() {
                axios.get( window.backend_url + `api/user/${this.user_id}/notifications?page=${this.page}`).then((response) => {
                    this.notifications = response.data.data;
                    this.$parent.loading = false;
                    this.pages = response.data.last_page;
                });
            },
            getType(notification) {
                switch (notification.type) {
                    case 'advert_blocked':
                        return 'Ваше объявление ' + notification.advert.id + ' было заблокировано администратором';
                    case 'advert_enabled':
                    case 'advert_accepted':
                        return 'Ваше объявление ' + notification.advert.id + ' теперь активно';
                }
            },
            getDate(notification) {
                let date = new Date(notification.created_at);
                return this.$moment(date).format("DD.MM.YYYY / HH:mm");
            },
            read(notify) {
                if (notify.status === 'new')
                    axios.put(window.backend_url + `api/user/${this.user_id}/notifications/${notify.id}`, { status: 'read' }).then((response) => {
                        notify.status = 'read';
                    });
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
                    this.getNotifications();
                    if (params.get('page') === value) return false;
                }

                window.location.href = window.location.href + '?page=' + value;
            }
        }
    }
</script>

<style>
    .type {
        display: flex;
    }

    .unread::before {
        content: "";
        display: inline-block;
        width: .5rem;
        height: .5rem;
        border-radius: 50%;
        margin-right: .625rem;
        background-color: orange;
    }

    .notify-header {
        cursor: pointer;
    }

    .notify-dates {
        color: var(--greyish-blue);
    }

    .notify-header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notify-item {
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-flex-wrap: wrap;
        flex-wrap: wrap;
        padding-top: 20px;
        padding-left: 8px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--gray-300)
    }
</style>
