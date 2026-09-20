@props([
    'seed' => '',
    'variant' => 'auto',   // network | columns | radial | contour | auto
])

@php
    /*
     * Deterministic blueprint cover art, drawn as inline SVG.
     *
     * Records without a photograph still need a cover that looks designed
     * rather than empty. Everything here is derived from a hash of the record
     * slug, so each one gets a distinct composition that stays stable across
     * page loads, costs no HTTP request, and scales to any size.
     *
     * Upload a real image on the record and it replaces this entirely.
     */
    $state = crc32((string) $seed) & 0x7fffffff;

    // Small LCG, so we do not disturb PHP's global mt_rand sequence. The low
    // bits of an LCG barely vary, so draw from the high ones.
    $rand = function (int $min, int $max) use (&$state): int {
        $state = ($state * 1103515245 + 12345) & 0x7fffffff;

        return $min + (($state >> 16) % max(1, $max - $min + 1));
    };

    // Pick the motif off a separate hash — one LCG step off a crc32 seed
    // clusters badly, and the whole point is that adjacent cards differ.
    $motifs = ['network', 'columns', 'radial', 'contour'];
    $motif = $variant === 'auto'
        ? $motifs[hexdec(substr(md5((string) $seed), 0, 6)) % 4]
        : $variant;

    $W = 400;
    $H = 250;

    $ink = '#0f4280';       // brand-600
    $inkSoft = '#89aed8';   // brand-300
    $grid = '#d5e1f1';
    $gridMajor = '#a8c1de';
    $warm = '#316d31';      // brand-600

    $gid = 'cv'.substr(md5((string) $seed), 0, 8);
@endphp

<svg {{ $attributes->merge(['class' => 'h-full w-full']) }}
     viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="xMidYMid slice"
     role="img" aria-hidden="true" focusable="false">

    <rect width="{{ $W }}" height="{{ $H }}" fill="#f6f8fd" />

    @switch ($motif)

        {{-- Nodes wired together — research, collaboration, the hub. --}}
        @case ('network')
            @php
                $nodes = [];
                for ($i = 0; $i < 7; $i++) {
                    $nodes[] = [$rand(45, $W - 45), $rand(40, $H - 40), $rand(3, 7)];
                }
                $hot = $rand(0, 6);
            @endphp

            @foreach ($nodes as $i => [$x, $y, $r])
                @foreach ($nodes as $j => [$x2, $y2, $r2])
                    @if ($j > $i && hypot($x - $x2, $y - $y2) < 150)
                        <line x1="{{ $x }}" y1="{{ $y }}" x2="{{ $x2 }}" y2="{{ $y2 }}"
                              stroke="{{ $ink }}" stroke-width="1" opacity="0.4" />
                    @endif
                @endforeach
            @endforeach

            @foreach ($nodes as $i => [$x, $y, $r])
                <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $r + 7 }}"
                        fill="{{ $i === $hot ? $warm : $ink }}" opacity="0.10" />
                <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $r }}"
                        fill="{{ $i === $hot ? $warm : $ink }}" />
            @endforeach
            @break

        {{-- Plotted series — measurement, evaluation, evidence. --}}
        @case ('columns')
            @php
                $count = 11;
                $gap = ($W - 80) / $count;
                $bars = [];
                for ($i = 0; $i < $count; $i++) {
                    $bars[] = $rand(22, 150);
                }
                $hot = $rand(0, $count - 1);
            @endphp

            <line x1="40" y1="{{ $H - 42 }}" x2="{{ $W - 40 }}" y2="{{ $H - 42 }}"
                  stroke="{{ $gridMajor }}" stroke-width="1.4" />

            @foreach ($bars as $i => $h)
                <rect x="{{ 40 + $i * $gap + 3 }}" y="{{ $H - 42 - $h }}"
                      width="{{ $gap - 6 }}" height="{{ $h }}" rx="2"
                      fill="{{ $i === $hot ? $warm : $ink }}"
                      opacity="{{ $i === $hot ? '0.95' : (0.25 + ($h / 300)) }}" />
            @endforeach
            @break

        {{-- Concentric survey — scope, reach, coverage. --}}
        @case ('radial')
            @php
                $cx = $rand(150, 250);
                $cy = $H / 2;
                $pts = [];
                for ($a = 0; $a < 360; $a += 45) {
                    $r = $rand(30, 78);
                    $pts[] = round($cx + cos(deg2rad($a)) * $r, 1).','.round($cy + sin(deg2rad($a)) * $r, 1);
                }
            @endphp

            @foreach ([30, 52, 74, 96] as $r)
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}" fill="none"
                        stroke="{{ $inkSoft }}" stroke-width="1" opacity="0.7" />
            @endforeach

            @foreach ([0, 45, 90, 135] as $a)
                <line x1="{{ round($cx - cos(deg2rad($a)) * 96, 1) }}" y1="{{ round($cy - sin(deg2rad($a)) * 96, 1) }}"
                      x2="{{ round($cx + cos(deg2rad($a)) * 96, 1) }}" y2="{{ round($cy + sin(deg2rad($a)) * 96, 1) }}"
                      stroke="{{ $inkSoft }}" stroke-width="0.8" opacity="0.6" />
            @endforeach

            <polygon points="{{ implode(' ', $pts) }}" fill="{{ $ink }}" fill-opacity="0.14"
                     stroke="{{ $ink }}" stroke-width="1.6" />
            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="5" fill="{{ $warm }}" />
            @break

        {{-- Contour lines — terrain, sites, field study. --}}
        @default
            @php
                $amp = $rand(14, 26);
                $lift = $rand(10, 24);
            @endphp

            @for ($k = 0; $k < 6; $k++)
                @php
                    $base = 40 + $k * 32;
                    $d = 'M -10 '.($base);
                    for ($x = 0; $x <= $W + 20; $x += 20) {
                        $y = $base + sin(($x / 70) + $k * 0.7) * ($amp - $k * 1.5) - $k * 2;
                        $d .= ' L '.$x.' '.round($y, 1);
                    }
                @endphp
                <path d="{{ $d }}" fill="none" stroke="{{ $k % 3 === 0 ? $ink : $inkSoft }}"
                      stroke-width="{{ $k % 3 === 0 ? 1.6 : 1 }}"
                      opacity="{{ $k % 3 === 0 ? 0.75 : 0.55 }}" />
            @endfor

            <circle cx="{{ $rand(90, 310) }}" cy="{{ $rand(70, 180) }}" r="5" fill="{{ $warm }}" />
    @endswitch
</svg>
