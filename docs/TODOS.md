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
