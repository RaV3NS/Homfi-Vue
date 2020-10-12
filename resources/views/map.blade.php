<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Map</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <v-app>
            <map-page city="{{ $city }}"></map-page>
        </v-app>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

<style>
    .v-menu__content {
        border-radius: 6px !important;
    }

    .fixed-pagination {
        position: fixed;
        bottom: 25px;
        left: 0;
        margin: 5px auto;
        right: 0;
    }

    .v-list-item:hover {
        background-color: #f1f1f4;
    }

    .v-list-item {
        padding: 0.7rem 1.2rem;
    }

    .v-select-list {
        padding: 0;
    }

    .v-list-item::before {
        background: none;
    }

    .date-select fieldset {
        border: 1px solid #d5dbe5;
    }

    .date-select {
        position: relative;
        top: 5px;
    }

    .advert-input-street fieldset {
        border-radius: 0 4px 4px 0;
        border-left: none;
        outline: none !important;
        border: 1px solid color: rgba(0,0,0,.87);
    }

    .advert-input-city fieldset {
        border-radius: 4px 0 0 4px;
        outline: none !important;
        border: 1px solid color: rgba(0,0,0,.87);
    }

    .filters button.dropdown-toggle::after {
        display: none !important;
    }

    .filters button.dropdown-toggle {
        --border-radius: 6px;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 1px solid var(--gray-300);
        background-color: var(--white);
        text-align: left;
        height: 100%;
        color: var(--greyish-blue);
        width: fit-content;
        padding: 0.6rem 0.8rem;

        min-width: 120px;
        justify-content: center;
    }

    .marker-homfi-hovered {
        z-index: 9999 !important;
    }

    .filters button.dropdown-toggle:focus,
    .filters button.dropdown-toggle:hover,
    .filters .btn-secondary:not(:disabled):not(.disabled):active,
    .filters .btn-secondary:not(:disabled):not(.disabled).active,
    .filters  .show > .btn-secondary.dropdown-toggle {
        background-color: var(--bg-grey);
        color: var(--greyish-blue);
        border: 1px solid var(--gray-300);
        box-shadow: none;
    }

    .filters .btn-filter:hover, .btn-filter:focus {
        background-color: var(--bg-grey) !important;
        color: var(--greyish-blue);
        border: 1px solid var(--gray-300);
        box-shadow: none;
    }

    .filters .dropdown-menu {
        width: 200%;
    }

    .m-2 {
        margin: 0.5rem 0.5rem !important;
    }

    .vs__search {
        color: var(--greyish-blue);
    }

    .vs__search, .vs__search:focus {
        padding: 3px 7px;
    }

    #streetFilter {
        left: -1px;
    }

    #streetFilter .vs__dropdown-toggle {
        border-radius: 0 4px 4px 0;
    }

    #citySearch .vs__dropdown-toggle {
        border-radius: 4px 0 0 4px;
    }

    #streetFilter input, #citySearch input {
        color: var(--greyish-blue);
    }

    ul.carousel__list {
        border-radius: 14px 14px 0 0;
        top: -2px;
        left: -2px;
        width: 101% !important;
    }

    .leaflet-popup-content {
        margin: 0;
    }

    .leaflet-popup-content {
        min-width: 270px !important;
    }

    .advert-card .carousel__list {
        width: 280px !important;
        border-radius: 4px;
        left: -1px;
        top: 0;
    }

    .advert-card .carousel__list img {
        height: 200px;
    }

    .disable-scrollbars::-webkit-scrollbar {
        width: 0px;
        background: transparent; /* Chrome/Safari/Webkit */
    }

    .disable-scrollbars {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE 10+ */
    }

    .list {
        overflow-y: scroll;
        height: 75vh;
    }


    .carousel {
        height: 14rem;
    }
    .carousel__list {
        height: 100%;
    }
    .carousel__item {
        height: 100%;
    }
    .slide {
        height: 100%;
        min-height: auto;
        background-size: cover;
        background-position: center;
    }

    .filter-bottom {
        align-items: center;
    }

    .map_toggle .map {
        display: none;
    }
    .map_toggle .left {
        flex-basis: 100%;
    }
    .map_toggle .advert-card {
        flex-direction: column;
    }
    .map_toggle .advert-card .carousel {
        height: 14rem;
    }
    .map_toggle .advert-card .carousel .carousel__list {
        width: 100% !important;
    }

    .dropdown-toggle::after {
        display: inline-block;
        margin-left: 0.355em;
        vertical-align: 0.255em;
        content: "";
        border-top: 0.25em solid;
        border-right: 0.25em solid transparent;
        border-bottom: 0;
        border-left: 0.25em solid transparent;
        color: #000;
    }

    .close_modal_btn {
        margin-left: auto;
        padding: 1rem;
        padding-bottom: 0;
        cursor: pointer;
    }

    .wrapper {
        padding: 2rem;
        padding-top: 0;
    }


    .bottom {
        display: flex;
        justify-content: space-between;
    }

    .modal_hr {
        margin: 30px 0;
        border: none;
        border-top: 1px solid var(--gray-300);
    }

    @media only screen and (max-width: 1280px) {
        .search-settings_header {
            display: block !important;
        }

        #dropdown-text:first-child {
            margin-left: 0 !important;
        }

        .filters {
            margin-top: 0.7rem !important;
        }
    }

    .header__advert-link {
        display: flex;
        align-items: center;
    }

    @media only screen and (max-width: 330px) {
        .advert-card .carousel--left {
            width: 90%;
        }

        .advert-card .right-card {
            width: 90%;
        }

        #left {
            padding: 0.15rem !important;
        }
    }

    @media only screen and (max-width: 750px) {
        .dropdown-filter, .btn-filter {
            font-size: 14px !important;
        }
    }

    @media only screen and (max-width: 656px) {
        .dropdown-filter, .btn-filter {
            width: 135px !important;
        }

        .left {
            padding: 0.5rem !important;
        }

        .dropdown-filter button, .btn-filter {
            font-size: 13px !important;
        }

        .btn-filter {
            font-size: 13px !important;
            width: fit-content !important;
            padding: 0.6rem 1.2rem !important;
            margin: 0 !important;
            height: 40px !important;
            margin-left: 0.2rem !important;
        }

        .m-2 {
            margin: 0 !important;
        }

        .filters {
            overflow-x: scroll;
        }

        .vm--modal {
            position: fixed;
            overflow: scroll;
            width: 100vw !important;
            height: 100vh !important;
            left: 0 !important;
        }

        .vm--modal .wrapper .grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }

    @media only screen and (max-width: 1360px) {
        .advert-card {
            flex-direction: column;
        }
        .advert-card  .carousel__list {
            width: 100% !important;
        }
        .advert-card .carousel {
            height: 17rem;
        }

        .map-toggler {
            display: none;
        }

        .leaflet-control-container {
            display: none;
        }

        .close-map {
            display: block;
        }
    }

    .sort .dropdown-item {
        padding: 6px 30px;
        color: var(--greyish-blue);
        outline: none;
    }

    .sort .dropdown-item:hover {
        color: var(--blue-900);
        background-color: var(--bg-grey);
    }

    .autocomplete {
        min-width: 50% !important;
    }

    .hidden {
        display: none;
    }

</style>
</html>
