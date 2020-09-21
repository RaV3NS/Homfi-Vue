@extends('adminlte::page')

@section('title', __('adminlte::admin.edit_advert'))

@section('content_header')
    <h1>{{__('adminlte::admin.edit_advert_profile_title')}}</h1>
@stop

@section('content')
    <div class="row">

        <!-- /.col -->
        <div class="col-md-10">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#general"
                                                data-toggle="tab">{{ __('adminlte::admin.general') }}</a></li>
                        <li class="nav-item"><a class="nav-link" href="#gallery"
                                                data-toggle="tab">{{ __('adminlte::admin.gallery') }}</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="active tab-pane" id="general">
                            <form id="advert_form" action="{{ route('admin.adverts.update', $advert->id) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="contianer col-sm-6 float-md-left">
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
                                                   target="_blank">ID {{$advert->user->id}} ({{ $advert->user->fullname }})</a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.last_name') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <input type="text" value="{{$advert->last_name}}" name="last_name"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.first_name') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <input type="text" value="{{$advert->first_name}}" name="first_name"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <input type="text" value="{{$advert->email}}" name="email"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.phones') }}</label>
                                        <div class="col-md-auto d-flex flex-column justify-content-start">
                                            @foreach($advert->phones as $phone)
                                                <div class="card card-outline card-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <input type="tel" name="phone[{{ $phone->id }}][number]"
                                                                   value="{{$phone->number}}"/>
                                                        </h3>

                                                        {{--<div class="card-tools">--}}
                                                            {{--<input type="checkbox" value="1"--}}
                                                                   {{--name="phone[{{ $phone->id }}][is_main]"--}}
                                                                   {{--@if($phone->is_main) checked--}}
                                                                   {{--@endif title="{{ __('adminlte::admin.phone_is_main') }}"/>--}}
                                                        {{--</div>--}}
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="messengers-on-phone">
                                                            @foreach(config('settings.messengers') as $messenger)
                                                                <label
                                                                    class="text-capitalize">{{ $messenger }}</label>
                                                                <input type="checkbox" value="{{ $messenger }}"
                                                                       name="phone[{{ $phone->id }}][messengers][]"
                                                                       @if(in_array($messenger, $phone->messengers))
                                                                       checked
                                                                    @endif />
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.created') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">{{$advert->created_at}}</div>
                                    </div>


                                    <h3>{{ __('adminlte::admin.social_nets_title') }}:</h3>
                                    @foreach(config('settings.user_social_links') as $social_link)
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4 col-form-label text-capitalize">{{ $social_link }}</label>
                                            <div class="col-md-8 d-flex justify-content-start">
                                                <input type="text" value="@if(isset($advert->social_links[$social_link])){{ $advert->social_links[$social_link] }} @endif"
                                                       name="social_links[{{ $social_link }}]"/>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="container col-lg-12 col-md-12 float-md-left pt-2">
                                        <h3>Описание :</h3>
                                        <div class="">
                                            <div class="col-md-12 d-flex justify-content-start">
                                                <textarea name="body" class="text-editor" style="display: none">@if(isset($advert->body)){{ $advert->body }}@endif</textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- right column block -->
                                <div class="contianer col-sm-6 float-md-right">
                                    {{--<div class="form-group row">--}}
                                        {{--<label--}}
                                            {{--class="col-sm-4 col-form-label">{{ __('adminlte::admin.geo.district') }}</label>--}}
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Город</label>

                                        <div class="col-md-8 d-flex justify-content-start">
                                            <select class="form-control select2 @error('city_id') is-invalid @enderror"
                                                    id="city" name="city_id" aria-describedby="city-error">
                                                @if($advert->city)
                                                    <option value="{{$advert->city->id}}">{{$advert->city->name_ru}}</option>
                                                @endif
                                            </select>
                                            <span id="city-error"
                                                  class="error invalid-feedback">@error('advert.city_id') {{$message}} @enderror</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.administrative') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <select class="form-control select2 @error('administrative_id') is-invalid @enderror"
                                                    id="administrative" name="administrative_id" aria-describedby="administrative-error">
                                                @if($advert->administrative)
                                                    <option value="{{$advert->administrative_id}}">{{$advert->administrative->name_ru}}</option>
                                                @endif
                                            </select>

                                            <span id="subway-error" class="error invalid-feedback">
                                                @error('advert.administrative_id') {{$message}} @enderror
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.address') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <select class="form-control select2 @error('street_id') is-invalid @enderror"
                                                    id="street" name="street_id" aria-describedby="street-error">
                                                @if($advert->street)
                                                    <option value="{{$advert->street_id}}">{{$advert->street->name_ru}}</option>
                                                @endif
                                            </select>

                                            <span id="street-error"
                                                  class="error invalid-feedback">@error('advert.street_id') {{$message}} @enderror</span>
                                            <div>
                                                <input type="text" value="{{$advert->address}}" name="address"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.subway') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <select class="form-control select2 @error('street_id') is-invalid @enderror"
                                                    id="subway" name="subway_id" aria-describedby="subway-error">
                                                @if($advert->subway)
                                                    <option value="{{$advert->subway_id}}">{{$advert->subway->name_ru}}</option>
                                                @endif
                                            </select>

                                            <span id="subway-error"
                                                  class="error invalid-feedback">@error('advert.subway_id') {{$message}} @enderror</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label
                                            class="col-sm-4 col-form-label">{{ __('adminlte::admin.price_for_month') }}</label>
                                        <div class="col-md-8 d-flex justify-content-start">
                                            <input type="number" value="{{$advert->price_month}}"
                                                   name="price_month"/> {{ config('settings.currency.sign') }}
                                        </div>
                                    </div>
                                </div>

                                <!-- right column block parameters -->
                                <div class="contianer container-bordered col-sm-5 float-md-right">
                                    <h3>Параметры</h3>

                                    @foreach($allParameters as $parameter)
                                        <div class="form-group row">
                                            <label
                                                class="col-sm-4 col-form-label">{{ trans('parameters.'.$parameter->key) }}</label>
                                            <div class="col-md-8 d-flex justify-content-start">
                                                @php
                                                if(!empty($advertParameters[$parameter->key])){
                                                    $aParameterValue = $advertParameters[$parameter->key]->value;
                                                } else {
                                                    $aParameterValue['key'] = '';
                                                    $aParameterValue['value_ru'] = '';
                                                }
                                                @endphp

                                                @if($parameter->type == 'select')
                                                    <select name="parameters[{{$parameter->id}}]" class="select-value">
                                                        @foreach($parameter->allowed_values as $aValue)
                                                            <option @if($aParameterValue['key'] == $aValue['code'])selected @endif value='{{ $aValue['code'] }}'>{{$aValue['value_ru']}}</option>
                                                        @endforeach
                                                    </select>

                                                @elseif($parameter->type == 'range')
                                                    <input type="text" name="parameters[{{$parameter->id}}]" value="{{$aParameterValue['value_ru']}}" class="numeric-value" />

                                                @elseif($parameter->type == 'checkbox')
                                                    <input type="text" name="parameters[{{$parameter->id}}]" value="1" @if($aParameterValue['value_ru'])checked @endif class="checkbox-value" />

                                                @endif

                                                {{ $parameter->unit['ru'] }}
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="">
                                        <h3>Удобства</h3>

                                        @foreach($allOptions as $option)
                                            <div class="form-group row">
                                                <label for="option_{{$option->id}}"
                                                    class="col-sm-4 col-form-label">{{ $option->name_ru }}</label>
                                                <div class="col-md-8 d-flex justify-content-start">
                                                    <input id="option_{{$option->id}}"
                                                           type="checkbox"
                                                           name="options[{{$option->id}}]"
                                                           value="1"
                                                           @if(!empty($advertOptions[$option->key]))checked @endif
                                                           style="margin-top: 14px;"
                                                    />
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </form>
                        </div>

                        <!-- tab-pane -->
                        <div class="tab-pane" id="gallery">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('adminlte::admin.advert_gallery_title') }}</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body p-0">
                                    <div class="container">
                                        <div class="row">
                                            @if (session()->has('error'))
                                                <div class="alert alert-danger">{{session()->get('error')}}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <form action="{{ route('admin.adverts.add-image', $advert->id) }}" method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mb-3 image-gallery">

                                        @if($advert->getMedia('images'))
                                            @foreach($advert->getMedia('images') as $image)
                                                <div class="block-image" data-id="{{ $image->id }}">
                                                    <div class="block-control">
                                                        <span class="rotate-image">
                                                            <span class="js-rotate-left"><i class="fa fa-undo"></i></span>
                                                            <span class="js-rotate-right"><i class="fa fa-undo fa-rotate-90 fa-flip-horizontal"></i>
                                                            </span>
                                                        </span>

                                                        <span class="delete-image js-delete-image">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </span>

                                                        <input type="hidden" name="degrees[{{ $image->id }}]" value="0">
                                                    </div>
                                                    <div class="block-thumb">
                                                        {{ $image('thumb') }}
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="new-block-image" data-id="">
                                                <div class="block-control">
                                                    <span class="rotate-image">
                                                        <span class="js-rotate-left"><i class="fa fa-undo"></i></span>
                                                        <span class="js-rotate-right"><i class="fa fa-undo fa-rotate-90 fa-flip-horizontal"></i>
                                                        </span>
                                                    </span>

                                                    <span class="delete-image js-delete-image">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </span>

                                                    <input type="hidden" name="degrees[]" value="0">
                                                </div>
                                                <div class="block-thumb">
                                                    <img class="new-image" src="" />
                                                    <input class="new-image-input" type="file" name="new-image[]" onchange="readURL(this);" >
                                                </div>
                                            </div>
                                        <!-- /.col -->
                                        @endif
                                    </div>


                                        <div class="input-group mb-3">
                                            <input type="button" value="Добавить"
                                                   class="btn btn-success ml-4 js-add-image">
                                        </div>
                                    </form>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->

        <div class="col-md-2">
            <!-- About Me Box -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ __('adminlte::admin.actions') }}</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    @if($advert->status === App\Advert::STATUS_ENABLED)
                        <div class="alert alert-success">
                            <h6><i class="icon fas fa-check"></i>{{ __('adminlte::admin.advert_active_status') }}</h6>
                        </div>
                    @elseif($advert->status === App\Advert::STATUS_DISABLED)
                        <div class="mb-4">
                            <div class="alert alert-warning">
                                <h6>
                                    <i class="icon fas fa-exclamation-triangle"></i>{{ __('adminlte::admin.advert_notactive_status') }}
                                </h6>
                            </div>
                        </div>
                    @elseif($advert->status === App\Advert::STATUS_MODERATE)
                        <div class="mb-4">
                            <div class="alert alert-warning">
                                <h6><i class="icon fas fa-edit"></i>{{ __('adminlte::admin.advert_status.moderate') }}
                                </h6>
                            </div>

                        </div>
                    @endif

                    <div class="mb-4">
                        <button type="button"
                                class="btn btn-block btn-success js-save-advert"
                                data-uid="{{ $advert->id }}">
                            {{ __('adminlte::admin.button.save') }}
                        </button>
                    </div>
                    <div class="mb-4">
                        <a href="{{ route('admin.adverts.show', $advert->id) }}">
                            <button type="button"
                                    class="btn btn-block btn-default js-cancel-advert"
                                    data-uid="{{ $advert->id }}">
                                {{ __('adminlte::admin.button.cancel') }}
                            </button>
                        </a>
                    </div>
                    <hr>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@stop

