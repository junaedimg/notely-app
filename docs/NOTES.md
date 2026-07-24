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
