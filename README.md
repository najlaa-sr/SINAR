# SINAR — Sistem Informasi Inventaris Perlengkapan Studio

**SINAR** adalah aplikasi web dan layanan RESTful API berbasis **Laravel** serta **MySQL/MariaDB** untuk mengelola inventaris barang perlengkapan studio (kamera, lensa, *lighting*, audio, *tripod*, dan aksesori pendukung) serta mencatat riwayat mutasi stok antar-ruang produksi. Proyek ini dikembangkan untuk memenuhi **Milestone 1 (UTS)** pada mata kuliah **Pengembangan Web**.

---

## Tim Pengembang (5 Anggota)

| No | Nama Lengkap | NIM | Peran | Tanggung Jawab Utama |
| :--- | :--- | :--- | :--- | :--- |
| 1 | **Najla Salsabila Ridwan** | `SI2512004` | Project Coordinator & Documentation Lead (PIC) | Manajemen repositori GitHub & rilis tag `uts-v1.0`, penyusunan proposal, `README.md`, dokumen kebutuhan/ERD, logbook kontribusi, catatan AI, serta pengumpulan LMS. |
| 2 | **Nurianti Sine** | `SI2512010` | Backend Developer — Autentikasi & Master Data | Integrasi login/logout, pengaturan hak akses peran (`admin` & `petugas`), validasi input, serta pengelolaan kategori dan lokasi/ruang studio. |
| 3 | **Muh. Akbar Sapar** | `SI2512001` | Backend Developer — Modul Barang & Mutasi Stok | Pengelolaan katalog perlengkapan studio, logika pencatatan mutasi stok (`masuk`, `keluar`, `pindah`), validasi kecukupan stok, dan transaksi database atomik. |
| 4 | **Marwah Akhiratul** | `SI2512008` | Backend & API QA — Pemasok & Pengujian API | Pengelolaan data pemasok/vendor, fitur riwayat mutasi & filter stok menipis, serta pengujian menyeluruh seluruh *endpoint* API melalui Postman. |
| 5 | **Muh. Farid Alfarizi H.** | `SI2512002` | Database & ORM Lead | Perancangan ERD, struktur basis data 5 tabel inti (*migration*), pengisian data uji sintetis (*seeder*), dan relasi Eloquent ORM. |

---

## Ruang Lingkup & Fitur Utama (Milestone 1 — UTS)

1. **Autentikasi & Pembatasan Hak Akses (RBAC):**
   * Endpoint `login` dan `logout` dengan verifikasi password ter-hash.
   * Dua peran pengguna: **`admin`** (akses penuh data master dan transaksi) dan **`petugas`** (akses baca katalog/lokasi dan pencatatan mutasi harian).
2. **Manajemen Data Master (CRUD):**
   * **Kategori Perlengkapan (`categories`):** Pengelompokan alat studio (Kamera, Lensa, Lighting, Audio, Aksesori).
   * **Lokasi / Ruang Studio (`locations`):** Titik penyimpanan alat (Gudang Utama, Studio Foto A, Studio Podcast/Audio).
   * **Pemasok / Vendor (`suppliers`):** Daftar penyedia perlengkapan studio.
   * **Perlengkapan Studio (`items`):** Katalog barang lengkap dengan kode SKU, jumlah stok saat ini, batas stok minimum, dan kondisi alat.
3. **Transaksi Mutasi Stok (`stock_mutations`) & Aturan Bisnis:**
   * Mendukung tiga jenis mutasi: **`masuk`**, **`keluar`**, dan **`pindah`** ruangan.
   * **Validasi Kecukupan Stok:** Mutasi `keluar` dan `pindah` otomatis ditolak (`422 Unprocessable Entity`) apabila jumlah melebihi stok tersedia.
   * **Transaksi Atomik (`DB::transaction`):** Setiap pencatatan mutasi otomatis memperbarui jumlah stok atau lokasi aktif barang secara konsisten.
4. **Filter & Pelacakan Inventaris:**
   * Filter daftar perlengkapan dengan stok menipis (`stock <= minimum_stock`) melalui parameter `?low_stock=1`.
   * Pelacakan riwayat mutasi lengkap dengan informasi barang, lokasi asal/tujuan, pemasok, dan operator pemroses.

---

## Struktur Basis Data (5 Tabel Bisnis Utama)

Selain tabel bawaan `users` (dengan penambahan kolom `role`), sistem menggunakan 5 tabel bisnis yang saling berelasi:

* **`categories`**: `id`, `code`, `name`, `description`
* **`locations`**: `id`, `code`, `name`, `floor_or_building`
* **`suppliers`**: `id`, `name`, `contact_person`, `phone`, `address`
* **`items`**: `id`, `category_id` (FK), `location_id` (FK), `sku`, `name`, `unit`, `stock`, `minimum_stock`, `condition`
* **`stock_mutations`**: `id`, `reference_no`, `item_id` (FK), `user_id` (FK), `supplier_id` (FK nullable), `from_location_id` (FK nullable), `to_location_id` (FK nullable), `type`, `quantity`, `mutation_date`, `notes`

---

## Daftar Endpoint RESTful API

| Method | Endpoint | Deskripsi | Hak Akses |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Autentikasi masuk pengguna | Publik |
| `POST` | `/api/logout` | Keluar dari sesi pengguna | Admin & Petugas |
| `GET` | `/api/categories` | Menampilkan daftar kategori perlengkapan | Admin & Petugas |
| `POST` | `/api/categories` | Menambahkan kategori baru | Admin |
| `PUT` | `/api/categories/{id}` | Memperbarui data kategori | Admin |
| `DELETE` | `/api/categories/{id}` | Menghapus kategori | Admin |
| `GET` | `/api/locations` | Menampilkan daftar lokasi/ruang studio | Admin & Petugas |
| `POST` | `/api/locations` | Menambahkan lokasi baru | Admin |
| `PUT` | `/api/locations/{id}` | Memperbarui data lokasi | Admin |
| `DELETE` | `/api/locations/{id}` | Menghapus lokasi | Admin |
| `GET` | `/api/suppliers` | Menampilkan daftar pemasok/vendor | Admin & Petugas |
| `POST` | `/api/suppliers` | Menambahkan pemasok baru | Admin |
| `PUT` | `/api/suppliers/{id}` | Memperbarui data pemasok | Admin |
| `DELETE` | `/api/suppliers/{id}` | Menghapus pemasok | Admin |
| `GET` | `/api/items` | Daftar perlengkapan studio (mendukung filter `?low_stock=1`) | Admin & Petugas |
| `POST` | `/api/items` | Menambahkan perlengkapan studio baru | Admin |
| `GET` | `/api/items/{id}` | Detail perlengkapan studio beserta riwayat mutasinya | Admin & Petugas |
| `PUT` | `/api/items/{id}` | Memperbarui data perlengkapan studio | Admin |
| `DELETE` | `/api/items/{id}` | Menghapus data perlengkapan studio | Admin |
| `GET` | `/api/mutations` | Menampilkan riwayat transaksi mutasi stok | Admin & Petugas |
| `POST` | `/api/mutations` | Mencatat transaksi mutasi baru (`masuk`, `keluar`, `pindah`) | Admin & Petugas |

---

## Teknologi yang Digunakan

* **Back-End Framework:** Laravel (PHP)
* **ORM:** Eloquent ORM
* **Basis Data:** MySQL / MariaDB
* **Pengujian API:** Postman Collection
* **Version Control:** Git & GitHub (Single Branch Workflow: `main`)