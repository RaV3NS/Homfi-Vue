@extends('adminlte::page')

@section('title', __('adminlte::admin.user_profile'))

@section('content_header')
    <h1>{{__('adminlte::admin.user_profile_title')}}</h1>
@stop

@section('content')
    <div class="row">

        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#general"
                                                data-toggle="tab">{{ __('adminlte::admin.general') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#adverts"
                                                data-toggle="tab">{{ __('adminlte::admin.adverts') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#timeline"
                                                data-toggle="tab">{{ __('adminlte::admin.history') }}</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">

                        <div class="active tab-pane lined" id="general">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ID</label>
                                <div class="col-md-4 d-flex justify-content-start">
                                    {{$user->id}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ __('adminlte::admin.last_name') }}</label>
                                <div class="col-md-4 d-flex justify-content-start">
                                    {{$user->last_name}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ __('adminlte::admin.first_name') }}</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$user->first_name}}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$user->email}}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ __('adminlte::admin.phones') }}</label>
                                <div class="col-md-auto d-flex flex-column">
                                    @foreach($user->phones as $phone)
                                        <div class="phone-row">
                                            <span class="card-title">{{$phone->number}}&nbsp; </span>

                                            <span class="card-tools">
                                                @if($phone->is_main)
                                                     <b>Основной</b>
                                                @endif

                                                <span class="messengers-on-phone">(@foreach($phone->messengers as $messenger)
                                                        @if(in_array($messenger, config('settings.messengers')))
                                                            <span
                                                                class="text-capitalize">{{ $messenger }}</span>@if(!$loop->last), @endif
                                                        @endif
                                                    @endforeach )</span>
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ __('adminlte::admin.register_date') }}</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$user->created_at}}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">{{ __('adminlte::admin.last_login') }}</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$user->last_login}}</div>
                            </div>

                            <h3>{{ __('adminlte::admin.social_nets_title') }}:</h3>

                            @foreach(config('settings.user_social_links') as $social_link)
{{--                                {{ dd($user->social_links) }}--}}
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label text-capitalize">
                                        @if($social_link == 'facebook_messenger')Facebook messenger @else{{ $social_link }}@endif</label>
                                    @if(isset($user->social_links[$social_link]))
                                        <div
                                            class="col-md-4 d-flex justify-content-start">
                                            @if(filter_var($user->social_links[$social_link], FILTER_VALIDATE_URL))
                                                <a href="{{ $user->social_links[$social_link] }}">{{ $user->social_links[$social_link] }}</a>
                                            @elseif($social_link == 'facebook_messenger')
                                                @if($user->social_links[$social_link])
                                                    <div class="icheck-primary">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAABwElEQVRIS72W7VnCMBDHLw0DMAJuELjyXSYQJlA2gAl8nABGYANhAvlOU7OBbGAHaDmfqylPjC0ttpKPbXK/u3/uJQIAQCnV7/V6z0T0BAB9/tbBSoQQmzRNX4wxiWCIlPKNeR0YLzNhsiybiDAMV0S04B1CiDUA7KIo2reBhmF4DwAPrl2BiJ8sF0OiKFq2AfhnnSASBpGNZtImkvF4rIhomqbpmu+EbfK30+n0ntsvQFpr8ddo7D1/2ERiyFxrvWV750C6ACHiKwBMXUcLxzsDISIDGOSuvdZ60llEnmQFKMmybGiMOXYGKpOMiJZxHHOZ5Ku1dHWSdQJqIlkjkO19C7cm3JuukGwex/HGL5FK6bzel/epogDZyGg0WgghVp7BrdZ6VlaHlSBE5Ep2G+wZppQaSCn5v9vhOcvuXGe86L87j1+wiMgVPvC8y2FSSq4XbpjumhVd4KqIbH/iseHPJW4t/rdKyRolwwWY6/RFyRqBeFMD2EXJGoNqYLWSXQWqgB1tL8tnTt36ld5BEAwPh4MpO2hl5NoZBEEwq9rnn+WRTkScWHl632aU/8fjxI71xx+Pk5s9t1i/WzwgvwAownKAMg9TjwAAAABJRU5ErkJggg==" width="16px"/>
                                                    </div>
                                                @else
                                                    <div class="icheck-primary">
                                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDklEQVQ4T6XTMWqEQBTG8XkjU4iChWAhCBaihcgueIEcYY+QI+QoOYJH2BvsHiFpxUIQLAQLCytxZvFLZkOK4EgGpvz/RuQ9Yv88tPVlWXpSyitj7OWAd+ecXwAURXEjoiMx3lFK3QHkea6+X64459XeV0gpXxlj22UAsiwDoJQ613X9uQekaXoioo8nkCQJgKZpAJoc3SCI4xhA27bGgG4QRFEEoOs6Y0A3CMIwBND3vTGgGwRBEAAYhsEY0A0C3/cBjONoDOgGged5AKZpMgZ0g8B13ecczPO8OweO4/yeA9u2NVCt6/q+LMufiBDiZFnWGxH9TKIQ4nZwkfSsfe3C9huI6HpkobZFUkpdHkopYB8evpGIAAAAAElFTkSuQmCC"/>
                                                    </div>
                                                @endif
                                            @else
                                                <span>{{ $user->social_links[$social_link] }}</span>
                                            @endif
                                        </div>
                                    @else

                                    @endif
                                </div>
                            @endforeach

                            <div class="card card-default">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-bullhorn"></i>
                                        Заметки о пользователе
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    @foreach($user->notes as $note)
                                    <div class="callout callout-info">
                                        <h5>{{ $note->author->name }}</h5>

                                        <p>{{ $note->body }}</p>
                                    </div>
                                    @endforeach

                                    <form action="{{ route('admin.users.note', $user->id) }}" method="POST">
                                        @csrf
                                        <div class="input-group-append">
                                            <input id="new_comment_input" class="form-control form-control-sm" type="text" name="body" placeholder="Введите текст" required minlength="10"/>
                                            <button type="submit" class="btn btn-danger">Отправить</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="adverts">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('adminlte::admin.user_adverts_title') }} ({{$user->adverts()->count()}} шт.)</h3>

                                    <div class="card-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">

                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table id="data-table" class="table table-bordered table-striped table-hover adverts-list">
                                        <thead>
                                        <tr>
                                            <th class="text-center" width="3%">ID объявления</th>
                                            <th>Дата создания</th>
                                            <th>Email</th>
                                            <th>Тип недвижимости</th>
                                            <th>Город</th>
                                            <th>Дата редактирования</th>
                                            <th>Статус</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>

                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <div class="timeline timeline-inverse">
                                @foreach($user->history as $event)

                                    <div class="time-label">
                                        <span class="bg-success">
                                          {{ $event->created_at->format('Y.m.d H:i') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($event->type === $user::STATUS_ACTIVE)
                                            <i class="fas fa-user bg-info"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header border-0">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.user.event.$event->type") }}
                                                    <div>
                                                        {{ __("adminlte::admin.user.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === $user::STATUS_BLOCKED)
                                            <i class="fas fa-ban bg-danger"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.user.event.$event->type") }}
                                                    <div>
                                                        {{ __("adminlte::admin.user.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>
                                                </h3>

                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === $user::STATUS_DISABLED)
                                            <i class="fas fa-warning bg-warning"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.user.event.$event->type") }}
                                                    <div>
                                                        {{ __("adminlte::admin.user.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>
                                                </h3>

                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === $user::STATUS_DELETED)
                                            <i class="fas fa-warning bg-warning"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.user.event.$event->type") }}
                                                    <div>
                                                        {{ __("adminlte::admin.user.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>
                                                </h3>

                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === 'advert_' . \App\Advert::STATUS_ENABLED)
                                            <i class="fas fa-user bg-info"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header border-0"><a
                                                        href="#">{{ $event->author->name }}</a>
                                                    {{ __("adminlte::admin.advert.event.$event->type") }}
                                                    <div>
                                                        {{ __("adminlte::admin.advert.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === 'advert_' . \App\Advert::STATUS_BLOCKED)
                                            <i class="fas fa-ban bg-danger"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.advert.event.$event->type") }}

                                                </h3>

                                                <div class="timeline-body">
                                                    <div>
                                                        {{ __("adminlte::admin.advert.$event->type"."_reason") }}:
                                                        {{$event->title}}
                                                    </div>

                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === 'advert_' . \App\Advert::STATUS_REJECTED)
                                            <i class="fas fa-times bg-warning" aria-hidden="true"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.advert.event.$event->type") }}

                                                </h3>

                                                <div class="timeline-body">
                                                    <div>
                                                        {{ __("adminlte::admin.advert.$event->type"."_reason") }}:
                                                        {{ __("reasons." . $event->title) }}
                                                    </div>
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->type === 'advert_' . \App\Advert::STATUS_ACCEPTED)
                                            <i class="fas fa-check bg-success"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    {{ __("adminlte::admin.advert.event.$event->type") }}

                                                </h3>

                                                <div class="timeline-body">
                                                    <div>
                                                        {{ __("reasons." . $event->title) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            @endforeach

                            <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success">
                                      {{ $user->created_at->format('Y.m.d H:i') }}
                                    </span>
                                </div>
                                <!-- /.timeline-label -->

                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{$user->created_at->diffForHumans()}}</span>

                                        <h3 class="timeline-header border-0">{{ __('adminlte::admin.history_title_registered_user') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

        <div class="col-md-3">
            <!-- About Me Box -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('adminlte::admin.actions') }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    @if($user->status === App\User::STATUS_ACTIVE)
                        <div class="alert alert-success text-center">
                            <h6><i class="icon fas fa-check"></i>{{ __('adminlte::admin.user_active_status') }}</h6>
                        </div>

                        <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\User::STATUS_BLOCKED }}">
                            <button type="submit"
                                    class="btn btn-block btn-danger js-profile-block-user" data-uid="{{ $user->id }}">
                                {{ __('adminlte::admin.button.block') }}
                            </button>
                        </form>

                        <small class="text-muted">{{ __('adminlte::admin.block_user_description') }}</small>
                    @elseif($user->status === App\User::STATUS_DISABLED)
                        <div class="alert alert-warning text-center">
                            <h6>
                                <i class="icon fas fa-exclamation-triangle"></i>{{ __('adminlte::admin.user_notactive_status') }}
                            </h6>
                        </div>

                        <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\User::STATUS_ACTIVE }}">
                            <button type="submit" class="btn btn-block btn-success js-profile-active-user">
                                {{ __('adminlte::admin.button.activate') }}
                            </button>
                        </form>

                        <p class="text-muted">{{ __('adminlte::admin.activate_user_description') }}</p>
                    @elseif($user->status === App\User::STATUS_BLOCKED)
                        <div class="alert alert-danger text-center">
                            <h6><i class="icon fas fa-ban"></i>{{ __('adminlte::admin.user_block_status') }}</h6>
                        </div>
                        <form action="{{ route('admin.users.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\User::STATUS_ACTIVE }}">
                            <button type="submit"
                                    class="btn btn-block btn-success js-profile-active-user">{{ __('adminlte::admin.button.unblock') }}</button>
                        </form>
                        <p class="text-muted">
                            {{ __('adminlte::admin.unblock_user_description') }}
                        </p>
                    @elseif($user->status === App\User::STATUS_DELETED)
                        <div class="alert alert-warning text-center">
                            <h6>
                                <i class="icon fas fa-exclamation-triangle"></i>{{ __('adminlte::admin.user_deleted_status') }}
                            </h6>
                        </div>

                        <p class="text-muted">
                            {{ __('adminlte::admin.deleted_user_description') }}
                        </p>
                    @endif
                    <hr>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

    @include('admin.partials.modals.block-user')

    @include('admin.partials.modals.unblock-user')

    @include('admin.partials.modals.activate-user')

@stop

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
@stop

@section('js')
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
        $('.js-profile-block-user').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', userRoute + except);
            $('#block-modal').modal();

            $('#modalLabel #adverts-count').text(advertsCount);

            $('.modal-content form').attr('action', userRoute + except);

        });

        $('.js-profile-active-user').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', userRoute + except);
            $('#unblock-modal').modal();

            $('#unblockModalLabel #adverts-count').text(advertsCount);

            $('.modal-content form').attr('action', userRoute + except);
        });

        const userRoute = '{{route('admin.users.update', $user->id)}}';
        let except = null;

        function getUsersExcept() {
            return '/users/search?except=' + except;
        }
    </script>

    <script>
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.adverts.list') . '?user_id=' . $user->id !!}',
            searching: false,
            order: [[0, 'asc']],
            columns: [
                {data: 'id', name: 'adverts.id',
                    render: function(data){
                        return '<a class="advert-link text-bold" href="/admin/adverts/'+data+'">'+data+'</a>';
                    }
                },
                {data: 'created_at', name: 'adverts.created_at', render: renderDate},
                {data: 'email', name: 'adverts.email', sorting: false},
                {
                    data: 'type', name: 'adverts.type', sorting: false,
                    render: function(data) {
                        let type = '';
                        switch(data){
                            case 'flat': type = "{{ __('adminlte::admin.advert_type.flat') }}"; break;
                            case 'house': type = "{{ __('adminlte::admin.advert_type.house') }}"; break;
                            case 'half-house': type = "{{ __('adminlte::admin.advert_type.half-house') }}"; break;
                            case 'room': type = "{{ __('adminlte::admin.advert_type.room') }}"; break;
                        }
                        return type;
                    }
                },
                {data: 'city.name_ru', name: 'city.name_ru', sorting: false},
                {data: 'updated_at', name: 'adverts.updated_at', render: renderDate},

                {
                    data: 'status', name: 'adverts.status', sorting: false,
                    render: function (data) {
                        let status = '';
                        switch(data){
                            case 'disabled': status = "{{ __('adminlte::admin.advert_status.disabled') }}"; break;
                            case 'enabled': status = "{{ __('adminlte::admin.advert_status.enabled') }}"; break;
                            case 'moderate': status = "{{ __('adminlte::admin.advert_status.moderate') }}"; break;
                            case 'accepted': status = "{{ __('adminlte::admin.advert_status.accepted') }}"; break;
                            case 'rejected': status = "{{ __('adminlte::admin.advert_status.rejected') }}"; break;
                            case 'deleted': status = "{{ __('adminlte::admin.advert_status.deleted') }}"; break;
                            case 'blocked': status = "{{ __('adminlte::admin.advert_status.blocked') }}"; break;
                        }
                        return status;
                    }
                }
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
                this.api().columns([6]).every( function () {
                    var column = this;

                    var select = $('<select id="advert_status_filter"><option value="">{{ __('adminlte::admin.status') }}</option></select>')
                        .prependTo( $('#data-table_filter') )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column.search( val ).draw();
                        } );

                    column.data().unique().sort().each( function ( data, j ) {
                        var cellValue = '{{ trans('adminlte::admin.enabled') }}';

                        if (data === 'draft') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.draft') }}';
                        }
                        if (data === 'disabled') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.disabled') }}';
                        }
                        if (data === 'enabled') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.enabled') }}';
                        }
                        if (data === 'moderate') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.moderate') }}';
                        }
                        if (data === 'rejected') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.rejected') }}';
                        }
                        if (data === 'accepted') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.accepted') }}';
                        }
                        if (data === 'blocked') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.blocked') }}';
                        }
                        if (data === 'deleted') {
                            cellValue = '{{ trans('adminlte::admin.advert_status.deleted') }}';
                        }

                        select.append('<option value="'+ data +'">'+cellValue+'</option>');
                    } );
                } );

                // TYPE
                this.api().columns([4]).every( function () {
                    var column = this;

                    var select = $('<select id="advert_type_filter"><option value="">{{ __('adminlte::admin.type') }}</option></select>')
                        .prependTo( $('#data-table_filter') )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column.search( val ).draw();
                        } );

                    column.data().unique().sort().each( function ( data, j ) {
                        var cellValue = '{{ trans('adminlte::admin.advert_type.flat') }}';

                        if (data === 'house') {
                            cellValue = '{{ trans('adminlte::admin.advert_type.house') }}';
                        }
                        if (data === 'half-house') {
                            cellValue = '{{ trans('adminlte::admin.advert_type.half-house') }}';
                        }
                        if (data === 'room') {
                            cellValue = '{{ trans('adminlte::admin.advert_type.room') }}';
                        }
                        select.append('<option value="'+ data +'">'+cellValue+'</option>');
                    });
                });

                // CITY
                this.api().columns([3]).every( function () {
                    var column = this;

                    var select = $('<select id="advert_city_filter"><option value="">{{ __('adminlte::admin.city') }}</option></select>')
                        .prependTo( $('#data-table_filter') )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column.search( val ).draw();
                        });

                    column.data().unique().sort().each( function ( data, j ) {
                        select.append('<option value="'+ data +'">'+data+'</option>');
                    });
                });
            }
        });

        function renderDate(data) {
            return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
        }
    </script>
@stop
