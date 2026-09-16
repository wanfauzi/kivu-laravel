# KIVU Design System

> **Official visual reference for the KIVU website and product
> interface.**
>
> KIVU is a modern marketplace that connects talented students with UMKM
> for real-world projects and collaboration.

------------------------------------------------------------------------

## 1. Purpose

This document is the single visual reference for building and
maintaining the KIVU website.

Use this design system for:

-   Landing page
-   Login and registration
-   Student marketplace
-   UMKM marketplace
-   Project detail
-   Talent profile
-   Student dashboard
-   UMKM dashboard
-   Admin dashboard
-   Forms and transactional interfaces
-   Future KIVU web and product pages

### Core principle

Every new page or component should feel like it belongs to the same KIVU
product.

**KIVU visual formula:**

`Modern + Friendly + Professional + Youthful + Trustworthy`

------------------------------------------------------------------------

# 2. Brand Direction

## 2.1 Brand Personality

  -----------------------------------------------------------------------
  Character                           Design implication
  ----------------------------------- -----------------------------------
  Modern                              Clean layouts, contemporary
                                      typography, restrained decoration

  Friendly                            Rounded components, approachable
                                      imagery, simple copy

  Professional                        Strong hierarchy, consistent
                                      spacing, clear information

  Youthful                            Blue + yellow identity, energetic
                                      accents

  Trustworthy                         High contrast, predictable
                                      interactions, structured UI

  Collaborative                       Student + UMKM imagery, connection
                                      and workflow visuals
  -----------------------------------------------------------------------

## 2.2 Brand Message

### Primary

**Talenta Muda untuk UMKM yang Lebih Maju**

### Supporting

**Bersama KIVU, Lebih Banyak Peluang untuk Indonesia.**

------------------------------------------------------------------------

# 3. Color System

## 3.1 Primary Blue

Blue is KIVU's primary brand color.

  Token           HEX         Usage
  --------------- ----------- --------------------------------------
  `primary-900`   `#063B82`   Main headings, strong text
  `primary-800`   `#06499B`   Dark brand surfaces
  `primary-700`   `#075DC5`   Main brand color
  `primary-600`   `#0875E1`   Interactive elements
  `primary-500`   `#1688F5`   Links, focus, secondary interactions
  `primary-100`   `#E6F3FF`   Soft blue surfaces
  `primary-50`    `#F3F9FF`   Section backgrounds

**Primary brand:** `#075DC5`

### Usage rules

Use blue for:

-   Logo
-   Main headings
-   Navigation active state
-   Links
-   Primary icons
-   Primary product actions
-   Information states

------------------------------------------------------------------------

## 3.2 Secondary Yellow

Yellow is KIVU's action and emphasis color.

  Token          HEX         Usage
  -------------- ----------- -----------------------
  `yellow-900`   `#B77900`   Dark yellow text
  `yellow-700`   `#E8A900`   Hover/active
  `yellow-600`   `#F5B900`   Strong accent
  `yellow-500`   `#FFD02F`   Primary CTA
  `yellow-100`   `#FFF5CC`   Soft yellow surface
  `yellow-50`    `#FFFBEB`   Very subtle highlight

**Secondary brand:** `#FFD02F`

### Usage rules

Use yellow for:

-   Primary CTA buttons
-   Highlighted words
-   Important emphasis
-   Selected promotional elements
-   Ratings/stars where appropriate
-   Small accent shapes

Do not use yellow as the dominant background for large page areas.

------------------------------------------------------------------------

