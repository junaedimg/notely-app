@extends('layouts.app')

@section('title', 'Edit Note')

@section('content')
<div class="max-w-[720px] mx-auto">
    <div class="mb-8">
        <a href="{{ route('notes.show', $note) }}" class="text-primary font-label-sm text-label-sm hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back
        </a>
    </div>

    <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-8">Edit Note</h2>

    <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-6">
        @csrf @method('PATCH')

        <div>
            <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Title</label>
            <input type="text" name="title" value="{{ old('title', $note->title) }}" required
                class="w-full bg-transparent border-t-0 border-l-0 border-r-0 border-b-2 border-outline-variant py-3 font-body-lg text-on-surface placeholder:text-outline-variant focus:outline-none focus:border-primary transition-colors">
            @error('title') <p class="text-error font-label-sm text-label-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Content</label>
            <textarea name="content" rows="15"
                class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-5 font-body-md text-on-surface placeholder:text-outline-variant focus:outline-none focus:border-primary transition-colors">{{ old('content', $note->content) }}</textarea>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_pinned" value="0">
                <input type="checkbox" name="is_pinned" value="1" {{ $note->is_pinned ? 'checked' : '' }} class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Pin this note</span>
            </label>

            <div>
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-3 block">Color</label>
                <div class="flex gap-4" id="color-picker">
                    @php $colors = ['yellow'=>'#f59e0b','blue'=>'#3b82f6','green'=>'#22c55e','red'=>'#ef4444','purple'=>'#a855f7']; @endphp
                    <input type="hidden" name="color" id="color-input" value="{{ old('color', $note->color) }}">
                    <label class="flex flex-col items-center gap-1 cursor-pointer group" onclick="document.getElementById('color-input').value='';document.querySelectorAll('#color-picker label .check').forEach(e=>e.style.display='none');document.querySelectorAll('#color-picker label .swatch').forEach(e=>e.style.outline='');this.querySelector('.check').style.display=''">
                        <div class="w-6 h-6 rounded-full border-2 border-dashed border-outline-variant flex items-center justify-center group-hover:scale-110 transition-transform swatch">
                            <span class="material-symbols-outlined text-[14px] text-primary check" style="display:{{ old('color', $note->color) ? 'none' : '' }}">check</span>
                        </div>
                        <span class="text-[11px] font-medium text-secondary">None</span>
                    </label>
                    @foreach($colors as $name=>$hex)
                    @php $sel = old('color', $note->color) === $name; @endphp
                    <label class="flex flex-col items-center gap-1 cursor-pointer group" onclick="document.getElementById('color-input').value='{{ $name }}';document.querySelectorAll('#color-picker label .check').forEach(e=>e.style.display='none');document.querySelectorAll('#color-picker label .swatch').forEach(e=>e.style.outline='');this.querySelector('.check').style.display='';this.querySelector('.swatch').style.outline='2px solid #15157d';this.querySelector('.swatch').style.outlineOffset='2px'">
                        <div class="w-6 h-6 rounded-full group-hover:scale-110 transition-transform relative flex items-center justify-center swatch" style="background:{{ $hex }};{{ $sel ? 'outline:2px solid #15157d;outline-offset:2px' : '' }}">
                            <span class="material-symbols-outlined text-[14px] text-white check" style="display:{{ $sel ? '' : 'none' }}">check</span>
                        </div>
                        <span class="text-[11px] font-medium text-secondary">{{ ucfirst($name) }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Update Note</button>
            <a href="{{ route('notes.show', $note) }}" class="px-8 py-3 rounded-lg border border-outline-variant font-label-sm text-label-sm text-on-surface hover:bg-surface-container-low transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
