# KIVU Design System

**Version:** 1.0  
**Status:** MVP  
**Product:** KIVU — Student × UMKM Micro-Freelance Marketplace  
**Implementation:** Laravel 12 + Blade + Livewire 3 + Tailwind CSS  
**Relationship:** Companion to `KIVU_PRD_FINAL_Full_Laravel.md`

---

## 1. Purpose

Dokumen ini adalah **source of truth untuk UI/visual KIVU**.

PRD mengatur:
- apa yang harus dilakukan produk,
- role dan permission,
- business rules,
- state machine,
- user flow,
- acceptance criteria.

Design System mengatur:
- bagaimana produk terlihat,
- bagaimana komponen digunakan,
- spacing dan typography,
- warna,
- status visual,
- layout,
- responsive behavior,
- reusable UI components,
- aturan implementasi Blade/Livewire/Tailwind.

### Source of Truth

```text
KIVU_PRD_FINAL_Full_Laravel.md
        ↓
Product / Business Rules
        ↓
KIVU_DESIGN_SYSTEM.md
        ↓
UI / Components / Visual Rules
        ↓
Blade + Livewire + Tailwind
```

Jika terdapat konflik:
1. Business rule mengikuti PRD.
2. Visual implementation mengikuti Design System.
3. Existing code harus diaudit sebelum diubah.
4. Jangan membuat komponen baru jika komponen existing dapat digunakan kembali.

---

# 2. Design Direction

## 2.1 Visual Character

KIVU harus terasa:

- **Modern**
- **Clean**
- **Friendly**
- **Trustworthy**
- **Student-oriented**
- **Professional**
- **Simple**
- **Accessible**
- **Action-oriented**

KIVU bukan platform enterprise yang kaku dan bukan pula aplikasi sosial yang terlalu playful.

Visual harus berada di tengah:

```text
Casual Student
      │
      │
   KIVU
      │
      │
Professional Marketplace
```

## 2.2 Design Principles

### 1. Simple First

User harus memahami halaman dan tindakan utama tanpa membaca banyak instruksi.

### 2. One Primary Action

Setiap halaman sebaiknya mempunyai satu CTA utama yang jelas.

### 3. Trust by Design

Status project, pembayaran, submission, dan transaksi harus mudah dipahami.

### 4. Consistency

Komponen yang sama harus memiliki tampilan dan perilaku yang sama di seluruh role.

### 5. Information Hierarchy

Informasi penting harus lebih menonjol daripada metadata.

### 6. Responsive by Default

Desktop bukan satu-satunya target. UI harus usable pada mobile.

### 7. Data Before Decoration

Jangan menambahkan dekorasi yang mengurangi keterbacaan informasi marketplace.

---

# 3. Brand Foundation

## 3.1 Logo

Logo KIVU harus digunakan secara konsisten pada:

- landing page,
- navbar,
- sidebar,
- authentication page,
- footer jika digunakan.

### Aturan

- Jangan mengubah proporsi logo.
- Jangan stretch logo.
- Jangan menambahkan shadow pada logo.
- Gunakan ukuran yang konsisten.
- Logo tidak boleh bersaing dengan heading halaman.

Recommended:

```text
Desktop navbar: 32–40px height
Sidebar: 32–40px height
Auth: 40–48px height
Landing hero: 40–56px height
```

---

# 4. Color System

KIVU menggunakan sistem warna berbasis **semantic tokens**, bukan warna hardcoded pada setiap komponen.

## 4.1 Color Roles

```text
Primary
├── Primary
├── Primary Hover
├── Primary Active
└── Primary Soft

Neutral
├── Background
├── Surface
├── Surface Muted
├── Border
├── Text Primary
├── Text Secondary
└── Text Muted

Semantic
├── Success
├── Warning
├── Danger
└── Info
```

## 4.2 Recommended Palette

> Nilai berikut adalah baseline Design System. Jika token warna sudah tersedia di repository, gunakan token existing dan jangan membuat warna baru tanpa alasan.

### Primary

```text
Primary       #2563EB
Primary Hover #1D4ED8
Primary Active#1E40AF
Primary Soft  #EFF6FF
```

### Neutral

```text
Background    #F8FAFC
Surface       #FFFFFF
Surface Muted #F1F5F9

Border        #E2E8F0

Text Primary  #0F172A
Text Secondary#475569
Text Muted    #64748B
```