## 3.3 Neutral

  Token           HEX         Usage
  --------------- ----------- -----------------------
  `neutral-950`   `#0B1F3A`   Strongest text
  `neutral-900`   `#102A43`   Primary body text
  `neutral-800`   `#243B53`   Secondary heading
  `neutral-700`   `#486581`   Secondary text
  `neutral-600`   `#627D98`   Supporting text
  `neutral-500`   `#829AB1`   Placeholder/muted
  `neutral-400`   `#9FB3C8`   Disabled/subtle
  `neutral-300`   `#CBD5E1`   Borders
  `neutral-200`   `#E2E8F0`   Card borders/dividers
  `neutral-100`   `#F1F5F9`   Soft surfaces
  `neutral-50`    `#F8FAFC`   Very light surfaces
  `white`         `#FFFFFF`   Main background

------------------------------------------------------------------------

## 3.4 Semantic Colors

  Semantic   Color       Background
  ---------- ----------- ------------
  Success    `#16A34A`   `#DCFCE7`
  Warning    `#F59E0B`   `#FEF3C7`
  Error      `#DC2626`   `#FEE2E2`
  Info       `#0EA5E9`   `#E0F2FE`

Use semantic colors consistently for validation, status, alerts, and
feedback.

------------------------------------------------------------------------

# 4. Typography

## 4.1 Font

### Primary font

**Plus Jakarta Sans**

Use the same font family throughout the product.

Recommended fallback:

``` text
Plus Jakarta Sans, Inter, Arial, sans-serif
```

Avoid mixing unrelated typefaces within the same interface.

------------------------------------------------------------------------

## 4.2 Type Scale

  Style             Size   Line height     Weight Usage
  --------------- ------ ------------- ---------- -----------------------
  Display XL        48px          56px   700--800 Hero
  H1                40px          48px        700 Main page heading
  H2                32px          40px        700 Section heading
  H3                24px          32px        700 Card/group heading
  H4                20px          28px        700 Small heading
  Body Large        18px          28px   400--500 Intro/supporting text
  Body              16px          24px        400 Standard text
  Body Small        14px          20px   400--500 Supporting text
  Caption           12px          18px        500 Metadata
  Button Large      16px          24px        600 Large CTA
  Button Medium     14px          20px        600 Standard CTA

### Typography rules

-   Headings use `primary-900` or `neutral-900`.
-   Body text should normally use `neutral-700`.
-   Muted metadata uses `neutral-500` or `neutral-600`.
-   Use yellow to highlight important words, not entire paragraphs.
-   Keep line lengths readable.
-   Do not use excessive font weights.

------------------------------------------------------------------------

# 5. Spacing System

KIVU uses a 4px-based spacing system.

  Token          Value
  ------------ -------
  `space-1`        4px
  `space-2`        8px
  `space-3`       12px
  `space-4`       16px
  `space-5`       20px
  `space-6`       24px
  `space-8`       32px
  `space-10`      40px
  `space-12`      48px
  `space-16`      64px
  `space-20`      80px
  `space-24`      96px
  `space-30`     120px

### Common usage

``` text
Card padding:       20–24px
Button padding:     12–16px
Card gap:           16–24px
Heading to body:    12–16px
Body to CTA:        24–32px
Section spacing:    64–96px
```

Prefer existing spacing tokens over arbitrary values.

------------------------------------------------------------------------

# 6. Layout & Grid

## 6.1 Desktop

``` text
Max content width: 1200px
Horizontal padding: 24px
```

## 6.2 Tablet

``` text
Max content width: 960px
Horizontal padding: 24px
```

## 6.3 Mobile

``` text
Horizontal padding: 16–20px
```

### Layout principle

Use a centered content container for marketing and marketplace pages.

``` text
┌───────────────────────────────────────────────┐
│                 Full viewport                 │
│                                               │
│      ┌─────────────────────────────┐          │
│      │       1200px container      │          │
│      │                             │          │
│      └─────────────────────────────┘          │
│                                               │
└───────────────────────────────────────────────┘
```

Avoid excessively wide text blocks.

------------------------------------------------------------------------

# 7. Border Radius

