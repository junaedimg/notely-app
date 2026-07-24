@props(['note'])
<div class="border border-outline-variant rounded-xl p-5 flex flex-col gap-2 hover:border-primary transition-colors cursor-pointer relative active:scale-[0.98] shadow-sm"
     style="{{ $note->color_hex ? 'border-left:4px solid '.$note->color_hex.';background-color:'.$note->color_bg : 'background-color:white' }}">
    <a href="{{ route('notes.show', $note) }}" class="absolute inset-0 z-0"></a>
    <div class="flex justify-between items-start relative z-10">
        <h3 class="font-headline-md text-primary font-bold">{{ $note->title }}</h3>
        <div class="flex items-center gap-1" onclick="event.stopPropagation()">
            <form method="POST" action="{{ route('notes.toggle-pin', $note) }}">
                @csrf
                <input type="hidden" name="_redirect" value="{{ request()->url() }}">
                <button class="material-symbols-outlined text-secondary hover:text-primary active:scale-90 transition-all p-1 text-[18px] {{ $note->is_pinned ? 'text-primary' : '' }}" style="{{ $note->is_pinned ? 'font-variation-settings: \'FILL\' 1;' : '' }}">push_pin</button>
            </form>
        </div>
    </div>
    @if($note->content)
    <p class="text-on-surface-variant font-body-md line-clamp-2 leading-relaxed relative z-10">{{ Str::limit(strip_tags($note->content), 200) }}</p>
    @endif
    <div class="mt-2 flex items-center gap-3 text-label-sm text-outline relative z-10">
        <span class="flex items-center gap-1">
            <span class="material-symbols-outlined text-[14px]">calendar_today</span> {{ $note->created_at->format('M d') }}
        </span>
    </div>
</div>
