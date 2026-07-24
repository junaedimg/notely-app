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
                <input type="hidden" name="is_important" value="0">
                <input type="checkbox" name="is_important" value="1" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Important</span>
                <x-tooltip position="left-1/2 -translate-x-1/2">Besar dampaknya
Contoh: bayar cicilan, laporan pajak</x-tooltip>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="is_urgent" value="0">
                <input type="checkbox" name="is_urgent" value="1" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                <span class="font-body-md text-on-surface">Urgent</span>
                <x-tooltip position="left-1/2 -translate-x-1/2">Butuh perhatian segera
Contoh: deadline hari ini, darurat</x-tooltip>
            </label>
        </div>

        <div class="border-t border-outline-variant pt-6">
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">Repeat Settings</h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="col-span-2">
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        Repeat
                        <x-tooltip position="left-1/2 -translate-x-1/2">Seberapa sering todo muncul ulang
Contoh: daily = tiap hari, weekly = tiap minggu</x-tooltip>
                    </label>
                    <select name="repeat_type" id="repeat-type" onchange="toggleRepeatFields()" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="none">No repeat</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                        <option value="interval">Interval</option>
                    </select>
                </div>

                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        Anchor
                        <x-tooltip>Schedule = repeat berdasarkan jadwal
Bills: due tgl 5, selesai tgl 7 → next due tgl 5 bulan depan

Completion = repeat berdasarkan selesai
Oli: ganti tgl 5, selesai tgl 7 → next ganti +3 bulan dari tgl 7</x-tooltip>
                    </label>
                    <select name="repeat_anchor" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="schedule">Schedule</option>
                        <option value="completion">Completion</option>
                    </select>
                </div>
            </div>

            {{-- Interval --}}
            <div id="interval-fields" class="grid grid-cols-2 gap-4 mt-4 hidden">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        Every
                        <x-tooltip>Todo muncul setiap X unit waktu
Contoh: 3 bulan sekali → isi Every=3, Unit=Months</x-tooltip>
                    </label>
                    <input type="number" name="interval_value" value="{{ old('interval_value') }}" min="1"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">Unit</label>
                    <select name="interval_unit" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="day">Days</option>
                        <option value="week">Weeks</option>
                        <option value="month">Months</option>
                        <option value="year">Years</option>
                    </select>
                </div>
            </div>

            {{-- Weekly --}}
            <div id="weekly-fields" class="mt-4 hidden">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                    On days
                    <x-tooltip>Todo muncul di hari yang dipilih
Contoh: pilih Senin & Rabu → muncul tiap Senin & Rabu</x-tooltip>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Mon'=>'1','Tue'=>'2','Wed'=>'3','Thu'=>'4','Fri'=>'5','Sat'=>'6','Sun'=>'7'] as $label=>$val)
                    <label class="flex items-center gap-2 bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 cursor-pointer hover:border-primary transition-colors has-[:checked]:bg-primary-fixed has-[:checked]:border-primary has-[:checked]:text-primary">
                        <input type="checkbox" name="days_of_week[]" value="{{ $val }}" class="sr-only">
                        <span class="font-body-md text-sm">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Monthly --}}
            <div id="monthly-fields" class="mt-4 hidden">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                    Day of month
                    <x-tooltip>Todo muncul di tanggal ini setiap bulan
Contoh: isi 15 → muncul tiap tanggal 15</x-tooltip>
                </label>
                <input type="number" name="day_of_month" value="{{ old('day_of_month') }}" min="1" max="31"
                    class="w-full max-w-[120px] bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
            </div>

            {{-- Yearly --}}
            <div id="yearly-fields" class="mt-4 hidden">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                    Month of year
                    <x-tooltip>Todo muncul di bulan ini setiap tahun
Contoh: pilih July → muncul tiap bulan July</x-tooltip>
                </label>
                <select name="month_of_year" class="w-full max-w-[200px] bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        First due date
                        <x-tooltip>Kapan todo pertama muncul di dashboard
Kosongkan → muncul hari ini juga</x-tooltip>
                    </label>
                    <input type="datetime-local" name="next_due_at" value="{{ old('next_due_at') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>

                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        Reminder time
                        <x-tooltip>Jam notifikasi pengingat
Fitur akan datang di versi selanjutnya</x-tooltip>
                    </label>
                    <input type="time" name="reminder_time" value="{{ old('reminder_time') }}"
                        class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                        End type
                        <x-tooltip>Never = repeat tidak pernah berhenti
End date = berhenti di tanggal tertentu
After N times = berhenti setelah N kali selesai</x-tooltip>
                    </label>
                    <select name="end_type" id="end-type" onchange="toggleEndFields()" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
                        <option value="never">Never</option>
                        <option value="date">End date</option>
                        <option value="count">After N times</option>
                    </select>
                </div>
            </div>

            <div id="end-date-fields" class="mt-4 hidden">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                    End date
                    <x-tooltip>Repeat berhenti otomatis setelah tanggal ini
Contoh: isi 2026-12-31 → berhenti akhir tahun 2026</x-tooltip>
                </label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                    class="w-full max-w-[240px] bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
            </div>

            <div id="end-count-fields" class="mt-4 hidden">
                <label class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-2 block">
                    End after N completions
                    <x-tooltip>Repeat berhenti setelah N kali selesai
Contoh: isi 6 → berhenti setelah 6 kali</x-tooltip>
                </label>
                <input type="number" name="end_count" value="{{ old('end_count') }}" min="1"
                    class="w-full max-w-[120px] bg-surface-container-low border border-outline-variant rounded-lg px-4 py-3 font-body-md text-on-surface focus:outline-none focus:border-primary">
            </div>
        </div>

        <script>
            function toggleRepeatFields() {
                const val = document.getElementById('repeat-type').value;
                document.getElementById('interval-fields').classList.toggle('hidden', val !== 'interval');
                document.getElementById('weekly-fields').classList.toggle('hidden', val !== 'weekly');
                document.getElementById('monthly-fields').classList.toggle('hidden', val !== 'monthly');
                document.getElementById('yearly-fields').classList.toggle('hidden', val !== 'yearly');
            }
            function toggleEndFields() {
                const val = document.getElementById('end-type').value;
                document.getElementById('end-date-fields').classList.toggle('hidden', val !== 'date');
                document.getElementById('end-count-fields').classList.toggle('hidden', val !== 'count');
            }
        </script>

        <div class="flex gap-4 pt-4">
            <button type="submit" class="bg-primary text-on-primary px-8 py-3 rounded-lg font-label-sm text-label-sm hover:brightness-110 active:scale-95 transition-all">Create Todo</button>
            <a href="{{ route('todos.index') }}" class="px-8 py-3 rounded-lg border border-outline-variant font-label-sm text-label-sm text-on-surface hover:bg-surface-container-low transition-all">Cancel</a>
        </div>
    </form>
</div>
@endsection
