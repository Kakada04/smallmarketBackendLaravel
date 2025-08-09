<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
</p>

# Small Market Web App

A full-stack web application for small market businesses, built with:

- **Laravel** (API backend)
- **Angular 20** (frontend)
- **Tailwind CSS** (responsive UI)
- **MySQL** (database)
- **Railway** (backend hosting)
- **Vercel** (frontend hosting)

---

## 🚀 Features

- Branded, responsive UI with smooth animations
- User authentication and profile management
- Backend API integration with Laravel
- Optimized deployment and environment setup
- Modular component design and clean Git workflow

---

## 🛠️ Tech Stack

| Layer       | Technology         |
|-------------|--------------------|
| Frontend    | Angular 20, Tailwind CSS |
| Backend     | Laravel 10         |
| Database    | MySQL              |
| Hosting     | Railway (API), Vercel (UI) |
| Deployment  | Git + CI/CD        |

---

## 🧪 Local Development

### Backend (Laravel)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --host=0.0.0.0 --port=8080
