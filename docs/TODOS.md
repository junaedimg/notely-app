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

UI hanya menampilkan field yang relevan berdasarkan pilihan `end_type`.

---

## Complete & Skip Behavior

Complete dan Skip redirect ke **halaman asal** (dashboard / todos index / todos show), bukan selalu ke todos index.

- Complete dari dashboard → redirect balik ke dashboard
- Complete dari todos index → redirect balik ke todos index

Ini diimplementasikan via hidden input `_redirect` yang dikirim oleh JS.

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

## Priority System

This application uses the Eisenhower Matrix instead of traditional priorities.

Instead of `Low / Medium / High`, the system asks two questions:

```
Is it Important?
Is it Urgent?
```

The quadrant is calculated automatically.

| Important | Urgent | Result    |
|-----------|--------|-----------|
| Yes       | Yes    | Do        |
| Yes       | No     | Plan      |
| No        | Yes    | Delegate  |
| No        | No     | Eliminate |

The quadrant is never stored in the database. It is always calculated.
