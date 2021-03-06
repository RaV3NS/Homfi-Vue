<?php /** @var \App\Administrative $administrative */?>
@php
    $city = \App\City::query()->with(['region', 'region.district'])->find(old('city_id'))
@endphp
@extends('adminlte::page')

@section('title', "Создание района" )

@section('content_header')
    <h1>Создание района  {{$city ? $city->name : ''}}</h1>
@stop

@section('content')
    {{ $message = 'Обязательное поле для заполнения' }}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#russian"
                                                data-toggle="tab">Русский *</a></li>

                        <li class="nav-item"><a class="nav-link" href="#ukrainian"
                                                data-toggle="tab">Українська</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" id="quickForm" novalidate="novalidate" method="post" action="{{route('admin.administrative.store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="city" lang="russian">Город</label>
                            <label for="city" lang="ukrainian" hidden>Місто</label>
                            <select class="form-control select2 @error('city_id') is-invalid @enderror"
                                    required="required" id="city" name="city_id" aria-describedby="city-error">
                                @if($city)
                                    <option value="{{old('city_id')}}">{{$city->name_ru}}</option>
                                @endif
                            </select>
                            <span id="city-error"
                                  class="error invalid-feedback">@error('city_id') {{$message}} @enderror</span>
                        </div>
                        <div class="tab-content">
                            <div class="active tab-pane" id="russian">
                                <div class="form-group">
                                    <label for="name_ru">Название района</label>
                                    <input type="text" name="name_ru" class="form-control @error('name_ru') is-invalid @enderror"
                                           id="name_ru" value="{{old('name_ru')}}"
                                           aria-describedby="name_ru-error" @error('name_ru') aria-invalid="true" @enderror>
                                    <span id="name_ru-error" class="error invalid-feedback">@error('name_ru') {{$message}} @enderror</span>
                                </div>
                                <div class="form-group">
                                    <label for="old_name_ru">Старое название</label>
                                    <input type="text" name="old_name_ru" class="form-control @error('old_name_ru') is-invalid @enderror"
                                           id="old_name_ru" value="{{old('old_name_ru')}}"
                                           aria-describedby="old_name_ru-error" @error('old_name_ru') aria-invalid="true" @enderror>
                                    <span id="old_name_ru-error" class="error invalid-feedback">@error('old_name_ru') {{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="tab-pane" id="ukrainian">
                                <div class="form-group">
                                    <label for="name_uk">Назва района</label>
                                    <input type="text" name="name_uk" class="form-control @error('name_uk') is-invalid @enderror"
                                           id="name_uk" value="{{old('name_uk')}}"
                                           aria-describedby="name_uk-error" @error('name_uk') aria-invalid="true" @enderror>
                                    <span id="name_uk-error" class="error invalid-feedback">@error('name_uk') {{$message}} @enderror</span>
                                </div>
                                <div class="form-group">
                                    <label for="old_name_uk">Стара назва</label>
                                    <input type="text" name="old_name_uk" class="form-control @error('old_name_uk') is-invalid @enderror"
                                           id="old_name_uk" value="{{old('old_name_uk')}}"
                                           aria-describedby="old_name_uk-error" @error('old_name_uk') aria-invalid="true" @enderror>
                                    <span id="old_name_uk-error" class="error invalid-feedback">@error('old_name_uk') {{$message}} @enderror</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <a href="{{route('admin.administrative.index')}}" class="btn btn-default float-right">Закрыть</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.box -->
@stop

@section('css')
@stop

@section('js')
    <script>
        $(function() {
            $('a.nav-link').on('click', function() {
                var lang = $(this).attr('href').toString().substr(1);
                $('.card-body [lang]').prop('hidden', true);
                $('.card-body [lang="'+lang+'"]').prop('hidden', false);
                if ($('#city').val()) {
                    $('#select2-city-container').text($('#city').select2('data')[0][lang]);
                }
            });
            $('#city').select2({
                ajax: {
                    url: '{{route('admin.cities.search')}}',
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    delay: 250,
                    width: 'element',
                    processResults: function (data) {
                        return {
                            results: $.map(data.results, function (item) {
                                return {
                                    id: item.id,
                                    text: $('#russian').hasClass('active') ? item.full_city_name_ru : item.full_city_name,
                                    russian: item.full_city_name_ru,
                                    ukrainian: item.full_city_name
                                }
                            })
                        };
                    }
                }
            });
            @if($city)
                $('#city').select2('data')[0]['ukrainian'] = '{{$city->full_city_name}}';
                $('#city').select2('data')[0]['russian'] = '{{$city->full_city_name_ru}}';
            @endif

            if($('#city.is-invalid').length) {
                $('[aria-labelledby="select2-city-container"]').css('border-color', 'red');
            }
        })
    </script>
@stop
