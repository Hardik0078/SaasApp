#  SaaS App — Multi-Tenant Project & Task Management System

> **Laravel 12** · **Sanctum Auth** · **stancl/tenancy** · **MySQL 8.0** · **Stripe Billing**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)


---

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Local Installation](#-local-installation)
- [Environment Configuration](#-environment-configuration)
- [Database Setup](#-database-setup)
- [Running the Application](#-running-the-application)
- [Production Deployment](#-production-deployment)
  - [Docker](#option-c-docker)
- [Queue Workers](#-queue-workers)
- [Scheduler](#-scheduler)
- [Stripe Configuration](#-stripe-configuration)
- [Testing](#-testing)
- [Useful Artisan Commands](#-useful-artisan-commands)
- [Troubleshooting](#-troubleshooting)

---

## ✨ Features

- 🏢 **Multi-Tenancy** — Fully isolated tenant workspaces via `stancl/tenancy`
- 🔐 **Token Auth** — Laravel Sanctum-powered API tokens
- 👥 **RBAC** — 4 roles: Super Admin, Company Admin, Manager, Employee
- 📋 **Projects & Tasks** — Full lifecycle management with history tracking
- 💬 **Comments & Attachments** — File uploads via Spatie MediaLibrary
- 📊 **Audit Logs** — Complete activity trail via Spatie ActivityLog
- 💳 **Billing** — Stripe integration with manual billing fallback
- 🔔 **Notifications** — DB + Email notifications for task events
- ⚡ **Queue Support** — Laravel queues for async notification delivery

---

## 📦 Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.2 or higher |
| Composer | 2.x |
| Node.js | 18.x or higher |
| npm | 9.x or higher |
| MySQL | 8.0+ (or PostgreSQL 14+ / SQLite) |
| Redis | 6.x+ *(optional, for queues/cache)* |

**Required PHP Extensions:**

```
BCMath, Ctype, cURL, DOM, Fileinfo, JSON,
Mbstring, OpenSSL, PDO, PDO_MySQL, Tokenizer, XML, ZIP
```

---

## 💻 Local Installation

### Step 1 — Clone the Repository

```bash
git clone https://github.com/Hardik0078/SaasApp.git
cd SaasApp
```

### Step 2 — Install PHP Dependencies

```bash
composer install
```

### Step 3 — Install Node Dependencies

```bash
npm install
```

### Step 4 — Copy Environment File

```bash
cp .env.example .env
```

### Step 5 — Generate Application Key

```bash
php artisan key:generate
```

---

## ⚙️ Environment Configuration

Open `.env` and configure the following sections:

### Application

```env
APP_NAME="SaaS App"
APP_ENV=local
APP_KEY=          # auto-filled by key:generate
APP_DEBUG=true
APP_URL=http://localhost:8000
```

### Database

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saasapp
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Mail

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@saasapp.test"
MAIL_FROM_NAME="SaaS App"
```

> 💡 For local development, [Mailtrap](https://mailtrap.io) or [Mailpit](https://github.com/axllent/mailpit) work great.

### Queue

```env
QUEUE_CONNECTION=database    # use 'redis' in production
```

### Cache & Session

```env
CACHE_STORE=database         # use 'redis' in production
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

### Stripe (optional for local)

```env
STRIPE_KEY=pk_test_xxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxx
```

### Filesystem

```env
FILESYSTEM_DISK=public      
```

---

## 🗄️ Database Setup

### Step 1 — Create Database

```sql
CREATE DATABASE saasapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2 — Run Migrations

```bash
php artisan migrate
```

This creates all tables including: `tenants`, `users`, `projects`, `tasks`, `task_comments`, `task_histories`, `company_subscriptions`, `billing_invoices`, `permissions`, `roles`, and more.

### Step 3 — Seed the Database

```bash
php artisan db:seed
```

This seeds:
- Roles: `Super Admin`, `Company Admin`, `Manager`, `Employee`
- Permissions: `projects.view`, `projects.manage`, `tasks.view`, `tasks.manage`, `users.manage`, `subscription.manage`, `audit.view`
- Subscription Plans: `Starter ($49/month)`, `Growth ($499/year)`
- Default platform admin user

> ⚠️ **Note:** Do NOT run `db:seed` in production after initial setup — it will attempt to re-seed roles and plans.

### Step 4 — Create Storage Symlink

```bash
php artisan storage:link
```

---

## ▶️ Running the Application

### Development Server

```bash
php artisan serve
```

App will be available at `http://localhost:8000`.

### Compile Assets (if applicable)

```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

### Start Queue Worker (required for notifications)

```bash
php artisan queue:work
```

---

### Option B: VPS / Ubuntu Server *(Recommended)*

#### 1. Server Requirements

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql \
  php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl \
  php8.2-zip php8.2-intl php8.2-fileinfo

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js 20.x
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL 8.0
sudo apt install -y mysql-server
sudo mysql_secure_installation

# Install Nginx
sudo apt install -y nginx

# Install Redis (for queues/cache)
sudo apt install -y redis-server
```

#### 2. Clone & Configure

```bash
cd /var/www
sudo git clone https://github.com/Hardik0078/SaasApp.git SaasApp
cd saasapp

sudo composer install --no-dev --optimize-autoloader
sudo npm install && sudo npm run build

sudo cp .env.example .env
sudo php artisan key:generate
```

Edit `.env` with production values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
FILESYSTEM_DISK=public
```

#### 3. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/SaasApp
sudo chmod -R 755 /var/www/SaasApp
sudo chmod -R 775 /var/www/Saasapp/storage
sudo chmod -R 775 /var/www/SaasApp/bootstrap/cache
```

#### 4. Run Migrations & Optimize

```bash
sudo php artisan migrate --force
sudo php artisan db:seed --force
sudo php artisan storage:link
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo php artisan event:cache
```

#### 5. Nginx Configuration

Create `/etc/nginx/sites-available/SaasApp`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/SaasApp/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/saasapp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

#### 6. SSL with Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

### Option C: Docker

#### 1. Build & Start Containers

```bash
docker compose up -d --build
```

#### 2. Run Setup Commands

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose exec app php artisan storage:link
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
```

> 💡 Make sure your `docker-compose.yml` defines services for `app` (PHP-FPM), `nginx`, `mysql`, and optionally `redis`.

---

## ⚙️ Queue Workers

SaaS App uses Laravel queues to dispatch email notifications asynchronously. In production, use **Supervisor** to keep workers running.

### Install Supervisor

```bash
sudo apt install -y supervisor
```

### Create Worker Config

Create `/etc/supervisor/conf.d/saasapp-worker.conf`:

```ini
[program:saasapp-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/saasapp/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/saasapp/storage/logs/worker.log
stopwaitsecs=3600
```

### Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start saasapp-worker:*
```

---

## ⏰ Scheduler

Add the Laravel scheduler to crontab:

```bash
sudo crontab -e -u www-data
```

Add this line:

```cron
* * * * * cd /var/www/saasapp && php artisan schedule:run >> /dev/null 2>&1
```

---

## 💳 Stripe Configuration

### 1. Set Keys in `.env`

```env
STRIPE_KEY=pk_live_xxxxxxxxxxxx
STRIPE_SECRET=sk_live_xxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxx
```

### 2. Register Webhook in Stripe Dashboard

Set the webhook endpoint to:

```
https://yourdomain.com/stripe/webhook
```

Listen for these events:
- `invoice.payment_succeeded`
- `invoice.payment_failed`
- `customer.subscription.updated`
- `customer.subscription.deleted`

### 3. Sync Stripe Price IDs

Update the `stripe_price_id` column in `subscription_plans` to match your Stripe Price IDs:

```sql
UPDATE subscription_plans SET stripe_price_id = 'price_xxxx' WHERE slug = 'starter-monthly';
UPDATE subscription_plans SET stripe_price_id = 'price_yyyy' WHERE slug = 'growth-yearly';
```

---

## 🛠️ Useful Artisan Commands

```bash
# Clear all caches
php artisan optimize:clear

# Re-cache for production
php artisan optimize

# View all registered routes
php artisan route:list

# Create a new tenant manually (if command exists)
php artisan tenant:create "Company Name" "company-slug" "admin@company.com"

# View queue status
php artisan queue:monitor

# Retry failed jobs
php artisan queue:retry all

# View activity log
php artisan activitylog:clean --days=90
```

---

## 🐛 Troubleshooting

### 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Fix permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Tenant Could Not Be Resolved (404)
- Ensure the correct slug is used in the URL: `/api/tenant/{slug}/...`
- Or pass the `X-Tenant: {slug}` header as fallback.
- Confirm the tenant exists: `SELECT * FROM tenants WHERE slug = 'your-slug';`

### Migrations Failing
```bash
# Check DB connection
php artisan db:show

# Run fresh migration (⚠️ destroys all data)
php artisan migrate:fresh --seed
```

### Queue Jobs Not Processing
```bash
# Check worker is running
sudo supervisorctl status saasapp-worker:*

# Restart worker
sudo supervisorctl restart saasapp-worker:*

# Process manually (debug)
php artisan queue:work --once -vvv
```

### Permission Errors After Deploy
```bash
php artisan permission:cache-reset
php artisan config:clear
php artisan cache:clear
```

### Storage Files Not Accessible
```bash
# Re-create symlink
php artisan storage:link
```

---

## 📁 Directory Structure

```
saas-app/
├── app/
│   ├── Http/Controllers/Api/   # API controllers
│   ├── Models/                 # Eloquent models
│   ├── Notifications/          # TaskAssigned, TaskCommentAdded
│   ├── Policies/               # ProjectPolicy, TaskPolicy
│   └── Services/               # BillingService, etc.
├── database/
│   ├── migrations/             # All DB migrations
│   └── seeders/                # Role, Permission, Plan seeders
├── routes/
│   └── api.php                 # All /api/tenant/{slug}/... routes
├── storage/
│   └── logs/                   # Application logs
├── tests/
│   ├── Feature/                # Feature tests
│   └── Unit/                   # Unit tests
└── .env.example                # Environment template
```

---

## 🔐 Default Credentials

> ⚠️ **Change these immediately after first login!**

| Role | Email | Password |
|------|-------|----------|
| Platform Admin | `admin@saasapp.test` | `password` |

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit changes: `git commit -m 'Add your feature'`
4. Push to branch: `git push origin feature/your-feature`
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.

---

<p align="center">Built with ❤️ using Laravel 12</p>
