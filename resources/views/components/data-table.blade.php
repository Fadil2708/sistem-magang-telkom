@props([
    'headers' => [],
    'paginator' => null,
    'emptyIcon' => 'ti-inbox',
    'emptyMessage' => 'Tidak ada data',
])

<div class="panel">
    <div class="overflow-x-auto">
    <table class="data">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
    </div>
</div>

@if($paginator)
<div class="pagination-wrap">
    {{ $paginator->withQueryString()->links('components.pagination', ['paginator' => $paginator]) }}
</div>
@endif
