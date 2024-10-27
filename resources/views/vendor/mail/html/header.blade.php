@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('images/diuacm-transparent.webp')}}" class="logo" alt="DIU ACM Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
