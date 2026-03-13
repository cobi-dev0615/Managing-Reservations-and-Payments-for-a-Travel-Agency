# MOJO Safaris & Tours — RPMS Feature Documentation

**Reservation & Payment Management System**
Version 1.0 — March 2026

---

## Table of Contents

1. [Platform Overview](#1-platform-overview)
2. [Authentication](#2-authentication)
3. [Operational Dashboard](#3-operational-dashboard)
4. [Tour Management](#4-tour-management)
5. [Client Management](#5-client-management)
6. [Booking Management](#6-booking-management)
7. [Payment Cockpit](#7-payment-cockpit)
8. [Email Templates](#8-email-templates)
9. [System Settings](#9-system-settings)
10. [Logs (Email & Activity)](#10-logs-email--activity)
11. [User Management](#11-user-management)
12. [User Profile](#12-user-profile)
13. [Role-Based Access Control (RBAC)](#13-role-based-access-control-rbac)
14. [Common UI Patterns & Design](#14-common-ui-patterns--design)

---

## 1. Platform Overview

MOJO RPMS is a web-based Reservation & Payment Management System built for travel and safari tour operators. It provides end-to-end management of tours, clients, bookings, and payment installments, with automated email reminders and comprehensive audit logging.

### Technology Stack

| Component        | Technology                                         |
|------------------|----------------------------------------------------|
| Backend          | Laravel 12.0 (PHP)                                 |
| Frontend         | Blade Templates, Bootstrap 5.3.3, Bootstrap Icons  |
| Database         | SQLite                                             |
| Authentication   | Session-based with RBAC (Admin / Manager / Viewer) |
| Typography       | Inter (Google Fonts)                               |
| Theme            | Light / Dark mode with localStorage persistence    |

### Key Capabilities

- Multi-currency support (USD, EUR, BRL, etc.)
- Automated email reminders at configurable intervals before/after payment due dates
- Real-time dashboard with payment and booking KPIs
- Role-based access control with three permission tiers
- Full activity and email audit logging
- Responsive design with dark mode support

---

## 2. Authentication

### 2.1 Login

`GET /login`

- Email and password authentication
- "Remember Me" checkbox for persistent sessions
- Form validation with inline error messages
- Link to registration page for new users

### 2.2 Registration

`GET /register`

- Self-service user registration
- Fields: Name, Email, Password, Password Confirmation
- Minimum 8-character password requirement
- Automatic redirect to dashboard upon registration

> Both pages use a standalone auth layout with a centered card and MOJO branding. They are only accessible to non-authenticated (guest) users.

---

## 3. Operational Dashboard

`GET /` — **All Users**

The dashboard provides a real-time overview of the entire system's operational status.

### Booking Summary Cards

- **Total Bookings** — overall count of all reservations
- **Pending** — bookings awaiting confirmation
- **Confirmed** — bookings that have been confirmed
- **Completed** — bookings that have been fulfilled

### Payment Status Cards

- **Due Soon** — installments due within the next 7 days
- **Due Today** — installments due on the current date
- **Overdue** — installments past their due date
- **Paid** — installments that have been settled
- **Missing Link** — link-method installments without a payment URL
- **Currency Totals** — pending amounts aggregated by currency

### Data Tables

#### Upcoming Installments
Table showing installments due within the next 7 days: due date, client, tour, amount, currency, and payment status.

#### Overdue Installments
Table showing past-due installments with the number of days overdue, including a direct link to the booking detail page.

#### Recent Activity
Feed of the last 10 system actions (tour created, booking updated, payment registered, etc.) with timestamps and entity links.

---

## 4. Tour Management

### 4.1 Tour List

`GET /tours` — **All Users**

- Paginated list of all tours with configurable items per page (10, 25, 50, 100)
- **Filters:** Type (Group, Private, Agency, Influencer), Status (Active, Inactive), text search by name or code
- **Table columns:** Code, Name, Type, Currency, Travelers (with capacity bar), Status, Actions
- Capacity progress bar: green (<70%), amber (70-90%), red (>90%)
- Quick actions: View details, Edit, Toggle active/inactive status

### 4.2 Tour Details

`GET /tours/{id}` — **All Users**

- Full tour information: Code, Name, Type, Currency, Status, Max Travelers, Notes
- Traveler count card with visual capacity bar and percentage
- "Full" badge displayed when tour reaches maximum capacity
- Associated bookings table listing all reservations linked to the tour
- Action buttons: Edit tour, Back to list

### 4.3 Create / Edit Tour

`GET /tours/create` | `GET /tours/{id}/edit`

**Form Fields:**

| Field          | Description                                        |
|----------------|----------------------------------------------------|
| Code           | Unique tour identifier                             |
| Name           | Tour display name                                  |
| Type           | Group, Private, Agency, or Influencer              |
| Currency       | Default currency for the tour                      |
| Max Travelers  | Capacity limit                                     |
| Notes          | Optional description or internal notes             |
| Status         | Active / Inactive (available on edit only)         |

---

## 5. Client Management

### 5.1 Client List

`GET /clients` — **All Users**

- Searchable list of all clients by name or email
- **Table columns:** Name, Email, Booking Count, Actions
- Quick actions: View details, Edit, Delete (with confirmation)
- Pagination support

### 5.2 Client Details

`GET /clients/{id}` — **All Users**

- Client profile: Name, Email (clickable mailto link), Notes
- Associated bookings table showing tour, date, value, currency, and status
- Quick-create button to add a new booking for this client

### 5.3 Create / Edit Client

`GET /clients/create` | `GET /clients/{id}/edit`

**Form Fields:**

| Field  | Description                      |
|--------|----------------------------------|
| Name   | Client full name                 |
| Email  | Contact email address            |
| Notes  | Optional notes about the client  |

---

## 6. Booking Management

### 6.1 Booking List

`GET /bookings` — **All Users**

- Paginated list with configurable items per page (10, 25, 50, 100)
- **Filters:** Status (Pending, Confirmed, Cancelled, Completed), Tour, text search
- **Table columns:** ID, Client, Tour, Start Date, Travelers, Total Value, Status, Actions
- Currency and status badges
- Quick actions: View, Edit, Delete (with confirmation)

### 6.2 Booking Details & Installments

`GET /bookings/{id}` — **All Users**

#### Booking Information
- Full details: ID, Client (linked), Tour (linked), Start Date, Currency, Total Value, Travelers, Status, Notes
- Discount notes section

#### Installment Management
- **Summary cards:** Total installments count, Sum of installment amounts, Difference from booking total
- **Installments table:** #, Amount, Due Date, Method, Payment Link, Status, Last Email Sent, Actions
- **Actions per installment:** Mark as Paid, Edit (modal), Resend Email, Delete
- **Add Installment:** Modal form with amount, due date, payment method, and optional payment link
- **Payment methods:** Link, PIX, Wise
- Color-coded rows: green (paid), red (overdue), amber (missing link)

### 6.3 Create / Edit Booking

`GET /bookings/create` | `GET /bookings/{id}/edit`

**Form Fields:**

| Field           | Description                                  |
|-----------------|----------------------------------------------|
| Client          | Select from existing clients                 |
| Tour            | Select from existing tours (or enter manually) |
| Start Date      | Booking start date                           |
| Currency        | Payment currency                             |
| Total Value     | Booking total amount                         |
| Travelers       | Number of travelers                          |
| Discount Notes  | Optional discount description                |
| Status          | Pending, Confirmed, Cancelled, Completed     |
| Notes           | General notes                                |

---

## 7. Payment Cockpit

`GET /pagamentos` — **All Users**

The Payment Cockpit provides a centralized, cross-booking view of all installments — the primary tool for day-to-day payment operations and follow-ups.

### Summary Cards

- **Pending** — count of pending installments
- **Paid** — count of paid installments
- **Overdue** — count of overdue installments
- **Missing Link** — installments with link method but no URL
- **Currency Totals** — pending amounts grouped by currency

### Advanced Filters

- Status (Pending, Paid, Overdue, Missing Link)
- Payment method (Link, PIX, Wise)
- Tour selection
- Date range (from / to)
- Text search by client or tour name
- Configurable results per page (10, 25, 50, 100)

### Installments Table

- **Columns:** Status, Due Date, Client, Tour, Installment #, Amount, Currency, Method, Link, Last Email, Actions
- **Color-coded rows:** Red (overdue), Amber (missing link), Green (paid)
- **Actions:** Mark as Paid, Edit (redirects to booking), Resend Email, Toggle Email Automation (pause/resume)

---

## 8. Email Templates

### 8.1 Template List

`GET /email-templates` — **All Users**

- Templates organized by type in collapsible accordion cards
- Count badge showing number of templates per type
- Table per type: Name, Subject (truncated), Actions
- Quick actions: Edit, Preview (opens in new tab), Delete

### 8.2 Create / Edit Template

`GET /email-templates/create` | `GET /email-templates/{id}/edit`

**Form Fields:**

| Field    | Description                                      |
|----------|--------------------------------------------------|
| Name     | Internal template identifier                     |
| Type     | Template category                                |
| Subject  | Email subject line (supports placeholders)       |
| Body     | Email body content with placeholder support      |

### 8.3 Template Preview

`GET /email-templates/{id}/preview`

- Rendered preview of the email template
- Shows subject and body with sample placeholder values

---

## 9. System Settings

`GET /configuracoes` — **Admin, Manager**

Settings are organized in accordion sections:

### Payment Messages
Customize messages displayed for each payment method (Link, PIX, Wise). These messages are included in payment emails sent to clients.

### PIX Instructions
General PIX payment instruction template with bank details and transfer guidelines.

### Email Automation
Toggle automatic email sending at each interval:
- 7 days before due date
- 3 days before due date
- On the due date
- 1 day after due date
- 7 days after due date

### SMTP Configuration
- Mail Host and Port
- Username and Password
- Encryption method (TLS / SSL)
- From Name and From Email address

### Cron Information
Instructions and cron command for setting up the Laravel task scheduler that powers automated email sending.

---

## 10. Logs (Email & Activity)

`GET /logs` — **Admin, Manager**

Tabbed interface with two sections:

### Email Logs Tab

- **Filters:** Date range, Trigger type (Manual / Automatic), Search by client
- **Table columns:** Date/Time, Client, Template, Subject, Status (Sent / Failed), Trigger Type, Actions
- **View action:** Opens modal with full email content
- Pagination and empty state handling

### Activity Logs Tab

- **Filters:** Date range, Entity type (Tour / Client / Booking / Installment), Search by action
- **Table columns:** Date/Time, Action, Entity Type, Entity ID, IP Address, Details
- **Details action:** Opens modal with JSON-formatted activity data
- Full audit trail of all CRUD operations in the system

---

## 11. User Management

`GET /users` — **Admin Only**

### User List
- **Table columns:** Avatar + Name, Email, Role, Created Date, Actions
- Color-coded role badges: Admin (red), Manager (yellow), Viewer (gray)
- "You" indicator on the currently logged-in user
- Actions: Edit, Delete (cannot delete yourself)

### Create / Edit User

`GET /users/create` | `GET /users/{id}/edit`

**Form Fields:**

| Field     | Description                             |
|-----------|-----------------------------------------|
| Name      | User display name                       |
| Email     | Login email address                     |
| Password  | Required on create, optional on edit    |
| Role      | Admin, Manager, or Viewer               |

---

## 12. User Profile

`GET /perfil` — **All Users**

### Profile Information
- Avatar with gradient background and user initial
- Editable fields: Name, Email
- Read-only display: Role (with colored badge), Member since date

### Change Password
- Current password verification
- New password with confirmation field
- Minimum 8-character requirement

---

## 13. Role-Based Access Control (RBAC)

### Role Definitions

| Role        | Description                                                                                                              |
|-------------|--------------------------------------------------------------------------------------------------------------------------|
| **Admin**   | Full system access. Can manage all resources including users, settings, and logs.                                         |
| **Manager** | Access to all operational modules. Can manage tours, clients, bookings, payments, settings, and view logs. Cannot manage users. |
| **Viewer**  | Read-only access to dashboard, tours, clients, bookings, and payments. Cannot create, edit, delete, or access administration pages. |

### Permission Matrix

| Module / Action                    | Admin | Manager | Viewer |
|------------------------------------|:-----:|:-------:|:------:|
| Dashboard                          |   Y   |    Y    |   Y    |
| Tours — View                       |   Y   |    Y    |   Y    |
| Tours — Create / Edit              |   Y   |    Y    |   -    |
| Clients — View                     |   Y   |    Y    |   Y    |
| Clients — Create / Edit / Delete   |   Y   |    Y    |   -    |
| Bookings — View                    |   Y   |    Y    |   Y    |
| Bookings — Create / Edit / Delete  |   Y   |    Y    |   -    |
| Payment Cockpit — View             |   Y   |    Y    |   Y    |
| Payments — Mark Paid / Send Email  |   Y   |    Y    |   -    |
| Email Templates                    |   Y   |    Y    |   -    |
| System Settings                    |   Y   |    Y    |   -    |
| Logs (Email & Activity)            |   Y   |    Y    |   -    |
| User Management                    |   Y   |    -    |   -    |
| Profile (own)                      |   Y   |    Y    |   Y    |

---

## 14. Common UI Patterns & Design

### Navigation & Layout
- **Sidebar:** Dark sidebar with MOJO branding, grouped navigation (Principal, Operacional, Sistema, Administracao), user profile section at bottom
- **Top Bar:** Sticky header with page title, subtitle, theme toggle (light/dark), and user info
- **Theme:** Light and dark mode with seamless toggle and localStorage persistence

### Data Display
- **Stat Cards:** Color-coded KPI cards with icons for quick metric overview
- **Data Tables:** Paginated, filterable tables with action buttons and color-coded rows
- **Status Badges:** Consistent color-coded badges (Pending, Confirmed, Active, Overdue, etc.)
- **Currency Badges:** Styled currency indicators (USD, EUR, BRL)
- **Empty States:** Friendly messages when no data matches current filters

### Interaction Patterns
- **Filter Bars:** Consistent filtering interface with search, dropdowns, and date pickers
- **Modal Forms:** In-page forms for quick actions (add/edit installments)
- **Confirmation Dialogs:** Required for destructive actions (delete, mark as paid)
- **Accordion Sections:** Collapsible groups for settings and template organization
- **Tabs:** Tabbed interfaces for related data views (Email / Activity logs)

### Responsive Design
- Mobile-responsive layout with collapsible sidebar
- Scrollable data tables on small screens
- Bootstrap 5.3.3 grid system for adaptive layouts

---

*MOJO Safaris & Tours — RPMS Feature Documentation — Version 1.0 — March 2026*
*Generated for internal use. All rights reserved.*