KIVU uses rounded interfaces as a core visual characteristic.

  Token             Value Usage
  --------------- ------- ----------------------
  `radius-xs`         6px Small controls
  `radius-sm`         8px Buttons/small inputs
  `radius-md`        12px Standard controls
  `radius-lg`        16px Cards
  `radius-xl`        20px Large cards
  `radius-2xl`       24px Feature surfaces
  `radius-full`     999px Pills/avatars

### Rules

-   Standard card: `16px`
-   Large feature card: `20–24px`
-   Button: `8–12px`
-   Badge: `999px`
-   Avatar: `999px`

------------------------------------------------------------------------

# 8. Shadow System

Shadows should be subtle.

## Shadow XS

``` css
0 1px 3px rgba(15, 23, 42, 0.06)
```

## Shadow SM

``` css
0 4px 12px rgba(15, 23, 42, 0.08)
```

## Shadow MD

``` css
0 8px 24px rgba(15, 23, 42, 0.10)
```

## Shadow LG

``` css
0 16px 40px rgba(15, 23, 42, 0.12)
```

### Rule

Prefer:

**Border + subtle shadow**

over:

**Heavy shadow + no border**

KIVU should feel lightweight and clean.

------------------------------------------------------------------------

# 9. Buttons

## 9.1 Primary Button

Use for the most important action.

Example:

**Mulai Sekarang →**

``` text
Background: #FFD02F
Text: #063B82
Radius: 10px
Height: 44–48px
Padding: 16–20px
Weight: 600
```

Hover:

``` text
Background: #F5B900
```

------------------------------------------------------------------------

## 9.2 Blue Action Button

Use for transactional product actions.

Example:

**Lamar Proyek**

``` text
Background: #075DC5
Text: #FFFFFF
Radius: 10px
```

------------------------------------------------------------------------

## 9.3 Secondary Button

Example:

**Tonton Video**

``` text
Background: #FFFFFF
Border: 1px solid #1688F5
Text: #075DC5
Radius: 10px
```

------------------------------------------------------------------------

## 9.4 Ghost Button

``` text
Background: transparent
Text: #075DC5
```

Example:

**Lihat Semua Proyek →**

------------------------------------------------------------------------

## Button rules

-   One dominant CTA per visual area where possible.
-   CTA text should start with a clear action.
-   Use icons only when they add meaning.
-   Keep button height consistent.
-   Never use multiple competing yellow CTAs in the same small area.

------------------------------------------------------------------------

# 10. Input System

## Default

``` text
Height: 44–48px
Background: #FFFFFF
Border: #CBD5E1
Radius: 10px
```

## Focus

``` text
Border: #1688F5
Focus ring:
0 0 0 3px rgba(22, 136, 245, 0.12)
```

## Error

``` text
Border: #DC2626
```

## Placeholder

``` text
#829AB1
```

### Input principles

-   Labels should be visible.
-   Placeholder text must not replace labels for important forms.
-   Error messages should appear close to the affected field.
-   Search fields may use leading search icons.

------------------------------------------------------------------------

# 11. Navigation

## Desktop Header

``` text
KIVU

Beranda
Tentang
Cara Kerja
Kategori
Testimoni
FAQ

                         Masuk
                         Daftar Sekarang →
```

Recommended height:

``` text
72–80px
```

### Navigation states

Default:

``` text
#486581
```

Active:

``` text
#075DC5
font-weight: 600
```

Hover:

``` text
#075DC5
```

### Mobile

Use:

``` text
Logo + hamburger menu
```

Do not force the full desktop navigation onto mobile.

------------------------------------------------------------------------

# 12. Section System

The landing page alternates between white and soft-blue sections.

## White

``` text
#FFFFFF
```

## Soft Blue

``` text
#F3F9FF
```

Use soft blue for:

-   About KIVU
-   How KIVU works
-   Supporting information
-   Selected feature areas

Avoid adding a background color to every section.

------------------------------------------------------------------------

# 13. Section Header

Standard structure:

``` text
EYEBROW

Main heading

Supporting description
```

Example:

``` text
KATEGORI PROYEK

Berbagai Kategori untuk Berbagai Kebutuhan

Temukan proyek sesuai minat dan keahlianmu.
```

### Eyebrow

``` text
12–14px
Uppercase
Semibold
#1688F5
```

------------------------------------------------------------------------

# 14. Hero System

The hero is the main visual entry point.

## Structure

``` text
┌──────────────────────────────────────────────────────┐
│                                                      │
│  Eyebrow                       Human illustration    │
│                                                      │
│  Talenta Muda                                       │
│  untuk UMKM                                         │
│  yang Lebih Maju                                    │
│                                                      │
│  Supporting text                                    │
│                                                      │
│  [Mulai Sekarang] [Tonton Video]                   │
│                                                      │
│  ✓ Mudah Digunakan  ✓ Aman  ✓ Berdampak Nyata      │
│                                                      │
└──────────────────────────────────────────────────────┘
```

### Hero rules

-   Heading must be immediately understandable.
-   Highlight important words with KIVU yellow.
-   Use a strong human visual on desktop.
-   Keep the CTA visible without scrolling where practical.
-   Do not overload the hero with decorative elements.

------------------------------------------------------------------------

# 15. Card System

Cards are fundamental to the KIVU marketplace.

## 15.1 Standard Card

``` text
Background: #FFFFFF
Border: #E2E8F0
Radius: 16px
Padding: 20–24px
Shadow: Shadow XS/SM
```

------------------------------------------------------------------------

## 15.2 Project Card

Recommended structure:

``` text
┌──────────────────────────┐
│                          │
│      Project Image       │
│                          │
├──────────────────────────┤
│ [Desain Grafis]       ♡  │
│                          │
│ Desain Konten Instagram  │
│ untuk UMKM F&B           │
│                          │
│ Avatar  Nabila Putri     │
│ ★ 4.9 (12)               │
│                          │
│ Rp 150.000    ◷ 3 hari   │
└──────────────────────────┘
```

### Specification

``` text
Width: 270–285px
Radius: 16px
Border: #E2E8F0
Background: #FFFFFF
Padding: 16px
```

Project image:

``` text
Aspect ratio: 16:9
Radius: 12px
Object-fit: cover
```

### Information hierarchy

1.  Category
2.  Project title
3.  Owner/talent information
4.  Rating
5.  Price
6.  Timeline

------------------------------------------------------------------------

# 16. Talent Card

Recommended structure:

``` text
Avatar
Name
University
Primary skill
Rating
Completed projects
Starting price
```

Keep the name and primary skill visually dominant.

------------------------------------------------------------------------

# 17. Category Card

Categories include:

-   Desain Grafis
-   Pengembangan Web
-   Digital Marketing
-   Penulisan
-   Foto & Video
-   Lainnya

### Specification

``` text
Background: #FFFFFF
Border: #E2E8F0
Radius: 16px
Padding: 20px
```

Icon container:

``` text
64 × 64px
Circular
```

Icon:

``` text
24–28px
```

------------------------------------------------------------------------

# 18. Badge / Tag System

Use pill-shaped tags.

## Blue

``` text
Background: #E6F3FF
Text: #075DC5
```

## Yellow

``` text
Background: #FFF5CC
Text: #B77900
```

## Purple

``` text
Background: #F3E8FF
Text: #7E22CE
```

## Green

``` text
Background: #DCFCE7
Text: #15803D
```

### Rule

Use category badges for classification, not decoration.

------------------------------------------------------------------------

# 19. Rating System

Standard rating:

``` text
★ 4.9 (12)
```

Star:

``` text
#F5B900
```

Rating number:

``` text
#102A43
```

Review count:

``` text
#829AB1
```

Do not make ratings visually stronger than the project title.

------------------------------------------------------------------------

# 20. Avatar System

  Size     Value Usage
  ------ ------- -----------------
  XS        24px Inline metadata
  SM        32px Compact list
  MD        40px Standard card
  LG        48px Profile
  XL        64px Feature/profile

