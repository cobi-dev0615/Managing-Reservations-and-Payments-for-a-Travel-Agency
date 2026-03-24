# RPMS - Reservation & Payment Management System

## Project Overview

**Project Name:** RPMS (Reservation & Payment Management System)
**Platform:** Web Application (Laravel 12)
**Company:** MOJO Safaris & Tours
**URL:** admin.mojosafaris.com
**Languages:** English, Portuguese (switchable)
**Currencies:** BRL (Real), USD (Dollar), EUR (Euro), ZAR (Rand)

RPMS is an internal management platform built for MOJO Safaris & Tours to manage tour reservations, client relationships, payment installments, and automated email notifications. The system provides role-based access control, a payment cockpit for financial oversight, and a configurable email automation engine.

---

## User Types & Permissions

The system has three user roles with distinct access levels:

| Feature | Administrator | Manager | Viewer |
|---|:---:|:---:|:---:|
| Dashboard (full analytics) | Yes | Yes | - |
| Dashboard (own data only) | - | - | Yes |
| View Tours | Yes | Yes | Yes |
| Create/Edit/Delete Tours | Yes | Yes | - |
| View Clients | Yes | Yes | Own only |
| Create/Edit/Delete Clients | Yes | Yes | - |
| View Bookings | Yes | Yes | Own only |
| Create/Edit/Delete Bookings | Yes | Yes | - |
| Manage Installments | Yes | Yes | - |
| Payment Cockpit | Yes | Yes | Own only |
| Email Templates | Yes | Yes | - |
| Settings | Yes | Yes | - |
| Logs | Yes | Yes | - |
| User Management | Yes | - | - |
| Profile | Yes | Yes | Yes |

### User Statuses

- **Pending** - Newly registered users awaiting admin approval
- **Approved** - Active users with full role-based access
- **Suspended** - Deactivated users who cannot log in

### Registration & Approval Flow

1. New users register with name, email, password, and role (Manager or Viewer)
2. Account is created with "Pending" status
3. Administrator reviews and approves or suspends the user
4. Users created directly by an Administrator are auto-approved

---

## Page Structure & Functions

### 1. Authentication Pages

#### Login Page (`/login`)
- Email and password authentication
- "Remember me" option for persistent sessions
- Displays toast notifications for:
  - Invalid credentials
  - Pending account approval
  - Suspended account
  - Expired session (CSRF token)
- Links to registration page

#### Registration Page (`/register`)
- Fields: Name, Email, Password, Confirm Password, Role (Manager/Viewer)
- Admin role is excluded from self-registration
- After registration, user is redirected to login with an informational message
- Account requires admin approval before access is granted

---

### 2. Dashboard (`/`)

**Admin & Manager View:**
- **KPI Cards:** Total bookings, active tours, total clients, pending installments count, pending amount
- **Pending Values by Currency:** Breakdown of pending amounts per currency (BRL, USD, EUR, ZAR)
- **Revenue Chart:** Monthly revenue bar chart grouped by currency
- **Upcoming Installments:** List of installments due soon with status, client, tour, and amount
- **Overdue Installments:** List of past-due installments requiring attention
- **Payment Status Chart:** Doughnut chart showing pending/paid/overdue/missing-link distribution
- **Tour Type Chart:** Doughnut chart showing group/private/agency/influencer distribution
- **Recent Activity:** Latest activity log entries

**Viewer View:**
- Simplified dashboard showing only the viewer's own data
- Own bookings count, own clients count, own pending installments

---

### 3. Tours (`/tours`)

#### Tour List Page (`/tours`)
- Table with columns: Name, Code, Type, Currency, Slots (capacity/occupied), Status
- **Filters:** Tour type (Group, Private, Agency, Influencer), Status (Active, Inactive), Text search
- **Pagination:** Configurable per-page (10, 25, 50, 100)
- Status badge shows Active (green) or Inactive (gray)
- Capacity bar shows visual fill indicator

#### Create Tour (`/tours/create`)
- **Fields:**
  - Tour Name (required)
  - Tour Code (required, unique)
  - Tour Type: Group, Private, Agency, Influencer (required)
  - Default Currency: BRL, USD, EUR, ZAR
  - Max Travelers (optional - leave empty for unlimited)
  - Notes (optional)

#### Edit Tour (`/tours/{tour}/edit`)
- Same fields as creation, pre-populated with existing data

#### Tour Details (`/tours/{tour}`)
- Tour information: name, code, type, currency, status, capacity, notes
- Traveler count (sum of confirmed bookings)
- Toggle status button (Active/Inactive)
- Associated bookings table with client name, start date, currency, value, status

---

### 4. Clients (`/clients`)

