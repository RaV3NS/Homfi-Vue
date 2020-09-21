@extends('adminlte::page')

@section('title', __('adminlte::admin.adverts') )

@section('content_header')
    <h1>{{ __('adminlte::admin.adverts') }}</h1>
@stop

@section('content')
    <div class="box-body table-responsive">
        <table id="data-table" class="table table-bordered table-striped table-hover adverts-list">
            <thead>
            <tr>
                <th width="3%">ID</th>
                <th>{{ __('adminlte::admin.created') }}</th>
                <th>E-mail</th>
                <th>{{ __('adminlte::admin.city') }}</th>
                <th>{{ __('adminlte::admin.type') }}</th>
                <th>{{ __('adminlte::admin.updated') }}</th>
                <th>{{ __('adminlte::admin.status') }}</th>
                <th width="7%">{{ __('adminlte::admin.actions') }}</th>
                <th></th>
            </tr>
            </thead>
        </table>
    </div>

    @include('admin.partials.modals.block-advert')

    @include('admin.partials.modals.unblock-advert')

    @include('admin.partials.modals.reject-advert')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    @error('user_log.title')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: '{{ __("adminlte::admin.advert.validation.title") }}',
            subtitle: '',
            body: '{{ __("adminlte::admin.advert.$message") }}'
        })
    </script>
    @enderror

    <script>
        let cityId;
        function composeTable() {
            const dataTable = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.adverts.list') !!}',
                    data: {
                        cityId: cityId
                    }
                },
                searching: true,
                order: [[5, 'desc']],
                pageLength: 20,
                columnDefs: [
                    {
                        "targets": [8],
                        "visible": false,
                        "searchable": true
                    }
                ],
                columns: [
                    {data: 'id', name: 'adverts.id', searchable: true},
                    {data: 'created_at', name: 'adverts.created_at', searchable: false, render: renderDate},
                    {data: 'email', name: 'adverts.email', searchable: true},
                    {data: 'city.name_ru', name: 'city.name_ru', searchable: false},
                    {
                        data: 'type', name: 'adverts.type',
                        render: function (data) {
                            let type = '';
                            switch (data) {
                                case 'flat':
                                    type = "{{ __('adminlte::admin.advert_type.flat') }}";
                                    break;
                                case 'house':
                                    type = "{{ __('adminlte::admin.advert_type.house') }}";
                                    break;
                                case 'half-house':
                                    type = "{{ __('adminlte::admin.advert_type.half-house') }}";
                                    break;
                                case 'room':
                                    type = "{{ __('adminlte::admin.advert_type.room') }}";
                                    break;
                            }
                            return type;
                        }
                    },
                    {data: 'updated_at', name: 'adverts.updated_at', searchable: false, render: renderDate},
                    {
                        data: 'status', name: 'adverts.status', searchable: true,
                        render: function (data) {
                            let status = '';
                            switch (data) {
                                case 'disabled':
                                    status = "{{ __('adminlte::admin.advert_status.disabled') }}";
                                    break;
                                case 'enabled':
                                    status = "{{ __('adminlte::admin.advert_status.enabled') }}";
                                    break;
                                case 'moderate':
                                    status = "{{ __('adminlte::admin.advert_status.moderate') }}";
                                    break;
                                case 'accepted':
                                    status = "{{ __('adminlte::admin.advert_status.accepted') }}";
                                    break;
                                case 'rejected':
                                    status = "{{ __('adminlte::admin.advert_status.rejected') }}";
                                    break;
                                case 'deleted':
                                    status = "{{ __('adminlte::admin.advert_status.deleted') }}";
                                    break;
                                case 'blocked':
                                    status = "{{ __('adminlte::admin.advert_status.blocked') }}";
                                    break;
                                case 'draft':
                                    status = "{{ __('adminlte::admin.advert_status.draft') }}";
                                    break;
                            }
                            return status;
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-nowrap'},
                    {data: 'city.id', name: 'city.id', searchable: true, hidden: true},
                ],
                language: {
                    "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                    "emptyTable": "{{ __('adminlte::admin.no_adverts') }}",
                    "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                    "infoEmpty": "{{ __('adminlte::admin.showing_info_dt_zero') }}",
                    "sZeroRecords": "К сожалению, Ваш запрос не дал результатов. Измените параметры фильтра и попробуйте еще раз",
                    "paginate": {
                        "first": "{{ __('adminlte::admin.dt_first') }}",
                        "last": "{{ __('adminlte::admin.dt_last') }}",
                        "next": "{{ __('adminlte::admin.dt_next') }}",
                        "previous": "{{ __('adminlte::admin.dt_prev') }}",
                    },
                    "search": "{{ __('adminlte::admin.search') }}",
                },
                drawCallback: function () {
                    $('#data-table td a.block').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();
                        const advertsCount = $(this).closest('tr').data('adverts');

                        $('.modal-content form').attr('action', advertRoute + except);
                        $('#block-modal').modal();

                        $('#modalLabel #adverts-count').text(advertsCount);

                        $('.modal-content form').attr('action', advertRoute + except);

                    });

                    $('#data-table td a.enable').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();
                        const advertsCount = $(this).closest('tr').data('adverts');

                        $('.modal-content form').attr('action', advertRoute + except);
                        $('#unblock-modal').modal();

                        $('#unblockModalLabel #adverts-count').text(advertsCount);

                        $('.modal-content form').attr('action', advertRoute + except);
                    });

                    $('#data-table td a.activate').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();

                        $('.modal-content form').attr('action', advertRoute + except);
                        $('#activate-modal').modal();

                        $('.modal-content form').attr('action', advertRoute + except);
                    });
                },
                initComplete: function () {
                    // STATUS
                    const statuses = this.api().ajax.json().statuses;
                    this.api().columns([6]).every(function () {
                        var column = this;

                        var select = $('<select id="advert_status_filter"><option value="">{{ __('adminlte::admin.status') }}</option></select>')
                            .prependTo($('#data-table_filter'))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column.search(val, true, false).draw();
                            });
                        if (statuses) {
                            for (let status in statuses) {
                                let cellValue = '';

                                switch (status) {
                                    case 'draft':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.draft') }}';
                                        break;

                                    case 'disabled':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.disabled') }}';
                                        break;

                                    case 'enabled':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.enabled') }}';
                                        break;

                                    case 'moderate':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.moderate') }}';
                                        break;

                                    case 'rejected':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.rejected') }}';
                                        break;

                                    case 'accepted':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.accepted') }}';
                                        break;

                                    case 'blocked':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.blocked') }}';
                                        break;

                                    case 'deleted':
                                        cellValue = '{{ trans('adminlte::admin.advert_status.deleted') }}';
                                        break;

                                }

                                if (status && cellValue) {
                                    select.append('<option value="' + status + '">' + cellValue + '</option>');
                                }
                            }
                        }
                    });

                    // TYPE
                    const types = this.api().ajax.json().types;
                    this.api().columns([4]).every(function () {
                        var column = this;

                        var select = $('<select id="advert_type_filter"><option value="">{{ __('adminlte::admin.type') }}</option></select>')
                            .appendTo($('#data-table_length'))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column.search('^' + val, true, false).draw();
                            });

                        let cellValue = '';
                        if (types) {
                            for (let type in types) {
                                switch (type) {
                                    case 'flat':
                                        cellValue = '{{ trans('adminlte::admin.advert_type.flat') }}';
                                        break;

                                    case 'house':
                                        cellValue = '{{ trans('adminlte::admin.advert_type.house') }}';
                                        break;

                                    case 'half-house':
                                        cellValue = '{{ trans('adminlte::admin.advert_type.half-house') }}';
                                        break;

                                    case 'room':
                                        cellValue = '{{ trans('adminlte::admin.advert_type.room') }}';
                                        break;
                                }
                                if (type && cellValue) {
                                    select.append('<option value="' + type + '">' + cellValue + '</option>');
                                }
                            }
                        }
                    });

                    // CITY
                    const cities = this.api().ajax.json().cities;

                    const citySelect = $('<select id="advert_city_filter"><option value="">{{ __('adminlte::admin.city') }}</option></select>')
                        .appendTo($('#data-table_length'))
                        .on('change', function (e) {
                            cityId = $(this).val();
                            dataTable.destroy();
                            composeTable();
                        });

                    for (let cityId in cities) {
                        citySelect.append('<option value="' + cityId + '">' + cities[cityId] + '</option>');
                    }

                    if (cityId !== undefined) {
                        $('#advert_city_filter option[value="' + cityId + '"]').attr('selected', true);
                    }
                }
            });

            $('#data-table_filter').append('<button data-url="{{ route('admin.adverts.index') }}" id="reset_filter" type="button" class="btn btn-default">Сбросить</button>');
            $('#data-table_filter').on('click', '#reset_filter', function () {
                window.location.href = $(this).data('url');
            });
        }

        function renderDate(data) {
            return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
        }

        var advertRoute = '{{route('admin.adverts.update.status', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');
        var except = null;

        composeTable();

    </script>
@stop
