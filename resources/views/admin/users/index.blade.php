@extends('adminlte::page')

@section('title', __('adminlte::admin.users'))

@section('content_header')
    <h1>{{ __('adminlte::admin.users') }}</h1>
@stop

@section('content')
    <div class="box-body table-responsive">
        <table id="data-table" class="table table-bordered table-striped table-hover users-list">
            <thead>
            <tr>
                <th width="3%">ID</th>
                <th>{{ __('adminlte::admin.full_name') }}</th>
                <th>E-mail</th>
                <th class="small-title" width="4%">Кол-во объявлений</th>
                <th class="small-title" width="10%">{{ __('adminlte::admin.registration_date') }}</th>
                <th class="small-title" width="3%">Подтвержден</th>
                <th width="5%">{{ __('adminlte::admin.status') }}</th>
                <th class="small-title" width="10%">Послединй вход</th>
                <th>Имя</th>
                <th width="6%">{{ __('adminlte::admin.actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

    @include('admin.partials.modals.block-user')

    @include('admin.partials.modals.unblock-user')

    @include('admin.partials.modals.activate-user')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>

@error('user_log.title')
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: '{{ __("adminlte::admin.user.validation.title") }}',
                subtitle: '',
                body: '{{ __("adminlte::admin.user.$message") }}'
            })
        </script>
    @enderror
    @error('user_log.body')
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: '{{ __("adminlte::admin.user.validation.title") }}',
                subtitle: '',
                body: '{{ __("adminlte::admin.user.$message") }}'
            })
        </script>
    @enderror

    <script>
        let minRangeLoginDate, maxRangeLoginDate;
        function composeTable() {
            const table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('admin.users.list') !!}',
                    data: {
                        lastLoginAfter: minRangeLoginDate,
                        lastLoginBefore: maxRangeLoginDate
                    }
                },
                searching: true,
                order: [[0, 'asc']],
                pageLength: 20,
                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-adverts', data.adverts);
                },
                columns: [
                    { data: 'id', name: 'users.id' },
                    { data: 'fullname', name: 'users.last_name' },
                    { data: 'email', name: 'users.email' },
                    { data: 'adverts_count', name: 'adverts_count', searchable: false, orderable: true },
                    { data: 'created_at', name: 'users.created_at', searchable: false, render: renderDate },
                    {
                        data: 'email_verified_at', name: 'email_verified_at', searchable: false,
                        render: function (data, type, row) {
                            var className = 'success',
                                cellValue = '<i class="fas fa-check-circle"></i>';
                            if (!data) {
                                className = 'danger';
                                cellValue = '<i class="fas fa-times-square"></i>';
                            }

                            return '<div class="text-center"><span class="label label-' + className + '">' + cellValue + '</span></div>';
                        }
                    },
                    {
                        data: 'status', name: 'status',
                        render: function (data, type, row) {
                            var className = 'success',
                                cellValue = '{{ trans('adminlte::admin.active') }}';
                            if (data === 'notactive') {
                                className = 'warning';
                                cellValue = '{{ trans('adminlte::admin.notactive') }}';
                            }
                            if (data === 'blocked') {
                                className = 'danger';
                                cellValue = '{{ trans('adminlte::admin.blocked') }}';
                            }
                            if (data === 'deleted') {
                                className = 'warning';
                                cellValue = '{{ trans('adminlte::admin.deleted') }}';
                            }
                            return '<span class="label label-' + className + '">' + cellValue + '</span>';
                        }
                    },
                    { data: 'last_login', name: 'users.last_login', searchable: true, orderable: true, render: renderDate},
                    { data: 'first_name', name: 'users.first_name', searchable: true },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-nowrap' },
                ],
                columnDefs: [
                    {
                        "targets": [8],
                        "visible": false,
                        "searchable": true
                    },
                ],
                language: {
                    "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                    "emptyTable": "{{ __('adminlte::admin.no_results') }}",
                    "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                    "infoEmpty": "{{ __('adminlte::admin.showing_info_dt_zero') }}",
                    "search": "{{ __('adminlte::admin.search') }}",
                    "sZeroRecords": "К сожалению, Ваш запрос не дал результатов. Измените параметры фильтра и попробуйте еще раз",
                    "paginate": {
                        "first": "{{ __('adminlte::admin.dt_first') }}",
                        "last": "{{ __('adminlte::admin.dt_last') }}",
                        "next": "{{ __('adminlte::admin.dt_next') }}",
                        "previous": "{{ __('adminlte::admin.dt_prev') }}",
                    },
                },
                drawCallback: function () {
                    $('#data-table td a.block').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();
                        const advertsCount = $(this).closest('tr').data('adverts');

                        $('.modal-content form').attr('action', userRoute + except);
                        $('#block-modal').modal();

                        $('#modalLabel #adverts-count').text(advertsCount);

                        $('.modal-content form').attr('action', userRoute + except);

                    });

                    $('#data-table td a.unblock').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();
                        const advertsCount = $(this).closest('tr').data('adverts');

                        $('.modal-content form').attr('action', userRoute + except);
                        $('#unblock-modal').modal();

                        $('#unblockModalLabel #adverts-count').text(advertsCount);

                        $('.modal-content form').attr('action', userRoute + except);
                    });

                    $('#data-table td a.activate').click(function (e) {
                        e.preventDefault();
                        except = $(this).closest('tr').find('td:first-child').text();

                        $('.modal-content form').attr('action', userRoute + except);
                        $('#activate-modal').modal();

                        $('.modal-content form').attr('action', userRoute + except);
                    });
                },
                initComplete: function () {
                    const statuses = this.api().ajax.json().statuses;
                    this.api().columns([6]).every(function () {
                        var column = this;

                        var select = $('<select id="user_type_filter"><option value="">{{ __('adminlte::admin.status') }}</option></select>')
                            .prependTo($('#data-table_filter'))
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column.search(val).draw();
                            });
                        let cellValue = '';
                        for(let status in statuses) {
                            if(status === 'active'){
                                cellValue = '{{ trans('adminlte::admin.active') }}';
                            }

                            if (status === 'disabled') {
                                cellValue = '{{ trans('adminlte::admin.disabled') }}';
                            }
                            if (status === 'blocked') {
                                cellValue = '{{ trans('adminlte::admin.blocked') }}';
                            }
                            if (status === 'deleted') {
                                cellValue = '{{ trans('adminlte::admin.deleted') }}';
                            }

                            if(status && cellValue) {
                                select.append('<option value="' + status + '">' + cellValue + '</option>')
                            }
                        }
                    });

                    if(minRangeLoginDate !== undefined){
                        $('#user_date_after_filter').val(minRangeLoginDate);
                    }

                    if(maxRangeLoginDate !== undefined){
                        $('#user_date_before_filter').val(maxRangeLoginDate);
                    }
                }
            });

            $('<input type="text" class="datepicker-input datatable_filter" id="user_date_after_filter" ' +
                'data-toggle="datepicker" placeholder="Дата от"/>').appendTo($('#data-table_length'));

            $('<input type="text" class="datepicker-input datatable_filter" id="user_date_before_filter"  ' +
                'data-toggle="datepicker" placeholder="Дата до" />').appendTo($('#data-table_length'));

            $('#data-table_filter').append('<input type="button" data-url="{{ route('admin.users.index') }}" id="reset_filter" class="" value="Сбросить"/>');
            $('#data-table_filter').on('click', '#reset_filter', function () {
                window.location.href = $(this).data('url');
            });

            const datepickerOptions = {
                locale: 'ru',
                language: 'ru',
                format: 'dd.mm.yyyy'
            };
            $('#user_date_after_filter').datepicker(datepickerOptions).on('changeDate', function (e) {
                $(this).datepicker('hide');
                minRangeLoginDate = $(this).val();
                table.destroy();
                composeTable();
            });
            $('#user_date_before_filter').datepicker(datepickerOptions).on('changeDate', function (e) {
                $(this).datepicker('hide');
                maxRangeLoginDate = $(this).val();
                table.destroy();
                composeTable();
            });
        }

        var userRoute = '{{route('admin.users.update', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');
        var except = null;

        composeTable();

        function renderDate(data) {
            return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
        }
    </script>
@stop