### Semantic

```text
Success       #16A34A
Success Soft  #F0FDF4

Warning       #D97706
Warning Soft  #FFFBEB

Danger        #DC2626
Danger Soft   #FEF2F2

Info          #0284C7
Info Soft     #F0F9FF
```

## 4.3 Color Usage

| Token | Digunakan untuk |
|---|---|
| Primary | CTA utama, active navigation, link penting |
| Primary Soft | selected state, background ringan |
| Text Primary | heading dan informasi utama |
| Text Secondary | body text |
| Text Muted | metadata |
| Border | separator dan card |
| Success | approved/completed/success |
| Warning | pending/warning |
| Danger | rejected/error/destructive |
| Info | information/status tambahan |

### Rule

Jangan menggunakan warna semantic hanya sebagai dekorasi.

Contoh:

```text
SUCCESS → tindakan/status berhasil
DANGER  → error/reject/delete
WARNING → pending/perhatian
INFO    → informasi
```

---

# 5. Typography

## 5.1 Font

Gunakan **Inter** sebagai font utama jika tersedia.

Fallback:

```css
font-family:
  Inter,
  ui-sans-serif,
  system-ui,
  sans-serif;
```

## 5.2 Type Scale

| Style | Size | Weight | Use |
|---|---:|---:|---|
| Display | 40–48px | 700 | Landing hero |
| H1 | 30–36px | 700 | Page heading |
| H2 | 24–28px | 700 | Section heading |
| H3 | 20–22px | 600 | Card/section title |
| Body Large | 18px | 400 | Intro |
| Body | 14–16px | 400 | Main content |
| Body Small | 13–14px | 400 | Secondary information |
| Caption | 12px | 400–500 | Metadata |
| Button | 14px | 600 | CTA |

## 5.3 Typography Rules

- Heading menggunakan weight 600–700.
- Body menggunakan weight 400.
- Jangan menggunakan terlalu banyak font weight.
- Line-height body harus nyaman dibaca.
- Jangan menggunakan ALL CAPS untuk paragraph.
- Metadata harus visually subordinate.

---

# 6. Spacing System

Gunakan spacing berbasis kelipatan 4px.

```text
4px   → 1
8px   → 2
12px  → 3
16px  → 4
20px  → 5
24px  → 6
32px  → 8
40px  → 10
48px  → 12
64px  → 16
80px  → 20
96px  → 24
```

## Recommended Usage

```text
Icon ↔ text           8px
Input internal        12–16px
Card internal         16–24px
Section gap           32–48px
Page section          48–80px
Major landing section 80–96px
```

### Rule

Jangan menggunakan arbitrary spacing tanpa alasan.

Prefer:

```html
gap-4
p-4
px-6
py-8
mt-8
```

daripada:

```html
mt-[37px]
px-[19px]
```

---

# 7. Border Radius

KIVU menggunakan radius yang modern tetapi tidak terlalu rounded.

```text
Small      6px
Medium     8px
Large      12px
XL         16px
Pill       9999px
```

## Usage

| Radius | Usage |
|---|---|
| 6px | small controls |
| 8px | buttons, inputs |
| 12px | cards |
| 16px | large containers |
| Pill | badges/status |

Default:

```text
Card    → 12px
Input   → 8px
Button  → 8px
Badge   → pill
Modal   → 16px
```

---

# 8. Shadow

Shadow harus subtle.

Gunakan shadow terutama untuk:

- dropdown,
- modal,
- floating elements,
- elevated card jika diperlukan.

Jangan memberikan shadow berat pada semua card.

Recommended hierarchy:

```text
No shadow
  ↓
Subtle shadow
  ↓
Medium shadow
  ↓
Strong shadow
```

Default card:

```text
border + surface
```

bukan:

```text
heavy shadow + border + gradient
```

---

# 9. Layout System

## 9.1 Main Container

Recommended:

```text
max-width: 1280px
margin: auto
padding-inline: 16px mobile
padding-inline: 24px tablet
padding-inline: 32px desktop
```

Tailwind direction:

```html
mx-auto
w-full
max-w-7xl
px-4
sm:px-6
lg:px-8
```

## 9.2 Dashboard Layout

```text
┌──────────────────────────────────────────┐
│ Topbar                                   │
├────────────┬─────────────────────────────┤
│ Sidebar    │ Main Content                │
│            │                             │
│ Navigation │ Page Header                │
│            │                             │
│            │ Content                     │
│            │                             │
└────────────┴─────────────────────────────┘
```

