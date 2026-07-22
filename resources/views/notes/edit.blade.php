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
                <input type="checkbox" name="is_pinned" value="1" {{ $note->is_pinned ? 'checked' : '' }} class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Pin this note</span>
            </label>

            <div>
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mr-2">Color</label>
                <select name="color" class="bg-surface-container-low border border-outline-variant rounded-lg px-3 py-2 font-body-md text-on-surface focus:outline-none focus:border-primary">
                    <option value="">None</option>
                    <option value="yellow" @if($note->color === 'yellow') selected @endif>Yellow</option>
                    <option value="blue" @if($note->color === 'blue') selected @endif>Blue</option>
                    <option value="green" @if($note->color === 'green') selected @endif>Green</option>
                    <option value="red" @if($note->color === 'red') selected @endif>Red</option>
                    <option value="purple" @if($note->color === 'purple') selected @endif>Purple</option>
                </select>
            </div>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Update Note</button>
            <a href="{{ route('notes.show', $note) }}" class="px-8 py-3 rounded-lg border border-outline-variant font-label-sm text-label-sm text-on-surface hover:bg-surface-container-low transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
