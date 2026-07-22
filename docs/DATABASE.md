# Database

## Architecture

```
+-----------+
|   Notes   |
+-----------+
      │
      │ optional
      ▼
+-----------+
|   Todos   |
+-----------+
      │
      ▼
+-------------------+
| Todo Histories    |
+-------------------+
```

## Design Principles

### Single Responsibility Principle

Each module has one responsibility.

- Notes manage knowledge.
- Todos manage actions.
- Histories manage execution.

### Keep Data Normalized

Data should never be duplicated.

- Todo stores rules.
- History stores execution.
- The scheduler calculates future occurrences.
