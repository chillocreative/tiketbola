<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
  <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue.js" />
  <img src="https://img.shields.io/badge/Inertia.js-2.x-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
  <img src="https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
</p>

<h1 align="center">TiketBola</h1>

<p align="center">
  <strong>A modern web form submission system with admin verification and WhatsApp notification powered by Sendora API.</strong>
</p>

<p align="center">
  Built with Laravel + Inertia.js + Vue 3 — a full-stack single-page application<br/>
  with real-time admin dashboard, one-click verification, and automated WhatsApp messaging.
</p>

---

## Overview

**TiketBola** is a streamlined web application that allows public users to submit requests through an elegant form, and administrators to manage, review, and verify those submissions. Upon verification, the system automatically sends a WhatsApp notification to the user via the **Sendora API**.

### How It Works

```
User submits form  -->  Data saved to DB  -->  Admin reviews  -->  Clicks "Verify"
                                                                        |
                                                                        v
                                                              WhatsApp sent via Sendora
                                                              Status updated to "verified"
```

---

## Features

| Feature | Description |
|---------|-------------|
| **Public Submission Form** | Clean, responsive form with validation (name, phone, email, message) |
| **Admin Authentication** | Secure login powered by Laravel Breeze |
| **Dashboard with Stats** | Real-time counters for total, pending, and verified submissions |
| **Submissions Management** | Paginated table with status badges and one-click verify |
| **WhatsApp Notifications** | Automated messages via Sendora API on verification |
| **Sendora Settings UI** | Admin panel to configure API URL, token, sender number |
| **Connection Testing** | Test Sendora API connectivity from the settings page |
| **Encrypted API Token** | API token stored encrypted at rest using Laravel Crypt |
| **Message Logging** | Every WhatsApp send attempt logged with status, response, and errors |
| **Graceful Degradation** | App continues working even if Sendora API is down or misconfigured |
| **Malaysia Phone Formatting** | Auto-converts `01x` numbers to `601x` format |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | PHP 8.3, Laravel 13 |
| **Frontend** | Vue 3, Inertia.js 2 |
| **Styling** | Tailwind CSS |
| **Auth** | Laravel Breeze |
| **Database** | MySQL 8 |
| **HTTP Client** | Axios (frontend), Laravel HTTP (backend) |
| **WhatsApp API** | Sendora (`sendora.id/api/v1`) |

---

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18 LTS
- MySQL 8.x

### Setup

```bash
# 1. Clone the repository
git clone https://github.com/chillocreative/tiketbola.git
cd tiketbola

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Environment configuration
cp .env.example .env
php artisan key:generate
```

### Database

```bash
# 5. Create MySQL database
mysql -u root -e "CREATE DATABASE tiketbola CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Update .env with your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tiketbola
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Run migrations and seed admin user
php artisan migrate
php artisan db:seed
```

### Build & Run

```bash
# Development (run in two terminals)
php artisan serve          # Backend  -> http://localhost:8000
npm run dev                # Vite     -> http://localhost:5173

# Production build
npm run build
```

---

## Default Admin Credentials

| Field | Value |
|-------|-------|
| **Email** | `admin@tiketbola.com` |
| **Password** | `password` |

> **Warning:** Change these immediately in production.

---

## Project Structure

```
tiketbola/
├── app/
│   ├── Http/Controllers/
│   │   ├── SubmissionController.php      # Public form + admin CRUD + verify
│   │   └── SendoraSettingController.php  # Sendora config + test connection
│   ├── Models/
│   │   ├── Submission.php                # Form submission model
│   │   ├── SendoraSetting.php            # Sendora config (encrypted token)
│   │   └── WhatsappMessage.php           # WhatsApp message log
│   └── Services/
│       └── WhatsappService.php           # Sendora API integration
├── database/migrations/
│   ├── create_submissions_table          # name, phone, email, message, status
│   ├── create_sendora_settings_table     # api_url, api_token, sender, active
│   └── create_whatsapp_messages_table    # phone, message, status, response
├── resources/js/Pages/
│   ├── Submissions/Create.vue            # Public form page
│   ├── Dashboard.vue                     # Admin dashboard with stats
│   └── Admin/
│       ├── Submissions/Index.vue         # Submissions list + verify
│       └── SendoraSettings.vue           # Sendora configuration UI
└── routes/web.php                        # All route definitions
```

---

## Routes

| Method | URI | Name | Description |
|--------|-----|------|-------------|
| `GET` | `/` | `submissions.create` | Public submission form |
| `POST` | `/submissions` | `submissions.store` | Store new submission |
| `GET` | `/dashboard` | `dashboard` | Admin dashboard |
| `GET` | `/admin/submissions` | `admin.submissions` | List all submissions |
| `POST` | `/admin/submissions/{id}/verify` | `admin.submissions.verify` | Verify & send WhatsApp |
| `GET` | `/admin/sendora` | `admin.sendora.edit` | Sendora settings form |
| `POST` | `/admin/sendora` | `admin.sendora.update` | Save Sendora settings |
| `POST` | `/admin/sendora/test` | `admin.sendora.test` | Test API connection |

---

## Sendora WhatsApp Integration

### Configuration

Navigate to **Admin Panel -> Sendora Settings** and fill in:

| Field | Description |
|-------|-------------|
| API URL | Sendora API base URL (default: `https://sendora.id/api/v1`) |
| API Token | Your Sendora authentication token (stored encrypted) |
| Sender Number | WhatsApp sender number registered with Sendora |
| Timeout | API request timeout in seconds (default: 30) |
| Active | Enable/disable WhatsApp sending |

### Security

- API token is **encrypted at rest** using Laravel's `Crypt` facade
- Token is **never exposed** to the frontend
- Settings page only shows whether a token exists, never the value
- All API calls are server-side only

### Message Logging

Every WhatsApp message attempt is logged in the `whatsapp_messages` table:

| Column | Description |
|--------|-------------|
| `phone` | Formatted recipient phone number |
| `message` | Message content sent |
| `status` | `pending` / `sent` / `failed` |
| `response` | Full API response (JSON) |
| `error` | Error message if failed |
| `submission_id` | Link to the related submission |

---

## Environment Variables

```env
APP_NAME=TiketBola
APP_URL=http://tiketbola.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tiketbola
DB_USERNAME=root
DB_PASSWORD=
```

---

## Production Deployment

```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions (Linux)
chmod -R 775 storage bootstrap/cache
```

---

## License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  Built with passion by <a href="https://github.com/chillocreative"><strong>Chillo Creative</strong></a>
</p>
