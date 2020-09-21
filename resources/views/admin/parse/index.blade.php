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
                <form method="post">
                    @csrf

                    @if (isset($advert))
                        {{ method_field('PUT') }}

                        <div class="form-group">
                            <label>Заголовок</label>
                            <input class="form-control" type="text" name="title" value="{{ $advert['title'] }}" required>
                        </div>

                        <div class="form-group">
                            <label>Имя автора</label>
                            <input class="form-control" type="text" name="name" value="{{ $advert['name'] }}" required>
                        </div>

                        <div class="form-group">
                            <label>Количество комнат</label>
                            <select name="room_count" id="room_count" class="form-control" required>
                                <option value="1">1 комната</option>
                                <option value="2">2 комнаты</option>
                                <option value="3">3 комнаты</option>
                                <option value="4">4 комнаты</option>
                                <option value="5">5+ комнат</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Телефон автора</label>
                            <input class="form-control" type="text" name="phone" required>
                        </div>

                        <div class="form-group">
                            <label>Улица</label>
                            <input class="form-control" type="text" name="street">
                        </div>

                        <div class="form-group">
                            <label>Дом</label>
                            <input class="form-control" type="text" name="address">
                        </div>

                        <div class="form-group">
                            <label>Цена (грн)</label>
                            <input class="form-control"  type="text" name="price" value="{{ $advert['price'] }}" required>
                        </div>

                        <div class="form-group">
                            <label>Город</label>
                            <input class="form-control" type="text" value="{{ $advert['city']->name_ru }}" readonly>
                            <input type="hidden" name="city" value="{{ $advert['city']->id }}">
                        </div>

                        <div class="form-group">
                            <label>Описание</label>
                            <textarea class="form-control" rows="7" name="about" id="about">{!! $advert['about'] !!}</textarea>
                        </div>

                        <div class="gallery">
                            @foreach ($advert['gallery'] as $photo)
                                <a href="{{ $photo }}" target="_blank">
                                    <img src="{{ $photo }}" alt="{{ \Illuminate\Support\Str::random(10) }}" style="width: 150px">
                                </a>
                            @endforeach

                            <input type="hidden" name="gallery" value="{{ implode('/--/', $advert['gallery']) }}">
                        </div>

                        <a href="/admin/parse" class="btn btn-primary" style="margin-top: 1rem;"> Выбрать другое</a>
                        <button type="submit" class="btn btn-success" style="margin-top: 1rem">Создать объявление</button>
                    @else
                        <label>Ссылка на объявление OLX</label>
                        <input type="text" class="form-control" name="url" required>

                        <button type="submit" class="btn btn-primary" style="margin-top: 1rem">Предпросмотр</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $("#city").select2();

        @if (isset($advert))
            $("#city").val({{ $advert['city']->id }});
        @endif

        function redirect_blank(url) {
            var a = document.createElement('a');
            a.target="_blank";
            a.href=url;
            a.click();
        }

        redirect_blank('http://google.com');
    </script>
@stop