Use circular avatars.

For avatar groups:

``` text
Overlap: approximately 8px
Border: 2px solid #FFFFFF
```

------------------------------------------------------------------------

# 21. Statistic Component

Used for metrics such as:

``` text
1.200+ Mahasiswa Terdaftar
300+ UMKM Bergabung
500+ Proyek Selesai
98% Tingkat Kepuasan
```

Structure:

``` text
Icon

Large number

Description
```

Number:

``` text
28–32px
Weight: 700
Color: #063B82
```

------------------------------------------------------------------------

# 22. Testimonial Card

Structure:

``` text
┌─────────────────────────────────────┐
│ Avatar                              │
│                                     │
│ "KIVU memberi saya kesempatan..."   │
│                                     │
│ Rafi Ahmad                          │
│ Mahasiswa Universitas Indonesia     │
│                                     │
│ ★★★★★                              │
└─────────────────────────────────────┘
```

Specification:

``` text
Background: #FFFFFF
Border: #E2E8F0
Radius: 16px
Padding: 20–24px
```

------------------------------------------------------------------------

# 23. Icon System

Use one icon family consistently.

Recommended:

-   Lucide
-   Phosphor
-   Tabler

Preferred style:

**Rounded + outline**

### Icon sizes

``` text
16px — inline
20px — button
24px — standard
32px — feature
40px — category
48–56px — visual feature
```

Avoid mixing multiple icon styles on the same page.

------------------------------------------------------------------------

# 24. Illustration & Photography

KIVU uses:

**Semi-realistic human photography + clean UI floating cards.**

Preferred subjects:

-   Students
-   UMKM owners
-   Laptop/workspace
-   Real project collaboration
-   Digital work
-   Product/business activity

### Visual style

Professional but youthful.

### Avoid

-   Childish cartoon aesthetics
-   Excessively corporate stock photography
-   Overly complex 3D scenes
-   Heavy visual effects
-   Excessive gradients

------------------------------------------------------------------------

# 25. Decorative Shapes

KIVU may use subtle abstract shapes inspired by the logo.

Examples:

-   Blue blobs
-   Yellow arcs
-   Soft blue circles
-   Yellow highlights

Recommended opacity:

``` text
10–25%
```

### Rule

Decorative shapes should support the content, never compete with it.

------------------------------------------------------------------------

# 26. Marketplace Visual Language

Because KIVU is a marketplace, all product interfaces should
communicate:

``` text
Temukan
   ↓
Pilih
   ↓
Kolaborasi
   ↓
Selesaikan
   ↓
Dapatkan pengalaman
```

Important marketplace components:

``` text
Project Card
Talent Card
Search
Filter
Category
Rating
Price
Deadline
Status
Profile
Review
Application
```

------------------------------------------------------------------------

# 27. Project Status

Recommended statuses:

  Status        Meaning
  ------------- ---------------------------
  Open          Project available
  Pending       Waiting for confirmation
  In Progress   Currently being worked on
  Completed     Project finished
  Cancelled     Project cancelled

Use semantic colors consistently.

------------------------------------------------------------------------

# 28. Responsive System

## Breakpoints

``` text
Mobile:        < 640px
Tablet:        640–1024px
Desktop:       1024–1280px
Large Desktop: > 1280px
```

## Mobile principles

### Hero

``` text
Desktop: 2 columns
Mobile: 1 column
```

### Project grid

``` text
Desktop: 4 columns
Tablet:  2–3 columns
Mobile:  1 column
```

### Category grid

``` text
Desktop: 6 columns
Tablet:  3 columns
Mobile:  2 columns
```

### Navigation

``` text
Desktop: full navigation
Mobile: logo + hamburger
```

Do not simply shrink desktop layouts. Reflow the information hierarchy
for mobile.

------------------------------------------------------------------------