#### Client List Page (`/clients`)
- Table with columns: Name, Email, Bookings count
- **Search:** Filter by name or email
- **Pagination:** Configurable per-page
- Viewers see only clients associated with their own bookings

#### Create Client (`/clients/create`)
- **Fields:**
  - Name (required)
  - Email (optional)
  - Notes (optional)

#### Edit Client (`/clients/{client}/edit`)
- Same fields as creation, pre-populated

#### Client Details (`/clients/{client}`)
- Client information: name, email, notes
- Associated bookings table with tour name, start date, currency, value, status

---

### 5. Bookings (`/bookings`)

#### Booking List Page (`/bookings`)
- Table with columns: Client, Tour, Start Date, Currency, Value, Status
- **Filters:** Status (Pending, Confirmed, Cancelled, Completed), Tour, Text search
- **Pagination:** Configurable per-page
- Viewers see only their own bookings

#### Create Booking (`/bookings/create`)
- **Client Section:**
  - Client selection (dropdown, required)
- **Tour Section:**
  - Tour mode: Catalog (select from active tours) or Manual (free-text tour name)
- **Booking Details:**
  - Start Date (required)
  - Currency: BRL, USD, EUR, ZAR (required)
  - Total Value (required)
  - Discount Notes (optional)
  - Number of Travelers (required, min: 1)
  - Status: Pending, Confirmed, Cancelled, Completed
  - Notes (optional)
- **Automatic Installments:**
  - Number of Installments (0 = no auto-generation)
  - Payment Method: Link, PIX, Wise
  - When generated, installments start from the current date with monthly intervals
  - The last installment absorbs any rounding difference to ensure the sum matches the total value exactly

#### Edit Booking (`/bookings/{booking}/edit`)
- Same fields as creation, pre-populated
- Does not modify existing installments

#### Booking Details (`/bookings/{booking}`)
- **Booking Information:** ID, client (linked), tour (linked), start date, currency, total value, travelers, status, discount notes, notes
- **Installments Section:**
  - Summary: Total installments count, Installments sum, Total difference (sum vs. total value)
  - Installment table with columns: #, Value, Due Date, Method, Link, Status, Last Email, Actions
  - **Status badges:** Pago (green), Pendente (yellow), Atrasado (red), Falta Link (orange)
  - **Actions per installment:**
    - Mark as Paid (green checkmark) - records payment with timestamp
    - Edit (pencil icon) - opens modal to edit installment number, value, due date, payment method, payment link
    - Resend Email (envelope icon) - manually sends payment reminder or overdue notice
    - Delete (trash icon) - removes installment with confirmation
  - Add Installment button opens modal form

---

### 6. Payment Cockpit (`/pagamentos`)

Central view for monitoring all installments across all bookings.

- **Summary Cards:** Status counts (Pending, Paid, Overdue, Missing Link) and totals per currency
- **Filters:** Status, Payment Method, Tour, Date Range (from/until), Text search
- **Installment Table:** Columns: Client, Tour, Installment #, Value, Currency, Due Date, Method, Link, Status, Last Email, Actions
- **Actions:** Mark as Paid, Edit, Resend Email, Toggle Email Automation (Pause/Resume), Delete
- **Pagination:** Configurable per-page
- Viewers see only installments from their own bookings

---

### 7. Email Templates (`/email-templates`)

#### Template List Page (`/email-templates`)
- Templates grouped by type:
  - **Booking Confirmation** (`confirmacao_reserva`)
  - **Payment Reminder** (`lembrete_pagamento`)
  - **Overdue Notice** (`aviso_atraso`)
  - **Payment Receipt** (`recibo_pagamento`)
- Each template shows name, subject, and action buttons (Edit, Preview, Delete)

#### Create Template (`/email-templates/create`)
- **Fields:**
  - Type (dropdown, required)
  - Name (required)
  - Subject (required)
  - Body (textarea, required)
- **Available Placeholders:**
  - `{client_name}` - Client's full name
  - `{tour_name}` - Tour name
  - `{tour_code}` - Tour code
  - `{amount}` - Installment value
  - `{due_date}` - Installment due date
  - `{payment_link}` - Payment link URL
  - `{pix_instructions}` - PIX payment instructions
  - `{installment_number}` - Installment sequence number
  - `{total_value}` - Booking total value
  - `{currency}` - Booking currency code

#### Edit Template (`/email-templates/{email_template}/edit`)
- Same fields as creation, pre-populated

#### Preview Template (`/email-templates/{email_template}/preview`)
- Renders the template with sample data showing how the email will look
- Displays both subject and body with placeholders replaced

---

### 8. Settings (`/configuracoes`)

