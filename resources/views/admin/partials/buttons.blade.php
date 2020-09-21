<?php /** @var array $buttons */ ?>

@foreach($buttons as $button)
    @if(isset($button['confirm']))
        <form style="display: inline-block;" action="{{ $button['route'] }}" method="POST">
            @csrf

            @if(!empty($button['method']))
                <input type="hidden" name="_method" value="{{$button['method']}}">
            @else
                @method('PUT')
            @endif
            <button type="submit" class="btn btn-xs btn-{{ $button['class'] }}"
                    onClick="return confirm('{{ trans('adminlte::admin.button.'.$button['confirm']) }}')">
                @if (!empty($button['glyphicon']))
                    <i class="glyphicon glyphicon-{{ $button['glyphicon'] }}"></i>
                @endif
                {{ trans('adminlte::admin.button.'.$button['name']) }}
            </button>
        </form>
    @else
        <a href="{{ $button['route'] }}" target="{{ $button['target'] ?? '_parent' }}"
           class="btn btn-xs btn-{{ $button['class'] }}">
            @if (!empty($button['glyphicon']))
                <i class="glyphicon glyphicon-{{ $button['glyphicon'] }}"></i>
            @endif
                {{ trans('adminlte::admin.button.'.$button['name']) }}
        </a>
    @endif
@endforeach
