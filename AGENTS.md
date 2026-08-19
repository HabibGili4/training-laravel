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

Product CRUD follows Repository Pattern:

```
Form Request → Controller → Service → Repository Interface → Eloquent Repository → Model
```

- **Contracts** (`app/Contracts/`): Interfaces for dependency inversion
- **Services** (`app/Services/`): Business logic only, no direct DB queries
- **Repositories** (`app/Repositories/Eloquent/`): All Eloquent queries live here
- **Controllers** (`app/Http/Controllers/`): HTTP layer only, delegates to services
- **Form Requests** (`app/Http/Requests/`): Validation rules, no business logic

Binding is in `AppServiceProvider::register()` — if you add a new repository, bind it there.

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

Follow this exact order:

1. Migration → `database/migrations/`
2. Model → `app/Models/`
3. Repository Interface → `app/Contracts/`
4. Repository Implementation → `app/Repositories/Eloquent/`
5. Service → `app/Services/`
6. Form Requests → `app/Http/Requests/`
7. Controller → `app/Http/Controllers/`
8. Routes → `routes/api.php` (use `Route::apiResource`)
9. Binding → `app/Providers/AppServiceProvider.php`
10. Tests → `tests/Feature/`
11. Factory → `database/factories/`

## Key Conventions

- Models use PHP 8 attributes: `#[Fillable([...])]` (not `$fillable` property)
- Casts use method: `protected function casts(): array` (not `$casts` property)
- API responses always wrap in `{"success": bool, "message": string, "data": ...}`
- Validation messages are in Bahasa Indonesia
- No business logic in controllers or repositories — keep it in services
