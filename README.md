# QMMC Janitorial Evaluation System — Setup Guide

## Prerequisites
- PHP 8.2+
- Composer
- SQLite (default) or MySQL 8+

---

## 1. Install into existing Laravel project

Copy files from this package into the matching folders of your Laravel project:

```
app/Http/Controllers/Admin/        ← Admin controllers
app/Http/Controllers/Evaluator/    ← Evaluator controller
app/Http/Controllers/Janitor/      ← Janitor controller
app/Http/Controllers/AuthController.php
app/Http/Middleware/CheckRole.php
app/Http/Kernel.php                ← replaces existing (adds 'role' alias)
app/Models/                        ← all models
app/Services/EvaluationService.php
database/migrations/               ← all 7 migration files
database/seeders/DatabaseSeeder.php
resources/views/                   ← all blade views
routes/web.php                     ← replaces existing
```

---

## 2. Install PDF package

```bash
composer require barryvdh/laravel-dompdf
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 3. Run migrations and seed

```bash
php artisan migrate:fresh --seed
```

Default admin credentials after seeding:
- Email:    admin@qmmc.gov.ph
- Password: Admin@1234

**Change the admin password immediately after first login.**

---

## 4. Configure .env

```env
APP_NAME="QMMC Evaluation"
APP_URL=http://localhost

# Use SQLite (default):
DB_CONNECTION=sqlite

# Or MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=qmmc_evaluation
# DB_USERNAME=root
# DB_PASSWORD=secret

SESSION_DRIVER=file
CACHE_STORE=file
```

---

## 5. Storage and cache

```bash
php artisan key:generate
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## URL Structure

| Role      | URL         | Description                    |
|-----------|-------------|--------------------------------|
| Admin     | /admin      | Full system control            |
| Evaluator | /evaluator  | Assigned janitors + form       |
| Janitor   | /portal     | Personal evaluation dashboard  |
| Auth      | /login      | Shared login page              |

---

## Workflow

### Admin workflow:
1. Create areas (pre-seeded from dataset)
2. Add janitors and assign areas
3. Create evaluator accounts
4. Assign janitors to evaluators (Assignments page)
5. Review evaluation records and export PDFs

### Evaluator workflow:
1. Log in → see assigned janitors
2. Click "Evaluate" on any janitor
3. Select area, fill in the checklist
4. Submit → score computed automatically

### Janitor workflow:
1. Admin creates janitor user account linked to janitor profile
2. Janitor logs in → sees their own evaluation scores and history

---

## Adding PDF support for list export

The list PDF view is at:
  `resources/views/admin/evaluations/pdf_list.blade.php`

Create this similarly to `pdf.blade.php` but as a landscape summary table.

---

## Security notes

- All routes are protected by the `auth` + `role` middleware chain
- Inactive accounts are blocked at the middleware level and at login
- Evaluators can only see/submit evaluations for their assigned janitors
- Janitors can only see their own evaluation records
- CSRF protection is active on all POST/PUT/DELETE requests
- Passwords are hashed with bcrypt via Laravel's `hashed` cast

---

## Optional: Audit logging

The `audit_logs` table is already migrated. To enable automatic logging,
add an `AuditObserver` to your models:

```php
// In AppServiceProvider::boot()
Janitor::observe(AuditObserver::class);
Evaluation::observe(AuditObserver::class);
User::observe(AuditObserver::class);
```

Then implement `AuditObserver` to write to the `audit_logs` table
on created/updated/deleted events.