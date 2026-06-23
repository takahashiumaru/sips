@php
    $showText = $showText ?? true;
    $tone = $tone ?? 'light';
    $size = $size ?? 'md';
    $markSize = $size === 'lg' ? 'h-14 w-14' : 'h-10 w-10';
    $textSize = $size === 'lg' ? 'text-3xl' : 'text-base';
    $textClass = $tone === 'dark' ? 'text-white' : 'text-slate-900';
@endphp

<span class="brand-lockup inline-flex items-center gap-3">
    <img src="{{ asset('logo.png') }}" alt="SIP-SPP logo" class="{{ $markSize }} object-contain shrink-0">
    @if($showText)
        <span class="brand-logo-text {{ $textClass }} {{ $textSize }} font-black leading-none">
            SIP<span style="color: {{ $tone === 'dark' ? 'rgba(255,255,255,.9)' : '#3B82F6' }}">-</span>SPP
        </span>
    @endif
</span>