#### Payment Messages Section
- **Payment Link Message:** Custom message included in emails when payment method is Link
- **PIX Message:** Custom message included in emails when payment method is PIX
- **Wise Message:** Custom message included in emails when payment method is Wise
- **PIX Instructions:** Detailed PIX payment instructions included via `{pix_instructions}` placeholder

#### Email Automation Section
- Toggle switches for automatic email triggers based on installment due dates:
  - 7 days before due date
  - 3 days before due date
  - On the due date
  - 1 day after due date
  - 7 days after due date

#### SMTP Configuration Section
- **Fields:** SMTP Host, Port, Username, Password, Encryption (TLS/SSL/None), Sender Name, Sender Email
- SMTP settings are stored in the database and loaded dynamically (no server restart required)

#### Test Email Section
- Send a test email to a specified address to verify SMTP configuration

#### Cron Schedule Section
- Displays the cron command needed for server setup to run the Laravel Scheduler
- The scheduler handles automatic email sending based on the automation rules above

---

### 9. Logs (`/logs`)

Two log tabs on a single page:

#### Email Logs Tab
- Table with columns: Client, Tour, Installment, Subject, Type (Manual/Automatic), Status (Sent/Failed), Sent At
- **Filters:** Client name search, Type, Status
- Expandable rows to view full email body
- **Pagination:** Configurable per-page

#### Activity Logs Tab
- Table with columns: User, Action, Entity (type + ID), Details, IP Address, Date
- **Filters:** Entity type, Action search
- Details column shows JSON data of what changed
- **Pagination:** Configurable per-page

---

### 10. User Management (`/users`) - Admin Only

#### User List Page (`/users`)
- Table with columns: Name, Email, Role, Status, Created At, Actions
- Pending approval count banner when users are awaiting approval
- **Actions per user:**
  - Edit - modify user details and role
  - Approve - activate pending user
  - Suspend/Reactivate - toggle user access
  - Delete - remove user (with safeguards against self-deletion and last-admin deletion)

#### Create User (`/users/create`)
- **Fields:** Name, Email, Password, Confirm Password, Role (Admin/Manager/Viewer)
- Users created by admin are automatically approved

#### Edit User (`/users/{user}/edit`)
- **Fields:** Name, Email, Role, New Password (optional)

---

### 11. Profile (`/perfil`)

Available to all authenticated users.

- **Profile Information:** Edit name and email
- **Change Password:** Requires current password, new password, and confirmation
- Displays role and member-since date

---

## Technical Architecture

### Technology Stack
- **Backend:** Laravel 12 (PHP 8.3)
- **Frontend:** Blade Templates, Bootstrap 5, Tailwind CSS 4, Chart.js
- **Database:** MySQL 8
- **Build Tool:** Vite 7
- **Session/Cache/Queue:** Database-driven

### Key Design Patterns
- **RBAC (Role-Based Access Control):** Three-tier role system enforced via middleware
- **Dynamic Configuration:** SMTP and email settings stored in database, loaded at runtime
- **Activity Logging:** All CRUD operations logged with IP tracking
- **Email Automation:** Cron-based scheduler with per-installment pause capability
- **Multi-Currency:** BRL, USD, EUR, ZAR supported across tours and bookings

### Middleware Stack
| Middleware | Purpose |
|---|---|
| `auth` | Ensures user is authenticated |
| `approved` | Ensures user account is approved and not suspended |
| `can.manage` | Restricts access to Admin and Manager roles |
| `role:{role}` | Restricts access to specific roles (e.g., `role:admin`) |
| `SetLocale` | Sets application language from session |

### Database Schema

```
users            - id, name, email, password, role, status, timestamps
tours            - id, name, code, type, default_currency, notes, status, max_travelers, timestamps
clients          - id, name, email, notes, timestamps
bookings         - id, client_id, tour_id, tour_manual, start_date, currency, total_value,
                   discount_notes, num_travelers, status, notes, created_by, timestamps
installments     - id, booking_id, installment_number, amount, due_date, status,
                   payment_method, payment_link, paid_at, last_email_sent_at,
                   last_email_template_id, email_paused, timestamps
email_templates  - id, type, name, subject, body, timestamps
email_logs       - id, installment_id, client_id, template_id, subject, body, status,
                   trigger_type, sent_at, timestamps
activity_logs    - id, action, entity_type, entity_id, details (JSON), ip_address, timestamps
settings         - id, key, value, group, timestamps
sessions         - id, user_id, ip_address, user_agent, payload, last_activity
```

### Error Handling
- **419 (CSRF Expired):** Redirects to login page with "Session expired" toast notification
- **500 (Server Error):** Redirects back to previous page with error toast notification
- **403 (Forbidden):** Custom styled error page with "Back to Dashboard" button
- **404 (Not Found):** Custom styled error page with "Back to Dashboard" button
