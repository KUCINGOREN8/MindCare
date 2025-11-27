# 🧠 Be Okay — Online Mental Health Counseling Platform

<p align="center">
  <strong>Supporting SDG 3: Good Health and Well-being</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/License-MIT-green" alt="License"/>
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

---

## 📖 About Be Okay

Be Okay is an online counseling platform that connects users with licensed psychologists for virtual therapy sessions. The platform promotes mental health awareness and provides accessible counseling services through a user-friendly web interface.

## ⚙️ Installation Guide

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/KUCINGOREN8/BeOkay.git
cd BeOkay
```

### 2️⃣ Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies (including Tailwind CSS)
npm install
```

### 3️⃣ Environment Configuration

```bash
# Copy environment file
make .env file
```

### Database Setup

```bash
Edit your `.env` file:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=
```

### 5️⃣ Run Migrations & Seeders

```bash
php artisan migrate --seed
```

### 7️⃣ Start Development Server

Open **two terminal windows**:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (for Tailwind CSS):**
```bash
npm run dev
```

🎉 **Your application is now running at:** http://127.0.0.1:8000

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

<p align="center">Made with ❤️ for better mental health</p>