Desktop:

```text
Sidebar → fixed width
Main    → flexible
```

Mobile:

```text
Sidebar → hidden / drawer
Main    → full width
```

---

# 10. Responsive Breakpoints

Gunakan breakpoint Tailwind standar.

```text
sm  → 640px
md  → 768px
lg  → 1024px
xl  → 1280px
2xl → 1536px
```

## Mobile First

Implementasi:

```text
base
↓
sm
↓
md
↓
lg
↓
xl
```

Jangan membuat desktop lalu memperbaiki mobile sebagai tahap terakhir.

---

# 11. Navigation

## 11.1 Public Navbar

Struktur:

```text
[ KIVU ]          Opportunities   How It Works   [Login] [Register]
```

Aturan:

- Logo kiri.
- Navigation tengah/kanan.
- CTA register menjadi primary action.
- Mobile menggunakan menu sederhana.

## 11.2 Dashboard Navigation

Student:

```text
Dashboard
Opportunities
My Applications
My Projects
Submissions
Wallet
Profile
```

UMKM:

```text
Dashboard
My Projects
Create Project
Applicants
Transactions
Profile
```

Admin:

```text
Dashboard
Users
Projects
Transactions
Withdrawals
Disputes
```

Navigation harus mengikuti role dan authorization dari PRD.

---

# 12. Button System

## 12.1 Variants

### Primary

Untuk tindakan utama.

```text
Background: Primary
Text: White
```

Contoh:

```text
Create Project
Apply
Submit Result
Approve
Withdraw
```

### Secondary

Untuk tindakan sekunder.

```text
Background: Surface
Border: Border
Text: Text Primary
```

### Ghost

Untuk tindakan ringan.

```text
Background: transparent
Text: Text Secondary
```

### Danger

Untuk tindakan destruktif.

```text
Background: Danger
Text: White
```

Contoh:

```text
Delete
Ban User
Reject
```

## 12.2 Button Sizes

```text
sm → 32px
md → 40px
lg → 44–48px
```

Default:

```text
height: 40px
padding-inline: 16px
radius: 8px
font-weight: 600
```

## 12.3 Button States

Semua button harus memiliki:

```text
Default
Hover
Active
Focus
Disabled
Loading
```

Loading:

```text
[ spinner ] Saving...
```

Jangan memungkinkan double submission ketika loading.

---

# 13. Input System

Komponen:

```text
Input
Textarea
Select
Checkbox
Radio
File Upload
Search
```

## Input Anatomy

```text
Label
↓
Input
↓
Helper / Error
```

Contoh:

```text
Project Title
[________________________]

Judul project harus jelas.

```

Error:

```text
Project Title
[________________________]

Project title wajib diisi.
```

## Input Rules

- Label wajib untuk field penting.
- Placeholder bukan pengganti label.
- Error harus berada dekat field.
- Input disabled harus jelas secara visual.
- Focus state harus visible.
- Gunakan validation Laravel/Livewire sebagai source of truth.

---

# 14. Card

Card adalah komponen utama KIVU.

## Base Card

```text
Background: Surface
Border: Border
Radius: 12px
Padding: 16–24px
```

Struktur:

```text
┌───────────────────────────────┐
│ Label / Badge                 │
│                               │
│ Title                         │
│ Description                   │
│                               │
│ Metadata                      │
│                               │
│              [Action]         │
└───────────────────────────────┘
```

Card tidak boleh memiliki terlalu banyak informasi.

---

# 15. Badge & Status

Status harus konsisten dengan state machine PRD.

## Project Status

```text
OPEN
→ Info / Primary

IN_PROGRESS
→ Warning

SUBMITTED
→ Info

COMPLETED
→ Success
```

## Application Status

```text
PENDING
→ Warning

ACCEPTED
→ Success

REJECTED
→ Danger
```

## Submission Status

```text
NOT_SUBMITTED
→ Neutral

SUBMITTED
→ Info

APPROVED
→ Success
```

## Withdrawal

```text
PENDING
→ Warning

APPROVED
→ Success

REJECTED
→ Danger
```

Badge tidak boleh menjadi satu-satunya cara menyampaikan status. Untuk status penting, gunakan label teks yang jelas.

---

# 16. Avatar

Avatar digunakan untuk:

- student,
- UMKM,
- admin,
- reviewer.

Ukuran:

```text
xs → 24px
sm → 32px
md → 40px
lg → 48px
xl → 64px
```

Fallback:

```text
initials
```

Jika foto tidak tersedia, jangan menampilkan broken image.

---

# 17. Modal

Modal digunakan untuk:

- confirmation,
- important action,
- review,
- destructive action.

Struktur:

```text
┌───────────────────────────────┐
│ Title                     [×] │
│                               │
│ Description                  │
│                               │
│ Content                       │
│                               │
│ [Cancel]        [Confirm]     │
└───────────────────────────────┘
```

Rule:

- Modal bukan pengganti halaman.
- Jangan gunakan modal untuk form panjang.
- Destructive confirmation harus menggunakan Danger CTA.
- Escape harus dapat menutup modal jika tidak ada alasan untuk mencegahnya.

---

# 18. Alert & Toast

## Alert

Untuk informasi yang perlu tetap terlihat.

```text
Success
Info
Warning
Danger
```

## Toast

Untuk feedback singkat setelah action.

Contoh:

```text
✓ Project berhasil dibuat.
✓ Submission berhasil dikirim.
✓ Withdrawal berhasil diajukan.
```

Toast tidak boleh menjadi satu-satunya tempat untuk menampilkan error penting.

---

# 19. Table

Table digunakan terutama pada Admin.

Contoh:

```text
Users
Projects
Transactions
Withdrawals
```

Struktur:

```text
┌────────┬──────────────┬────────┬──────────┐
│ Name   │ Role         │ Status │ Action   │
├────────┼──────────────┼────────┼──────────┤
│ ...    │ Student      │ Active │ View     │
└────────┴──────────────┴────────┴──────────┘
```

Rules:

- Header jelas.
- Status menggunakan badge.
- Action tidak terlalu banyak.
- Mobile harus memiliki strategi responsive.
- Gunakan pagination untuk dataset besar.

---

# 20. Tabs

Tabs digunakan ketika beberapa informasi masih berada dalam satu konteks.

Contoh:

```text
Overview | Applications | Submission
```

Active tab:

```text
Primary text
Primary indicator
```

Jangan menggunakan tabs untuk menggantikan navigasi utama.

---

# 21. Domain Components

Komponen berikut harus dibuat reusable karena muncul di workflow utama.

## 21.1 Project Card

Informasi minimum:

```text
Project title
UMKM
Description excerpt
Budget
Deadline jika tersedia
Status
CTA
```

Student:

```text
[View Project]
```

UMKM:

```text
[View Project]
[Applicants]
```

## 21.2 Applicant Card

```text
Avatar
Student name
Application message
Relevant metadata
Status
[Accept]
[Reject]
```

Accept hanya tersedia sesuai authorization dan state project.

## 21.3 Submission Card

```text
Project
Student
Submission description
Attachment/link jika tersedia
Submitted date
Status
[Review]
[Approve]
```

## 21.4 Wallet Balance

```text
Available Balance

Rp xxx.xxx

[Withdraw]
```

Balance harus menjadi informasi yang mudah ditemukan student.

## 21.5 Transaction Item

```text
Project
Amount
Date
Status
```

---

# 22. Landing Page Pattern

Landing page harus menjelaskan KIVU dengan cepat.

Recommended structure:

```text
Navbar
↓
Hero
↓
Value Proposition
↓
How It Works
↓
For Students
↓
For UMKM
↓
Trust / Benefits
↓
CTA
↓
Footer
```

## Hero

Harus memiliki:

```text
Headline
Supporting text
Primary CTA
Secondary CTA
Visual
```

Primary CTA harus mengarahkan ke action yang paling relevan untuk visitor.

---

# 23. Authentication Pattern

## Login

```text
Logo

Welcome back

Email
Password

[Login]

Forgot password jika tersedia

Don't have an account?
Register
```

## Register

Role selection:

```text
I'm a Student
I'm an UMKM
```

Setelah role dipilih, tampilkan field yang relevan.

Authentication flow harus mengikuti PRD.

---

# 24. Student Dashboard Pattern

Prioritas informasi:

```text
Welcome
↓
Available Opportunities
↓
Application Status
↓
Active Projects
↓
Submission / Action Needed
↓
Wallet
```

Dashboard harus menonjolkan **next action**.

Contoh:

