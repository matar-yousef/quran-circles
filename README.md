<div align="center">

# 📖 Quran Circles Management System

**A modern web application for managing Quran memorisation circles and tracking student progress effectively.**

[Features](#features) · [Built With](#built-with) · [Getting Started](#getting-started) · [Project Areas](#project-areas) · [Testing](#testing) · [Author](#author)

</div>

---

## About the Project

**Quran Circles Management System** is a full-stack web application designed to manage Quran memorisation circles and track student progress. It helps teachers organise students, record daily progress, create study plans, manage assessments, and review comprehensive performance reports.

This project was built as part of a professional portfolio to demonstrate clean architecture, practical data handling, and structured educational workflow design.

## Features

- **Teacher and Circle Management:** User registration and a dedicated Quran circle for each teacher.
- **Student Management:** Create, update, search, and organise student records; import students in bulk from Excel files.
- **Study and Revision Plans:** Create customised memorisation and revision plans for students.
- **Daily Tracking:** Record memorisation, revision, and attendance progress each day.
- **Smart Calculations:** Automatically calculate completed pages from selected Surah and Ayah ranges.
- **Assessments:** Manage student assessments and record results.
- **Analytics and Reports:** Review performance statistics, attendance, absences, and detailed reports from a central dashboard.
- **Parent Portal:** A dedicated parent-facing page for tracking a student's progress and attendance.

## Built With

- **Backend:** PHP 8.3, Laravel 13, Eloquent ORM
- **Frontend:** Blade templates and Tailwind CSS
- **Database:** SQLite for local development, with support for any database supported by Laravel
- **Tools and Packages:** Vite, npm, Composer, Git, and Laravel Excel

## Getting Started

### Prerequisites

- PHP 8.3 or later
- Composer
- Node.js and npm

### Installation

1. Clone the repository and navigate into the project directory:

   ```bash
   git clone https://github.com/matar-yousef/quran-circles.git
   cd quran-circles
   ```

2. Install the PHP and JavaScript dependencies:

   ```bash
   composer install
   npm install
   ```

3. Create the environment file and generate the application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows PowerShell, use this command instead:

   ```powershell
   Copy-Item .env.example .env
   ```

4. Configure the database settings in `.env`. For local SQLite development, make sure the SQLite database file exists and the connection is configured correctly.

5. Run database migrations and seeders:

   ```bash
   php artisan migrate --seed
   ```

6. Build frontend assets and start the local development server:

   ```bash
   npm run build
   php artisan serve
   ```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser.

### Development Mode

Run these commands in separate terminals while developing:

```bash
php artisan serve
```

```bash
npm run dev
```

## Project Areas

| Area | Description |
| --- | --- |
| Dashboard | Centralised overview of system statistics, attendance, and performance metrics. |
| Circles and Students | Student profile management, search functions, and Excel data import. |
| Plans and Progress | Creation of memorisation goals and daily tracking of recitation and revision. |
| Exams and Reports | Management of testing outcomes and generation of progress reports. |
| Parent Portal | Parent access to student progress and attendance information. |

## Testing

Run the automated test suite with:

```bash
php artisan test
```

## Security

- The `.env` file is excluded from version control.
- Never commit database credentials, access keys, or real student data to a public repository.

## License

This project is available for portfolio and educational purposes.

## Author

**Yousef Matar**

- GitHub: [@matar-yousef](https://github.com/matar-yousef)
- LinkedIn: [Yousef Matar](https://www.linkedin.com/in/yousef-matar-28264a422/)
- Email: [dev.yousef.matar@gmail.com](mailto:dev.yousef.matar@gmail.com)
