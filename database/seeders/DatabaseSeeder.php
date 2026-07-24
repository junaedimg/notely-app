<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\Todo;
use App\Models\TodoHistory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = now();
        $lastMonth = $now->copy()->subMonth();
        $nextMonth = $now->copy()->addMonth();

        // =====================================
        // NOTES
        // =====================================

        $note1 = Note::create([
            'title' => 'Belajar Laravel 13',
            'content' => "Materi yang perlu dipelajari:\n- Routing\n- Controller\n- Blade Template\n- Eloquent ORM\n- Migration",
            'is_pinned' => true,
            'color' => 'blue',
            'created_at' => $lastMonth->copy()->subDays(10),
            'updated_at' => $now->copy()->subDays(2),
        ]);

        Note::create([
            'title' => 'Catatan Servis Motor',
            'content' => "Servis terakhir: 10 Juni 2026 di AHASS.\nGanti oli + filter.\nBerikutnya: 10 September 2026.",
            'is_pinned' => true,
            'color' => 'yellow',
            'created_at' => $lastMonth->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(1),
        ]);

        Note::create([
            'title' => 'Ide Blog Post',
            'content' => "Topik yang menarik:\n1. Pengalaman belajar Laravel dari nol\n2. Tips produktivitas dengan Eisenhower Matrix\n3. Review tools personal productivity",
            'is_pinned' => false,
            'color' => 'green',
            'created_at' => $lastMonth->copy()->addDays(3),
            'updated_at' => $lastMonth->copy()->addDays(3),
        ]);

        Note::create([
            'title' => 'Resep Masakan Favorit',
            'content' => "Nasi Goreng Spesial:\n- Nasi putih\n- Telur\n- Ayam suwir\n- Kecap manis\n- Bawang merah & putih\n- Cabai",
            'is_pinned' => false,
            'color' => 'red',
            'created_at' => $lastMonth->copy()->addDays(7),
            'updated_at' => $lastMonth->copy()->addDays(7),
        ]);

        Note::create([
            'title' => 'Catatan Rapat Q3',
            'content' => "Target penjualan Q3: 500 unit\nBudget marketing: 50jt\nDeadline campaign: 15 Agustus",
            'is_pinned' => true,
            'color' => 'purple',
            'created_at' => $lastMonth->copy()->addDays(14),
            'updated_at' => $now->copy()->subDays(3),
        ]);

        Note::create([
            'title' => 'Daily Journal Template',
            'content' => "Pagi: meditasi 10 menit\nSiang: fokus kerja 4 jam\nSore: olahraga 30 menit\nMalam: baca buku",
            'is_pinned' => false,
            'color' => '',
            'created_at' => $now->copy()->subDays(20),
            'updated_at' => $now->copy()->subDays(20),
        ]);

        // Soft deleted note
        $deletedNote = Note::create([
            'title' => 'Catatan Lama: Project X',
            'content' => 'Dokumentasi project lama yang sudah tidak relevan.',
            'is_pinned' => false,
            'color' => 'green',
        ]);
        $deletedNote->delete();

        // =====================================
        // TODOS
        // =====================================

        // 1. DO — urgent & important, due today
        $todo1 = Todo::create([
            'note_id' => $note1->id,
            'title' => 'Selesaikan Modul Controller',
            'description' => 'Route resource, dependency injection, dan middleware.',
            'status' => 'active',
            'is_important' => true,
            'is_urgent' => true,
            'repeat_type' => 'none',
            'next_due_at' => $now->copy()->setHour(17)->setMinute(0),
        ]);

        // 2. DECIDE — important only, repeat weekly Mon/Wed/Fri
        Todo::create([
            'note_id' => $note1->id,
            'title' => 'Review Materi Eloquent ORM',
            'description' => 'Relationship, eager loading, query scopes.',
            'status' => 'active',
            'is_important' => true,
            'is_urgent' => false,
            'repeat_type' => 'weekly',
            'days_of_week' => [1, 3, 5],
            'repeat_anchor' => 'schedule',
            'next_due_at' => $now->copy()->nextWeekday()->setHour(9)->setMinute(0),
        ]);

        // 3. DELEGATE — urgent only, repeat daily
        Todo::create([
            'title' => 'Cek & Balas Email',
            'description' => 'Email client dan internal.',
            'status' => 'active',
            'is_important' => false,
            'is_urgent' => true,
            'repeat_type' => 'daily',
            'repeat_anchor' => 'schedule',
            'next_due_at' => $now->copy()->setHour(10)->setMinute(0),
        ]);

        // 4. No repeat, no due date (appears on dashboard)
        Todo::create([
            'note_id' => null,
            'title' => 'Beli Bahan Masak Mingguan',
            'description' => 'Sayur, daging, bumbu dapur.',
            'status' => 'active',
            'is_important' => false,
            'is_urgent' => false,
            'repeat_type' => 'monthly',
            'day_of_month' => 1,
            'repeat_anchor' => 'schedule',
            'end_type' => 'never',
            'next_due_at' => null,
            'completed_count' => 0,
        ]);

        // 5. Interval repeat — every 3 months
        Todo::create([
            'note_id' => $note1->id,
            'title' => 'Ganti Oli Motor',
            'description' => 'Servis rutin setiap 3 bulan.',
            'status' => 'active',
            'is_important' => true,
            'is_urgent' => false,
            'repeat_type' => 'interval',
            'interval_value' => 3,
            'interval_unit' => 'month',
            'repeat_anchor' => 'completion',
            'next_due_at' => $nextMonth->copy()->addDays(10)->setHour(10)->setMinute(0),
        ]);

        // 6. Paused
        Todo::create([
            'title' => 'Belajar Vue.js',
            'description' => 'Fundamental Vue 3, Composition API.',
            'status' => 'paused',
            'is_important' => true,
            'is_urgent' => false,
        ]);

        // 7. Archived
        Todo::create([
            'title' => 'Install Ulang OS Laptop',
            'description' => 'Migration Windows ke Linux.',
            'status' => 'archived',
            'is_important' => false,
            'is_urgent' => false,
            'completed_count' => 1,
        ]);

        // 8. Overdue — due yesterday
        $overdueTodo = Todo::create([
            'note_id' => $note1->id,
            'title' => 'Siapkan Laporan Q3',
            'description' => 'Data penjualan, grafik, dan analisis.',
            'status' => 'active',
            'is_important' => true,
            'is_urgent' => true,
            'repeat_type' => 'none',
            'next_due_at' => $now->copy()->subDay()->setHour(12)->setMinute(0),
        ]);

        // 9. Due tomorrow
        Todo::create([
            'title' => 'Bayar Tagihan Listrik',
            'description' => 'No. pelanggan: 123456789.',
            'status' => 'active',
            'is_important' => true,
            'is_urgent' => false,
            'repeat_type' => 'monthly',
            'day_of_month' => $now->copy()->addDay()->day,
            'repeat_anchor' => 'schedule',
            'next_due_at' => $now->copy()->addDay()->setHour(16)->setMinute(0),
        ]);

        // 10. Yearly repeat
        Todo::create([
            'title' => 'Review Tahunan Blog',
            'description' => 'Review traffic dan konten populer.',
            'status' => 'active',
            'is_important' => false,
            'is_urgent' => false,
            'repeat_type' => 'yearly',
            'month_of_year' => $nextMonth->month,
            'repeat_anchor' => 'schedule',
            'next_due_at' => $nextMonth->copy()->setDay(1)->setHour(9)->setMinute(0),
        ]);

        // 11. End type count
        Todo::create([
            'title' => 'Fotokopi Dokumen',
            'description' => 'Fotokopi KTP, KK, ijazah.',
            'status' => 'active',
            'is_important' => false,
            'is_urgent' => true,
            'repeat_type' => 'interval',
            'interval_value' => 1,
            'interval_unit' => 'month',
            'repeat_anchor' => 'completion',
            'end_type' => 'count',
            'end_count' => 3,
            'completed_count' => 1,
            'next_due_at' => $nextMonth->copy()->setDay(5)->setHour(8)->setMinute(0),
        ]);

        // 12. Soft deleted todo
        $deletedTodo = Todo::create([
            'title' => 'Project Lama — Refactor Code',
            'description' => 'Refactor legacy codebase.',
            'status' => 'active',
            'is_important' => false,
            'is_urgent' => false,
            'repeat_type' => 'none',
            'next_due_at' => $lastMonth->copy()->addDays(10),
        ]);
        $deletedTodo->delete();

        // =====================================
        // TODO HISTORIES
        // =====================================

        // Completed yesterday (for overview calendar)
        TodoHistory::create([
            'todo_id' => $todo1->id,
            'due_at' => $now->copy()->subDay()->setHour(17)->setMinute(0),
            'completed_at' => $now->copy()->subDay()->setHour(16)->setMinute(30),
            'completion_note' => 'Selesai lebih awal.',
        ]);

        // Completed 3 days ago
        TodoHistory::create([
            'todo_id' => $todo1->id,
            'due_at' => $now->copy()->subDays(3)->setHour(17)->setMinute(0),
            'completed_at' => $now->copy()->subDays(3)->setHour(16)->setMinute(45),
            'completion_note' => 'Modul routing selesai.',
        ]);

        // Overdue completion
        TodoHistory::create([
            'todo_id' => $overdueTodo->id,
            'due_at' => $now->copy()->subDays(7)->setHour(12)->setMinute(0),
            'completed_at' => $now->copy()->subDays(5)->setHour(9)->setMinute(0),
            'completion_note' => 'Telat 2 hari.',
        ]);

        // Skipped
        TodoHistory::create([
            'todo_id' => $todo1->id,
            'due_at' => $now->copy()->subDays(5)->setHour(17)->setMinute(0),
            'skipped_at' => $now->copy()->subDays(5)->setHour(8)->setMinute(0),
        ]);

        // History for deleted todo
        TodoHistory::create([
            'todo_id' => $deletedTodo->id,
            'due_at' => $lastMonth->copy()->addDays(10)->setHour(9)->setMinute(0),
            'completed_at' => $lastMonth->copy()->addDays(12)->setHour(14)->setMinute(0),
            'completion_note' => 'Selesai.',
        ]);

        TodoHistory::create([
            'todo_id' => $deletedTodo->id,
            'due_at' => $lastMonth->copy()->subDays(5)->setHour(14)->setMinute(0),
            'skipped_at' => $lastMonth->copy()->subDays(5)->setHour(14)->setMinute(0),
        ]);

        // Completed today
        TodoHistory::create([
            'todo_id' => $todo1->id,
            'due_at' => $now->copy()->setHour(17)->setMinute(0),
            'completed_at' => $now->copy()->setHour(16)->setMinute(0),
            'completion_note' => 'Target hari ini tercapai.',
        ]);

        $this->command->info('✅ Database seeded!');
        $this->command->info('📅 Today: ' . $now->format('Y-m-d'));
        $this->command->info('📅 Last month: ' . $lastMonth->format('Y-m-d'));
        $this->command->info('📅 Next month: ' . $nextMonth->format('Y-m-d'));
    }
}