```text
Project siap dikerjakan
→ [Open Project]

Submission perlu dikirim
→ [Submit Result]

Project selesai
→ [View Wallet]
```

---

# 25. UMKM Dashboard Pattern

Prioritas:

```text
Overview
↓
Active Projects
↓
Pending Applicants
↓
Submission Awaiting Review
↓
Transactions
```

Primary CTA:

```text
+ Create Project
```

---

# 26. Admin Dashboard Pattern

Admin lebih information-dense tetapi tetap clean.

KPI:

```text
Users
Active Projects
Transactions
Pending Withdrawals
```

Di bawah KPI:

```text
Recent Projects
Recent Transactions
Pending Moderation
Pending Withdrawals
```

Admin UI tidak boleh menggunakan visual yang terlalu dekoratif.

---

# 27. Empty States

Empty state harus menjelaskan:

1. Apa yang kosong?
2. Mengapa?
3. Apa yang dapat dilakukan user?

Contoh:

```text
No applications yet

Project ini belum memiliki mahasiswa yang melamar.

[Browse Opportunities]
```

Jangan hanya:

```text
No data.
```

---

# 28. Loading States

Livewire action wajib memiliki feedback loading.

Gunakan:

```html
wire:loading
wire:target="..."
```

Contoh:

```text
[ Submit Result ]
        ↓
[ spinner ] Submitting...
```

Button action harus disabled selama request berjalan jika double action dapat menyebabkan masalah.

---

# 29. Error States

Error harus:

- jelas,
- dekat dengan sumber masalah,
- actionable,
- tidak menyalahkan user.

Contoh:

```text
Something went wrong.

Submission belum dapat dikirim.
Silakan coba lagi.

[Try Again]
```

Untuk validation:

```text
Budget harus berupa angka.
```

---

# 30. Success States

Success harus menjelaskan action yang selesai.

Contoh:

```text
✓ Project berhasil dibuat.
```

Untuk workflow penting:

```text
✓ Submission approved.
Payment Rp100.000 telah ditambahkan
ke wallet mahasiswa.
```

Feedback harus konsisten dengan business rule PRD.

---

# 31. Confirmation Patterns

Gunakan confirmation untuk action yang mengubah state penting.

Contoh:

```text
Accept Applicant?

Mahasiswa ini akan menjadi worker
untuk project tersebut.

[Cancel] [Accept Applicant]
```

Untuk approval:

```text
Approve Submission?

Setelah disetujui, pembayaran akan
dicatat dan saldo mahasiswa bertambah.

[Cancel] [Approve]
```

Confirmation copy harus menjelaskan consequence.

---

# 32. Forms

Form harus:

```text
Short
Clear
Grouped
Validated
Responsive
```

### Form Layout

Desktop:

```text
┌───────────────────────────┐
│ Main form                 │
│                           │
│ field                     │
│ field                     │
│ field                     │
│                           │
│ [Cancel] [Save]           │
└───────────────────────────┘
```

Mobile:

```text
field
field
field

[Save]
```

Jangan membuat dua kolom jika membuat form sulit dipahami.

---

# 33. Responsive Rules

## Mobile

Prioritas:

```text
Content
↓
Primary action
↓
Secondary action
```

Rules:

- Button penting dapat full width.
- Card menjadi single column.
- Table dapat berubah menjadi card/list.
- Sidebar menjadi drawer.
- Navigation tidak boleh overflow.
- Modal harus sesuai viewport.

## Tablet

Gunakan layout intermediate.

## Desktop

Manfaatkan whitespace dan multi-column layout jika membantu hierarchy.

---

# 34. Accessibility

Minimum requirements:

- semantic HTML,
- visible focus state,
- keyboard navigable,
- label untuk form,
- sufficient color contrast,
- button tidak hanya dibedakan berdasarkan warna,
- icon button memiliki accessible label,
- error dapat dipahami tanpa mengandalkan warna,
- jangan menghilangkan focus outline tanpa pengganti.

---

# 35. Iconography

Gunakan satu icon library secara konsisten.

Recommended:

```text
Lucide
```

Rules:

- Jangan mencampur banyak icon style.
- Icon action harus intuitif.
- Icon-only button wajib memiliki accessible label.
- Icon tidak menggantikan label pada action penting.

Contoh:

```text
✓ Approve
× Reject
→ View
+ Create
```

---

# 36. Image & Illustration

