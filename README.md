# Village Meeting Information System

## Project structure

- `index.php`: public landing page and upcoming meetings
- `auth/`: login, registration, and logout
- `admin/`: administrator dashboard and user management
- `chairman/`: meeting, agenda, attendance, announcement, minutes, resolution, SMS, feedback, and profile modules
- `citizen/`: citizen dashboard and published village information
- `meetings/`: chairman meeting creation workflow
- `config/`: database connection and authentication helpers
- `includes/`: shared layout and helper files
- `assets/`: shared stylesheets and form styles
- `database/village_management_db.sql`: database schema and current local data export

## Main routes

- `/index.php`
- `/auth/login.php`
- `/auth/register.php`
- `/admin/dashboard.php`
- `/chairman/dashboard.php`
- `/citizen/dashboard.php`

## Setup

1. Start Apache and MySQL in XAMPP.
2. Create or import the `village_management_db` database.
3. Update database credentials in `config/db.php` if needed.
4. Open the project through XAMPP at `/village meeting projects/`.

## Online deployment

1. Create a PHP/MySQL hosting account and a database.
2. Upload the project files to the hosting document root.
3. Import `database/village_management_db.sql` through phpMyAdmin.
4. Set `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`, and `APP_URL` in the hosting environment.
5. Keep the database credentials out of GitHub.

The root application is the canonical source. The old duplicate `public/` application tree has been removed.
