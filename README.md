# Training Laravel - Product CRUD

Project training Laravel 13 yang mengimplementasikan **Product CRUD** dengan arsitektur sederhana dan clean.

## Fitur

- ✅ Create Product
- ✅ Read All Products
- ✅ Read Single Product
- ✅ Update Product
- ✅ Delete Product
- ✅ Form Validation (Store & Update)
- ✅ Feature Tests (8 tests, 24 assertions)
- ✅ UI dengan Tailwind CSS

---

## Tech Stack

| Tool | Version | Keterangan |
|------|---------|------------|
| PHP | 8.3+ | Bahasa pemrograman |
| Laravel | 13.x | Framework |
| PostgreSQL | 15+ | Database |
| Tailwind CSS | 4.x | CSS Framework |
| PHPUnit | 12.x | Testing Framework |
| Node.js | 18+ | Untuk build assets |

---

## Arsitektur

Project ini mengikuti arsitektur **Layered Architecture** yang sederhana:

```
Routes
   ↓
Form Request        → Input Validation
   ↓
Controller          → HTTP Handler
   ↓
Service             → Business Logic
   ↓
Model / Eloquent    → Database Query
   ↓
Database            → PostgreSQL
```

### Penjelasan Setiap Layer

| Layer | Lokasi | Tugas |
|-------|--------|-------|
| **Form Request** | `app/Http/Requests/` | Validasi input dari user |
| **Controller** | `app/Http/Controllers/` | Menerima HTTP request, memanggil service |
| **Service** | `app/Services/` | Business logic, langsung pakai Eloquent |
| **Model** | `app/Models/` | Representasi tabel database, Eloquent ORM |

### Kenapa Arsitektur Ini?

1. **Sederhana** — Tidak ada layer yang tidak perlu
2. **Mudah Dipahami** — Junior programmer langsung paham
3. **Cukup untuk CRUD** — Tidak over-engineering
4. **Maintainable** — Code tetap rapi dan terorganisir

---

## Struktur Directory

```
training-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php         # HTTP handler
│   │   └── Requests/
│   │       ├── StoreProductRequest.php       # Validasi create
│   │       └── UpdateProductRequest.php      # Validasi update
│   ├── Models/
│   │   └── Product.php                       # Eloquent model
│   ├── Providers/
│   │   └── AppServiceProvider.php            # Service provider
│   └── Services/
│       └── ProductService.php                # Business logic
├── database/
│   ├── factories/
│   │   └── ProductFactory.php                # Fake data untuk testing
│   └── migrations/
│       └── 2026_08_19_000001_create_products_table.php
├── resources/
│   └── views/
│       └── products/
│           └── index.blade.php               # UI Product
├── routes/
│   ├── api.php                               # API routes
│   └── web.php                               # Web routes
└── tests/
    └── Feature/
        └── ProductTest.php                   # Feature tests
```

---

## Cara Setup

### Prasyarat

Pastikan sudah terinstall:
- PHP 8.3 atau lebih tinggi
- Composer
- Node.js & npm
- PostgreSQL

### Step 1: Clone Repository

```bash
git clone https://github.com/HabibGili4/training-laravel.git
cd training-laravel
```

### Step 2: Install Dependencies PHP

```bash
composer install
```

### Step 3: Install Dependencies Node

```bash
npm install
```

### Step 4: Copy Environment File

```bash
cp .env.example .env
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Konfigurasi Database

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=etalio_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Pastikan database sudah dibuat di PostgreSQL:**

```bash
# Login ke PostgreSQL
psql -U your_username

# Buat database
CREATE DATABASE etalio_app;

# Exit
\q
```

### Step 7: Jalankan Migration

```bash
php artisan migrate
```

### Step 8: Build Frontend Assets

```bash
npm run build
```

### Step 9: Jalankan Development Server

```bash
php artisan serve
```

### Step 10: Akses Aplikasi

- **UI Product**: http://localhost:8000/products
- **API**: http://localhost:8000/api/products

---

## API Endpoints

### Base URL

```
http://localhost:8000/api
```

### Endpoints

| Method | URI | Description | Request Body |
|--------|-----|-------------|--------------|
| `POST` | `/products` | Buat produk baru | `{"name": "...", "price": ..., "stock": ...}` |
| `GET` | `/products` | Ambil semua produk | - |
| `GET` | `/products/{id}` | Ambil satu produk | - |
| `PUT` | `/products/{id}` | Update produk | `{"name": "...", "price": ..., "stock": ...}` |
| `DELETE` | `/products/{id}` | Hapus produk | - |

### Contoh Request & Response

#### Create Product

**Request:**
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name": "Kopi Arabica", "price": 25000, "stock": 10}'
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 1,
        "name": "Kopi Arabica",
        "price": "25000.00",
        "stock": 10,
        "created_at": "2026-08-19T10:00:00.000000Z",
        "updated_at": "2026-08-19T10:00:00.000000Z"
    }
}
```