@section('css')
    {{--    <link rel="stylesheet" href="/css/admin_custom.css">--}}
    <link rel="stylesheet" href="/vendor/summernote/summernote.css">
@stop

@section('js')
    <script src="/vendor/jsl-image/load-image.all.min.js"></script>
    <script src="/vendor/summernote/summernote-bs4.min.js"></script>

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
        $('.js-save-advert').on('click', function () {
            $('.tab-content .active form').submit();
        });

        $('.js-profile-block-advert').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', advertRoute + except);
            $('#block-modal').modal();

            $('#modalLabel #adverts-count').text(advertsCount);

            $('.modal-content form').attr('action', advertRoute + except);

        });

        $('.js-profile-active-advert').click(function (e) {
            e.preventDefault();
            except = $(this).closest('tr').find('td:first-child').text();
            const advertsCount = $(this).closest('tr').data('adverts');

            $('.modal-content form').attr('action', advertRoute + except);
            $('#unblock-modal').modal();

            $('#unblockModalLabel #adverts-count').text(advertsCount);

            $('.modal-content form').attr('action', advertRoute + except);
        });

        const advertRoute = '{{route('admin.adverts.update', $advert->id)}}';
        let except = null;

        function getAdvertsExcept() {
            return '/adverts/search?except=' + except;
        }


        //////////////////////////////////////////////////////
        $('.js-add-image').on('click', function (e) {
            e.preventDefault();
            let clone = $('.new-block-image').clone(true);;

            $('.image-gallery').append(clone);
            clone.find('input[type="file"]').click();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var imgBlock = $('.image-gallery').find('.new-image').last();
                reader.onload = function (e) {
                    imgBlock
                        .attr('src', e.target.result)
                        .height(200)
                    imgBlock.closest('.new-block-image').removeClass('new-block-image').addClass('block-image');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $('#gallery').on('click', '.js-rotate-right', function () {
            $(this).addClass('clicked');
            let blockImage = $(this).closest('.block-image');
            let degree = parseInt(blockImage.find('input[name^="degrees"]').val());
            degree += 90;
            blockImage.find('img').css({
                "-webkit-transform": "rotate("+degree+"deg)",
                "-moz-transform": "rotate("+degree+"deg)",
                "transform": "rotate("+degree+"deg)"
            });
            blockImage.find('input[name^="degrees"]').val(degree);
            $(this).removeClass('clicked');
        });

        $('#gallery').on('click', '.js-rotate-left', function () {
            $(this).addClass('clicked');
            let blockImage = $(this).closest('.block-image');
            let degree = parseInt(blockImage.find('input[name^="degrees"]').val());
            degree -= 90;
            blockImage.find('img').css({
                "-webkit-transform": "rotate("+degree+"deg)",
                "-moz-transform": "rotate("+degree+"deg)",
                "transform": "rotate("+degree+"deg)"
            });
            blockImage.find('input[name^="degrees"]').val(degree);
            $(this).removeClass('clicked');
        });

        $('#gallery').on('click', '.js-delete-image', function () {
            $(this).addClass('clicked');
            let blockImage = $(this).closest('.block-image');
            $(this).closest('form').append('<input type="hidden" name="deleted_image[]" value="'+blockImage.data('id')+'" />');
            blockImage.remove();
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
                                text: item.full_city_name_ru,
                                russian: item.full_city_name_ru,
                            }
                        })
                    };
                }
            }
        });
        $('#city').select2('data')[0]['russian'] = '{{$advert->city->full_city_name_ru}}';

        let cityId = '{{ $advert->city_id }}';

        $('#city').on('change.select2', function (e) {
            cityId = $(this).val();
            $('#administrative').select2(getAdministrativeOptions());
            $('#street').select2(getStreetOptions());
            $('#subway').select2(getSubwayOptions());
        });

        function getStreetOptions() {
            return {
                ajax: {
                    url: getStreetsUrl(),
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
                                    text: item.name_ru,
                                    russian: item.name_ru,
                                }
                            })
                        };
                    }
                }
            }
        }

        function getAdministrativeOptions() {
            return {
                ajax: {
                    url: getAdministrativeUrl(),
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
                                    text: item.name_ru,
                                    russian: item.name_ru,
                                }
                            })
                        };
                    }
                }
            }
        }

        function getSubwayOptions() {
            return {
                ajax: {
                    url: getSubwayUrl(),
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
                                    text: item.name_ru,
                                    russian: item.name_ru,
                                }
                            })
                        };
                    }
                }
            }
        }


        function getStreetsUrl() {
            return '{{route('admin.streets.search')}}' + '?city_id=' + cityId;
        }

        function getAdministrativeUrl() {
            return '{{route('admin.administrative.search')}}' + '?city_id=' + cityId;
        }

        function getSubwayUrl() {
            return '{{route('admin.subway.search')}}' + '?city_id=' + cityId;
        }

        $('#administrative').select2(getAdministrativeOptions());
        if($('#administrative').select2('data')[0]){
            $('#administrative').select2('data')[0]['russian'] = '@if($advert->administrative){{$advert->administrative->name_ru}} @endif';
        }

        $('#subway').select2(getSubwayOptions());
        if($('#subway').select2('data')[0]){
            $('#subway').select2('data')[0]['russian'] = '@if($advert->subway){{$advert->subway->name_ru}} @endif';
        }

        $('#street').select2(getStreetOptions());
        $('#street').select2('data')[0]['russian'] = '{{$advert->city->name_ru}}';


        let finish = true;

        $('a, button').on('click', function(e) {
            if($(e.target).hasClass('js-save-advert')) {
                finish = false;
                window.onbeforeunload = undefined;
            }else{
                window.onbeforeunload = function() {
                    return '';
                };
            }
        });

        window.onbeforeunload = function() {
            return '';
        };

        window.onunload = function() {
            localStorage.setItem('finishEditing', {{$advert->id}});
        }

        $('.text-editor').summernote({
            height: 250,
        });
        // e.returnValue = '';
        {{--window.addEventListener('beforeunload', function (e) {--}}
            {{--// Cancel the event--}}
            {{--e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown--}}
            {{--localStorage.setItem('finishEditing', {{$advert->id}});--}}
            {{--finishEditing();--}}
            {{--// Chrome requires returnValue to be set--}}
        {{--});--}}

        // window.addEventListener('beforeunload', function (e) {
        //     if(finish) {
        //         console.log('beforeunload call finishEditing');
        //         console.log('result ', finishEditing());
        //     }
        //     console.log('finish', finish);
        // });

        // window.addEventListener('unload', function (e) {
        //
        //     if(finish) {
        //         console.log('unload call finishEditing');
        //         console.log('result ', finishEditing());
        //     }
        //     console.log('finish', finish);
        // });


    </script>
@stop
