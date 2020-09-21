@extends('adminlte::page')

@section('title', "Страница OLX парсинга")

@section('content_header')
    <h1>Парсинг из OLX</h1>
@stop

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

    <div class="row">
        <div class="card col-lg-12">
            <div class="card-header">Парсинг объявления</div>

            <div class="card-body">
                <b>Объявление успешно создано!</b>
                <br>
                <a href="/admin/parse" class="btn btn-success">Вернутся назад</a>
                <a href="{{ $url }}" class="btn btn-primary" target="_blank">Просмотреть объявление</a>
            </div>
        </div>
    </div>
@stop
