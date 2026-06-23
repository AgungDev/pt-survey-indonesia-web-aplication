@props([
    'title' => 'Title',
    'value' => '0',
    'icon' => 'bi bi-bar-chart-line',
    'color' => 'primary',
])

<div class="col-lg-3 col-md-6 col-sm-12">
    <div class="small-box bg-{{ $color }}">
        <div class="inner">
            <h3>{{ $value }}</h3>
            <p>{{ $title }}</p>
        </div>
        <div class="icon">
            <i class="{{ $icon }}"></i>
        </div>
    </div>
</div>
