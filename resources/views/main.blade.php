<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Мой профиль</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
<div id="app">
    <v-app>
        <main-form></main-form>
    </v-app>
</div>
<script src="{{ asset('js/app.js') }}"></script>

<style>

    .filter-button-mobile {
        padding-left: 1rem;
        font-size: 16px;
        line-height: 36px;
        color: var(--blue-900);
        width: 100%;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -webkit-justify-content: space-between;
        -moz-box-pack: justify;
        justify-content: space-between;
        -webkit-align-items: center;
        -moz-box-align: center;
        align-items: center;
        color: var(--disabled-color,var(--greyish-blue));
        font-weight: 300;
    }

    .has-error .v-input__slot {
        border: 2px solid crimson !important;
    }

    .filter-button:not(:first-of-type) {
        border-left: none;
    }

    .filter-button:first-of-type {
        border-top-left-radius: var(--border-radius);
        border-bottom-left-radius: var(--border-radius);
    }

    .filter-button:last-of-type {
        border-top-right-radius: var(--border-radius);
        border-bottom-right-radius: var(--border-radius);
    }

    .filter-toggle {
        width: 100%;
        height: 100%;
        padding: 0 1.25rem;
        background-color: transparent;
        border: none;
        color: var(--greyish-blue);
        text-align: left;
        outline: none;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        cursor: pointer;
        border-radius: var(--border-radius);
        -webkit-transition: background-color .2s ease;
        transition: background-color .2s ease;
        will-change: background-color;
    }

    .filter-button {
        --border-radius: 6px;
        position: relative;
        display: -webkit-flex;
        display: -moz-box;
        display: flex;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        border: 1px solid var(--gray-300);
        background-color: var(--white);
        text-align: left;
        min-width: auto;
        height: 100%;
        color: #000;
    }

    .option-content {
        padding: 0.7rem 0.2rem;
    }

    .option-content-low {
        padding: 0.1rem 0.2rem !important;
    }

    .option-header {
        display: flex;
        font-family: Rubik;
        color: rgb(12, 36, 85);
    }

    .option-bold {
        font-weight: 600;
        font-family: Rubik;
        margin-left: 23px;
        margin-top: 3px;
        display: block;
        color: rgb(12, 36, 85);
    }

    .v-menu__content {
        z-index: 1200 !important;
    }

    .v-list-item:hover {
        background-color: #f1f1f4;
    }

    .v-select-list {
        padding: 0;
    }

    .v-list-item::before {
        background: none;
    }

    .v-input--selection-controls {
        margin-top: 0;
    }

    .filters-top .city-filter .v-input__slot {
        border: none;
        border-radius: 5px 0 0 5px;
        border-right: 1px solid #eaeaea;
    }

    .filters-top .street-filter .v-input__slot {
        border-radius: 0px 5px 5px 0;
        border-left: none;
    }

    .v-input__slot {
        height: 60px;
    }

    @media only screen and (max-width: 768px) {
        .v-input__slot {
            height: 40px;
            min-height: 0;
            font-size: 16px;
        }

        .city-filter .v-input__slot {
            border-radius: 5px 5px 0 0;
            box-shadow: none !important;
        }

        .street-filter .v-input__slot {
            border-radius: 0;
            border-top: 1px solid #eaeaea;
            box-shadow: none !important;
        }

        .filter-button-mobile {
            border-top: 1px solid #eaeaea;
        }

        .filter-button {
            border: none !important;
            box-shadow: none;
        }

        .filter-button:last-of-type {
            border-radius: 0 0 5px 5px;
        }

        .vm--modal {
            position: fixed;
            overflow: scroll;
            width: 100% !important;
            height: 100% !important;
            left: 0 !important;
            top: 0 !important;
            padding: 1.5rem;
            overflow-x: hidden;
        }

        .has-error .v-input__slot {
            border: 3px solid crimson !important;
            position: relative;
            top: -2px;
        }
    }
    @media only screen and (max-width: 1280px) {
        .container {
            max-width: 100%;
        }
    }
</style>

</body>
</html>
