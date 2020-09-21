@extends('adminlte::page')

@section('title', "Список районов" )

@section('content_header')
    <h1>Административные районы</h1>
    <div class="btn-group float-right" style="margin-bottom: 10px;">
        <a class="btn btn-primary" href="{{route('admin.administrative.create')}}">Добавить</a>
    </div>
@stop

@section('content')
    <div class="box-body table-responsive">
        <table id="data-table" class="table table-bordered table-striped table-hover notifications-list">
        </table>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.administrative.list') !!}',
            searching: true,
            order: [[0, 'asc'], [1, 'asc']],
            columns: [
                {
                    data: 'city_name', name: 'cities.name_ru', orderable: true, title: 'Название города'
                },
                {
                    data: 'name_ru', name: 'administrative.name_ru', orderable: true, title: 'Название района'
                },
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            language: {
                "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                "emptyTable": "Нет уведомлений",
                "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                "infoEmpty": "Система не нашла уведомлений согласно выбранным фильтрам",
                "sZeroRecords": "К сожалению, Ваш запрос не дал результатов. Измените параметры поиска и попробуйте еще раз",
                "paginate": {
                    "first": "{{ __('adminlte::admin.dt_first') }}",
                    "last": "{{ __('adminlte::admin.dt_last') }}",
                    "next": "{{ __('adminlte::admin.dt_next') }}",
                    "previous": "{{ __('adminlte::admin.dt_prev') }}",
                },
                "search": "{{ __('adminlte::admin.search') }}",
            },
        });
    </script>
@stop
