{{--
  Komponen header kolom sortable.

  Pakai:
    <x-sort field="nama_barang" :sort="$sort" :direction="$direction">Nama Barang</x-sort>

  Props:
    - field      : string, nama kolom untuk sort (wajib)
    - sort       : string, current sort param (dari request)
    - direction  : string, current direction ('asc' | 'desc')
    - label      : optional, jika tidak isi, pakai slot
    - extra      : array, query string tambahan (existing filter)
--}}
@props([
    'field'     => '',
    'sort'      => '',
    'direction' => 'asc',
    'extra'     => [],
])

@php
    $isActive    = $sort === $field;
    $nextDir     = $isActive && $direction === 'asc' ? 'desc' : 'asc';
    $arrow       = !$isActive ? '' : ($direction === 'asc' ? ' ▲' : ' ▼');
    $merged      = array_merge($extra, ['sort' => $field, 'direction' => $nextDir]);
    $queryString = http_build_query($merged);
    $url         = url()->current() . '?' . $queryString;
    $label       = $label ?? $slot;
@endphp

<a href="{{ $url }}" class="text-decoration-none text-reset d-inline-flex align-items-center gap-1 sortable-th {{ $isActive ? 'fw-bold' : '' }}">
    <span>{{ $label }}</span>
    @if($arrow)
        <small class="text-primary">{{ $arrow }}</small>
    @else
        <small class="text-muted opacity-50" style="font-size: 0.7em;">⇅</small>
    @endif
</a>
