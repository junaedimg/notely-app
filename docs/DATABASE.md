# Database Schema

Database dibagi menjadi tiga tabel utama.

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

**Type**

```
bigint
```

Primary Key.

Identitas unik setiap Note.

---

### title

**Type**

```
varchar(255)
```

Judul Note.

Contoh

```
Belajar Laravel

Catatan Servis Motor

Ide Blog
```

---

### content

**Type**

```
longtext
```

Isi Note.

Mendukung Markdown atau HTML.

---

### is_pinned

**Type**

```
boolean
```

Menentukan apakah Note dipin agar muncul di bagian atas.

Default

```
false
```

---

### color

**Type**

```
varchar(20)
```

Warna Note.

Digunakan hanya untuk tampilan.

Contoh

```
yellow

blue

green
```

---

### created_at

Timestamp saat Note dibuat.

---

### updated_at

Timestamp terakhir Note diubah.

---

# Table: todos

## Purpose

Menyimpan pekerjaan atau aksi.

Todo merupakan pusat seluruh aktivitas aplikasi.

Todo mendukung:

- Reminder
- Repeat
- Scheduler
- Eisenhower Matrix
- Status
- Riwayat Penyelesaian

Todo dapat berdiri sendiri maupun berasal dari sebuah Note.

---

## Fields

### id

Primary Key.

Identitas unik Todo.

---

### note_id

Foreign Key ke tabel Notes.

Nullable.

Jika NULL berarti Todo berdiri sendiri.

Contoh

```
☐ Bayar Internet
```

Tidak berasal dari Note.

Sedangkan

```
Note

Belajar Laravel

↓

Todo

☐ Routing

☐ Controller
```

berarti Todo memiliki note_id.

---

### title

Judul Todo.

Contoh

```
Bayar Internet

Belajar Laravel

Ganti Oli
```

---

### description

Deskripsi tambahan Todo.

Opsional.

---

### status

Menentukan apakah Todo masih aktif.

Possible values

```
active

paused

archived
```

Meaning

active

Scheduler berjalan.

paused

Scheduler mengabaikan Todo.

archived

Todo sudah tidak digunakan lagi.

---

### is_important

Boolean.

Menandakan apakah Todo termasuk pekerjaan penting.

Digunakan oleh Eisenhower Matrix.

---

### is_urgent

Boolean.

Menandakan apakah Todo termasuk pekerjaan mendesak.

Digunakan oleh Eisenhower Matrix.

---

### repeat_type

Menentukan pola pengulangan Todo.

Possible values

```
none

daily

weekly

monthly

yearly

interval
```

---

### interval_value

Digunakan hanya jika

```
repeat_type = interval
```

Contoh

```
3
```

Artinya

```
Setiap 3 bulan
```

---

### interval_unit

Satuan interval.

Possible values

```
day

week

month

year
```

---

### days_of_week

Digunakan hanya untuk repeat mingguan.

Format

```
JSON Array
```

Contoh

```
[1,3,5]
```

Artinya

```
Senin

Rabu

Jumat
```

---

### day_of_month

Digunakan untuk repeat bulanan.

Contoh

```
15
```

Artinya

```
Setiap tanggal 15
```

---

### month_of_year

Digunakan untuk repeat tahunan.

Contoh

```
7
```

Artinya

```
Juli
```

---

### repeat_anchor

Menentukan dasar perhitungan repeat berikutnya.

Possible values

```
schedule

completion
```

schedule

Repeat dihitung berdasarkan jadwal sebelumnya.

completion

Repeat dihitung berdasarkan tanggal penyelesaian.

---

### end_type

Menentukan kapan repeat berhenti.

Possible values

```
never

date

count
```

---

### end_date

Digunakan jika

```
end_type = date
```

Repeat berhenti setelah tanggal tersebut.

---

### end_count

Digunakan jika

```
end_type = count
```

Repeat berhenti setelah selesai sebanyak N kali.

---

### completed_count

Jumlah penyelesaian Todo.

Scheduler menggunakan field ini untuk menentukan apakah repeat masih berlaku.

---

### next_due_at

Tanggal Todo berikutnya muncul.

Nilai ini dihitung oleh Scheduler.

UI menggunakan field ini untuk menampilkan Today's Todo.

---

### reminder_time

Jam reminder.

Opsional.

Contoh

```
08:00

20:30
```

---

### paused_until

Jika Todo di-pause sementara.

Setelah waktu ini Scheduler akan mengaktifkan kembali Todo.

---

### created_at

Timestamp Todo dibuat.

---

### updated_at

Timestamp terakhir Todo diperbarui.

---

# Table: todo_histories

## Purpose

Menyimpan riwayat penyelesaian Todo.

Todo menyimpan aturan.

History menyimpan hasil eksekusi.

Satu Todo dapat memiliki banyak History.

---

## Fields

### id

Primary Key.

---

### todo_id

Foreign Key ke Todo.

Menunjukkan Todo mana yang diselesaikan.

---

### due_at

Jadwal seharusnya Todo dikerjakan.

Contoh

```
22 Juli
```

---

### completed_at

Tanggal Todo benar-benar selesai.

Contoh

```
25 Juli
```

---

### skipped_at

Tanggal Todo dilewati.

Digunakan jika user memilih Skip.

---

### completion_note

Catatan ketika menyelesaikan Todo.

Contoh

```
Servis dilakukan di AHASS.

Mengganti oli dan filter.
```

---

### created_at

Timestamp riwayat dibuat.

---

# Relationships

```
Notes

1
│
│
├────────────── N Todos

Todos

1
│
│
├────────────── N Todo Histories
```

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
