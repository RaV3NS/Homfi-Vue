@extends('adminlte::page')

@section('title', __('adminlte::admin.advert_profile_title'))

@section('content_header')
    <h1>{{__('adminlte::admin.advert_profile_title')}}</h1>
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
                        <li class="nav-item"><a class="nav-link" href="#gallery"
                                                data-toggle="tab">{{ __('adminlte::admin.gallery') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#complains"
                                                data-toggle="tab">{{ __('adminlte::admin.complains') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#timeline"
                                                data-toggle="tab">{{ __('adminlte::admin.history') }}</a></li>

                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane lined" id="general">
                            <div class="container container-bordered col-lg-5 col-md-12 float-md-left">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">ID</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{$advert->id}}
                                    </div>
                                </div>
                                @if($advert->user)
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.user_profile') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <a href="{{ route('admin.users.show', $advert->user->id) }}"
                                               target="_blank">ID {{$advert->user->id}} ({{ $advert->user->fullname }}
                                                )</a>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">{{ __('adminlte::admin.last_name') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{$advert->last_name}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.first_name') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">{{$advert->first_name}}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Email</label>
                                    <div class="col-md-8 d-flex justify-content-start">{{$advert->email}}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">{{ __('adminlte::admin.phones') }}</label>
                                    <div class="col-md-auto d-flex flex-column justify-content-start">
                                        @foreach($advert->phones as $phone)
                                            <div class="phone-row">
                                                <span class="card-title">{{$phone->number}}&nbsp;</span>

                                                <span class="card-tools">
                                                @if($phone->is_main)
                                                        <b>Основной</b>
                                                    @endif

                                                <span class="messengers-on-phone">
                                                    @if(count($phone->messengers))
                                                    (
                                                    @endif

                                                    @foreach($phone->messengers as $messenger)
                                                        @if(in_array($messenger, config('settings.messengers')))
                                                            <span
                                                                class="text-capitalize">{{ $messenger }}</span>@if(!$loop->last)
                                                                , @endif
                                                        @endif
                                                    @endforeach

                                                    @if(count($phone->messengers))
                                                        )
                                                    @endif</span>
                                            </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">{{ __('adminlte::admin.created') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">{{$advert->created_at}}</div>
                                </div>

                                <h3>{{ __('adminlte::admin.social_nets_title') }}:</h3>

                                @foreach(config('settings.user_social_links') as $social_link)
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-capitalize">
                                            @if($social_link == 'facebook_messenger')Facebook
                                            messenger @else{{ $social_link }}@endif</label>
                                        @if(isset($advert->social_links[$social_link]))
                                            <div
                                                class="col-md-4 d-flex justify-content-start">
                                                @if(filter_var($advert->social_links[$social_link], FILTER_VALIDATE_URL))
                                                    <a href="{{ $advert->social_links[$social_link] }}">{{ $advert->social_links[$social_link] }}</a>
                                                @elseif($social_link == 'facebook_messenger')
                                                    @if($advert->social_links[$social_link])
                                                        <div class="icheck-primary">
                                                            <img
                                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAABwElEQVRIS72W7VnCMBDHLw0DMAJuELjyXSYQJlA2gAl8nABGYANhAvlOU7OBbGAHaDmfqylPjC0ttpKPbXK/u3/uJQIAQCnV7/V6z0T0BAB9/tbBSoQQmzRNX4wxiWCIlPKNeR0YLzNhsiybiDAMV0S04B1CiDUA7KIo2reBhmF4DwAPrl2BiJ8sF0OiKFq2AfhnnSASBpGNZtImkvF4rIhomqbpmu+EbfK30+n0ntsvQFpr8ddo7D1/2ERiyFxrvWV750C6ACHiKwBMXUcLxzsDISIDGOSuvdZ60llEnmQFKMmybGiMOXYGKpOMiJZxHHOZ5Ku1dHWSdQJqIlkjkO19C7cm3JuukGwex/HGL5FK6bzel/epogDZyGg0WgghVp7BrdZ6VlaHlSBE5Ep2G+wZppQaSCn5v9vhOcvuXGe86L87j1+wiMgVPvC8y2FSSq4XbpjumhVd4KqIbH/iseHPJW4t/rdKyRolwwWY6/RFyRqBeFMD2EXJGoNqYLWSXQWqgB1tL8tnTt36ld5BEAwPh4MpO2hl5NoZBEEwq9rnn+WRTkScWHl632aU/8fjxI71xx+Pk5s9t1i/WzwgvwAownKAMg9TjwAAAABJRU5ErkJggg=="
                                                                width="16px"/>
                                                        </div>
                                                    @else
                                                        <div class="icheck-primary">
                                                            <img
                                                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAABDklEQVQ4T6XTMWqEQBTG8XkjU4iChWAhCBaihcgueIEcYY+QI+QoOYJH2BvsHiFpxUIQLAQLCytxZvFLZkOK4EgGpvz/RuQ9Yv88tPVlWXpSyitj7OWAd+ecXwAURXEjoiMx3lFK3QHkea6+X64459XeV0gpXxlj22UAsiwDoJQ613X9uQekaXoioo8nkCQJgKZpAJoc3SCI4xhA27bGgG4QRFEEoOs6Y0A3CMIwBND3vTGgGwRBEAAYhsEY0A0C3/cBjONoDOgGged5AKZpMgZ0g8B13ecczPO8OweO4/yeA9u2NVCt6/q+LMufiBDiZFnWGxH9TKIQ4nZwkfSsfe3C9huI6HpkobZFUkpdHkopYB8evpGIAAAAAElFTkSuQmCC"/>
                                                        </div>
                                                    @endif
                                                @else
                                                    <span>{{ $advert->social_links[$social_link] }}</span>
                                                @endif
                                            </div>

                                        @else

                                        @endif
                                    </div>
                                @endforeach

                                <div class="container border-1 col-lg-12 col-md-12 float-md-left pt-2">
                                    <h3>Описание :</h3>
                                    <div class="col-md-12 d-flex justify-content-start">{!! $advert->body !!}</div>
                                </div>
                            </div>

                            <!-- right column block -->
                            <div class="contianer container-bordered col-sm-5 float-md-right">
                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.geo.district') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{$advert->city->region->district->name_ru}}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">Административный район</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        @if($advert->administrative)
                                            {{$advert->administrative->name_ru}}
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.geo.city') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{ $advert->city->name_ru }}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.address') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{ $advert->street->name_ru }}@if(!empty($advert->address)), {{ $advert->address }} @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.subway') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        @if($advert->subway)
                                            {{$advert->subway->name_ru}}
                                        @endif
                                    </div>
                                </div>

                                {{--<div class="form-group row">--}}
                                {{--<label--}}
                                {{--class="col-sm-4 col-form-label">{{ __('adminlte::admin.type') }}</label>--}}
                                {{--<div class="col-md-8 d-flex justify-content-start">--}}
                                {{--{{ __('adminlte::admin.advert_type.'.$advert->type) }}--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <div class="form-group row">
                                    <label
                                        class="col-sm-4 col-form-label">{{ __('adminlte::admin.price_for_month') }}</label>
                                    <div class="col-md-8 d-flex justify-content-start">
                                        {{$advert->price_month}} {{ config('settings.currency.sign') }}
                                    </div>
                                </div>
                                @if($advert->publish_date)
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">Дата публикации</label>
                                        <div
                                            class="col-md-8 d-flex justify-content-start">{{ $advert->publish_date->format('d.m.Y H:i') }}</div>
                                    </div>
                                @endif
                            </div>

                            <!-- right column block parameters -->
                            <div class="contianer container-bordered col-sm-5 float-md-right">
                                <h3>Параметры</h3>

                                @foreach($advert->parameters()->get() as $parameter)
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ trans('parameters.'.$parameter->key) }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            {{ $parameter->value['value_ru']}} {{ $parameter->unit['ru'] }}
                                        </div>
                                    </div>
                                @endforeach


                            <!-- right column block options -->
                                @if($advert->options()->count())
                                    <div class="">
                                        <h3>Удобства</h3>

                                        @foreach($advert->options()->get() as $option)
                                            <div class="form-group">
                                                <div class="col-md-8 d-flex justify-content-start">
                                                    {{ $option->name_ru }}
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- tab-pane -->
                        <div class="tab-pane" id="gallery">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('adminlte::admin.advert_gallery_title') }}</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    @if($advert->getMedia('images'))
                                        <div class="row mb-3">
                                            @foreach($advert->getMedia('images') as $image)
                                                <div class="m-2">
                                                    {{ $image('card') }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="complains">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('adminlte::admin.advert_complains_title') }}</h3>

                                    <div class="card-tools">

                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="box-body table-responsive">
                                    <table id="data-table"
                                           class="table table-bordered table-striped table-hover complains-list">
                                        <thead>
                                        <tr>
                                            <th width="3%">ID</th>
                                            <th>Дата</th>
                                            <th>ID Объявления</th>
                                            <th>ID Пользователя</th>
                                            <th>E-mail</th>
                                            <th>Причины жалобы</th>
                                            <th>Комментарий</th>
                                            <th>Статус</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- /.tab-pane -->

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <div class="timeline timeline-inverse">
                                @foreach($advert->history as $event)

                                    <div class="time-label">
                                        <span class="bg-success">
                                          {{ $event->created_at->format('Y.m.d H:i') }}
                                        </span>
                                    </div>
                                    <div>
                                        @if($event->type === 'advert_' . \App\Advert::STATUS_ENABLED)
                                            <i class="fas fa-check bg-success"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> {{$event->created_at->diffForHumans()}}</span>

                                                <h3 class="timeline-header border-0"><a
                                                        href="#">{{ $event->author->name }}</a>
                                                    {{ __("adminlte::admin.advert.event.$event->type") }}

                                                </h3>
                                                {{--<div class="timeline-body">--}}
                                                    {{--<div>--}}
                                                        {{--{{ __("adminlte::admin.advert.$event->type"."_reason") }}:--}}

                                                        {{--@if(trans_fb("reasons." . $event->title))--}}
                                                            {{--{{ __("reasons." . $event->title) }}--}}
                                                        {{--@else--}}
                                                            {{--{{ $event->title }}--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                    {{--{{ $event->body }}--}}
                                                {{--</div>--}}
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

                                                        @if(trans_fb("reasons." . $event->title))
                                                            {{ __("reasons." . $event->title) }}
                                                        @else
                                                            {{ $event->title }}
                                                        @endif
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
                                                        @if(trans_fb("reasons." . $event->title))
                                                            {{ __("reasons." . $event->title) }}
                                                        @else
                                                            {{ $event->title }}
                                                        @endif
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
                                                        @if(trans_fb("reasons." . $event->title))
                                                            {{ __("reasons." . $event->title) }}
                                                        @else
                                                            {{ $event->title }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            @endforeach

                            <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success">
                                      {{ $advert->created_at->format('Y.m.d H:i') }}
                                    </span>
                                </div>
                                <!-- /.timeline-label -->

                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{$advert->created_at->diffForHumans()}}</span>

                                        <h3 class="timeline-header border-0">{{ __('adminlte::admin.history_title_created_advert') }}</h3>
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

                    @if($advert->status === $advert::STATUS_ENABLED)
                        <div class="alert alert-success text-center">
                            <h6><i class="icon fas fa-check"></i>{{ __('adminlte::admin.advert_active_status') }}</h6>
                        </div>

                    @elseif($advert->status === $advert::STATUS_BLOCKED)
                        <div class="alert alert-danger text-center">
                            <h6><i class="icon fas fa-ban"></i>{{ __('adminlte::admin.advert_blocked_status') }}</h6>
                        </div>

                    @elseif($advert->status === $advert::STATUS_DISABLED)
                        <div class="mb-4">
                            <div class="alert alert-warning text-center">
                                <h6>
                                    <i class="icon fas fa-exclamation-triangle"></i>{{ __('adminlte::admin.advert_notactive_status') }}
                                </h6>
                            </div>

                        </div>
                    @elseif($advert->status === $advert::STATUS_REJECTED)
                        <div class="mb-4">
                            <div class="alert alert-warning text-center">
                                <h6>
                                    <i class="icon fas fa-exclamation-triangle"></i>{{ __('adminlte::admin.advert_rejected_status') }}
                                </h6>
                            </div>

                        </div>
                    @elseif($advert->status === $advert::STATUS_MODERATE)
                        <div class="mb-4">
                            <div class="alert alert-warning text-center">
                                <h6><i class="icon fas fa-edit"></i>{{ __('adminlte::admin.advert_status.moderate') }}
                                </h6>
                            </div>

                        </div>
                    @endif

                    @if($advert->status !== $advert::STATUS_DRAFT)
                        @if($advert->status != $advert::STATUS_BLOCKED)
                            <div class="mb-4">
                                <form action="{{ route('admin.adverts.update.status', $advert->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="{{ $advert::STATUS_BLOCKED }}">
                                    <button type="submit"
                                            class="btn btn-block btn-danger js-block-advert">{{ __('adminlte::admin.button.block') }}</button>
                                </form>
                            </div>
                        @elseif($advert->status === $advert::STATUS_BLOCKED)
                            <div class="mb-4">
                                <form action="{{ route('admin.adverts.update.status', $advert->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="{{ $advert::STATUS_ENABLED }}">
                                    <input type="hidden" name="user_log[title]"
                                           value="Объявление успешно прошло модерацию">
                                    <button type="submit"
                                            class="btn btn-block btn-success js-confirm-advert">{{ __('adminlte::admin.button.enable') }}</button>
                                </form>
                            </div>
                        @endif

                        <div class="mb-4">
                            <a href="{{ route('admin.adverts.edit', $advert->id) }}">

                                <button type="button"
                                        class="btn btn-block btn-primary js-edit-advert"
                                        data-uid="{{ $advert->id }}">
                                    {{ __('adminlte::admin.button.edit') }}
                                </button>
                            </a>
                        </div>

                        @if($advert->status === $advert::STATUS_MODERATE)
                            <div class="mb-4">
                                <form action="{{ route('admin.adverts.update.status', $advert->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="{{ $advert::STATUS_ACCEPTED }}">
                                    <input type="hidden" name="user_log[title]" value="success_moderate">
                                    @if(!$advert->publish_date)
                                        <input type="hidden" name="publish_date" value="1"/>
                                    @endif
                                    <input type="hidden" name="user_log[title]" value="success_moderate">
                                    <button type="submit"
                                            class="btn btn-block btn-success js-confirm-advert">{{ __('adminlte::admin.button.confirm') }}</button>
                                </form>
                            </div>

                            <div class="mb-4">
                                <form action="{{ route('admin.adverts.update.status', $advert->id) }}" method="post">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="status" value="{{ $advert::STATUS_REJECTED }}">
                                    <button type="submit"
                                            class="btn btn-block btn-danger js-reject-advert">{{ __('adminlte::admin.button.reject') }}</button>
                                </form>
                            </div>

                        @endif
                    @elseif($advert->status === $advert::STATUS_DRAFT)
                        <div class="mb-4">
                            <div class="alert alert-warning text-center">
                                <h6><i class="icon fas fa-edit"></i>{{ __('adminlte::admin.advert_status.draft') }}
                                </h6>
                            </div>
                        </div>

                        @if($advert->front_editing)
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa exclamation-triangle"></i> Внимание!</h4>
                                В данный момент владелец объявления вносит изменения в это объявление.
                            </div>
                        @endif

                        <div class="mb-4">
                            <a href="{{ route('admin.adverts.edit', $advert->id) }}">

                                <button type="button"
                                        class="btn btn-block btn-primary js-edit-advert"
                                        data-uid="{{ $advert->id }}">
                                    {{ __('adminlte::admin.button.edit') }}
                                </button>
                            </a>
                        </div>
                    @endif
                    <hr>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->

    @include('admin.partials.modals.block-advert')

    @include('admin.partials.modals.unblock-advert')

    @include('admin.partials.modals.reject-advert')
@stop

@section('css')
@stop

@section('js')
    @error('advert_log.title')
    <script>
        $(document).Toasts('create', {
            class: 'bg-danger',
            title: '{{ __("adminlte::admin.advert.validation.title") }}',
            subtitle: '',
            body: '{{ __("adminlte::admin.advert.$message") }}'
        })
    </script>
    @enderror
    @error('advert_log.body')
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

        $('.js-profile-active-advert').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', advertRoute + except);
            $('#unblock-modal').modal();

            $('#unblockModalLabel #adverts-count').text(advertsCount);

            $('.modal-content form').attr('action', advertRoute + except);
        });

        $('.js-reject-advert').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', advertRoute + except);
            $('#reject-modal').modal();

            $('.modal-content form').attr('action', advertRoute + except);
        });

        const advertRoute = '{{route('admin.adverts.update.status', $advert->id)}}';
        let except = null;

        function getAdvertsExcept() {
            return '/adverts/search?except=' + except;
        }
    </script>

    <script>
        const dataTable = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('admin.complains.list') . '?advert_id='.$advert->id !!}',
            searching: false,
            order: [[0, 'asc']],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-complains', data.complains);
            },
            columns: [
                {
                    data: 'id', name: 'complains.id', searchable: false, render: function (data) {
                        return '<a href="/admin/complains/' + data + '">' + data + '</a>';
                    }
                },
                {data: 'created_at', name: 'complains.created_at', searchable: false, render: renderDate},
                {data: 'advert_id', name: 'advert_id', searchable: true},
                {data: 'user_id', name: 'user_id', searchable: false},
                {data: 'email', name: 'complains.email', searchable: true},
                {data: 'reason', name: 'complains.reason', searchable: false},
                {data: 'body', name: 'complains.body', searchable: false},
                {
                    data: 'status', name: 'status', searchable: false,
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
            ],
            language: {
                "lengthMenu": "{{ __('adminlte::admin.dt_menu_title') }}",
                "emptyTable": "Нет жалоб",
                "info": "{{ __('adminlte::admin.showing_info_dt') }}",
                "infoEmpty": "Нет жалоб",//"К сожалению, Ваш запрос не дал результатов. Измените параметры поиска и попробуйте еще раз",
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
                    const complainsCount = $(this).closest('tr').data('complains');

                    $('.modal-content form').attr('action', complainRoute + except);
                    $('#block-modal').modal();

                    $('#modalLabel #complains-count').text(complainsCount);

                    $('.modal-content form').attr('action', complainRoute + except);

                });

                $('#data-table td a.unblock').click(function (e) {
                    e.preventDefault();
                    except = $(this).closest('tr').find('td:first-child').text();
                    const complainsCount = $(this).closest('tr').data('complains');

                    $('.modal-content form').attr('action', complainRoute + except);
                    $('#unblock-modal').modal();

                    $('#unblockModalLabel #complains-count').text(complainsCount);

                    $('.modal-content form').attr('action', complainRoute + except);
                });

                $('#data-table td a.activate').click(function (e) {
                    e.preventDefault();
                    except = $(this).closest('tr').find('td:first-child').text();

                    $('.modal-content form').attr('action', complainRoute + except);
                    $('#activate-modal').modal();

                    $('.modal-content form').attr('action', complainRoute + except);
                });
            },
        });
        var complainRoute = '{{route('admin.complains.update', ['id'])}}'.replace(/^(.*\/)[^\/]+$/ig, '$1');

        function renderDate(data) {
            return data === null ? null : moment(data).format('DD.MM.YYYY HH:mm');
        }

        $('body').on('click', '#complains .complains-list tr', function () {

            let elemLink = $(this).find('td:first a');
            if (elemLink.attr('href') !== undefined) {
                window.location.href = elemLink.attr('href');
            }
        });

    </script>
@stop
