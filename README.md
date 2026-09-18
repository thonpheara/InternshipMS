# 🎓 SmartIntern — Internship Management System

SmartIntern is a modern web application built with **Laravel 12** and **Tailwind CSS (Vite)** designed to bridge the gap between students seeking internships and companies offering opportunities, overseen by a centralized admin dashboard.

---

## 📋 Table of Contents
- [System Requirements](#-system-requirements)
- [Step-by-Step Installation & Setup](#-step-by-step-installation--setup)
- [Default Login Credentials](#-default-login-credentials)
- [Running the Application](#-running-the-application)
- [Updating the Project After Changes](#-updating-the-project-after-changes)
- [Useful Commands & Troubleshooting](#-useful-commands--troubleshooting)

---

## 💻 System Requirements

Before running the project, make sure you have installed:
- **PHP** >= 8.2 (with `pdo_mysql`, `fileinfo`, `mbstring`, `openssl` extensions enabled)
- **Composer** (PHP dependency manager)
- **Node.js** (v18.x or later) & **npm**
- **MySQL / MariaDB** (via XAMPP, Laragon, WampServer, or native MySQL server)
- **Git** (optional, for cloning)

---

## 🚀 Step-by-Step Installation & Setup

### Step 1: Open Terminal & Navigate to Project Directory
Open PowerShell, Command Prompt, or your IDE terminal inside the project directory:
```bash
cd "d:\USEA\Years 3\S2\Android App Development\SmartIntern"
```

---

### Step 2: Install Dependencies

1. **Install PHP Dependencies (Composer):**
   ```bash
   composer install
   ```

2. **Install Node.js Dependencies (npm):**
   ```bash
   npm install
   ```

---

### Step 3: Configure Environment File (`.env`)

1. **Copy the example environment file:**
   - **Windows (PowerShell):**
     ```powershell
     Copy-Item .env.example .env
     ```
   - **Windows (CMD):**
     ```cmd
     copy .env.example .env
     ```
   - *Or manually duplicate `.env.example` and rename it to `.env`.*

2. **Generate the Application Encryption Key:**
   ```bash
   php artisan key:generate
   ```

---

### Step 4: Configure the Database

1. **Start your MySQL server** (e.g., click **Start** for Apache and MySQL in XAMPP / Laragon).
2. **Create the Database:**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`) or MySQL CLI.
   - Create a new database named: `intern_db` (with collation `utf8mb4_unicode_ci`).
3. **Verify Database settings in `.env`:**
   Open the `.env` file and confirm your connection parameters:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=intern_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Change `DB_USERNAME` and `DB_PASSWORD` if your MySQL server uses non-default credentials).*

---

### Step 5: Run Database Migrations & Seeders

Run the database migrations and seed the initial administrator account:
```bash
php artisan migrate --seed
```

> **Note:** If you ever need to reset and re-seed the database with fresh data:
> ```bash
> php artisan migrate:fresh --seed
> ```

---

### Step 6: Create Storage Symbolic Link

To ensure uploaded resumes, profile pictures, and company logos display properly:
```bash
php artisan storage:link
```

---

## 🏃 Running the Application

To run the full development environment, you will need to keep both the **Laravel backend** and the **Vite frontend server** running.

### Option A: Running in Two Terminals (Recommended)

1. **Terminal 1 — Laravel Backend Server:**
   ```bash
   php artisan serve
   ```
   *The server starts at `http://127.0.0.1:8000`.*

2. **Terminal 2 — Vite Asset Server:**
   ```bash
   npm run dev
   ```
   *Compiles Tailwind CSS and enables hot-reload.*

---

### Option B: Running with Single Dev Command
You can also launch everything simultaneously with:
```bash
composer run dev
```

---

### Step 7: Open the Application in Browser
Once both servers are running, open your web browser and navigate to:
```
http://127.0.0.1:8000
```
or
```
http://localhost:8000
```

---

## 🔑 Default Login Credentials

### 1. System Administrator
- **URL:** `http://127.0.0.1:8000/login`
- **Email:** `admin@gmail.com`
- **Password:** `1234567890`
- **Role:** Administrator (full access to manage users, listings, approvals, and system logs)

### 2. Company & Student Accounts
- Companies and Students can self-register via the registration page:
- **Registration URL:** `http://127.0.0.1:8000/register`
- Select either **Student** or **Company** role during signup.

---

## 🔄 Updating the Project After Changes

Whenever you update, pull new changes, or modify the codebase (such as adding new migrations, routes, or UI components), follow these simple steps to ensure the project runs smoothly:

### 1. Apply New Database Migrations
If new database tables or columns have been added (e.g. messaging, posts, or applications):
```bash
php artisan migrate
```

### 2. Install / Update Dependencies
If packages in `composer.json` or `package.json` were updated:
```bash
composer install
npm install
```

### 3. Clear and Refresh Application Caches
Ensure Laravel recognizes newly registered routes, views, and configuration updates:
```bash
php artisan optimize:clear
```
*(This single command safely clears config, routes, views, and cache simultaneously).*

### 4. Keep Both Dev Servers Running
Ensure your development servers are active in two separate terminal windows:
- **Terminal 1 (Laravel Backend):**
  ```bash
  php artisan serve
  ```
- **Terminal 2 (Vite CSS/JS hot reload):**
  ```bash
  npm run dev
  ```

---

## 🛠 Useful Commands & Troubleshooting

| Issue / Action | Command / Solution |
| :--- | :--- |
| **Clear configuration cache** | `php artisan config:clear` |
| **Clear route cache** | `php artisan route:clear` |
| **Clear application cache** | `php artisan cache:clear` |
| **Clear compiled Blade views** | `php artisan view:clear` |
| **Reset entire database with seed** | `php artisan migrate:fresh --seed` |
| **Production asset build** | `npm run build` |
| **Database connection error** | Ensure MySQL is running in XAMPP/Laragon and the database `intern_db` exists. |
| **Vite styles not loading** | Ensure `npm run dev` is running in an active terminal window. |

---

## 📄 License
This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
