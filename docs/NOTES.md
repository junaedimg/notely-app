# Notes

Notes are used to store information.

## Examples

- Learning notes
- Daily knowledge
- Documentation
- Vehicle maintenance records
- Personal ideas

## Characteristics

A Note is **passive**. It never contains scheduling logic.

A Note does **not** know about:

- Reminder
- Deadline
- Repeat
- Priority
- Task status

Notes simply answer one question:

> **What do I know?**

## Responsibilities

**Responsible for:**

- Knowledge
- Documentation
- Ideas
- Learning notes

**Not responsible for:**

- Reminder
- Schedule
- Repeat
- Priority
- Progress
- Deadline

---

## Pin / Unpin

Pin toggle tersedia langsung dari halaman **index** dan **show** tanpa harus masuk form edit.

- Klik icon pin di pojok kanan card → toggle `is_pinned`
- Pinned notes muncul di urutan atas di index dan di dashboard
- State visual: filled (primary) = pinned, outline (secondary) = unpinned

---

## Soft Delete

Notes mendukung soft delete. Saat dihapus, data tidak langsung hilang dari database melainkan diberi timestamp `deleted_at`.

- Delete dari halaman show → soft delete
- Data yang sudah dihapus tidak muncul di index atau dashboard
- (Fitur restore akan datang)

---

## Color

Notes memiliki field `color` dengan nilai: `none`, `yellow`, `blue`, `green`, `red`, `purple`.

### Form create/edit — Swatch Picker
Dropdown color diganti dengan visual swatch picker:
- Lingkaran 24px dengan warna solid
- None: lingkaran dashed border
- Klik → icon check muncul + outline primary
- Value disimpan di hidden input `name="color"`

### Index card — Color Accent
Setiap card note menampilkan warna sebagai:
- **Left border** 4px solid sesuai warna
- **Background tint** tipis sesuai warna (e.g., yellow → `#fefce8`)
- Jika `color = none`, card white tanpa border kiri

### Dashboard pinned notes
Sama seperti index — menggunakan component `x-note-card` yang konsisten.

### Color yang tersedia

| Name | Hex | Background Tint |
|------|-----|----------------|
| Yellow | `#f59e0b` | `#fefce8` |
| Blue | `#3b82f6` | `#eff6ff` |
| Green | `#22c55e` | `#f0fdf4` |
| Red | `#ef4444` | `#fef2f2` |
| Purple | `#a855f7` | `#faf5ff` |
