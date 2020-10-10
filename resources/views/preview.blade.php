<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<div id="app">
    <preview-page id="{{ $id }}"></preview-page>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>

<style>

    .leaflet-control-attribution.leaflet-control {
        display: none;
    }

    .vm--container {
        z-index: 9999;
    }

    .marker-house img {
        position: relative;
        top: -40px;
        left: -34px;
    }

    .carousel-img {
        max-width: 100%;
        max-height: 100%;
    }

    .carousel-container .carousel--slidable {
        height: 31rem;
    }

    .carousel-container .carousel--slidable .slide {
        height: 31rem;
        background-position: center;
        background-repeat: no-repeat;
    }

    .slide {
        min-height: 30rem;
        background-position: center;
        background-repeat: no-repeat;
    }

    @media only screen and (max-width: 768px) {
        .carousel-container .carousel--slidable .slide {
            height: 20rem;
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .slide {
            min-height: 18rem;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .carousel-container .carousel--slidable {
            height: 18rem;
        }
    }

    @media only screen and (max-width: 400px) {
        .vm--modal {
            overflow-y: scroll;
        }

        .carousel-container .carousel__indicators {
            position: relative;
            top: -5.5rem;
        }

        .carousel-container .carousel--left {
            height: 20rem;
        }
    }

    @media only screen and (max-width: 350px) {
        .carousel-container .carousel__indicators {
            position: relative;
            top: -6.5rem;
        }

        .leaflet-control-attribution {
            display: none;
        }

        .vm--modal {
            width: 100vw !important;
            height: 100vh !important;
        }
    }

    .btn-mobile {
        outline: none;
        position: absolute;
        left: 10px;
        bottom: 10px;
        z-index: 9;
        line-height: 28px;
        padding: 0 10px;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        --btn-background-color: var(--white);
        --btn-background-color-hover: var(--beige-400);
        --btn-color: var(--blue-900);
        border: 1px solid var(--gray-300);
        --button-transition: background-color .3s;
        background-color: var(--btn-background-color);
        color: var(--btn-color,currentColor);
        cursor: pointer;
        border-radius: .375rem;
        font-family: inherit;
        font-size: var(--btn-font-size,1rem);
        font-weight: var(--btn-font-weight,400);
        text-decoration: none;
        -webkit-transition: var(--button-transition);
        transition: var(--button-transition);
    }

    .swiper-slide {
        width: 120px !important;
        margin: 0 1rem;
    }
</style>
</html>