#### Get All Products

**Request:**
```bash
curl http://localhost:8000/api/products
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Products retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Kopi Arabica",
            "price": "25000.00",
            "stock": 10
        }
    ]
}
```

#### Get Product by ID

**Request:**
```bash
curl http://localhost:8000/api/products/1
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Product retrieved successfully",
    "data": {
        "id": 1,
        "name": "Kopi Arabica",
        "price": "25000.00",
        "stock": 10
    }
}
```

#### Update Product

**Request:**
```bash
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name": "Kopi Robusta", "price": 30000, "stock": 20}'
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Product updated successfully",
    "data": {
        "id": 1,
        "name": "Kopi Robusta",
        "price": "30000.00",
        "stock": 20
    }
}
```

#### Delete Product

**Request:**
```bash
curl -X DELETE http://localhost:8000/api/products/1 \
  -H "Accept: application/json"
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Product deleted successfully"
}
```

### Validation Error

**Request:**
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name": "", "price": -100, "stock": -5}'
```

**Response (422 Unprocessable Entity):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["Nama produk wajib diisi."],
        "price": ["Harga produk tidak boleh negatif."],
        "stock": ["Stok produk tidak boleh negatif."]
    }
}
```

### 404 Not Found

**Request:**
```bash
curl http://localhost:8000/api/products/9999
```

**Response (404 Not Found):**
```json
{
    "success": false,
    "message": "Product not found"
}
```

---

## Testing

### Jalankan Semua Tests

```bash
php artisan test
```

### Jalankan Tests Tertentu

```bash
# Jalankan hanya ProductTest
php artisan test --filter=ProductTest

# Jalankan test tertentu
php artisan test --filter=test_create_product_success
```

### Test Coverage

| Test | Description |
|------|-------------|
| `test_create_product_success` | Test create produk berhasil |
| `test_create_product_validation_error` | Test validasi error saat create |
| `test_get_products_success` | Test ambil semua produk |
| `test_get_product_by_id_success` | Test ambil produk berdasarkan ID |
| `test_get_product_by_id_not_found` | Test 404 jika produk tidak ditemukan |
| `test_update_product_success` | Test update produk berhasil |
| `test_update_product_validation_error` | Test validasi error saat update |
| `test_delete_product_success` | Test hapus produk berhasil |

**Total: 8 tests, 24 assertions** ✅

---

## Troubleshooting

### 1. Database Connection Failed

**Error:**
```
SQLSTATE[08006] connection refused
```

**Solusi:**
- Pastikan PostgreSQL sudah running
- Cek konfigurasi di `.env` (host, port, username, password)
- Pastikan database sudah dibuat

### 2. Port 8000 Sudah Digunakan

**Error:**
```
Address already in use
```

**Solusi:**
```bash
# Gunakan port lain
php artisan serve --port=8001
```

### 3. Migration Gagal

**Error:**
```
Table already exists
```

**Solusi:**
```bash
# Rollback migration lalu jalankan ulang
php artisan migrate:refresh
```

### 4. PHP Extension Tidak Terinstall

**Error:**
```
Class 'pgsql' not found
```

**Solusi:**
```bash
# Ubuntu/Debian
sudo apt-get install php-pgsql

# macOS
brew install php
```

### 5. Node Modules Error

**Solusi:**
```bash
# Hapus node_modules lalu install ulang
rm -rf node_modules
npm install
```

---

## Flow Development

Ketika menambahkan fitur baru, ikuti urutan ini:

1. **Migration** — Buat/tabel database
2. **Model** — Buat Eloquent model
3. **Service** — Buat business logic di `app/Services/`
4. **Form Request** — Buat validasi di `app/Http/Requests/`
5. **Controller** — Buat HTTP handler di `app/Http/Controllers/`
6. **Routes** — Daftarkan route di `routes/api.php`
7. **Tests** — Buat tests di `tests/Feature/`
8. **UI** — Buat view di `resources/views/`

---

## Kontribusi

1. Fork repository ini
2. Buat branch baru (`git checkout -b feature/nama-fitur`)
3. Commit perubahan (`git commit -m 'feat: tambah fitur baru'`)
4. Push ke branch (`git push origin feature/nama-fitur`)
5. Buat Pull Request

---

## License

MIT License

---

## Author

**HabibGili4** - [GitHub](https://github.com/HabibGili4)
