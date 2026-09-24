@props([
    'heads' => [],
    'rows' => [],
    'widths' => null,   // e.g. '1fr 2fr 1fr'
    'tint' => 'brand',  // brand | navy | amber — the header band colour
])

@php
    $tints = [
        'brand' => 'bg-brand-50 text-brand-900',
        'navy' => 'bg-navy-50 text-navy-900',
        'amber' => 'bg-amber-50 text-amber-900',
    ];
@endphp

{{-- A table set the way the framework document sets them: ruled, headed and
     read left to right. It scrolls sideways on small screens rather than
     reflowing, so rows stay comparable. --}}
<div {{ $attributes->merge(['class' => 'reveal overflow-x-auto rounded-xl border border-ink-300']) }}>
    <table class="w-full border-collapse text-left align-top" @if ($widths) style="table-layout: fixed; min-width: 640px" @endif>
        @if ($heads)
            <thead>
                <tr class="{{ $tints[$tint] ?? $tints['brand'] }}">
                    @foreach ($heads as $i => $head)
                        <th class="border-b border-ink-300 px-4 py-3 font-display text-[13.5px] font-bold @if ($i) border-l border-ink-300 @endif"
                            @if ($widths) style="width: {{ explode(' ', $widths)[$i] ?? 'auto' }}" @endif>
                            {{ $head }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif

        <tbody>
            @foreach ($rows as $row)
                <tr class="align-top">
                    @foreach ($row as $i => $cell)
                        <td class="border-t border-ink-200 px-4 py-3 text-[13.5px] leading-relaxed text-ink-800 @if ($i) border-l border-ink-200 @endif">
                            @if (is_array($cell))
                                <ul class="space-y-1">
                                    @foreach ($cell as $item)
                                        <li class="flex gap-2">
                                            <span aria-hidden="true">•</span>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                {{ $cell }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
