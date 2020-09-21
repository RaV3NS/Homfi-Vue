@extends('adminlte::page')

@section('title', "Список уведомлений" )

@section('content_header')
    <h1>Уведомления</h1>
@stop

@section('content')
    <div class="box-body table-responsive">
        <table id="data-table" class="table table-bordered table-striped table-hover notifications-list">
            <thead>
            <tr>
                <th>Уведомление</th>
                <th width="3%">ID</th>
                <th>{{ __('adminlte::admin.created') }}</th>
                <th>{{ __('adminlte::admin.type') }}</th>
                <th>{{ __('adminlte::admin.status') }}</th>
            </tr>
            </thead>
        </table>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        #data-table_filter label {
            float: left;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>

    <script>
        let minRangeDate, maxRangeDate;
        function composeTable() {
            const table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,

            ajax: {
                url: '{!! route('admin.notifications.list') !!}',
                data: {
                    filterDateAfter: minRangeDate,
                    filterDateBefore: maxRangeDate
                }
            },
            searching: true,
            search: true,
            order: [[2, 'desc']],
            pageLength: 20,
            columns: [
                {
                    data: 'title', name: 'title', searchable: false, orderable: false,
                    render: function (data) {
                        return decodeHTMLEntities(data);
                    }
                },
                {data: 'advert_id', name: 'advert_id', searchable: true, orderable: true, render: function (data) {
                        return "<a href='/admin/adverts/" + data + "'>"+data+"</a>";
                    }
                },
                {data: 'created_at', name: 'created_at', searchable: false, render: renderDate},
                {
                    data: 'type', name: 'type', visible: true, searchable: true,
                    render: function (data) {
                        let type = '';
                        switch (data) {
                            case 'new_advert':
                                type = "Новое объявление";
                                break;
                            case 'complain':
                                type = "Жалоба";
                                break;
                            case 'advert_moderate':
                                type = "Объявление отредактировано";
                                break;
                        }
                        return type;
                    }
                },
                {
                    data: 'status', name: 'status', searchable: true, visible: true,
                    render: function (data) {
                        let status = '';
                        switch (data) {
                            case 'read':
                                status = "Прочитано";
                                break;
                            case 'new':
                                status = "Непрочитано";
                                break;
                        }
                        return status;
                    }
                },
            ],
            language: {
                "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                "emptyTable": "Нет уведомлений",
                "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                "infoEmpty": "Система не нашла уведомлений согласно выбранным фильтрам",
                "sZeroRecords": "К сожалению, Ваш запрос не дал результатов. Измените параметры фильтра и попробуйте еще раз",
                "paginate": {
                    "first": "{{ __('adminlte::admin.dt_first') }}",
                    "last": "{{ __('adminlte::admin.dt_last') }}",
                    "next": "{{ __('adminlte::admin.dt_next') }}",
                    "previous": "{{ __('adminlte::admin.dt_prev') }}",
                },
                "search": "{{ __('adminlte::admin.search') }}",
            },
            initComplete: function () {
                // TYPE
                const types = this.api().ajax.json().types;
                this.api().columns([3]).every(function () {
                    var column = this;

                    var typeSelect = $('<select id="notification_type_filter" class="datatable_filter"><option value="">Тип</option></select>')
                        .prependTo($('#data-table_filter'))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column.search(val).draw();
                        });

                    if (types) {
                        for (let type in types) {
                            let cellValue = '';

                            switch (type) {
                                case 'new_advert':
                                    cellValue = 'Новое объявление';
                                    break;

                                case 'complain':
                                    cellValue = 'Жалоба';
                                    break;

                                case 'advert_moderate':
                                    cellValue = 'Объявление отредактировано';
                                    break;
                            }

                            if (type && cellValue) {
                                typeSelect.append('<option value="' + type + '">' + cellValue + '</option>');
                            }
                        }
                    }
                });

                // СТАТУС
                const statuses = this.api().ajax.json().statuses;
                this.api().columns([4]).every(function () {
                    var column = this;

                    var statusSelect = $('<select id="notification_status_filter" class="datatable_filter"><option value="">Статус</option></select>')
                        .prependTo($('#data-table_filter'))
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column.search(val).draw();
                        });

                    if (statuses) {
                        for (let status in statuses) {
                            let cellValue = '';

                            switch (status) {
                                case 'read':
                                    cellValue = 'Прочитано';
                                    break;

                                case 'new':
                                    cellValue = 'Непрочитано';
                                    break;
                            }

                            if (status && cellValue) {
                                statusSelect.append('<option value="' + status + '">' + cellValue + '</option>');
                            }
                        }
                    }
                });

                // Filter Date
                if(minRangeDate !== undefined){
                    $('#notification_date_after_filter').val(minRangeDate);
                }

                if(maxRangeDate !== undefined){
                    $('#notification_date_before_filter').val(maxRangeDate);
                }
            }
        });

        $('<input type="text" class="datepicker-input datatable_filter" id="notification_date_after_filter" ' +
            'data-toggle="datepicker" placeholder="Дата от"/>').appendTo($('#data-table_length'));

        $('<input type="text" class="datepicker-input datatable_filter" id="notification_date_before_filter"  ' +
            'data-toggle="datepicker" placeholder="Дата до" />').appendTo($('#data-table_length'));

        $('#data-table_filter').append('<input type="button" data-url="{{ route('admin.notifications.index') }}" id="reset_filter" class="btn btn-default" value="Сбросить"/>');
        $('#data-table_filter').on('click', '#reset_filter', function () {
            window.location.href = $(this).data('url');
        });

        const datepickerOptions = {
            locale: 'ru',
            language: 'ru',
            format: 'dd.mm.yyyy'
        };
        $('#notification_date_after_filter').datepicker(datepickerOptions).on('changeDate', function (e) {
            $(this).datepicker('hide');
            minRangeDate = $(this).val();
            table.destroy();
            composeTable();
        });
        $('#notification_date_before_filter').datepicker(datepickerOptions).on('changeDate', function (e) {
            $(this).datepicker('hide');
            maxRangeDate = $(this).val();
            table.destroy();
            composeTable();
        });
    }

    function urlify(text) {
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, function(url) {
            return '<a href="' + url + '">' + text + '</a>';
        })
    }

    function decodeHTMLEntities(text) {
        var textArea = document.createElement('textarea');
        textArea.innerHTML = text;
        return textArea.value;
    }

    function renderDate(data) {
        return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
    }

    $('body').on('click', '.notifications-list tr', function () {

        let elemLink = $(this).find('td:first a');
        if( elemLink.attr('href') !== undefined){
            window.location.href = elemLink.attr('href');
        }
    });

    composeTable();

    </script>
@stop
