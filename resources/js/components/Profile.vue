<template>
    <div>
    <pageloader v-if="loading"></pageloader>
    <header-vue></header-vue>
    <v-container class="page-container">
        <v-row class="no-padding">
        <v-col cols="12" class="profile-col">
            <v-flex class="profile-row">
                <div class="sidebar-visible">
                    <div class="sidebar">
                        <ul class="profile-block">
                            <li><a class="profile-link" :class="{ 'profile-link-active': route === 'main' }" href="/profile">Мой профиль</a></li>
                            <li><a class="profile-link" :class="{ 'profile-link-active': route === 'adverts' }" href="/profile/adverts">Мои объявления</a></li>
                            <li><a class="profile-link" :class="{ 'profile-link-active': route === 'notifications' }" href="/profile/notifications">Мои уведомления</a></li>
                            <li><a class="profile-link" :class="{ 'profile-link-active': route === 'favourites' }"  href="/profile/favourites">Избранное</a></li>
                            <li><a class="profile-link" href="/profile/logout">Выйти</a></li>
                        </ul>
                    </div>
                </div>

                <div class="content-part">
                    <settings v-if="this.route && this.route === 'main'"></settings>
                    <notifications v-if="this.route && this.route === 'notifications'"></notifications>
                    <favourites v-if="this.route && this.route === 'favourites'"></favourites>
                    <adverts v-if="this.route && this.route === 'adverts'"></adverts>
                </div>
            </v-flex>
        </v-col>
        </v-row>
    </v-container>

    </div>
</template>

<script>
    import Favourites from "./Favourites";
    import Notifications from "./Notifications";
    import Adverts from "./Adverts";

    export default {
        components: {Adverts, Notifications, Favourites },
        data: function() {
            return {
                loading: true,
                route: null,
            }
        },
        mounted() {
            let location = window.location.href;
            let url = location.split('?')[0]
            this.route = url.split('/')[4];
            if (!this.route) {
                this.route = 'main';
            }
        }
    }
</script>

<style lang="scss">

    .modal-h4 {
        font-weight: 400;
        font-size: 18px;
        line-height: 22px;
        padding-right: 2.5rem;
    }

    .action-container button {
        margin: 0;
    }

    .action-container {
        justify-content: space-between;
        align-items: center;
        margin-top: -0.8rem;
    }

    .modal-success-wrapper {
        display: flex;
        justify-content: center;
        padding: 0 2rem 2rem;
        flex-direction: column;
    }

    .close_modal_btn {
        padding: 0.5rem;
        margin-left: auto;
        cursor: pointer;
    }

    .contact-row > *:not(:last-child) {
        margin-right: 1rem;
    }

    .contact-row {
        display: flex;
        align-items: center;
        margin-top: 1rem;
    }

    .close-btn {
        position: relative;
        top: -9px;
        margin-left: 5px;
        cursor: pointer;
    }

    .number-row {
        justify-content: flex-start;
    }

    .profile-save {
        text-transform: none;
        margin-top: -30px;
        --btn-background-color: var(--blue-400);
        --btn-background-color-hover: var(--blue-600);
        --btn-color: var(--white);
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border: none;
        border-radius: .375rem;
        padding: 0.7rem 2rem !important;
        font-family: inherit;
        font-size: var(--btn-font-size,1rem);
        font-weight: var(--btn-font-weight,400);
        line-height: var(--btn-line-height,1.375rem);
        text-decoration: none;
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);
        width: fit-content;
        height: auto !important;
    }

    .section-content p {
        margin: 0;
    }

    .section-content {
        margin-top: 1rem;
    }

    .no-data {
        color: var(--gray-600);
    }

    .btn-link {
        background: transparent;
        border: none;
        outline: none;
        text-decoration: underline;
        color: var(--blue-600);
        font-weight: 400;
        font-size: 16px;
        line-height: 22px;
        cursor: pointer;
    }

    .profile-section .section-header {
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
    }

    .profile-section:not(:last-child) {
        border-bottom: 1px solid var(--gray-300);
    }

    .profile-section {
        padding: 1.875rem 0;
    }

    * {
        font-family: Rubik;
    }

    .content-wrapper h4 {
        font-size: 18px;
        line-height: 24px;
        font-weight: 400;
    }

    .content-wrapper h1 {
        font-size: 18px;
        line-height: 24px;
        font-weight: 400;
    }

    @media only screen and (min-width: 1904px) {
        .profile-col {
            padding: 0 20rem;
        }
    }

    .profile-row {
        justify-content: flex-start;
        align-items: flex-start;
    }

    .content-wrapper {
        border-radius: .5rem;
        border: 1px solid var(--gray-300);
        box-shadow: 0 0 0.625rem rgba(0,0,0,.1);
        -webkit-flex-grow: 1;
        -moz-box-flex: 1;
        flex-grow: 1;
        color: var(--text-color-primary);
        background: -webkit-gradient(linear,left top,left bottom,color-stop(0,var(--blue-400)),color-stop(.5rem,var(--blue-400)),color-stop(.5rem,transparent));
        background: linear-gradient(var(--blue-400) 0,var(--blue-400) .5rem,transparent .5rem);
        padding: 1.875rem 1.5rem;
    }

    .page-container {
        margin-top: 1rem;
    }

    .profile-link-active {
        pointer-events: none;
        background: -webkit-gradient(linear,left top,right top,color-stop(0,var(--blue-400)),color-stop(.375rem,var(--blue-400)),color-stop(.375rem,transparent));
        background: linear-gradient(90deg,var(--blue-400) 0,var(--blue-400) .375rem,transparent .375rem);
        font-weight: 400;
    }

    .profile-link {
        display: block;
        padding: .3rem 1rem;
        text-decoration: none;
        font-family: Rubik;
        color: #000 !important;
    }

    .profile-block {
        border-radius: .5rem;
        border: 1px solid var(--gray-300);
        box-shadow: 0 0 0.625rem rgba(0,0,0,.1);
        width: 260px;
        margin-right: 1.25rem;
        padding: 1rem 0;
        list-style: none;
        padding-left: 0 !important;
    }

    .content-part {
        flex-basis: 100%;
    }

    .no-padding {
        padding: 0;
    }

    .w100 {
        width: 100%;
    }

    @media only screen and (max-width: 1280px) {
        .sidebar-visible {
            display: none;
        }

        .page-container {
            padding: 0;
            margin: 0;
        }

        .content-wrapper {
            background: none;
            border-radius: 0;
        }

        .profile-col {
            padding: 0;
        }

        .no-padding {
            margin: 0 !important;
        }
    }
</style>
