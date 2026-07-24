# Scheduler

Recurring tasks are handled by a scheduler.

## Examples

- Every day
- Every Saturday
- Every month
- Every 3 months

The scheduler decides when a Todo should appear. The UI never displays raw database rows. Instead, it displays the result of scheduler calculation.

```
Todo: Study Laravel
Repeat: Every Saturday
```

If today is **Saturday**:

```
Today's Todo

☐ Study Laravel
```

If today is **Monday**:

```
Today's Todo

(empty)
```

---

## Repeat Strategy

Two scheduling strategies are supported.

### Schedule Based

The next occurrence is calculated from the planned schedule.

```
Due:        5 July
Completed:  7 July
Next Due:   5 August
```

Suitable for:

- Bills
- Monthly reports
- Meetings

---

### Completion Based

The next occurrence is calculated from the completion date.

```
Due:        22 July
Completed:  25 July
Next Due:   25 October
```

Suitable for:

- Vehicle maintenance
- Filter replacement
- Backup routines

---

## Weekly with Days of Week

When `repeat_type = weekly` and `days_of_week` is set, the scheduler uses **nextWeekdayFromList** instead of simple `addWeek()`.

Logic:

1. Get anchor day (ISO: 1=Mon, 7=Sun)
2. Sort `days_of_week` ascending
3. Find the next day in the list that is greater than anchor day
4. If none found, wrap to first day in list (next week)

```
days_of_week = [6, 7]  (Sabtu, Minggu)

Complete Sabtu (6)  → next = Minggu (7)   → same week
Complete Minggu (7) → next = Sabtu (6)    → next week
```

This ensures all selected days get their turn, not just one day per week.

---

## First Due Date

If `next_due_at` is empty:
- **No repeat**: todo appears on dashboard (via `whereNull('next_due_at') AND completed_count = 0`)
- **With repeat**: anchor falls back to `simulated_today()` for the first calculation