Visual KIVU harus mendukung konteks:

- mahasiswa,
- UMKM,
- digital work,
- collaboration,
- project marketplace.

Hindari:

- stock image yang terlalu generik,
- visual berlebihan,
- terlalu banyak gradient,
- ilustrasi yang tidak berhubungan dengan workflow.

Image harus memiliki purpose.

---

# 37. Motion

Animation harus subtle.

Gunakan terutama untuk:

- hover,
- dropdown,
- modal,
- toast,
- loading,
- state transition.

Avoid:

- excessive bouncing,
- continuous animation,
- animation yang memperlambat task.

Recommended duration:

```text
100–150ms → micro interaction
150–250ms → component transition
250–300ms → modal/drawer
```

---

# 38. Tailwind Implementation Rules

## 38.1 Prefer Utility Composition

Gunakan Tailwind untuk styling komponen.

Contoh:

```html
<button
    class="
        inline-flex items-center justify-center
        rounded-lg
        px-4 py-2.5
        text-sm font-semibold
        transition
        focus:outline-none
        focus:ring-2
        focus:ring-offset-2
    "
>
    Apply
</button>
```

## 38.2 Avoid Excessive Arbitrary Values

Prefer:

```text
rounded-lg
gap-4
p-6
max-w-7xl
```

daripada:

```text
rounded-[13px]
gap-[17px]
p-[23px]
```

kecuali memang dibutuhkan oleh design.

## 38.3 Reusable Components

Komponen UI yang digunakan berulang harus diekstrak.

Contoh struktur:

```text
resources/views/components/
├── ui/
│   ├── button.blade.php
│   ├── input.blade.php
│   ├── card.blade.php
│   ├── badge.blade.php
│   ├── alert.blade.php
│   ├── modal.blade.php
│   └── avatar.blade.php
│
└── domain/
    ├── project-card.blade.php
    ├── applicant-card.blade.php
    ├── submission-card.blade.php
    ├── wallet-balance.blade.php
    └── transaction-item.blade.php
```

---

# 39. Livewire UI Rules

Livewire digunakan untuk interaksi dinamis.

Contoh:

```text
Application
Accept applicant
Submit result
Approve submission
Withdrawal
Filters
Pagination
Modal confirmation
```

Rules:

- Loading state harus terlihat.
- Jangan membuat request Livewire yang tidak diperlukan.
- State UI harus mudah dipahami.
- Validation tetap dilakukan backend-side.
- Authorization tidak boleh hanya bergantung pada visibility UI.

---

# 40. Authorization & UI

UI boleh menyembunyikan action yang tidak tersedia untuk role tertentu.

Namun:

```text
UI hiding ≠ authorization
```

Authorization tetap wajib dilakukan melalui:

```text
Policies
Gates
Middleware
Backend validation
```

Contoh:

```text
Student
→ tidak boleh approve submission.

UMKM
→ tidak boleh approve submission milik project lain.

Admin
→ dapat melakukan moderation sesuai policy.
```

---

# 41. State-Driven UI

UI harus merepresentasikan state database.

### Project

```text
OPEN
    ↓
IN_PROGRESS
    ↓
SUBMITTED
    ↓
COMPLETED
```

Jangan menampilkan action berdasarkan asumsi UI.

Contoh:

```text
OPEN
→ Apply

IN_PROGRESS
→ Project in progress

SUBMITTED
→ Review submission / waiting approval

COMPLETED
→ Completed
```

---

# 42. Design Tokens

Jika menggunakan CSS variables, gunakan semantic naming.

```css
:root {
    --kivu-primary: #2563EB;
    --kivu-primary-hover: #1D4ED8;

    --kivu-background: #F8FAFC;
    --kivu-surface: #FFFFFF;

    --kivu-border: #E2E8F0;

    --kivu-text-primary: #0F172A;
    --kivu-text-secondary: #475569;
    --kivu-text-muted: #64748B;

    --kivu-success: #16A34A;
    --kivu-warning: #D97706;
    --kivu-danger: #DC2626;
    --kivu-info: #0284C7;
}
```

Nama token harus semantic.

Prefer:

```text
--kivu-primary
```

bukan:

```text
--blue-600
```

---

# 43. Component Naming Convention

Gunakan naming yang konsisten.

```text
UiButton
UiInput
UiCard
UiBadge
UiModal

ProjectCard
ApplicantCard
SubmissionCard
WalletBalance
TransactionItem
```

