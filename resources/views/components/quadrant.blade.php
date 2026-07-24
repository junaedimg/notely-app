@php
    $colors = match($quadrant) {
        'do' => ['text' => 'text-quadrant-do', 'bg' => 'bg-quadrant-do', 'border' => 'border-quadrant-do', 'label' => 'Do', 'icon' => 'priority_high'],
        'decide' => ['text' => 'text-quadrant-decide', 'bg' => 'bg-quadrant-decide', 'border' => 'border-quadrant-decide', 'label' => 'Decide', 'icon' => 'calendar_month'],
        'delegate' => ['text' => 'text-quadrant-delegate', 'bg' => 'bg-quadrant-delegate', 'border' => 'border-quadrant-delegate', 'label' => 'Delegate', 'icon' => 'arrow_forward'],
        'delete' => ['text' => 'text-quadrant-delete', 'bg' => 'bg-quadrant-delete', 'border' => 'border-quadrant-delete', 'label' => 'Delete', 'icon' => 'remove_circle_outline'],
        default => ['text' => 'text-secondary', 'bg' => 'bg-surface-container-low', 'border' => 'border-outline-variant', 'label' => 'Normal', 'icon' => 'remove'],
    };
@endphp
<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $colors['bg'] }} {{ $colors['text'] }} border {{ $colors['border'] }} font-label-sm text-[10px] uppercase font-semibold tracking-wide {{ $class ?? '' }}">
    <span class="material-symbols-outlined text-[12px] leading-none">{{ $colors['icon'] }}</span>
    {{ $colors['label'] }}
</span>