# 29. Accessibility

KIVU interfaces should be usable and readable.

### Rules

-   Maintain sufficient text/background contrast.
-   Do not communicate important states using color alone.
-   Buttons must have clear labels.
-   Interactive elements need visible focus states.
-   Images should have meaningful alternative text where appropriate.
-   Touch targets should be comfortably tappable.
-   Avoid very small body text for important information.

------------------------------------------------------------------------

# 30. Content & Copy Style

KIVU copy should be:

**Simple + Direct + Friendly + Action-oriented**

Prefer:

> Temukan proyek yang sesuai dengan keahlianmu.

Instead of:

> Kami menyediakan sebuah ekosistem digital yang memungkinkan pengguna
> melakukan pencarian terhadap berbagai macam proyek yang tersedia.

### CTA examples

Good:

-   Mulai Sekarang
-   Lihat Proyek
-   Lamar Proyek
-   Posting Proyek
-   Cari Talenta
-   Lihat Profil
-   Pelajari Selengkapnya

Avoid vague CTAs:

-   Klik di sini
-   Submit
-   Proceed
-   Continue

------------------------------------------------------------------------

# 31. Design Tokens

Recommended CSS foundation:

``` css
:root {
  /* Brand */
  --color-primary: #075DC5;
  --color-primary-dark: #063B82;
  --color-primary-light: #E6F3FF;

  --color-secondary: #FFD02F;
  --color-secondary-dark: #E8A900;
  --color-secondary-light: #FFF5CC;

  /* Neutral */
  --color-text: #102A43;
  --color-text-secondary: #486581;
  --color-text-muted: #829AB1;

  --color-border: #E2E8F0;

  --color-background: #FFFFFF;
  --color-background-soft: #F3F9FF;

  /* Semantic */
  --color-success: #16A34A;
  --color-warning: #F59E0B;
  --color-error: #DC2626;
  --color-info: #0EA5E9;

  /* Radius */
  --radius-xs: 6px;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 20px;
  --radius-2xl: 24px;
  --radius-full: 999px;

  /* Spacing */
  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-8: 32px;
  --space-10: 40px;
  --space-12: 48px;
  --space-16: 64px;
  --space-20: 80px;
  --space-24: 96px;
}
```

------------------------------------------------------------------------

# 32. Component Architecture

Recommended component structure:

``` text
KIVU DESIGN SYSTEM
│
├── Foundations
│   ├── Colors
│   ├── Typography
│   ├── Spacing
│   ├── Grid
│   ├── Radius
│   ├── Shadows
│   └── Icons
│
├── Components
│   ├── Button
│   ├── Input
│   ├── SearchInput
│   ├── Select
│   ├── Textarea
│   ├── Badge
│   ├── Avatar
│   ├── Rating
│   ├── Card
│   ├── ProjectCard
│   ├── TalentCard
│   ├── CategoryCard
│   ├── StatisticCard
│   ├── TestimonialCard
│   ├── Modal
│   ├── Dropdown
│   ├── Tabs
│   ├── Toast
│   └── Pagination
│
├── Navigation
│   ├── Navbar
│   ├── Sidebar
│   ├── Breadcrumb
│   └── Footer
│
├── Patterns
│   ├── Hero
│   ├── SectionHeader
│   ├── ProjectListing
│   ├── TalentListing
│   ├── SearchAndFilter
│   ├── EmptyState
│   └── LoadingState
│
└── Pages
    ├── Landing
    ├── Login
    ├── Register
    ├── Marketplace
    ├── ProjectDetail
    ├── TalentProfile
    ├── StudentDashboard
    ├── UMKMDashboard
    └── AdminDashboard
```

------------------------------------------------------------------------

# 33. Page-Level Rules

## Landing Page

Goal:

**Build trust and drive registration.**

Priority:

1.  Hero
2.  KIVU explanation
3.  Categories
4.  Available projects
5.  How it works
6.  Statistics
7.  Testimonials
8.  Final CTA
9.  Footer

------------------------------------------------------------------------

## Marketplace

Goal:

**Help users discover relevant projects/talents quickly.**

Priority:

``` text
Search
→ Category
→ Filter
→ Results
→ Project/Talent Card
```

Keep discovery faster than decoration.

------------------------------------------------------------------------

## Project Detail

Goal:

**Give enough information for a student to decide whether to apply.**

Prioritize:

-   Project title
-   Category
-   Description
-   Requirements
-   Budget
-   Deadline
-   UMKM profile
-   Expected deliverables
-   Application CTA

------------------------------------------------------------------------

## Student Dashboard

Goal:

**Help students manage opportunities and active work.**

Primary information:

-   Recommended projects
-   Applications
-   Active projects
-   Completed projects
-   Earnings/rewards
-   Profile completeness

------------------------------------------------------------------------

## UMKM Dashboard

Goal:

**Help UMKM post and manage projects.**

Primary information:

-   Post project
-   Active projects
-   Applications
-   Selected students
-   Completed projects
-   Project status

------------------------------------------------------------------------

## Admin Dashboard

Goal:

**Monitor and manage the KIVU ecosystem.**

Primary areas:

-   Overview
-   Users
-   Students
-   UMKM
-   Projects
-   Applications
-   Reports
-   Verification
-   Categories
-   Reviews
-   Platform settings

Admin UI can be denser than the public website, but must still use the
same colors, typography, radius, and component language.

------------------------------------------------------------------------

# 34. Visual Do / Don't

## DO

-   Use generous white space.
-   Use blue for brand identity.
-   Use yellow for action and emphasis.
-   Use rounded cards.
-   Use subtle shadows.
-   Use consistent spacing.
-   Use real human imagery.
-   Keep information hierarchy obvious.
-   Use consistent iconography.
-   Design mobile-first behavior intentionally.

## DON'T

-   Do not introduce unrelated brand colors.
-   Do not use heavy shadows everywhere.
-   Do not use excessive gradients.
-   Do not mix several typography systems.
-   Do not make every element rounded excessively.
-   Do not overcrowd cards.
-   Do not use yellow for large amounts of body text.
-   Do not create visually different components for the same function.
-   Do not sacrifice usability for decoration.

------------------------------------------------------------------------

# 35. KIVU Design Principles

All future design decisions should follow these principles:

### 1. Clear before beautiful

Users should understand the interface before noticing its visual style.

### 2. Consistent before creative

New components should reuse the existing system before introducing new
patterns.

### 3. Human before corporate

KIVU connects people. Visuals should communicate people and
collaboration.

### 4. Action-oriented

Important actions must be easy to find.

### 5. Lightweight

Avoid unnecessary decoration, shadows, gradients, and visual noise.

### 6. Marketplace-first

Project discovery, talent discovery, applications, and collaboration are
core product experiences.

### 7. Scalable

Every component should be reusable across landing page, marketplace,
dashboard, and admin.

------------------------------------------------------------------------

# 36. Final Brand Formula

The KIVU interface should consistently feel like:

``` text
             KIVU
              │
      ┌───────┴───────┐
      │               │
   BLUE             YELLOW
 Trust +             Action +
 Brand              Energy
      │               │
      └───────┬───────┘
              │
        CLEAN WHITE SPACE
              │
       ROUNDED COMPONENTS
              │
      HUMAN PHOTOGRAPHY
              │
      MARKETPLACE UX
              │
       MODERN + FRIENDLY
```

### Primary visual identity

``` text
#075DC5  → Brand Blue
#FFD02F  → Brand Yellow
#FFFFFF  → Main Background
#F3F9FF  → Soft Section Background
#102A43  → Main Text
#E2E8F0  → Border
```

**When in doubt:** use fewer colors, more white space, clearer
hierarchy, and existing components.
