# PRD — Sistem Informasi Pembayaran SPP (SIP-SPP)

**Versi:** 1.1.0  
**Tanggal:** Juni 2026  
**Status:** Draft  
**Author:** Tim Pengembang  

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Latar Belakang & Masalah](#2-latar-belakang--masalah)
3. [Tujuan & Sasaran](#3-tujuan--sasaran)
4. [Ruang Lingkup](#4-ruang-lingkup)
5. [Stakeholder & Role](#5-stakeholder--role)
6. [User Stories](#6-user-stories)
7. [Spesifikasi Fitur](#7-spesifikasi-fitur)
8. [Alur Sistem (Flow Diagram)](#8-alur-sistem)
9. [Desain Database](#9-desain-database)
10. [API Endpoints](#10-api-endpoints)
11. [Desain UI/UX](#11-desain-uiux)
12. [Tech Stack](#12-tech-stack)
13. [Non-Functional Requirements](#13-non-functional-requirements)
14. [Timeline & Milestone](#14-timeline--milestone)
15. [Risiko & Mitigasi](#15-risiko--mitigasi)
16. [Kriteria Selesai (Definition of Done)](#16-kriteria-selesai)

---

## 1. Ringkasan Eksekutif

**SIP-SPP** (Sistem Informasi Pembayaran SPP) adalah aplikasi web manajemen pembayaran SPP sekolah yang mendigitalisasi proses pencatatan, penagihan, dan pelaporan pembayaran SPP siswa secara end-to-end. Sistem ini menggantikan pencatatan manual (buku/spreadsheet) yang rawan human error dan sulit dilacak secara real-time.

Sistem dibangun menggunakan **Laravel 11** (full-stack) dengan **MySQL 8.0** sebagai database utama, **Blade + Alpine.js** untuk frontend, dan **Spatie Laravel-Permission** untuk manajemen role & akses.

Sistem melayani 4 role pengguna: **Admin**, **Bendahara**, **Wali Murid**, dan **Kepala Sekolah**, masing-masing dengan akses dan tanggung jawab yang berbeda dalam siklus pembayaran SPP.

---

## 2. Latar Belakang & Masalah

### 2.1 Kondisi Saat Ini (Pain Points)

| # | Masalah | Dampak |
|---|---------|--------|
| 1 | Pencatatan SPP masih manual (buku/Excel) | Data rawan hilang, tidak real-time |
| 2 | Wali murid harus datang ke sekolah untuk bayar | Tidak efisien, antrian panjang |
| 3 | Laporan tunggakan dibuat manual setiap bulan | Menyita waktu bendahara, potensi error |
| 4 | Tidak ada notifikasi otomatis tagihan jatuh tempo | Tunggakan meningkat karena lupa |
| 5 | Kepala sekolah tidak punya akses dashboard real-time | Keputusan finansial lambat dan tidak akurat |
| 6 | Kwitansi ditulis tangan atau cetak manual satu per satu | Tidak efisien, rawan palsu |

### 2.2 Kebutuhan Utama

- Digitalisasi pencatatan dan pembayaran SPP
- Transparansi data pembayaran untuk semua pihak sesuai hak akses
- Laporan otomatis dan dapat diunduh
- Kwitansi digital tercetak resmi

---

## 3. Tujuan & Sasaran

### 3.1 Tujuan Bisnis

- Mengurangi tunggakan SPP sebesar **≥ 20%** dalam 6 bulan pertama
- Mengeliminasi **100%** pencatatan manual SPP
- Memperpendek waktu pembuatan laporan bulanan dari **2 hari → < 5 menit**

### 3.2 Tujuan Produk

- Menyediakan dashboard real-time untuk monitoring pembayaran SPP
- Mengotomatisasi pembuatan tagihan SPP bulanan
- Memberikan akses mandiri ke wali murid untuk cek dan bayar tagihan
- Menghasilkan kwitansi resmi tercetak secara otomatis

### 3.3 OKR

| Objective | Key Result |
|-----------|------------|
| Digitalisasi penuh administrasi SPP | 100% transaksi tercatat di sistem dalam 3 bulan |
| Kurangi tunggakan | Tingkat pelunasan bulan berjalan ≥ 90% |
| Efisiensi operasional bendahara | Waktu rekap laporan < 5 menit |
| Kepuasan wali murid | CSAT score ≥ 4.0/5.0 setelah 3 bulan |

---

## 4. Ruang Lingkup

### 4.1 In Scope ✅

- Manajemen data siswa (CRUD)
- Manajemen tarif SPP per kelas/tahun ajaran
- Generate tagihan SPP otomatis per bulan
- Pencatatan dan konfirmasi pembayaran
- Riwayat pembayaran per siswa
- Cetak kwitansi PDF
- Laporan tunggakan (per siswa, per kelas, per bulan)
- Dashboard statistik (Kepala Sekolah & Bendahara)
- Manajemen role dan akses pengguna
- Export laporan ke PDF dan Excel

### 4.2 Out of Scope ❌

- Integrasi payment gateway (QRIS, transfer bank otomatis) — *fase 2*
- Aplikasi mobile (Android/iOS) — *fase 2*
- Manajemen gaji guru/staff
- Sistem akademik (nilai, absensi)
- Multi-sekolah / SaaS mode — *fase 3*

---

## 5. Stakeholder & Role

### 5.1 Matriks Role & Akses

| Fitur | Admin | Bendahara | Wali Murid | Kepala Sekolah |
|-------|:-----:|:---------:|:----------:|:--------------:|
| Dashboard statistik | ✅ Full | ✅ Full | ❌ | ✅ Read-only |
| Manajemen User | ✅ Full | ❌ | ❌ | ❌ |
| Data Siswa | ✅ CRUD | ✅ Read | ✅ Own child | ✅ Read |
| Tarif SPP | ✅ CRUD | ✅ Read | ❌ | ✅ Read |
| Tagihan SPP | ✅ CRUD | ✅ CRUD | ✅ Own child | ✅ Read |
| Input Pembayaran | ✅ | ✅ Full | ✅ Self-pay* | ❌ |
| Riwayat Pembayaran | ✅ All | ✅ All | ✅ Own child | ✅ All |
| Cetak Kwitansi | ✅ | ✅ | ✅ Own | ✅ |
| Laporan Tunggakan | ✅ | ✅ Full | ❌ | ✅ Read |
| Export PDF/Excel | ✅ | ✅ | ✅ Own | ✅ |

> *Self-pay: Wali murid hanya dapat melakukan konfirmasi pembayaran manual (upload bukti transfer). Verifikasi tetap dilakukan Bendahara.

### 5.2 Deskripsi Role

**Admin**
- Superuser sistem
- Mengelola akun pengguna semua role
- Konfigurasi tarif SPP, tahun ajaran, dan kelas
- Akses penuh ke semua data

**Bendahara**
- Operator utama transaksi keuangan
- Input dan verifikasi pembayaran SPP
- Generate dan cetak kwitansi
- Membuat laporan tunggakan

**Wali Murid**
- Melihat tagihan dan riwayat pembayaran anak
- Upload konfirmasi pembayaran
- Download kwitansi pembayaran yang sudah lunas

**Kepala Sekolah**
- Read-only access ke seluruh data finansial
- Monitoring dashboard dan statistik
- Download laporan untuk keperluan manajemen

---

## 6. User Stories

### 6.1 Admin

```
US-A01  Sebagai Admin, saya ingin menambah/edit/hapus data siswa
        agar data siswa selalu up-to-date.

US-A02  Sebagai Admin, saya ingin mengatur tarif SPP per kelas per tahun ajaran
        agar tagihan otomatis sesuai dengan kebijakan sekolah.

US-A03  Sebagai Admin, saya ingin membuat akun pengguna baru untuk
        Bendahara, Wali Murid, dan Kepala Sekolah.

US-A04  Sebagai Admin, saya ingin generate tagihan SPP bulanan secara massal
        agar seluruh siswa mendapatkan tagihan sekaligus di awal bulan.
```

### 6.2 Bendahara

```
US-B01  Sebagai Bendahara, saya ingin mencatat pembayaran SPP siswa
        agar rekap transaksi tersimpan otomatis dan akurat.

US-B02  Sebagai Bendahara, saya ingin mencetak kwitansi pembayaran secara otomatis
        agar tidak perlu tulis tangan.

US-B03  Sebagai Bendahara, saya ingin melihat daftar siswa yang menunggak
        agar bisa melakukan penagihan tepat sasaran.

US-B04  Sebagai Bendahara, saya ingin mengekspor laporan tunggakan ke PDF dan Excel
        agar mudah dilaporkan ke kepala sekolah.

US-B05  Sebagai Bendahara, saya ingin memverifikasi pembayaran dari wali murid
        yang mengupload bukti transfer.
```

### 6.3 Wali Murid

```
US-W01  Sebagai Wali Murid, saya ingin melihat tagihan SPP anak saya
        agar tahu berapa yang harus dibayar dan kapan jatuh temponya.

US-W02  Sebagai Wali Murid, saya ingin melihat riwayat pembayaran anak saya
        agar bisa memantau status lunas/menunggak.

US-W03  Sebagai Wali Murid, saya ingin mengunduh kwitansi pembayaran
        agar bisa menyimpan bukti sebagai arsip.

US-W04  Sebagai Wali Murid, saya ingin mengupload bukti transfer
        agar bendahara bisa memverifikasi pembayaran saya.
```

### 6.4 Kepala Sekolah

```
US-K01  Sebagai Kepala Sekolah, saya ingin melihat dashboard statistik pembayaran
        agar bisa memantau kesehatan keuangan sekolah secara real-time.

US-K02  Sebagai Kepala Sekolah, saya ingin melihat tren pembayaran per bulan
        agar bisa mengidentifikasi pola dan masalah lebih awal.

US-K03  Sebagai Kepala Sekolah, saya ingin melihat daftar siswa tunggakan terbesar
        agar bisa mengambil keputusan kebijakan yang tepat.

US-K04  Sebagai Kepala Sekolah, saya ingin mengekspor laporan keuangan
        agar bisa dipresentasikan di rapat dewan guru.
```

---

## 7. Spesifikasi Fitur

### 7.1 Data Siswa

**Deskripsi:** Modul master data siswa yang menjadi fondasi seluruh sistem.

**Field Data Siswa:**

| Field | Tipe | Keterangan |
|-------|------|------------|
| NIS | VARCHAR(20) | Nomor Induk Siswa, unik |
| Nama Lengkap | VARCHAR(100) | Nama resmi siswa |
| Kelas | FK → kelas | Kelas aktif siswa |
| Tahun Masuk | YEAR | Tahun ajaran masuk |
| Jenis Kelamin | ENUM | L / P |
| Tanggal Lahir | DATE | |
| Alamat | TEXT | |
| Status | ENUM | Aktif / Lulus / Pindah / Keluar |
| Wali Murid | FK → users | Terhubung ke akun wali murid |

**Fungsionalitas:**
- CRUD siswa (Admin)
- Pencarian dan filter berdasarkan kelas, status, nama, NIS
- Import data siswa via file Excel (template tersedia untuk unduh)
- Paginasi dengan default 20 baris per halaman
- Setiap siswa dapat dihubungkan ke 1 akun wali murid

**Validasi:**
- NIS tidak boleh duplikat
- Kelas harus terdaftar di master kelas
- Status Aktif diperlukan agar siswa mendapat tagihan

---

### 7.2 Tagihan SPP

**Deskripsi:** Modul pengelolaan tagihan SPP per siswa per bulan.

**Field Tagihan:**

| Field | Tipe | Keterangan |
|-------|------|------------|
| ID Tagihan | UUID | Primary key |
| Siswa | FK → siswa | |
| Bulan | TINYINT | 1–12 |
| Tahun | YEAR | |
| Jumlah Tagihan | DECIMAL(12,2) | Sesuai tarif kelas |
| Status | ENUM | Belum Bayar / Menunggu Verifikasi / Lunas |
| Jatuh Tempo | DATE | Default tanggal 10 tiap bulan |
| Catatan | TEXT | Opsional, diisi Bendahara |

**Fungsionalitas:**
- **Generate massal:** Admin/Bendahara dapat generate tagihan sekaligus untuk semua siswa aktif di awal bulan, berdasarkan tarif kelas masing-masing
- Tagihan dapat dibuat manual satu per satu (untuk kasus khusus)
- Edit tagihan (jumlah, catatan, jatuh tempo) oleh Admin/Bendahara
- Hapus tagihan hanya oleh Admin (soft delete)
- Filter tagihan: per bulan, per tahun, per kelas, per status
- Indikator warna status: 🔴 Belum Bayar / 🟡 Menunggu Verifikasi / 🟢 Lunas
- Notifikasi in-app untuk tagihan yang mendekati jatuh tempo (H-3)

**Tarif SPP (Master):**
```
Tarif = f(kelas, tahun_ajaran)
Contoh:
  Kelas 7  → IDR 150,000/bulan
  Kelas 8  → IDR 150,000/bulan
  Kelas 9  → IDR 175,000/bulan
```

---

### 7.3 Pembayaran

**Deskripsi:** Modul pencatatan dan verifikasi transaksi pembayaran SPP.

**Field Pembayaran:**

| Field | Tipe | Keterangan |
|-------|------|------------|
| ID Pembayaran | UUID | Primary key |
| ID Tagihan | FK → tagihan | Tagihan yang dibayar |
| Jumlah Bayar | DECIMAL(12,2) | Bisa partial payment |
| Tanggal Bayar | DATETIME | |
| Metode Bayar | ENUM | Tunai / Transfer / QRIS |
| Bukti Transfer | VARCHAR | Path file upload (opsional) |
| Dicatat Oleh | FK → users | Bendahara/Admin yang input |
| Status Verifikasi | ENUM | Pending / Terverifikasi / Ditolak |
| Catatan Verifikasi | TEXT | Alasan jika ditolak |

**Fungsionalitas — Alur Pembayaran:**

```
ALUR 1: Bayar Tunai (Langsung ke Bendahara)
  Siswa datang → Bendahara input pembayaran → Status: Terverifikasi
  → Generate kwitansi otomatis

ALUR 2: Transfer Bank / Upload Bukti (Wali Murid)
  Wali murid upload bukti → Status: Menunggu Verifikasi
  → Bendahara verifikasi → Status: Terverifikasi
  → Generate kwitansi otomatis

ALUR 3: Pembayaran Ditolak
  Bendahara verifikasi → Status: Ditolak
  → Wali murid notifikasi dengan alasan penolakan
  → Wali murid upload ulang bukti
```

**Partial Payment:**
- Sistem mendukung cicilan pembayaran
- Tagihan dianggap "Lunas" hanya jika total bayar ≥ jumlah tagihan
- Sisa tagihan (kekurangan) otomatis tercatat dan ditampilkan

---

### 7.4 Riwayat Pembayaran

**Deskripsi:** Rekap historis semua transaksi pembayaran per siswa.

**Tampilan & Filter:**

| Filter | Opsi |
|--------|------|
| Siswa | NIS / Nama |
| Kelas | Semua / Per Kelas |
| Bulan & Tahun | Pilih range |
| Status | Semua / Lunas / Menunggu / Ditolak |
| Metode Bayar | Semua / Tunai / Transfer / QRIS |

**Fungsionalitas:**
- Tabel riwayat dengan sortasi per kolom
- Detail transaksi: klik baris → modal detail lengkap termasuk bukti transfer
- Export riwayat ke PDF atau Excel
- Wali murid hanya melihat riwayat anak sendiri
- Tampilan timeline per siswa: urutan bulan dengan status masing-masing

**Tampilan Timeline Riwayat (per siswa):**
```
[Jan ✅] [Feb ✅] [Mar ✅] [Apr ✅] [Mei ✅] [Jun ✅]
[Jul ✅] [Agu ✅] [Sep ✅] [Okt ✅] [Nov 🔴] [Des 🔴]
```

---

### 7.5 Cetak Kwitansi

**Deskripsi:** Pembuatan dan cetak kwitansi pembayaran SPP resmi dalam format PDF.

**Konten Kwitansi:**
```
┌──────────────────────────────────────────────────┐
│  [LOGO SEKOLAH]    KWITANSI PEMBAYARAN SPP       │
│  SMP Negeri 1 Example                            │
│  Jl. Pendidikan No. 123, Jakarta                 │
├──────────────────────────────────────────────────┤
│  No. Kwitansi : KWT-2024-10-001234               │
│  Tanggal      : 14 Oktober 2024, 14:30 WIB       │
├──────────────────────────────────────────────────┤
│  Nama Siswa   : Aisha Rahma                      │
│  NIS          : 20240001                         │
│  Kelas        : 9A                               │
│  Pembayaran   : SPP Bulan Oktober 2024           │
│  Jumlah       : Rp 1.500.000                     │
│  Terbilang    : Satu juta lima ratus ribu rupiah │
│  Metode Bayar : Transfer Bank                    │
├──────────────────────────────────────────────────┤
│  Petugas      : [Nama Bendahara]                 │
│                                                  │
│  TTD & Cap Sekolah          QR Verifikasi        │
│                                                  │
│  ___________________        [QR CODE]            │
└──────────────────────────────────────────────────┘
```

**Fungsionalitas:**
- Generate kwitansi PDF otomatis setelah pembayaran terverifikasi
- Nomor kwitansi auto-increment dengan format: `KWT-{YYYY}-{MM}-{NNNNNN}`
- QR Code pada kwitansi untuk verifikasi keaslian
- Cetak ulang kwitansi kapan saja (history tersimpan)
- Kwitansi dapat diunduh sebagai PDF atau dikirim via link berbagi
- Format A5 landscape (ukuran standar kwitansi)

---

### 7.6 Laporan Tunggakan

**Deskripsi:** Laporan komprehensif siswa yang belum melunasi SPP.

**Jenis Laporan:**

| Jenis | Deskripsi |
|-------|-----------|
| Tunggakan Bulan Ini | Siswa belum lunas SPP bulan berjalan |
| Tunggakan Akumulasi | Total utang SPP per siswa dari semua bulan |
| Tunggakan Per Kelas | Rekap tunggakan dikelompokkan per kelas |
| Tunggakan Per Bulan | Perbandingan tunggakan antar bulan (trend) |

**Field Laporan Tunggakan:**

| Kolom | Deskripsi |
|-------|-----------|
| NIS | Nomor induk siswa |
| Nama Siswa | Nama lengkap |
| Kelas | Kelas aktif |
| Bulan Menunggak | Daftar bulan yang belum lunas |
| Jumlah Bulan | Total bulan tunggakan |
| Total Tunggakan | Total IDR yang harus dibayar |
| Kontak Wali | Nomor HP wali murid |

**Fungsionalitas:**
- Filter: per kelas, per rentang bulan, minimum jumlah tunggakan
- Sort: berdasarkan jumlah tunggakan tertinggi / terbanyak bulan
- Export ke PDF (format laporan resmi dengan kop sekolah)
- Export ke Excel (untuk analisis lebih lanjut)
- Ringkasan di atas tabel: total siswa menunggak, total IDR tunggakan, persentase keterlambatan

---

### 7.7 Dashboard

**Deskripsi:** Halaman utama dengan ringkasan statistik keuangan SPP real-time.

**Stat Cards (Header):**

| Card | Deskripsi | Warna |
|------|-----------|-------|
| Total Siswa Aktif | Jumlah siswa terdaftar aktif | Biru |
| Lunas Bulan Ini | Jumlah & persentase siswa lunas | Hijau |
| Menunggak Bulan Ini | Jumlah siswa belum bayar | Merah |
| Total Tunggakan (IDR) | Akumulasi tunggakan semua bulan | Oranye |

**Komponen Dashboard:**

1. **Tren Pembayaran Bulanan** — Line chart IDR terkumpul per bulan (tahun berjalan)
2. **Transaksi SPP Terbaru** — Tabel 10 transaksi terakhir (nama, kelas, bulan, tanggal, jumlah, status)
3. **Pembayaran Terbaru** — Detail transaksi terakhir + tombol cetak kwitansi
4. **Kwitansi Terakhir** — Preview thumbnail kwitansi terakhir diterbitkan
5. **Siswa Tunggakan Tertinggi** — Tabel 5 siswa dengan tunggakan IDR terbesar
6. **Ringkasan Per Kelas** — Bar chart perbandingan persentase lunas antar kelas

---

## 8. Alur Sistem

### 8.1 Alur Generate Tagihan Bulanan

```
[Admin/Bendahara]
       │
       ▼
Pilih Bulan & Tahun
       │
       ▼
Sistem ambil semua siswa berstatus "Aktif"
       │
       ▼
Untuk setiap siswa:
  └── Ambil tarif SPP sesuai kelas siswa
  └── Cek: apakah tagihan bulan ini sudah ada?
        ├── [Sudah Ada] → Skip
        └── [Belum Ada] → Create tagihan baru
               Status: "Belum Bayar"
               Jatuh Tempo: tanggal 10 bulan tersebut
       │
       ▼
Tampilkan ringkasan:
  - X tagihan berhasil dibuat
  - Y tagihan di-skip (sudah ada)
```

### 8.2 Alur Pembayaran & Kwitansi

```
JALUR A — Bayar Tunai:

[Bendahara] → Input Data Pembayaran
    │  (siswa, bulan, jumlah, metode: Tunai)
    ▼
Sistem Update Tagihan → Status: "Lunas"
    │
    ▼
Generate Kwitansi PDF (auto)
    │
    ▼
[Bendahara] Cetak & Serahkan ke Siswa ✅

─────────────────────────────────────────────

JALUR B — Transfer Bank (Wali Murid):

[Wali Murid] → Upload Bukti Transfer
    │
    ▼
Tagihan → Status: "Menunggu Verifikasi" 🟡
    │
    ▼
[Bendahara] Terima Notifikasi
    │
    ├── [Verifikasi ✅]
    │       │
    │       ▼
    │   Tagihan → Status: "Lunas" 🟢
    │       │
    │       ▼
    │   Generate Kwitansi PDF (auto)
    │       │
    │       ▼
    │   [Wali Murid] Download Kwitansi ✅
    │
    └── [Tolak ❌]
            │
            ▼
        Tagihan → Status: "Belum Bayar" 🔴
            │
            ▼
        [Wali Murid] Notifikasi + Alasan Penolakan
            │
            ▼
        Wali Murid Upload Ulang Bukti
```

---

## 9. Desain Database

### 9.1 Entity Relationship Diagram (Overview)

```
users (1) ──────< (N) siswa
  └── [Admin, Bendahara, WaliMurid, KepalaSekolah]

kelas (1) ──────< (N) siswa
kelas (1) ──────< (N) tarif_spp

siswa (1) ──────< (N) tagihan_spp
tagihan_spp (1) < (N) pembayaran
pembayaran (1) ──< (1) kwitansi
```

### 9.2 DDL — Tabel Lengkap

```sql
-- ─────────────────────────────────────────
-- 1. USERS
-- ─────────────────────────────────────────
CREATE TABLE users (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)    NOT NULL,
    email       VARCHAR(150)    NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL,
    role        ENUM('admin','bendahara','wali_murid','kepala_sekolah') NOT NULL,
    phone       VARCHAR(20),
    avatar      VARCHAR(255),
    is_active   TINYINT(1)      NOT NULL DEFAULT 1,
    last_login  DATETIME,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_role (role),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 2. TAHUN AJARAN
-- ─────────────────────────────────────────
CREATE TABLE tahun_ajaran (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(20)     NOT NULL,  -- e.g. "2024/2025"
    tahun_mulai YEAR            NOT NULL,
    tahun_akhir YEAR            NOT NULL,
    is_aktif    TINYINT(1)      NOT NULL DEFAULT 0,
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 3. KELAS
-- ─────────────────────────────────────────
CREATE TABLE kelas (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran_id INT UNSIGNED    NOT NULL,
    nama_kelas      VARCHAR(10)     NOT NULL,  -- e.g. "9A", "8B"
    tingkat         TINYINT         NOT NULL,  -- 7, 8, 9
    wali_kelas      VARCHAR(100),
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id),
    INDEX idx_tahun_kelas (tahun_ajaran_id, tingkat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 4. TARIF SPP
-- ─────────────────────────────────────────
CREATE TABLE tarif_spp (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tahun_ajaran_id INT UNSIGNED        NOT NULL,
    tingkat         TINYINT             NOT NULL,  -- berlaku untuk semua kelas tingkat ini
    jumlah          DECIMAL(12,2)       NOT NULL,
    keterangan      VARCHAR(255),
    created_by      BIGINT UNSIGNED     NOT NULL,
    created_at      DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_tarif (tahun_ajaran_id, tingkat),
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 5. SISWA
-- ─────────────────────────────────────────
CREATE TABLE siswa (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nis             VARCHAR(20)     NOT NULL UNIQUE,
    nama_lengkap    VARCHAR(100)    NOT NULL,
    jenis_kelamin   ENUM('L','P')   NOT NULL,
    tanggal_lahir   DATE,
    alamat          TEXT,
    foto            VARCHAR(255),
    kelas_id        INT UNSIGNED,
    wali_murid_id   BIGINT UNSIGNED,           -- FK ke users (role: wali_murid)
    status          ENUM('aktif','lulus','pindah','keluar') NOT NULL DEFAULT 'aktif',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      DATETIME,                  -- soft delete
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL,
    FOREIGN KEY (wali_murid_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_nis (nis),
    INDEX idx_status (status),
    INDEX idx_kelas (kelas_id),
    FULLTEXT INDEX ft_nama (nama_lengkap)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 6. TAGIHAN SPP
-- ─────────────────────────────────────────
CREATE TABLE tagihan_spp (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    siswa_id        BIGINT UNSIGNED         NOT NULL,
    tahun_ajaran_id INT UNSIGNED            NOT NULL,
    bulan           TINYINT UNSIGNED        NOT NULL,  -- 1-12
    tahun           YEAR                    NOT NULL,
    jumlah_tagihan  DECIMAL(12,2)           NOT NULL,
    total_dibayar   DECIMAL(12,2)           NOT NULL DEFAULT 0.00,
    sisa_tagihan    DECIMAL(12,2)           GENERATED ALWAYS AS (jumlah_tagihan - total_dibayar) STORED,
    status          ENUM('belum_bayar','menunggu_verifikasi','lunas') NOT NULL DEFAULT 'belum_bayar',
    jatuh_tempo     DATE                    NOT NULL,
    catatan         TEXT,
    created_by      BIGINT UNSIGNED         NOT NULL,
    created_at      DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_tagihan (siswa_id, bulan, tahun),
    FOREIGN KEY (siswa_id) REFERENCES siswa(id),
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_status_bulan (status, bulan, tahun),
    INDEX idx_siswa_tahun (siswa_id, tahun)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 7. PEMBAYARAN
-- ─────────────────────────────────────────
CREATE TABLE pembayaran (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tagihan_id          BIGINT UNSIGNED         NOT NULL,
    jumlah_bayar        DECIMAL(12,2)           NOT NULL,
    tanggal_bayar       DATETIME                NOT NULL,
    metode_bayar        ENUM('tunai','transfer','qris') NOT NULL DEFAULT 'tunai',
    bukti_transfer      VARCHAR(500),
    status_verifikasi   ENUM('pending','terverifikasi','ditolak') NOT NULL DEFAULT 'pending',
    catatan_verifikasi  TEXT,
    dicatat_oleh        BIGINT UNSIGNED         NOT NULL,
    diverifikasi_oleh   BIGINT UNSIGNED,
    diverifikasi_at     DATETIME,
    created_at          DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME                NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tagihan_id) REFERENCES tagihan_spp(id),
    FOREIGN KEY (dicatat_oleh) REFERENCES users(id),
    FOREIGN KEY (diverifikasi_oleh) REFERENCES users(id),
    INDEX idx_tagihan (tagihan_id),
    INDEX idx_tanggal (tanggal_bayar),
    INDEX idx_status_verif (status_verifikasi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 8. KWITANSI
-- ─────────────────────────────────────────
CREATE TABLE kwitansi (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_kwitansi  VARCHAR(30)     NOT NULL UNIQUE,  -- KWT-2024-10-000001
    pembayaran_id   BIGINT UNSIGNED NOT NULL UNIQUE,
    file_path       VARCHAR(500),                      -- path PDF tersimpan
    dicetak_oleh    BIGINT UNSIGNED NOT NULL,
    dicetak_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pembayaran_id) REFERENCES pembayaran(id),
    FOREIGN KEY (dicetak_oleh) REFERENCES users(id),
    INDEX idx_nomor (nomor_kwitansi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────
-- 9. NOTIFIKASI
-- ─────────────────────────────────────────
CREATE TABLE notifikasi (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     BIGINT UNSIGNED     NOT NULL,
    judul       VARCHAR(200)        NOT NULL,
    pesan       TEXT                NOT NULL,
    tipe        ENUM('info','warning','success','error') NOT NULL DEFAULT 'info',
    is_read     TINYINT(1)          NOT NULL DEFAULT 0,
    created_at  DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 10. Routes & Controllers (Laravel)

Sistem menggunakan **Laravel web routes** (session-based, Blade SSR) sebagai primary, dengan beberapa **API routes** untuk kebutuhan AJAX/Alpine.js (dashboard stats, autocomplete, verifikasi).

**Auth:** Laravel Sanctum (session guard untuk web, token guard untuk API)  
**RBAC Middleware:** `role:admin`, `role:bendahara`, dsb. — via **Spatie Laravel-Permission**

---

### 10.1 Auth Routes (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/login` | `AuthController@showLogin` | `guest` | Form login |
| POST | `/login` | `AuthController@login` | `guest` | Proses login |
| POST | `/logout` | `AuthController@logout` | `auth` | Logout |
| GET | `/profil` | `AuthController@profil` | `auth` | Halaman profil |
| PUT | `/profil/password` | `AuthController@changePassword` | `auth` | Ganti password |

---

### 10.2 Dashboard (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/dashboard` | `DashboardController@index` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Halaman dashboard |

**API Routes (AJAX) — `routes/api.php`:**

| Method | URI | Controller@Method | Deskripsi |
|--------|-----|-------------------|-----------|
| GET | `/api/dashboard/stats` | `DashboardController@stats` | Stat cards JSON |
| GET | `/api/dashboard/tren` | `DashboardController@tren` | Data chart tren bulanan |
| GET | `/api/dashboard/transaksi-terbaru` | `DashboardController@transaksiTerbaru` | 10 transaksi terbaru |
| GET | `/api/dashboard/tunggakan-tertinggi` | `DashboardController@tunggakanTertinggi` | Top 5 tunggakan |

---

### 10.3 User Management (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/users` | `UserController@index` | `auth`, `role:admin` | List user |
| GET | `/users/create` | `UserController@create` | `auth`, `role:admin` | Form tambah user |
| POST | `/users` | `UserController@store` | `auth`, `role:admin` | Simpan user baru |
| GET | `/users/{id}/edit` | `UserController@edit` | `auth`, `role:admin` | Form edit user |
| PUT | `/users/{id}` | `UserController@update` | `auth`, `role:admin` | Update user |
| DELETE | `/users/{id}` | `UserController@destroy` | `auth`, `role:admin` | Soft delete user |
| PATCH | `/users/{id}/toggle` | `UserController@toggleStatus` | `auth`, `role:admin` | Aktif/nonaktif |

---

### 10.4 Master Data — Kelas & Tarif (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/kelas` | `KelasController@index` | `auth`, `role:admin` | List kelas |
| POST | `/kelas` | `KelasController@store` | `auth`, `role:admin` | Tambah kelas |
| PUT | `/kelas/{id}` | `KelasController@update` | `auth`, `role:admin` | Edit kelas |
| DELETE | `/kelas/{id}` | `KelasController@destroy` | `auth`, `role:admin` | Hapus kelas |
| GET | `/tarif-spp` | `TarifSppController@index` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | List tarif |
| POST | `/tarif-spp` | `TarifSppController@store` | `auth`, `role:admin` | Set tarif baru |
| PUT | `/tarif-spp/{id}` | `TarifSppController@update` | `auth`, `role:admin` | Update tarif |

---

### 10.5 Data Siswa (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/siswa` | `SiswaController@index` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | List siswa + filter |
| GET | `/siswa/create` | `SiswaController@create` | `auth`, `role:admin` | Form tambah siswa |
| POST | `/siswa` | `SiswaController@store` | `auth`, `role:admin` | Simpan siswa |
| GET | `/siswa/{id}` | `SiswaController@show` | `auth`, `can:view,siswa` | Detail siswa |
| GET | `/siswa/{id}/edit` | `SiswaController@edit` | `auth`, `role:admin` | Form edit siswa |
| PUT | `/siswa/{id}` | `SiswaController@update` | `auth`, `role:admin` | Update siswa |
| DELETE | `/siswa/{id}` | `SiswaController@destroy` | `auth`, `role:admin` | Soft delete |
| POST | `/siswa/import` | `SiswaController@import` | `auth`, `role:admin` | Import Excel (Maatwebsite) |
| GET | `/siswa/export` | `SiswaController@export` | `auth`, `role:admin\|bendahara` | Export Excel |

> Policy `SiswaPolicy@view`: wali murid hanya bisa view siswa yang terhubung ke akunnya.

---

### 10.6 Tagihan SPP (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/tagihan` | `TagihanController@index` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | List tagihan + filter |
| GET | `/tagihan/siswa/{siswaId}` | `TagihanController@bySiswa` | `auth`, `can:view,siswa` | Tagihan per siswa |
| GET | `/tagihan/create` | `TagihanController@create` | `auth`, `role:admin\|bendahara` | Form tagihan manual |
| POST | `/tagihan` | `TagihanController@store` | `auth`, `role:admin\|bendahara` | Simpan tagihan manual |
| POST | `/tagihan/generate` | `TagihanController@generateMassal` | `auth`, `role:admin\|bendahara` | **Generate massal** (dispatch Job) |
| GET | `/tagihan/{id}/edit` | `TagihanController@edit` | `auth`, `role:admin\|bendahara` | Form edit tagihan |
| PUT | `/tagihan/{id}` | `TagihanController@update` | `auth`, `role:admin\|bendahara` | Update tagihan |
| DELETE | `/tagihan/{id}` | `TagihanController@destroy` | `auth`, `role:admin` | Soft delete |

> Generate massal di-dispatch ke `GenerateTagihanBulananJob` via Laravel Queue agar tidak timeout.

---

### 10.7 Pembayaran (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/pembayaran` | `PembayaranController@index` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | List pembayaran |
| GET | `/pembayaran/siswa/{siswaId}` | `PembayaranController@bySiswa` | `auth`, `can:view,siswa` | Riwayat per siswa |
| GET | `/pembayaran/create/{tagihanId}` | `PembayaranController@create` | `auth`, `role:admin\|bendahara` | Form input bayar tunai |
| POST | `/pembayaran` | `PembayaranController@store` | `auth`, `role:admin\|bendahara` | Simpan pembayaran tunai |
| POST | `/pembayaran/{tagihanId}/upload-bukti` | `PembayaranController@uploadBukti` | `auth`, `role:wali_murid` | Upload bukti transfer |
| PATCH | `/pembayaran/{id}/verifikasi` | `PembayaranController@verifikasi` | `auth`, `role:admin\|bendahara` | Verifikasi / tolak |
| GET | `/pembayaran/export` | `PembayaranController@export` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Export Excel/PDF |

---

### 10.8 Kwitansi (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/kwitansi/{pembayaranId}` | `KwitansiController@download` | `auth`, `can:downloadKwitansi,pembayaran` | Download PDF (DomPDF) |
| POST | `/kwitansi/generate/{pembayaranId}` | `KwitansiController@generate` | `auth`, `role:admin\|bendahara` | Generate ulang kwitansi |
| GET | `/kwitansi/verify/{nomor}` | `KwitansiController@verify` | `—` (public) | Verifikasi QR code kwitansi |

---

### 10.9 Laporan Tunggakan (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/laporan/tunggakan` | `LaporanController@tunggakan` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Halaman laporan tunggakan |
| GET | `/laporan/tunggakan/export/pdf` | `LaporanController@exportPdf` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Download PDF laporan resmi |
| GET | `/laporan/tunggakan/export/excel` | `LaporanController@exportExcel` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Download Excel |
| GET | `/laporan/rekap-bulanan` | `LaporanController@rekapBulanan` | `auth`, `role:admin\|bendahara\|kepala_sekolah` | Rekap per bulan |

---

### 10.10 Wali Murid — Portal Mandiri (`routes/web.php`)

| Method | URI | Controller@Method | Middleware | Deskripsi |
|--------|-----|-------------------|------------|-----------|
| GET | `/portal` | `PortalWaliController@index` | `auth`, `role:wali_murid` | Dashboard wali murid |
| GET | `/portal/tagihan` | `PortalWaliController@tagihan` | `auth`, `role:wali_murid` | Tagihan anak |
| GET | `/portal/riwayat` | `PortalWaliController@riwayat` | `auth`, `role:wali_murid` | Riwayat pembayaran |
| GET | `/portal/kwitansi/{id}` | `PortalWaliController@kwitansi` | `auth`, `role:wali_murid` | Download kwitansi milik sendiri |

---

## 11. Desain UI/UX

### 11.1 Design System

**Palet Warna:**

| Token | Hex | Penggunaan |
|-------|-----|------------|
| `--primary` | `#1E3A5F` | Navbar, header utama |
| `--primary-light` | `#2563EB` | Tombol primer, link aktif |
| `--success` | `#16A34A` | Status Lunas, card positif |
| `--danger` | `#DC2626` | Status Menunggak, error |
| `--warning` | `#D97706` | Status Menunggu Verifikasi |
| `--surface` | `#F8FAFC` | Background halaman |
| `--card` | `#FFFFFF` | Background card |
| `--text-primary` | `#1E293B` | Teks utama |
| `--text-secondary` | `#64748B` | Teks sekunder, label |
| `--border` | `#E2E8F0` | Border card, divider |

**Tipografi:**
- Display / Heading: **Plus Jakarta Sans** (600–700)
- Body: **Plus Jakarta Sans** (400–500)
- Data / Angka: **DM Mono** (untuk IDR, NIS, nomor kwitansi)

**Border Radius:** `8px` (card), `6px` (tombol), `4px` (input)

### 11.2 Layout

```
┌────────────────────────────────────────────────────┐
│  [NAVBAR] SIP-SPP    [User: Bendahara ▾]  [🔔] [→] │
├──────────┬─────────────────────────────────────────┤
│          │                                         │
│ SIDEBAR  │           MAIN CONTENT                  │
│          │                                         │
│ Dashboard│  [Stat Cards]                           │
│ Data     │  [Chart]          [Right Panel]         │
│  Siswa   │  [Table]                                │
│ Tagihan  │                                         │
│  SPP     │                                         │
│ Pembayar │                                         │
│  an      │                                         │
│ Riwayat  │                                         │
│ Laporan  │                                         │
│          │                                         │
└──────────┴─────────────────────────────────────────┘
```

### 11.3 Halaman Utama per Role

| Role | Halaman Default | Konten Prioritas |
|------|----------------|------------------|
| Admin | Dashboard | Statistik + User Management shortcut |
| Bendahara | Dashboard | Transaksi terbaru + Menunggu verifikasi |
| Wali Murid | Tagihan Anak | Status tagihan bulan ini + riwayat |
| Kepala Sekolah | Dashboard | KPI keuangan + Trend chart |

### 11.4 Responsif

- **Desktop:** Sidebar tetap (280px) + main content full
- **Tablet (≤1024px):** Sidebar collapsible (overlay mode)
- **Mobile (≤768px):** Sidebar hidden default, hamburger menu, bottom navigation untuk wali murid

---

## 12. Tech Stack

### 12.1 Stack Utama

| Layer | Teknologi | Versi | Alasan |
|-------|-----------|-------|--------|
| Framework | **Laravel** | 11.x | Full-stack PHP, ekosistem lengkap, maintainable |
| Language | **PHP** | 8.3 | Typed properties, enums, fibers |
| Database | **MySQL** | 8.0 | Relasional, ACID, FULLTEXT, generated column |
| Frontend View | **Blade + Alpine.js** | — | SSR ringan, interaktif tanpa build step |
| CSS Framework | **Tailwind CSS** | 3.x | Utility-first, konsisten dengan design token |
| Auth | **Laravel Sanctum** | — | Session guard (web) + token guard (API AJAX) |
| RBAC | **Spatie Laravel-Permission** | 6.x | Role & permission granular, middleware bawaan |
| PDF Generator | **barryvdh/laravel-dompdf** | 3.x | Render Blade → PDF, tidak butuh binary eksternal |
| Excel Import/Export | **maatwebsite/excel** | 3.x | Import siswa dari .xlsx, export laporan |
| Queue | **Laravel Queue** (driver: `database`) | — | Generate tagihan massal tanpa timeout |
| Cache | **Redis** via Laravel Cache | — | Cache dashboard stats (TTL 5 menit) |
| File Storage | **Laravel Storage** (local / S3-compatible) | — | Upload bukti transfer, simpan PDF kwitansi |
| QR Code | **simplesoftwareio/simple-qrcode** | — | QR verifikasi pada kwitansi |
| Containerization | **Docker + Docker Compose** | — | Konsisten di semua environment |
| Web Server | **Nginx + PHP-FPM** | — | Production setup |

---

### 12.2 Package Composer (composer.json)

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^11.0",
        "laravel/sanctum": "^4.0",
        "spatie/laravel-permission": "^6.0",
        "barryvdh/laravel-dompdf": "^3.0",
        "maatwebsite/excel": "^3.1",
        "simplesoftwareio/simple-qrcode": "^4.2",
        "intervention/image": "^3.0"
    },
    "require-dev": {
        "laravel/pint": "^1.0",
        "pestphp/pest": "^2.0",
        "pestphp/pest-plugin-laravel": "^2.0",
        "fakerphp/faker": "^1.23"
    }
}
```

---

### 12.3 Struktur Direktori Laravel

```
sip-spp/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── GenerateTagihanBulananCommand.php  ← artisan command (opsional cron)
│   ├── Exports/                                   ← Maatwebsite Export classes
│   │   ├── SiswaExport.php
│   │   ├── PembayaranExport.php
│   │   └── LaporanTunggakanExport.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── KelasController.php
│   │   │   ├── TarifSppController.php
│   │   │   ├── SiswaController.php
│   │   │   ├── TagihanController.php
│   │   │   ├── PembayaranController.php
│   │   │   ├── KwitansiController.php
│   │   │   ├── LaporanController.php
│   │   │   └── Portal/
│   │   │       └── PortalWaliController.php       ← khusus wali murid
│   │   ├── Middleware/
│   │   │   └── CheckActiveUser.php                ← cek is_active sebelum akses
│   │   └── Requests/                              ← Form Request validation
│   │       ├── StoreSiswaRequest.php
│   │       ├── StoreTagihanRequest.php
│   │       ├── StorePembayaranRequest.php
│   │       └── VerifikasiPembayaranRequest.php
│   ├── Imports/
│   │   └── SiswaImport.php                        ← Maatwebsite Import class
│   ├── Jobs/
│   │   └── GenerateTagihanBulananJob.php          ← Queue job generate massal
│   ├── Models/
│   │   ├── User.php
│   │   ├── TahunAjaran.php
│   │   ├── Kelas.php
│   │   ├── TarifSpp.php
│   │   ├── Siswa.php
│   │   ├── TagihanSpp.php
│   │   ├── Pembayaran.php
│   │   ├── Kwitansi.php
│   │   └── Notifikasi.php
│   ├── Policies/
│   │   ├── SiswaPolicy.php                        ← atur akses wali murid ke siswa
│   │   └── PembayaranPolicy.php
│   └── Services/
│       ├── TagihanService.php                     ← logika generate tagihan
│       ├── PembayaranService.php                  ← logika verifikasi & update status
│       ├── KwitansiService.php                    ← render Blade → DomPDF, simpan file
│       └── LaporanService.php                     ← query laporan tunggakan
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_tahun_ajaran_table.php
│   │   ├── 2024_01_01_000003_create_kelas_table.php
│   │   ├── 2024_01_01_000004_create_tarif_spp_table.php
│   │   ├── 2024_01_01_000005_create_siswa_table.php
│   │   ├── 2024_01_01_000006_create_tagihan_spp_table.php
│   │   ├── 2024_01_01_000007_create_pembayaran_table.php
│   │   ├── 2024_01_01_000008_create_kwitansi_table.php
│   │   ├── 2024_01_01_000009_create_notifikasi_table.php
│   │   └── 2024_01_01_000010_create_permission_tables.php  ← Spatie
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RoleSeeder.php                         ← seed 4 role
│       ├── AdminUserSeeder.php
│       └── TahunAjaranSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php                      ← layout utama (sidebar + navbar)
│   │   │   └── auth.blade.php                     ← layout login
│   │   ├── auth/
│   │   │   └── login.blade.php
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── users/
│   │   ├── siswa/
│   │   ├── tagihan/
│   │   ├── pembayaran/
│   │   ├── kwitansi/
│   │   │   └── template.blade.php                 ← template PDF kwitansi
│   │   ├── laporan/
│   │   │   ├── tunggakan.blade.php
│   │   │   └── pdf/
│   │   │       └── tunggakan.blade.php            ← template PDF laporan
│   │   └── portal/                                ← views khusus wali murid
│   │       ├── index.blade.php
│   │       ├── tagihan.blade.php
│   │       └── riwayat.blade.php
│   ├── css/
│   │   └── app.css                                ← Tailwind CSS entry
│   └── js/
│       └── app.js                                 ← Alpine.js init + Axios
├── routes/
│   ├── web.php                                    ← semua halaman (Blade SSR)
│   └── api.php                                    ← endpoint AJAX dashboard
├── storage/
│   └── app/
│       ├── bukti_transfer/                        ← upload bukti dari wali murid
│       └── kwitansi/                              ← PDF kwitansi tersimpan
├── tests/
│   ├── Feature/
│   │   ├── Auth/
│   │   ├── Tagihan/
│   │   ├── Pembayaran/
│   │   └── Laporan/
│   └── Unit/
│       └── Services/
├── docker-compose.yml
├── Dockerfile
└── .env.example
```

---

### 12.4 Konfigurasi Utama (`.env.example`)

```env
APP_NAME="SIP-SPP"
APP_ENV=production
APP_KEY=
APP_URL=https://sip-spp.sekolah.sch.id

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sip_spp
DB_USERNAME=sipspp_user
DB_PASSWORD=

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database

REDIS_HOST=redis
REDIS_PORT=6379

FILESYSTEM_DISK=local
# Ganti ke 's3' jika pakai object storage

DOMPDF_OPTIONS_DEFAULT_PAPER_SIZE=A5
DOMPDF_OPTIONS_DEFAULT_PAPER_ORIENTATION=landscape
```

---

### 12.5 Docker Compose

```yaml
services:
  app:
    build: .
    volumes:
      - .:/var/www/html
      - ./storage:/var/www/html/storage
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: sip_spp
      MYSQL_USER: sipspp_user
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql

  redis:
    image: redis:7-alpine
    volumes:
      - redis_data:/data

  queue:
    build: .
    command: php artisan queue:work --sleep=3 --tries=3
    depends_on:
      - mysql
      - redis

volumes:
  mysql_data:
  redis_data:
```

---

### 12.6 Eloquent Model Relationships (Ringkasan)

```php
// User
User::hasMany(Siswa::class, 'wali_murid_id')    // wali murid → siswa
User::hasMany(Pembayaran::class, 'dicatat_oleh')

// Siswa
Siswa::belongsTo(Kelas::class)
Siswa::belongsTo(User::class, 'wali_murid_id')
Siswa::hasMany(TagihanSpp::class)

// TagihanSpp
TagihanSpp::belongsTo(Siswa::class)
TagihanSpp::hasMany(Pembayaran::class)

// Pembayaran
Pembayaran::belongsTo(TagihanSpp::class)
Pembayaran::hasOne(Kwitansi::class)
Pembayaran::belongsTo(User::class, 'dicatat_oleh')
Pembayaran::belongsTo(User::class, 'diverifikasi_oleh')
```

---

## 13. Non-Functional Requirements

### 13.1 Performa

| Metrik | Target |
|--------|--------|
| Waktu respons API (P95) | < 300ms |
| Generate kwitansi PDF | < 2 detik |
| Export Excel 1000 baris | < 5 detik |
| Generate tagihan massal 1250 siswa | < 10 detik |
| Dashboard load time | < 1.5 detik |

### 13.2 Keamanan

- **Auth:** Laravel Sanctum — session guard untuk web (stateful), token guard untuk API AJAX
- **Session:** driver Redis, lifetime 480 menit (8 jam), cookie `HttpOnly` + `Secure` + `SameSite=Lax`
- **Password:** di-hash dengan **Laravel Hash** (bcrypt, cost factor 12 default)
- **RBAC:** Spatie Laravel-Permission — middleware `role:*` diterapkan per route group; akses yang tidak berwenang mengembalikan `403`
- **Proteksi Form:** Laravel CSRF token (`@csrf`) di setiap form Blade
- **SQL Injection:** Eloquent query builder menggunakan PDO prepared statement secara default; raw query hanya via `DB::select()` dengan binding
- **Upload File:** validasi `mimes`, `max:5120` (5MB), simpan dengan nama ter-hash (`Storage::putFile`), path tidak dapat diakses langsung via URL publik
- **Rate Limiting:** throttle middleware `60:1` untuk route login; `100:1` untuk API AJAX
- **HTTPS:** wajib di production via Nginx SSL termination (TLS 1.2+)
- **Audit Log:** Eloquent `creating/updating/deleting` observer mencatat `user_id`, `action`, `table`, `before`, `after` ke tabel `audit_logs`
- **Mass Assignment:** setiap Model menggunakan `$fillable` (whitelist), bukan `$guarded = []`

### 13.3 Ketersediaan

- Uptime target: **99.5%** (maintenance window maks 3.6 jam/bulan)
- Database backup otomatis: daily, retensi 30 hari
- Disaster recovery: RTO < 4 jam, RPO < 24 jam

### 13.4 Skalabilitas

- Sistem dirancang untuk menangani **≤ 2,000 siswa** (single school)
- Database query dioptimasi dengan index yang tepat (lihat DDL)
- Dashboard stats di-cache Redis dengan TTL 5 menit

### 13.5 Aksesibilitas

- Minimal WCAG 2.1 Level A
- Semua tabel dengan header yang benar (`<th scope>`)
- Form dengan label yang terhubung ke input
- Kontras warna minimum 4.5:1 untuk teks

---

## 14. Timeline & Milestone

### Estimasi: 10 Sprint × 2 Minggu = 20 Minggu (~5 Bulan)

| Sprint | Durasi | Deliverable |
|--------|--------|-------------|
| **S1** | Minggu 1–2 | Setup Laravel 11, Docker Compose, migrasi DB lengkap, Sanctum auth (login/logout/session), Spatie Permission (4 role), layout Blade + Tailwind + Alpine.js |
| **S2** | Minggu 3–4 | Master Data: Tahun Ajaran, Kelas, Tarif SPP, User Management CRUD (Admin) + seeder |
| **S3** | Minggu 5–6 | Modul Data Siswa: CRUD, search/filter, import Excel |
| **S4** | Minggu 7–8 | Modul Tagihan SPP: CRUD manual + generate massal |
| **S5** | Minggu 9–10 | Modul Pembayaran: input tunai, upload bukti, verifikasi Bendahara |
| **S6** | Minggu 11–12 | Modul Kwitansi: generate PDF, QR code, cetak & download |
| **S7** | Minggu 13–14 | Riwayat Pembayaran: filter, timeline per siswa, export Excel/PDF |
| **S8** | Minggu 15–16 | Laporan Tunggakan: semua jenis laporan, export |
| **S9** | Minggu 17–18 | Dashboard: stat cards, chart tren, transaksi terbaru, notifikasi |
| **S10** | Minggu 19–20 | Testing (UAT), bug fix, optimasi performa, deployment, dokumentasi |

### Milestone Kunci

| Milestone | Target Tanggal | Kriteria |
|-----------|---------------|----------|
| **M1 - Foundation Ready** | End of S2 | Auth berjalan, DB terbuat, RBAC aktif |
| **M2 - Core Transaction Ready** | End of S5 | Input pembayaran tunai dan upload bukti berjalan end-to-end |
| **M3 - Reporting Ready** | End of S8 | Semua laporan dapat diexport |
| **M4 - Production Launch** | End of S10 | UAT passed, deployed, semua role dapat login dan menggunakan sistem |

---

## 15. Risiko & Mitigasi

| # | Risiko | Probabilitas | Dampak | Mitigasi |
|---|--------|:---:|:---:|---------|
| R1 | Data siswa saat ini tidak lengkap/tidak konsisten | Tinggi | Tinggi | Template import Excel dengan validasi; migrasi data dilakukan Sprint 3 |
| R2 | Resistance dari bendahara yang terbiasa manual | Sedang | Sedang | Training dan pendampingan; tampilan UI dibuat semirip mungkin alur manual |
| R3 | Bug pada generate tagihan massal (duplikasi) | Sedang | Tinggi | UNIQUE constraint di DB; unit test wajib untuk fitur generate |
| R4 | PDF kwitansi tidak konsisten antar browser/OS | Sedang | Sedang | Gunakan DomPDF (`barryvdh/laravel-dompdf`) — render server-side dari Blade template, output selalu konsisten tanpa tergantung browser |
| R5 | Upload bukti transfer: file berbahaya | Rendah | Tinggi | Validasi ekstensi + magic bytes + scan antivirus sebelum simpan |
| R6 | Keterlambatan sprint karena scope creep | Sedang | Sedang | Fitur di luar scope dimasukkan backlog fase 2; sprint review ketat |

---

## 16. Kriteria Selesai

### 16.1 Definition of Done — Per Fitur

Setiap fitur dianggap selesai apabila:

- [ ] Semua user stories untuk fitur tersebut terimplementasi
- [ ] Feature test (Pest) coverage ≥ 70% untuk controller & service layer
- [ ] Form Request validation berjalan untuk semua input (field wajib, format, max length)
- [ ] RBAC berjalan — role yang tidak berwenang mendapat `403 Forbidden` atau redirect login
- [ ] CSRF protection aktif di semua form Blade
- [ ] Route & endpoint terdokumentasi di Postman Collection
- [ ] `php artisan route:list` tidak ada konflik atau route duplikat
- [ ] Reviewed oleh minimal 1 developer lain (code review)
- [ ] Tidak ada bug Priority 1 (Critical) yang belum terselesaikan

### 16.2 Definition of Done — Launch Production

- [ ] Semua milestone M1–M4 terpenuhi
- [ ] UAT dilakukan oleh minimal: 1 Admin, 1 Bendahara, 2 Wali Murid, 1 Kepala Sekolah
- [ ] UAT sign-off diterima dari pihak sekolah
- [ ] Semua data siswa existing telah dimigrasi ke sistem baru
- [ ] Dokumentasi user guide tersedia (per role)
- [ ] Backup otomatis database terkonfigurasi dan telah diuji restore
- [ ] SSL/TLS aktif di domain production
- [ ] Response time dashboard < 1.5 detik pada load data nyata
- [ ] Training selesai untuk Bendahara dan Admin

---

## Lampiran

### A. Glosarium

| Istilah | Definisi |
|---------|----------|
| SPP | Sumbangan Pembinaan Pendidikan — iuran bulanan siswa |
| NIS | Nomor Induk Siswa — kode unik identitas siswa |
| Kwitansi | Tanda bukti pembayaran resmi |
| Tunggakan | Tagihan SPP yang belum dilunasi |
| Wali Murid | Orang tua / wali yang bertanggung jawab atas siswa |
| Bendahara | Staf sekolah yang mengelola keuangan SPP |
| UAT | User Acceptance Testing — pengujian oleh pengguna nyata |
| RBAC | Role-Based Access Control — kontrol akses berbasis role |
| Sanctum | Package Laravel untuk autentikasi berbasis session dan API token |
| Spatie Permission | Package Laravel untuk manajemen role & permission granular |
| DomPDF | Library PHP untuk generate PDF dari HTML/Blade template |
| Maatwebsite Excel | Package Laravel untuk import/export file Excel (.xlsx) |
| Form Request | Kelas Laravel untuk validasi input HTTP request |
| Eloquent | ORM bawaan Laravel berbasis Active Record pattern |
| Middleware | Filter HTTP request di Laravel (auth, role check, CSRF) |
| Job/Queue | Proses background Laravel untuk tugas berat (generate massal) |

### B. Referensi Desain

- Screenshot referensi UI: `www.sekolahin.com/dashboard/spp/smar_test_system`
- Pola navigasi: Sidebar + Top Navbar (dua tingkat)
- Warna identitas: Navy Blue `#1E3A5F` sebagai primary brand color

### C. Dokumen Terkait

| Dokumen | Status |
|---------|--------|
| `ARCHITECTURE.md` | Belum dibuat |
| `DESIGN.md` | Belum dibuat |
| `PLAN.md` | Belum dibuat |
| `AGENTS.md` | Belum dibuat |
| API Documentation (OpenAPI) | Dibuat saat implementasi |
| User Guide — Admin | Dibuat Sprint 10 |
| User Guide — Bendahara | Dibuat Sprint 10 |
| User Guide — Wali Murid | Dibuat Sprint 10 |

---

*Dokumen ini bersifat living document — perubahan scope, prioritas, atau timeline harus didiskusikan bersama seluruh stakeholder dan dicatat versinya.*
