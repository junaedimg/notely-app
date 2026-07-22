---
name: Kinetic Logic
colors:
  surface: '#fcf8ff'
  surface-dim: '#dbd9e1'
  surface-bright: '#fcf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f5f2fb'
  surface-container: '#f0ecf5'
  surface-container-high: '#eae7f0'
  surface-container-highest: '#e4e1ea'
  on-surface: '#1b1b21'
  on-surface-variant: '#464652'
  inverse-surface: '#303036'
  inverse-on-surface: '#f2eff8'
  outline: '#777683'
  outline-variant: '#c7c5d4'
  surface-tint: '#4f54b4'
  primary: '#15157d'
  on-primary: '#ffffff'
  primary-container: '#2e3192'
  on-primary-container: '#9da1ff'
  inverse-primary: '#c0c1ff'
  secondary: '#5d5f5c'
  on-secondary: '#ffffff'
  secondary-container: '#e2e3df'
  on-secondary-container: '#636562'
  tertiary: '#292828'
  on-tertiary: '#ffffff'
  tertiary-container: '#3f3e3e'
  on-tertiary-container: '#aba9a9'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e1e0ff'
  primary-fixed-dim: '#c0c1ff'
  on-primary-fixed: '#04006d'
  on-primary-fixed-variant: '#373a9b'
  secondary-fixed: '#e2e3df'
  secondary-fixed-dim: '#c6c7c3'
  on-secondary-fixed: '#1a1c1a'
  on-secondary-fixed-variant: '#454745'
  tertiary-fixed: '#e5e2e1'
  tertiary-fixed-dim: '#c8c6c5'
  on-tertiary-fixed: '#1c1b1b'
  on-tertiary-fixed-variant: '#474746'
  background: '#fcf8ff'
  on-background: '#1b1b21'
  surface-variant: '#e4e1ea'
typography:
  headline-lg:
    fontFamily: Geist
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg-mobile:
    fontFamily: Geist
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Geist
    fontSize: 20px
    fontWeight: '500'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 32px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-sm:
    fontFamily: Geist
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  unit: 8px
  container-max: 1200px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 40px
---

## Brand & Style

The design system is built on the duality of "Knowledge vs. Action." It serves a high-performance user base that requires a tool for deep thinking and rapid execution. The aesthetic is **High-Contrast Minimalism**: a rigorous, functional style that eliminates visual noise to prioritize cognitive clarity.

The brand personality is **Focused, Quiet, and Efficient**. It avoids the "gamified" feel of modern apps in favor of a digital workspace that feels like a high-end stationery set. The emotional response should be one of "structured calm"—providing the user with a sense of control over their information architecture and daily tasks.

## Colors

The palette uses high-contrast mapping to distinguish between two primary modes of work:
- **Action (Indigo):** Used exclusively for interactive elements, primary buttons, and task-related UI.
- **Knowledge (Cream/Grey):** Used for surfaces where information is consumed or edited, mimicking the feel of physical paper.

**Semantic Accents:**
The Eisenhower Matrix is represented through "Subtle Urgency" tones. These colors are used sparingly—only for indicators and flags—to prevent the interface from feeling loud or stressful.
- **Background:** A crisp off-white (#FAFAFA) keeps the UI feeling airy and open.
- **Text:** Deep charcoal (#1A1A1A) ensures maximum legibility for long-form note-taking.

## Typography

Typography is the core of this design system. We use **Geist** for structural elements (Headlines, Labels, UI Buttons) to provide a technical, modern edge. **Inter** is used for all body copy to ensure comfort during extended reading and writing sessions.

The "Knowledge" aspect of the app utilizes a generous **32px line height** for `body-lg`, creating a breathable environment for notes. All labels use a slight letter spacing and uppercase styling to clearly distinguish "metadata" from "content."

## Layout & Spacing

The system follows a **Fixed Grid** philosophy for desktop to maintain focus, while utilizing a fluid 4-column structure for mobile. 

- **Focus Mode:** In the note-taking view, the layout centers a single column (max 720px) to prevent eye strain and promote "Deep Work."
- **Dashboard:** A 12-column grid is used for the Eisenhower Matrix view, with 24px gutters to clearly separate "Action" quadrants.
- **Rhythm:** All spacing (padding, margins) must be increments of 8px. Use 40px margins on desktop to create a "gallery" feel for your data.

## Elevation & Depth

This design system avoids heavy shadows. Depth is communicated through **Tonal Layers** and **Low-Contrast Outlines**:

1.  **Level 0 (Base):** The #FAFAFA background.
2.  **Level 1 (Cards):** Cream surfaces (#F5F5F1) with a 1px solid border (#E5E5E0). No shadow.
3.  **Level 2 (Modals/Popovers):** White surfaces with a very soft, 10% opacity indigo shadow (4px blur) to suggest a slight lift from the paper.

Interactive elements do not use "glows." Instead, they use sharp state changes (e.g., a button moving from deep indigo to black on hover).

## Shapes

The shape language is **Soft (0.25rem)**. The system avoids aggressive "pill" shapes for primary containers to maintain a professional, architectural feel. 

- **Cards and Inputs:** Use the standard 4px (0.25rem) radius.
- **Status Indicators:** Use perfect circles (no radius) for a technical, "data-point" look.
- **Action Buttons:** May use a slightly larger 8px radius (`rounded-lg`) to make them more "clickable" and distinct from static content cards.

## Components

### Buttons
- **Primary (Action):** Solid Indigo background, White text. No border.
- **Secondary (Knowledge):** Cream background, Indigo text, 1px Indigo border.
- **Ghost:** Transparent background, Charcoal text. Used for low-priority navigation.

### Cards
Cards are the containers for both Notes and Todos. Notes use the Cream background to signify "Knowledge," while Todos are white with a thick left-accent border in Indigo.

### Status Indicators
- **Active:** Small Indigo dot.
- **Paused:** Small Grey dot.
- **Urgent/Important:** Subtle 1px bordered badges with text in the Eisenhower accent colors (Soft Red/Amber).

### Input Fields
Minimalist style. Only a bottom border (2px) that turns Indigo on focus. No background fill for inputs within notes to keep the experience feeling like "writing on paper."

### Eisenhower Matrix Flags
Small, vertical 4px bars on the left edge of list items, colored by quadrant priority. This allows for rapid scanning without cluttering the text.