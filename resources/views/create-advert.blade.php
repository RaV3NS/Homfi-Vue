<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Создание объявления</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <v-app>
        <create-advert></create-advert>
    </v-app>
</div>
<script src="{{ asset('js/app.js') }}"></script>

<style>
    .toast-flex {
        display: flex;
        align-items: center;
    }

    .toast-flex img {
        width: 50px;
    }

    .vue2leaflet-map {
        z-index: 1;
    }

    .upload-area .v-btn {
        background-color: transparent !important;
        margin-top: 0;
    }

    .upload-area span {
        color: #d5dbe5;
        font-size: 12px;
        white-space: initial !important;
        text-align: center;
    }

    .upload-area:hover span {
        color: #000;
    }

    .upload-area:hover {
        border: 2px dashed #000;
    }

    .upload-area .v-btn__content {
        max-width: 100%;
        padding: 40px 100px;
    }

    @media (min-width: 550px) {
        .upload-area .v-btn__content {
            padding: 70px 160px;
        }
    }

    @media (max-width: 400px) {
        .upload-area .v-btn__content {
            padding: 40px 50px;
        }
    }

    .upload-area {
        border-radius: 6px;
        border: 2px dashed #d5dbe5;
        -webkit-flex-direction: column;
        -moz-box-orient: vertical;
        -moz-box-direction: normal;
        flex-direction: column;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        -webkit-justify-content: center;
        -moz-box-pack: center;
        justify-content: center;
        cursor: pointer;
        padding: 0 !important;
        display: block;
        margin: 0 auto;
        margin-top: 1rem;
    }

    .photo-block > div {
        display: flex;
        flex-wrap: wrap;
    }

    .photo-block .photo-wrapper:first-child {
        width: 100%;
        height: auto;
    }

    .vue-upload-btn .v-btn__content span {
        padding: 0.8rem 0.4rem;
        font-size: 14px;
        text-transform: none;
    }

    .v-input--selection-controls {
        margin-top: 0;
    }

    input[type='number'] {
        -moz-appearance:textfield;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    .advert-input fieldset {
        border: 1px solid #d5dbe5;
    }

    .advert-input .v-text-field__slot input::placeholder,.advert-input .v-select__slot input::placeholder {
        color: gray;
    }

    .v-autocomplete .v-input__slot {
        box-shadow: none !important;
        border: 1px solid darkgrey !important;
    }

    .v-input--is-disabled .v-input__slot {
        background-color: lightgray !important;
    }

    .v-text-field .v-input__slot {
        border: none;
    }

    .marker-house img {
        position: relative;
        top: -40px;
        left: -34px;
    }
</style>
</body>
</html>
