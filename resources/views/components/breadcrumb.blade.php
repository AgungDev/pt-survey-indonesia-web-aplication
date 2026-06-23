@props(['items' => []])

@if(count($items))
    <ol class="breadcrumb float-sm-right">
        @foreach($items as $item)
            <li class="breadcrumb-item {{ empty($item['url']) ? 'active' : '' }}">
                @if(!empty($item['url']))
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @else
                    {{ $item['label'] }}
                @endif
            </li>
        @endforeach
    </ol>
@endif
