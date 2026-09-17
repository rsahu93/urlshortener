# URL Shortener

A multi-tenant URL shortener built with Laravel 12 and MySQL, per the assignment brief. Companies
have users; users have exactly one of five roles (`SuperAdmin`, `Admin`, `Member`, `Sales`,
`Manager`); role determines what a user can create, see, and invite.

## Setup

Requirements: PHP 8.2+, Composer, MySQL, Node (optional — only needed if you touch front-end assets;
the views here are plain server-rendered Blade with no build step).

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your local MySQL credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) — the
defaults assume a local MySQL with an empty root password and a database called `url_shortener`.
Create that database first:

```sql
CREATE DATABASE url_shortener;
```

Then:

```bash
php artisan migrate --seed
php artisan serve
```

Visit `http://localhost:8000`.

### Seeded SuperAdmin credentials

The `SuperAdminSeeder` inserts exactly one SuperAdmin using raw SQL (`DB::statement`, not Eloquent —
per the assignment's explicit requirement), with a bcrypt-hashed password:

```
Email:    superadmin@example.com
Password: password

```

The seeder is idempotent — running `--seed` again won't create a duplicate or error out.


### A note on this build environment

This project was assembled in a sandboxed container without access to `packagist.org`, so
`composer install` could not actually be executed here — only PHP itself and Composer were
available, and the framework/package source had to be scaffolded from the official `laravel/laravel`
12.x skeleton on GitHub plus hand-written application code. Everything under `app/`, `database/`,
`routes/`, and `resources/views/` is real, complete code (syntax-checked with `php -l` throughout),
but it has not been executed against a live MySQL instance or through PHPUnit. Run `composer install`
and the commands above locally to actually boot it — that's the one step this environment couldn't do
for you.

---
I used ChatGPT mainly for syntax references, debugging errors, and understanding Laravel concepts. The overall architecture, business logic, implementation, and problem-solving were done by me.