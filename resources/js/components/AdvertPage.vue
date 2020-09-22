<template>
    <div>
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
            <div class="gallery">
                <div class="gallery-item gallery-item-first">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                    <button type="button" class="btn-favourites">
                        <svg viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#filter0_d)"><path d="M11.9029 19.0207L11.8813 18.9991L11.8584 18.9788C9.16904 16.5979 6.93836 14.6188 5.36799 12.7092C3.80984 10.8144 3 9.10239 3 7.27308C3 4.30715 5.30623 2 8.26799 2C9.74942 2 11.2282 2.62176 12.2929 3.68646L13 4.39352L13.7071 3.68646C14.7718 2.62176 16.2506 2 17.732 2C20.6938 2 23 4.30715 23 7.27308C23 9.10361 22.189 10.8168 20.6288 12.7131C19.0563 14.6244 16.8228 16.6053 14.1304 18.989L14.1074 19.0094L14.0857 19.0311L12.9998 20.1184L11.9029 19.0207Z" stroke="white" stroke-width="2"></path></g><defs><filter id="filter0_d" x="0" y="0" width="26" height="24.5333" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"></feColorMatrix><feOffset dy="1"></feOffset><feGaussianBlur stdDeviation="1"></feGaussianBlur><feColorMatrix type="matrix" values="0 0 0 0 0.416476 0 0 0 0 0.484595 0 0 0 0 0.620833 0 0 0 0.5 0"></feColorMatrix><feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
                    </button>
                </div>

                <div class="gallery-item gallery-item-second">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                </div>

                <div class="gallery-item gallery-item-third">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                </div>

                <div class="gallery-item gallery-item-fourth">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                </div>

                <div class="gallery-item gallery-item-fifth">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                </div>

                <div class="gallery-item gallery-item-six">
                    <img class="gallery-image" src="/images/gallery-placeholders/card.svg" decoding="async" loading="lazy">
                </div>
            </div>

            <button type="button" class="btn-light btn-wishlist"> Все фотографии </button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                advert: null,
                seo: null,
                h1: null,
                city_name: null
            }
        },
        props: ["id"],
        mounted() {
            this.getAdvert()
        },
        methods: {
            getAdvert: function() {
                axios.get('http://localhost:8000/api/adverts/' + this.id).then((response) => {
                    console.log(response.data);
                    this.advert = response.data.advert;
                    this.seo = response.data.seo;

                    this.h1 = response.data.seo.h1;
                    this.city_name = response.data.advert.city.name_ru;
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
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

    .gallery-item-first {
        grid-column-start: 1;
        grid-column-end: 2;
        grid-row-start: 1;
        grid-row-end: 3;
    }

    .gallery-item-second {
        grid-row-start: 1;
        grid-row-end: 2;
        grid-column-start: 2;
        grid-column-end: 5;
    }

    .gallery-item-third {
        grid-row-start: 1;
        grid-row-end: 2;
        grid-column-start: 5;
        grid-column-end: 8;
    }

    .gallery-item-fourth {
        grid-row-start: 2;
        grid-row-end: 3;
        grid-column-start: 2;
        grid-column-end: 4;
    }

    .gallery-item-fifth {
        grid-row-start: 2;
        grid-row-end: 3;
        grid-column-start: 4;
        grid-column-end: 6;
    }

    .gallery-item-six {
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

    @media only screen and (min-width: 1024px) {
        .breadcrumbs-header {
            width: 852px;
            margin: 0 auto;
        }
    }

    .page-title {
        font-size: 20px;
        line-height: 1.5em;
        font-weight: 400;
        color: var(--text-color-primary);
        margin: 10px 0;
    }
</style>
