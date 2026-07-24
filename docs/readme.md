# Documentation Guide

> **Starting point for developers and AI agents reading this project.**

This directory contains the design specification for **Notely** — a personal productivity application focused on knowledge management and task management.

---

## How to Read This Documentation

The docs are organized by domain, not by file type. Start with the module you need to understand, then dive deeper.

| Order | Document | What It Covers |
|-------|----------|----------------|
| 1 | [NOTES.md](NOTES.md) | Notes module — storing knowledge |
| 2 | [TODOS.md](TODOS.md) | Todos module — representing actions, scheduling, priorities |
| 3 | [SCHEDULER.md](SCHEDULER.md) | Recurring task scheduling logic |
| 4 | [DATABASE.md](DATABASE.md) | Database architecture and relationships |
| 5 | [ROADMAP.md](ROADMAP.md) | Current and future scope |

---

## Conventions Used

- **Computed fields** are never stored in the database — they are calculated at runtime (e.g., Eisenhower quadrant, next occurrence).
- **Single Responsibility** — each module owns one concern and does not leak into others.
- **Data is normalized** — rules, execution records, and calculations are kept separate.
- **Single user** — no authentication, no login/register, no user model.

---

## Key Concepts

```
Knowledge  → Notes       (passive, no deadlines)
Action     → Todos       (active, has schedule & priority)
History    → Histories   (records of actual execution)
```

Notes are optional parents of Todos. Todos are parents of Histories. The Scheduler and Eisenhower Matrix are service-level calculations, not stored state.

---

## For AI Agents

When asked to implement or modify a feature:

1. Read the relevant doc(s) first to understand intent.
2. Check `database/migrations/` and `app/Models/` for existing implementation.
3. Follow the separation rules — never mix knowledge, action, and history.
4. Computed values must be derived at runtime, never persisted.

---

## Project Status

This is a **partially implemented project**. Most core features (Notes CRUD, Todos CRUD, Scheduler, Eisenhower Matrix, History) are built. Auth/user layer has been removed — this is a single-user application.
