@extends('adminlte::page')

@section('title', "Страница жалобы пользователя")

@section('content_header')
    <h1>Страница жалобы пользователя</h1>
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
                                    {{$complain->id}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ID объявления</label>
                                <div class="col-md-4 d-flex justify-content-start">
                                    <a href="{{ route('admin.adverts.show', $complain->advert_id) }}">{{$complain->advert->id}} ({{ $complain->advert->title['ru'] }})</a>
                                </div>
                            </div>
                            @if ($complain->user_id)
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">User ID</label>
                                <div class="col-md-4 d-flex justify-content-start">
                                    <a href="{{ route('admin.users.show', $complain->user_id) }}">{{$complain->user->id}} ({{ $complain->user->fullname }})</a>
                                </div>
                            </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Статус</label>
                                <div class="col-md-4 d-flex justify-content-start">{{ __('adminlte::admin.complain.status.'.$complain->status) }}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Дата создания</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$complain->created_at}}</div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-md-4 d-flex justify-content-start"><a href="mailto:{{$complain->email}}">{{$complain->email}}</a></div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Телефон</label>
                                <div class="col-md-4 d-flex justify-content-start">@if($complain->phone)<a href="tel:+{{$complain->phone}}">+{{$complain->phone}}</a>@endif</div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Причина жалобы</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$complain->reason}}</div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Комментарий пользователя</label>
                                <div class="col-md-4 d-flex justify-content-start">{{$complain->body}}</div>
                            </div>

                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <div class="timeline timeline-inverse">
                                @foreach($complain->history as $event)
                                    <div class="time-label">
                                        <span class="bg-success">
                                          {{ $event->created_at->format('Y.m.d H:i') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($event->status === $complain::STATUS_PENDING)
                                            <i class="fas fa-hourglass bg-warning"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header border-0">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    сменил статус на <span class="font-weight-bold">Ожидающая</span>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->status === $complain::STATUS_REJECTED)
                                            <i class="fas fa-ban bg-danger"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header">
                                                    @if($event->author)
                                                        <a href="#">{{ $event->author->name }}</a>
                                                    @endif

                                                    <div>
                                                        сменил статус на <span class="font-weight-bold"> Отклонена </span>
                                                    </div>
                                                </h3>

                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @elseif($event->status === $complain::STATUS_SOLVED)
                                            <i class="fas fa-check bg-success"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header border-0">
                                                    @if($event->author)
                                                        <a
                                                        href="#">{{ $event->author->name }}</a>
                                                    @endif
                                                    сменил статус на <span class="font-weight-bold"> Решена </span>
                                                </h3>
                                                <div class="timeline-body">
                                                    {{ $event->body }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            @endforeach

                            <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success">
                                      {{ $complain->created_at->format('Y.m.d H:i') }}
                                    </span>
                                </div>
                                <!-- /.timeline-label -->

                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{$complain->created_at->diffForHumans()}}</span>

                                        <h3 class="timeline-header border-0">Дата создания жалобы</h3>
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

                    @if($complain->status === App\Complain::STATUS_PENDING)
                        <div class="alert alert-warning text-center">
                            <h6><i class="icon fas fa-exclamation-triangle"></i>Ожидающая</h6>
                        </div>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post" class="mb-3">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_REJECTED }}">
                            <button type="submit"
                                    class="btn btn-block btn-danger js-rejected-complain" data-uid="{{ $complain->id }}">
                                Отклонить
                            </button>
                        </form>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_SOLVED }}">
                            <button type="submit" class="btn btn-block btn-success js-solved-complain">
                                Решена
                            </button>
                        </form>
                    @elseif($complain->status === App\Complain::STATUS_REJECTED)
                        <div class="alert alert-danger text-center mb-2">
                            <h6><i class="icon fas fa-ban"></i>Отклонена</h6>
                        </div>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post" class="mb-3">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_PENDING }}">
                            <button type="submit"
                                    class="btn btn-block btn-warning js-pending-complain" data-uid="{{ $complain->id }}">
                                Ожидающая
                            </button>
                        </form>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_SOLVED }}">
                            <button type="submit" class="btn btn-block btn-success js-solved-complain">
                                Решена
                            </button>
                        </form>

                    @elseif($complain->status === App\Complain::STATUS_SOLVED)
                        <div class="alert alert-success text-center">
                            <h5><i class="icon fas fa-check"></i>Решена</h5>
                        </div>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post" class="mb-3">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_PENDING }}">
                            <button type="submit"
                                    class="btn btn-block btn-warning js-pending-complain" data-uid="{{ $complain->id }}">
                                Ожидающая
                            </button>
                        </form>

                        <form action="{{ route('admin.complains.update.status', $complain->id) }}" method="post">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ App\Complain::STATUS_REJECTED }}">
                            <button type="submit" class="btn btn-block btn-danger js-rejected-complain">
                                Отклонить
                            </button>
                        </form>
                    @endif
                    <hr>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

    <div class="modal fade" id="solved-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel"> Статус жалобы</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" id="customRadio3" type="radio" name="status" value="{{ $complain::STATUS_SOLVED }}"/>
                                <label for="customRadio3" class="custom-control-label">Решенная</label>
                            </div>
                            <div class="custom-control custom-radio user--another-reason">
                                <input class="custom-control-input" id="customRadio4" type="radio" name="status" value="{{ $complain::STATUS_REJECTED }}" />
                                <label for="customRadio4" class="custom-control-label">Отклонена</label>
                            </div>


                            <div class="custom-control form-group">
                                <textarea class="form-control user-reason--another-field" name="body" placeholder="Комментарий"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('adminlte::admin.button.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">Применить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
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
        $('.js-rejected-complain').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();

            $('.modal-content form').attr('action', complainRoute + except);
            $('#solved-modal').modal();


            $('.modal-content form').attr('action', complainRoute + except);
            $('.modal-content form #customRadio4').attr('checked', 1);
        });

        $('.js-solved-complain').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();

            $('.modal-content form').attr('action', complainRoute + except);
            $('#solved-modal').modal();

            $('.modal-content form').attr('action', complainRoute + except);
            $('.modal-content form #customRadio3').attr('checked', 1);
        });

        const complainRoute = '{{route('admin.complains.update.status', $complain->id)}}';
        let except = null;

        function getComplainsExcept() {
            return '/complains/search?except=' + except;
        }
    </script>
@stop
