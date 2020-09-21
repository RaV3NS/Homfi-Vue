<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ $url }}/vendor/adminlte/dist/img/logo.svg" class="logo" alt="Homfi Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
