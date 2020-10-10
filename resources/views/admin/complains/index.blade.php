@extends('adminlte::page')

@section('title', __('adminlte::admin.complains'))

@section('content_header')
    <h1>{{ __('adminlte::admin.complains') }}</h1>
@stop

@section('content')
    <div class="box-body table-responsive">
        <table id="data-table" class="table table-bordered table-striped table-hover complains-list">
            <thead>
            <tr>
                <th width="3%">ID</th>
                <th>Дата</th>
                <th>ID Объявления</th>
                <th>ID Пользователя</th>
                <th>E-mail</th>
                <th>Телефон</th>
                <th>Причины жалобы</th>
                <th>Комментарий</th>
                <th class="small-title">Дата действия</th>
                <th>Статус</th>
                <th>{{ __('adminlte::admin.actions') }}</th>
            </tr>
            </thead>
        </table>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    @error('user_log.title')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: '{{ __("adminlte::admin.complain.validation.title") }}',
            subtitle: '',
            body: '{{ __("adminlte::admin.complain.$message") }}'
        })
    </script>
    @enderror
    @error('user_log.body')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: '{{ __("adminlte::admin.complain.validation.title") }}',
            subtitle: '',
            body: '{{ __("adminlte::admin.complain.$message") }}'
        })
    </script>
    @enderror

    <script>
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.complains.list') !!}',
            searching: true,
            order: [[0, 'desc']],
            pageLength: 20,
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-complains', data.complains);
            },
            columns: [
                {
                    data: 'id', name: 'complains.id', searchable: true, render: function (id) {
                        return getAhref((complainRoute + id), id);
                    }
                },
                {data: 'created_at', name: 'complains.created_at', searchable: false, render: renderDate},
                {
                    data: 'advert_id', name: 'advert_id', searchable: true, render: function (id) {
                        return getAhref((advertRoute + id), id);
                    }
                },
                {
                    data: 'user_id', name: 'user_id', searchable: true, render: function (id) {
                        return getAhref((userRoute + id), id);
                    }
                },
                {data: 'email', name: 'complains.email', searchable: true},
                {data: 'phone', name: 'complains.phone', searchable: true},
                {data: 'reason', name: 'complains.reason', searchable: false},
                {data: 'body', name: 'complains.body', searchable: false},
                {data: 'updated_at', name: 'updated_at', searchable: false, render: renderDate},
                {
                    data: 'status', name: 'status', searchable: true,
                    render: function (data, type, row) {
                        var className = 'pending',
                            cellValue = 'Ожидающая';
                        if (data === 'pending') {
                            className = 'warning';
                            cellValue = 'Ожидающая';
                        }
                        if (data === 'rejected') {
                            className = 'danger';
                            cellValue = 'Отклонена';
                        }
                        if (data === 'solved') {
                            className = 'success';
                            cellValue = 'Решена';
                        }
                        return '<span class="label label-' + className + '">' + cellValue + '</span>';
                    }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-nowrap'},
            ],
            language: {
                "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                "emptyTable": "Нет жалоб",
                "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                "infoEmpty": "К сожалению, Ваш запрос не дал результатов. Измените параметры поиска и попробуйте еще раз",
                "sZeroRecords": "К сожалению, Ваш запрос не дал результатов. Измените параметры фильтра и попробуйте еще раз",
                "paginate": {
                    "first": "{{ __('adminlte::admin.dt_first') }}",
                    "last": "{{ __('adminlte::admin.dt_last') }}",
                    "next": "{{ __('adminlte::admin.dt_next') }}",
                    "previous": "{{ __('adminlte::admin.dt_prev') }}",
                },
            },
            initComplete: function () {
                // STATUS
                const statuses = this.api().ajax.json().statuses;
                this.api().columns([9]).every(function () {
                    const column = this;

                    const select = $('<select id="complain_status_filter" class="datatable_filter"><option value="">{{ __('adminlte::admin.status') }}</option></select>')
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
                                case 'pending':
                                    cellValue = 'Ожидающая';
                                    break;

                                case 'rejected':
                                    cellValue = 'Отклонена';
                                    break;

                                case 'solved':
                                    cellValue = 'Решена';
                                    break;
                            }

                            if (status && cellValue) {
                                select.append('<option value="' + status + '">' + cellValue + '</option>');
                            }
                        }
                    }
                });

            }
        });
        const complainRoute = '{{route('admin.complains.update', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');
        const advertRoute = '{{route('admin.adverts.show', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');
        const userRoute = '{{route('admin.users.show', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');

        $('#data-table_filter').append('<button data-url="{{ route('admin.complains.index') }}" id="reset_filter" type="button" class="btn btn-default">Сбросить</button>');
        $('#data-table_filter').on('click', '#reset_filter', function () {
            window.location.href = $(this).data('url');
        });

        function getAhref(url, name = url) {
            console.log('name', name);
            if(name !== null){
                return '<a href="' + url + '" target="_blank">' + name + '</a>';
            } else {
                return 'Не указан';
            }
        }

        function renderDate(data) {
            return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
        }
    </script>
@stop
