@extends('adminlte::page')

@section('title', "Страница управления контентом")

@section('content_header')
    <h1>Контент</h1>
@stop

@section('content')
<h4>Страница условий и использования сайта</h4>
<div class="row">

        <div class="col-md-12">
            <form action="{{ route('admin.content.update', 1) }}" method="post">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#russian"
                                                data-toggle="tab">Русский *</a></li>

                        <li class="nav-item"><a class="nav-link" href="#ukranian"
                                                data-toggle="tab">Украинский</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="russian">
                            <div class="col-md-9" style="display: inline-block">
                                <textarea name="body_ru" class="text-editor" required>@if(isset($content->body_ru)){{ $content->body_ru }}@endif</textarea>
                            </div>
                            <div class="form-group col-md-2" style="display: inline-block">
                                <button type="submit"
                                        class="btn btn-block btn-success m-2">
                                    Сохранить
                                </button>
                                <a href="{{ route('admin.content.index') }}">
                                    <button type="submit"
                                            class="btn btn-block btn-default m-2">
                                        Отмена
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="tab-pane" id="ukranian">
                            <div class="col-md-9" style="display: inline-block">
                                <textarea name="body_uk" class="text-editor">@if(isset($content->body_uk)){{ $content->body_uk }}@endif</textarea>
                            </div>
                            <div class="form-group col-md-2" style="display: inline-block">
                                <button type="submit"
                                        class="btn btn-block btn-success m-2">
                                    Сохранить
                                </button>
                                <a href="{{ route('admin.content.index') }}">
                                    <button type="submit"
                                            class="btn btn-block btn-default m-2">
                                        Отмена
                                    </button>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @csrf
            @method('PUT')
        </div>
    </form>
</div>
@stop

@section('css')
        <link rel="stylesheet" href="/vendor/summernote/summernote.css">
@stop

@section('js')
    @error('body_ru')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: 'Ошибка валидации',
            subtitle: '',
            body: 'Заполните обязательное поле '
        })
    </script>
    @enderror
    @error('body_uk')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: 'Ошибка валидации',
            subtitle: '',
            body: 'Заполните обязательное поле '
        })
    </script>
    @enderror

    <script src="/vendor/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function () {
            // Summernote
            $('.text-editor').summernote({
                height: 250,
            })
        })
    </script>
@stop
