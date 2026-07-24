# Todos & Todo History

## Todos

Todos represent actions.

### Examples

- Pay internet bill
- Change engine oil
- Study Laravel
- Publish article

### Note Relationship

A Todo may exist independently or may belong to a Note.

```
Note

Belajar Laravel

↓

Todo

☐ Routing
☐ Controller
☐ Middleware
```

Todos answer one question:

> **What should I do?**

### Responsibilities

- Actions
- Scheduling
- Reminder
- Repeat
- Priority
- Status

---

## Repeat Types

| Type | Description | Additional Fields |
|------|-------------|-------------------|
| `none` | No repeat | — |
| `daily` | Every day | — |
| `weekly` | Every selected days | `days_of_week` (Mon–Sun checkboxes) |
| `monthly` | Every month on selected date | `day_of_month` |
| `yearly` | Every year in selected month | `month_of_year` |
| `interval` | Every N days/weeks/months/years | `interval_value` + `interval_unit` |

### Interval Details

```
repeat_type = interval
interval_value = 3
interval_unit = month
```

Artinya: **setiap 3 bulan sekali** (ganti oli, servis berkala, dll).

### Weekly Details

Scheduler menggunakan **nextWeekdayFromList**: mencari hari berikutnya dari daftar `days_of_week`.

Contoh: days = [Sabtu, Minggu]
- Complete Sabtu → next due Minggu
- Complete Minggu → next due Sabtu depan
- Complete Jumat → next due Sabtu (hari berikutnya dari daftar)

### end_type

| end_type | Field | Behavior |
|----------|-------|----------|
| `never` | — | Repeat forever |
| `date` | `end_date` | Stop after this date |
| `count` | `end_count` | Stop after N completions |

UI hanya menampilkan field yang relevan berdasarkan pilihan `end_type` (show/hide via JS).

---

## Complete & Skip Behavior

Complete dan Skip redirect ke **halaman asal** (dashboard / todos index / todos show), bukan selalu ke todos index.

- Complete dari dashboard → redirect balik ke dashboard
- Complete dari todos index → redirect balik ke todos index

Ini diimplementasikan via hidden input `_redirect` yang dikirim oleh JS.

### SweetAlert2 Confirmation

Saat klik Complete atau Skip, muncul modal SweetAlert2:

**Complete:**
- Title: ✅ Complete
- Menampilkan todo title + tanggal saat ini
- Input "Completion note (optional)"
- Tombol [Cancel] [Complete]

**Skip:**
- Title: ⏭ Skip
- Menampilkan todo title + tanggal saat ini
- Input "Reason (optional)"
- Tombol [Cancel] [Skip]

Semua tombol delete (todo, note, history) juga menggunakan SweetAlert2:
- Title: "Delete [item]?"
- "This action cannot be undone."
- Tombol [Cancel] [Delete] (merah)

---

## Soft Delete, Restore & Permanent Delete

Todos mendukung **soft delete**. Saat dihapus dari halaman index/show, data tidak langsung hilang — hanya diberi timestamp `deleted_at`.

### Alur

| Aksi | Behavior |
|------|----------|
| **Delete** (dari index/show) | Soft delete → data masuk ke tab **Trash** |
| **Restore** (dari tab Trash) | Kembalikan data ke daftar aktif |
| **Delete Permanently** (dari tab Trash) | Hapus data dari database secara permanen (`forceDelete`) |

### Tab Trash

Terdapat di halaman index todos: tab **Trash** menampilkan semua todos yang sudah di-soft-delete.
- Tombol **Restore** (icon `restore_from_trash`) — mengembalikan todo
- Tombol **Delete Permanently** (icon `delete_forever`) — hapus permanen dengan konfirmasi SweetAlert2
- Jika trash kosong, menampilkan pesan "Trash is empty."

---

## Soft Delete

Todos mendukung soft delete. Saat dihapus, data tidak langsung hilang dari database melainkan diberi timestamp `deleted_at`.

- Delete dari halaman todos → soft delete
- Data yang sudah dihapus tidak muncul di index, dashboard, atau calendar
- (Fitur restore akan datang)

---

## Status Count

Tab navigation di todos index menampilkan jumlah per status:

```
Active (12)     Paused (3)     Archived (5)
```

Angka di badge berwarna **primary-filled** saat tab aktif, **abu-abu** saat tidak aktif.

---

## Todo History

Todo History stores actual execution.

Todo stores the plan. History stores reality.

```
Todo

Due: 22 July
```

Reality:

```
Completed: 25 July
```

The Todo never changes. The History records what actually happened.

### Responsibilities

- Completion history
- Actual completion date
- Completion notes
- Skip records

---

## Priority System — Eisenhower Matrix

This application uses the Eisenhower Matrix instead of traditional priorities.

Instead of `Low / Medium / High`, the system asks two questions:

```
Is it Important?
Is it Urgent?
```

The quadrant is calculated automatically at runtime (computed attribute, never stored).

### Quadrant Mapping

| Important | Urgent | Quadrant | Label | Color |
|-----------|--------|----------|-------|-------|
| Yes | Yes | `do` | Do | 🔴 Red |
| Yes | No | `decide` | Decide | 🟡 Yellow |
| No | Yes | `delegate` | Delegate | 🟢 Green |
| No | No | `delete` | Delete | 🔵 Blue |

### Visual

Setiap card todo memiliki:
1. **Left border accent** — 4px solid color sesuai kuadran
2. **Badge** — rounded-full dengan icon + label per kuadran

Warna diambil dari `$todo->quadrant_color` (computed attribute di model), bukan disimpan di database.

### UI Components

```
Component: <x-quadrant :quadrant="$todo->quadrant" />
Color:      $todo->quadrant_color → hex color string
```
