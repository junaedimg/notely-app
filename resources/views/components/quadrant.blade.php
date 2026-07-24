@php $data = \App\View\Components\Quadrant::get($quadrant); $c = $data['class']; @endphp
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-quadrant-{{ $c }} text-quadrant-{{ $c }} border border-quadrant-{{ $c }} font-label-sm text-[10px] uppercase font-semibold tracking-wide {{ $class ?? '' }}">
    <span class="material-symbols-outlined text-[12px] leading-none">{{ $data['icon'] }}</span>
    {{ $data['label'] }}
</span>
