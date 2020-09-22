<template>
    <header class="header" v-bind:class="{ 'hidden': !active }">
        <div class="header__inner">
            <div class="header__container">
                <div class="header__left">
                    <a class="header__logo">
                        <img src="/icons/logo.svg" alt="Homfi" decoding="async" />
                    </a>

                    <a class="link link-secondary" href="/kiev">
                        Хочу снять жилье
                    </a>
                </div>

                <div class="header__right">
                <!-- <LanguageSwitcher />-->
                    <a class="btn btn_attention header__advert-link header__right-item">
                    <img
                        class="header__advert-icon"
                        src="/icons/plus-white.svg"
                        alt=""
                        decoding="async"
                    />
                    <span>Создать объявление</span>
                    </a>

<!--                    <Notifications userId={user && user.id} t={t} />-->

<!--                    <Profile />-->
                </div>
            </div>
        </div>
    </header>
</template>

<script>
export default {
    data: function () {
        return {
            active: true
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '../../sass/header.scss';
    @import '../../sass/buttons.scss';
    @import '../../sass/link.scss';
</style>
