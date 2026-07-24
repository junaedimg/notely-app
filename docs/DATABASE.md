# Database Schema

Database dibagi menjadi tiga tabel utama + tabel sistem Laravel.

```
Notes
    │
    │ optional
    ▼
Todos
    │
    ▼
Todo Histories
```

---

# Table: notes

## Purpose

Menyimpan informasi atau pengetahuan.

Notes bersifat pasif dan **tidak memiliki** konsep reminder, repeat, deadline, status, maupun scheduler.

Contoh:

- Catatan belajar
- Dokumentasi
- Ide artikel
- Catatan kendaraan

---

## Fields

### id

**Type**: bigint

Primary Key. Identitas unik setiap Note.

---

### title

**Type**: varchar(255)

Judul Note.

```
Belajar Laravel
Catatan Servis Motor
Ide Blog
```

---

### content

**Type**: longtext

Isi Note. Mendukung Markdown atau HTML.

---

### is_pinned

**Type**: boolean

Default: `false`

Menentukan apakah Note dipin agar muncul di bagian atas. Bisa di-toggle langsung dari index/show tanpa masuk form edit.

---

### deleted_at

**Type**: timestamp (nullable)

Soft delete. Data tidak langsung hilang saat dihapus, hanya ditandai dengan timestamp.

---

### color

**Type**: varchar(20)

Warna Note. Digunakan hanya untuk tampilan.

```
yellow, blue, green, red, purple
```

---

### created_at / updated_at

Timestamp standar Laravel.

---

# Table: todos

## Purpose

Menyimpan pekerjaan atau aksi. Todo merupakan pusat seluruh aktivitas aplikasi.

Todo mendukung:
- Reminder
- Repeat (daily, weekly, monthly, yearly, interval)
- Scheduler
- Eisenhower Matrix
- Status (active, paused, archived)
- Riwayat Penyelesaian

Todo dapat berdiri sendiri maupun berasal dari sebuah Note.

---

## Fields

### id

Primary Key. Identitas unik Todo.

---

### note_id

Foreign Key ke tabel Notes. Nullable. Jika NULL berarti Todo berdiri sendiri.

---

### title

**Type**: varchar(255)

Judul Todo.

---

### description

**Type**: text (nullable)

Deskripsi tambahan Todo.

---

### status

**Type**: varchar(20), default: `active`

| Value | Meaning |
|-------|---------|
| `active` | Scheduler berjalan |
| `paused` | Scheduler mengabaikan Todo |
| `archived` | Todo sudah tidak digunakan lagi |

---

### is_important / is_urgent

**Type**: boolean

Digunakan oleh Eisenhower Matrix. Quadrant dihitung runtime, tidak disimpan.

---

### repeat_type

**Type**: varchar(20), default: `none`

| Value | Description |
|-------|-------------|
| `none` | No repeat |
| `daily` | Every day |
| `weekly` | Selected days of week |
| `monthly` | Selected day of month |
| `yearly` | Selected month of year |
| `interval` | Every N days/weeks/months/years |

---

### interval_value / interval_unit

Digunakan jika `repeat_type = interval`.

- `interval_value`: integer (e.g. 3)
- `interval_unit`: day / week / month / year

Contoh: `interval_value=3, interval_unit=month` → setiap 3 bulan.

---

### days_of_week

Digunakan jika `repeat_type = weekly`. Format: JSON Array.

```
[1,3,5]  → Senin, Rabu, Jumat
```

Scheduler mencari hari berikutnya dari daftar ini, bukan `addWeek()`.

---

### day_of_month

Digunakan untuk repeat bulanan. Contoh: `15` → setiap tanggal 15.

---

### month_of_year

Digunakan untuk repeat tahunan. Contoh: `7` → Juli.

---

### repeat_anchor

**Type**: varchar(20), default: `schedule`

| Value | Behavior |
|-------|----------|
| `schedule` | Next due dihitung dari jadwal sebelumnya |
| `completion` | Next due dihitung dari tanggal selesai |

---

### end_type / end_date / end_count

| end_type | Required Field | Behavior |
|----------|---------------|----------|
| `never` | — | Repeat forever |
| `date` | `end_date` | Stop after this date |
| `count` | `end_count` | Stop after N completions |

Form hanya menampilkan field yang relevan (show/hide via JS).

---

### completed_count

**Type**: integer, default: 0

Jumlah penyelesaian Todo. Digunakan scheduler untuk mengecek `end_type = count`.

---

### next_due_at

**Type**: timestamp (nullable)

Tanggal Todo berikutnya muncul. Dihitung oleh Scheduler.

Jika NULL dan `completed_count = 0`, todo muncul di dashboard sebagai todo baru.

---

### reminder_time

**Type**: varchar(5) (nullable)

Jam reminder. Format: `08:00`, `20:30`. (Fitur akan datang)

---

### paused_until

**Type**: timestamp (nullable)

Jika Todo di-pause sementara. Setelah waktu ini scheduler akan mengaktifkan kembali Todo.

---

### created_at / updated_at

Timestamp standar.

---

### deleted_at

**Type**: timestamp (nullable)

Soft delete untuk Todo.

---

# Table: todo_histories

## Purpose

Menyimpan riwayat penyelesaian Todo. Satu Todo dapat memiliki banyak History.

## Fields

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary Key |
| `todo_id` | bigint FK | Foreign Key ke Todo |
| `due_at` | timestamp | Jadwal seharusnya |
| `completed_at` | timestamp | Tanggal selesai |
| `skipped_at` | timestamp | Tanggal dilewati |
| `completion_note` | text | Catatan penyelesaian |
| `created_at` | timestamp | Waktu riwayat dibuat |
| `updated_at` | timestamp | Timestamp update |

---

# System Tables

### sessions

Digunakan oleh Laravel session handler. Tidak terkait user.

| Field | Type |
|-------|------|
| `id` | varchar (PK) |
| `user_id` | varchar (nullable) — tidak digunakan |
| `ip_address` | varchar(45) |
| `user_agent` | text |
| `payload` | longtext |
| `last_activity` | integer |

### cache / cache_locks / jobs / job_batches / failed_jobs

Default Laravel tables. Tidak ada customisasi.

---

# Relationships

```
Notes
1
│
├────────────── N Todos

Todos
1
│
├────────────── N Todo Histories
```

Tidak ada tabel Users. Aplikasi single-user tanpa autentikasi.

---

# Database Rules

- Notes hanya menyimpan pengetahuan.
- Todo hanya menyimpan aturan pekerjaan.
- Todo History hanya menyimpan riwayat penyelesaian.
- Todo boleh tidak memiliki Note.
- Satu Note boleh memiliki banyak Todo.
- Satu Todo boleh memiliki banyak History.
- Scheduler hanya membaca data dari tabel Todos.
- Todo History tidak pernah digunakan untuk menentukan jadwal berikutnya secara langsung, kecuali jika `repeat_anchor = completion`.
- Eisenhower Matrix dihitung dari `is_important` dan `is_urgent`.
- Quadrant **tidak disimpan** di database.
- UI menampilkan hasil perhitungan Scheduler, bukan seluruh data Todo.
- Notes & Todos mendukung **soft delete** (`deleted_at`). Data tidak langsung hilang.
- Halaman index & dashboard secara otomatis mengecualikan data yang sudah di-soft-delete.