Blade:

```text
<x-ui.button>
<x-ui.input>
<x-ui.card>

<x-domain.project-card>
<x-domain.applicant-card>
```

Nama harus menggambarkan fungsi, bukan visual.

Jangan:

```text
<x-blue-button>
<x-big-card>
<x-rounded-box>
```

---

# 44. Page Naming

Gunakan role-based organization.

```text
resources/views/
├── auth/
│
├── student/
│   ├── dashboard.blade.php
│   ├── opportunities/
│   ├── applications/
│   ├── submissions/
│   └── wallet/
│
├── umkm/
│   ├── dashboard.blade.php
│   ├── projects/
│   ├── applicants/
│   └── transactions/
│
└── admin/
    ├── dashboard.blade.php
    ├── users/
    ├── projects/
    ├── transactions/
    └── withdrawals/
```

---

# 45. UI State Matrix

| State | Visual | CTA |
|---|---|---|
| Default | normal | primary |
| Hover | slight emphasis | same |
| Focus | visible ring | same |
| Disabled | muted | disabled |
| Loading | spinner + label | disabled |
| Success | success feedback | next action |
| Warning | warning alert | corrective action |
| Error | danger feedback | retry/fix |
| Empty | illustration/icon + explanation | relevant action |

---

# 46. Content & Copy Rules

KIVU menggunakan copy yang:

- singkat,
- jelas,
- human,
- action-oriented,
- tidak terlalu formal.

Prefer:

```text
Create Project
```

daripada:

```text
Create a New Project Entry
```

Prefer:

```text
No applications yet
```

daripada:

```text
There are currently no application records available.
```

Gunakan Bahasa Indonesia untuk UI utama jika bahasa produk ditetapkan Indonesia, kecuali istilah teknis/brand yang memang dipertahankan.

---

# 47. Currency

Nominal uang harus menggunakan format Indonesia.

Contoh:

```text
Rp100.000
Rp1.500.000
```

Jangan:

```text
100000 IDR
1,500,000
```

UI harus konsisten.

---

# 48. Date & Time

Gunakan format yang mudah dipahami user Indonesia.

Contoh:

```text
14 September 2026
14 Sep 2026
```

Gunakan format relatif hanya jika membantu:

```text
2 jam yang lalu
```

Untuk informasi deadline/payment penting, tampilkan tanggal eksplisit.

---

# 49. Project Workflow UI

Golden path KIVU:

```text
POSTING
   ↓
LAMAR
   ↓
TERIMA
   ↓
KIRIM
   ↓
SETUJUI
   ↓
BAYAR
   ↓
SELESAI
```

Setiap state harus mempunyai visual yang berbeda dan action yang sesuai.

Contoh:

```text
OPEN
[Apply]

PENDING
Waiting for response

ACCEPTED
[Open Project]

SUBMITTED
Waiting for UMKM approval

APPROVED
Payment recorded

COMPLETED
Completed
```

---

# 50. Payment & Wallet UI

MVP menggunakan payment simulation.

UI tidak boleh memberikan kesan bahwa KIVU sudah memiliki real payment gateway jika belum tersedia.

Gunakan terminology:

```text
Payment
Transaction
Wallet Balance
Withdrawal
```

Jangan mengklaim:

```text
Bank transfer confirmed
Escrow secured
Payment gateway processed
```

jika fitur tersebut belum benar-benar ada.

---

# 51. Demo-First UI

KIVU harus dapat didemokan dalam sekitar 5 menit.

Golden demo:

```text
UMKM login
↓
Create Project
↓
Student login
↓
Apply
↓
UMKM accept
↓
Student submit
↓
UMKM approve
↓
Wallet updated
↓
Admin inspect transaction
```

Setiap langkah harus mudah ditemukan.

Jangan menyembunyikan core action di dalam terlalu banyak menu.

---

# 52. Do / Don't

## Do

```text
✓ Consistent spacing
✓ Clear CTA
✓ Reusable components
✓ Semantic colors
✓ Visible states
✓ Responsive layout
✓ Clear empty states
✓ Loading feedback
✓ Accessible controls
✓ State-driven UI
```

## Don't

```text
✗ Random colors
✗ Excessive gradients
✗ Excessive shadows
✗ Giant rounded containers
✗ Too many CTAs
✗ Hardcoded duplicated components
✗ UI-only authorization
✗ Fake loading
✗ Fake payment status
✗ Unnecessary animation
✗ Arbitrary spacing everywhere
```

