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
