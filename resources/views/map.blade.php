<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Map</title>
</head>
<body>
    <div id="app">
        <header-vue></header-vue>
        <map-page city="{{ $city }}"></map-page>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

<style>
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
        padding: 0.6rem 1.2rem;

        min-width: 120px;
        justify-content: center;
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

</style>
</html>
