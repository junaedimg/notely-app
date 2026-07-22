@extends('layouts.app')

@section('title', 'Create Todo')

@section('content')
<div class="max-w-[720px] mx-auto">
    <div class="mb-8">
        <a href="{{ route('todos.index') }}" class="text-primary font-label-sm text-label-sm hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Todos
        </a>
    </div>

    <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface mb-8">New Todo</h2>

    <form method="POST" action="{{ route('todos.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required
                class="w-full bg-transparent border-t-0 border-l-0 border-r-0 border-b-2 border-outline-variant py-3 font-body-lg text-on-surface placeholder:text-outline-variant focus:outline-none focus:border-primary transition-colors"
                placeholder="What needs to be done?">
            @error('title') <p class="text-error font-label-sm text-label-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Description</label>
            <textarea name="description" rows="3"
                class="w-full bg-surface-container-low border border-outline-variant rounded-lg p-4 font-body-md text-on-surface placeholder:text-outline-variant focus:outline-none focus:border-primary transition-colors"
                placeholder="Optional details...">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Parent Note (optional)</label>
            <select name="note_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                <option value="">Standalone todo</option>
                @foreach($notes as $note)
                <option value="{{ $note->id }}" @if(old('note_id') == $note->id) selected @endif>{{ $note->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_important" value="1" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Important</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_urgent" value="1" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Urgent</span>
            </label>
        </div>

        <div class="border-t border-outline-variant pt-6">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">Repeat Settings</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Repeat</label>
                    <select name="repeat_type" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="none">No repeat</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="interval">Interval</option>
                    </select>
                </div>

                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Anchor</label>
                    <select name="repeat_anchor" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="schedule">Schedule based</option>
                        <option value="completion">Completion based</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">First due date</label>
                    <input type="datetime-local" name="next_due_at" value="{{ old('next_due_at') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Reminder time</label>
                    <input type="time" name="reminder_time" value="{{ old('reminder_time') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">End type</label>
                    <select name="end_type" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="never">Never</option>
                        <option value="date">End date</option>
                        <option value="count">After N times</option>
                    </select>
                </div>

                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">End date</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="mt-4">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">End after N completions</label>
                <input type="number" name="end_count" value="{{ old('end_count') }}" min="1"
                    class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
            </div>
        </div>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Todo</button>
            <a href="{{ route('todos.index') }}" class="px-8 py-3 rounded-lg border border-outline-variant font-label-sm text-label-sm text-on-surface hover:bg-surface-container-low transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