---

# 53. Vibe Coding Rules — Design

AI coding agent wajib mengikuti aturan:

### Rule 01 — Inspect First

Sebelum membuat UI:

```text
Inspect repository
→ Inspect existing components
→ Inspect Tailwind configuration
→ Inspect layouts
→ Inspect design tokens
→ Implement
```

### Rule 02 — Reuse First

Cari komponen existing sebelum membuat komponen baru.

### Rule 03 — Don't Invent

Jangan membuat:

- warna baru,
- typography baru,
- status baru,
- component variant baru,
- layout pattern baru,

tanpa alasan dan tanpa memastikan tidak tersedia.

### Rule 04 — Business Rule Comes From PRD

Design System tidak boleh mengubah business logic.

### Rule 05 — Responsive Is Required

Setiap UI baru harus diuji minimal:

```text
Mobile
Tablet
Desktop
```

### Rule 06 — State Is Required

Action penting harus memiliki:

```text
Default
Loading
Success
Error
Disabled
```

sesuai kebutuhan.

### Rule 07 — Accessibility Is Required

Jangan mengorbankan accessibility demi visual.

### Rule 08 — Keep Components Small

Jika komponen terlalu besar dan mengandung banyak responsibility, pecah menjadi reusable components.

---

# 54. Definition of Done — UI

Sebuah UI feature dianggap selesai jika:

```text
[ ] Sesuai PRD
[ ] Sesuai Design System
[ ] Menggunakan component existing jika tersedia
[ ] Responsive
[ ] Loading state tersedia jika async
[ ] Empty state tersedia jika relevan
[ ] Error state tersedia jika relevan
[ ] Success feedback tersedia jika relevan
[ ] Authorization benar
[ ] Validation benar
[ ] Tidak ada duplicated styling besar
[ ] Tidak ada arbitrary values berlebihan
[ ] Keyboard/focus usable
[ ] Tidak merusak halaman existing
[ ] Golden path tetap berjalan
```

---

# 55. Recommended Component Inventory

Minimum UI library KIVU:

```text
UI
├── Button
├── Input
├── Textarea
├── Select
├── Checkbox
├── Radio
├── FileUpload
├── Card
├── Badge
├── Avatar
├── Modal
├── Dropdown
├── Tabs
├── Alert
├── Toast
├── Spinner
├── Skeleton
├── EmptyState
├── Pagination
├── Table
└── Breadcrumb

DOMAIN
├── ProjectCard
├── ProjectStatus
├── ApplicationStatus
├── SubmissionStatus
├── ApplicantCard
├── SubmissionCard
├── WalletBalance
├── TransactionItem
└── ReviewCard
```

---

# 56. Implementation Priority

Jangan membuat seluruh component library sekaligus.

Urutan:

```text
Phase 1
Button
Input
Card
Badge
Layout
Navbar
Sidebar

Phase 2
Modal
Alert
Toast
Loading
Empty State
Table
Pagination

Phase 3
ProjectCard
ApplicantCard
SubmissionCard
WalletBalance
TransactionItem
ReviewCard
```

Implementasikan hanya ketika dibutuhkan oleh feature.

---

# 57. Final Design Principle

KIVU tidak membutuhkan UI yang paling kompleks.

KIVU membutuhkan UI yang:

```text
Mudah dipahami
      +
Mudah digunakan
      +
Terpercaya
      +
Konsisten
      +
Cepat
      +
Responsive
      =
KIVU MVP yang kuat
```

**Golden Rule:**

> **Jangan membuat KIVU lebih dekoratif. Buat KIVU lebih jelas.**

---

# 58. Relationship With KIVU PRD

Dokumen ini harus selalu digunakan bersama:

```text
KIVU_PRD_FINAL_Full_Laravel.md
```

PRD menjawab:

> "Apa yang harus dilakukan KIVU?"

Design System menjawab:

> "Bagaimana KIVU harus terlihat dan berinteraksi?"

Jika agent menerima task UI:

```text
Read PRD
→ Read Design System
→ Inspect existing implementation
→ Plan
→ Implement
→ Test
→ Review
```

Jangan mengimplementasikan UI KIVU hanya berdasarkan screenshot atau prompt singkat jika aturan PRD dan Design System sudah tersedia.
