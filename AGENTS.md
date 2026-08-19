# AGENTS.md

## Quick Commands

```bash
# Setup (first time)
composer setup          # installs deps, copies .env, generates key, migrates, builds assets

# Development
php artisan serve       # starts dev server on :8000
npm run dev             # starts Vite dev server

# Testing (uses PostgreSQL etalio_test on port 5433)
php artisan test                                    # all tests
php artisan test --filter=ProductTest               # product tests only
php artisan test --filter=test_create_product_success  # single test

# Code style
./vendor/bin/pint        # Laravel Pint (PSR-12)
```

## Architecture

Product CRUD follows this flow:

```
Routes → Form Request → Controller → Service → Model → Database
```

- **Form Requests** (`app/Http/Requests/`): Validation rules, no business logic
- **Controllers** (`app/Http/Controllers/`): HTTP layer only, delegates to services
- **Services** (`app/Services/`): Business logic, uses Eloquent directly
- **Models** (`app/Models/`): Eloquent ORM, represents database tables

## Database

- **Production**: PostgreSQL on port 5433 (user: `etalio`, db: `etalio_app`)
- **Testing**: PostgreSQL on port 5433 (db: `etalio_test`) — configured in `phpunit.xml`
- **NOT SQLite** — `.env.example` defaults to sqlite but actual `.env` uses pgsql. Tests also use pgsql. Do not assume SQLite is available.

## Testing Quirks

- Tests use `RefreshDatabase` trait — each test gets a clean database
- Test database `etalio_test` must exist before running tests
- Factories are in `database/factories/` — use them for test data, don't hardcode
- Feature tests go in `tests/Feature/`, Unit tests in `tests/Unit/`

## Adding a New Entity (e.g., Category)

Follow this order:

1. Migration → `database/migrations/`
2. Model → `app/Models/`
3. Service → `app/Services/`
4. Form Requests → `app/Http/Requests/`
5. Controller → `app/Http/Controllers/`
6. Routes → `routes/api.php` (use `Route::apiResource`)
7. Tests → `tests/Feature/`
8. Factory → `database/factories/`

## Key Conventions

- Models use PHP 8 attributes: `#[Fillable([...])]` (not `$fillable` property)
- Casts use method: `protected function casts(): array` (not `$casts` property)
- API responses always wrap in `{"success": bool, "message": string, "data": ...}`
- Validation messages are in Bahasa Indonesia
- No business logic in controllers — keep it in services
