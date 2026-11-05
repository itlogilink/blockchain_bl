# Electronic Bill of Lading (eBL) berbasis Blockchain - Prototipe Laravel

**Repository:** blockchain_bl  
**Teknologi:** PHP Laravel + Blockchain (Ethereum-compatible via web3.php + simulasi chain lokal)

## Konsepsi

### Latar Belakang
Bill of Lading (B/L) tradisional adalah dokumen kertas yang berfungsi sebagai:
1. Bukti penerimaan barang oleh carrier
2. Bukti kontrak pengangkutan
3. Dokumen kepemilikan (document of title) yang dapat dialihkan

Masalah utama: lambat, mahal, rentan penipuan (double financing), sulit dilacak, dan tidak ramah lingkungan.

**Electronic Bill of Lading (eBL)** mendigitalisasi dokumen ini. Dengan **Blockchain**, kita mendapatkan:
- **Immutability**: Data tidak bisa diubah setelah dicatat
- **Transparency & Auditability**: Semua pihak dapat memverifikasi
- **Exclusive Control**: Kepemilikan dialihkan secara aman (mirip transfer possession)
- **Smart Contracts**: Otomatisasi transfer title, release cargo, bahkan settlement pembayaran
- **Compliance**: Mendukung prinsip MLETR (Model Law on Electronic Transferable Records) - uniqueness, exclusive control, integrity

### Arsitektur Sistem
```
┌──────────────────┐     ┌──────────────────┐     ┌────────────────────┐
│   Laravel App   │────▶│  Blockchain Layer│────▶│ Ethereum / Private  │
│ (PHP Backend)   │     │  (web3.php +     │     │ Chain / Hyperledger │
│ - Auth          │     │   Local Chain)   │     │                     │
│ - eBL CRUD      │     └──────────────────┘     └────────────────────┘
│ - Transfer      │
│ - Verification  │
└─────────────────┘
         │
         ▼
    ┌─────────┐
    │ MySQL   │
    │ (Off-chain data) │
    └─────────┘
```

**Model Token (NFT-like):**
- Setiap eBL direpresentasikan sebagai unique record di blockchain (hash + metadata)
- Ownership dipegang oleh address (wallet) pemilik saat ini
- Transfer = update ownership + record transaksi baru di chain

**Alur Bisnis:**
1. **Issue**: Carrier membuat eBL → hash data (shipper, consignee, cargo, vessel, dll) → catat di blockchain
2. **Transfer**: Pemilik saat ini mentransfer ke pihak baru (buyer/bank) → update owner + record
3. **Endorse / Surrender**: Untuk claim cargo di tujuan → verifikasi ownership di chain
4. **Amend**: Hanya dengan multi-party consent (bisa via smart contract)

**Keuntungan:**
- Mengurangi biaya $6.5B+ per tahun (estimasi DCSA)
- Mempercepat proses dari hari menjadi menit
- Mencegah fraud
- Integrasi mudah dengan trade finance (letter of credit otomatis)

### Implementasi di Prototipe Ini
- **Laravel** sebagai backend utama
- **Simulasi Blockchain lokal** (chain of blocks dengan SHA-256) untuk demo cepat tanpa node
- **Integrasi siap** dengan `web3p/web3.php` untuk Ethereum Sepolia/Polygon
- Model: `BillOfLading`, `Block` (blockchain), `Transfer`
- Service: `BlockchainService` yang bisa switch antara local dan real chain

## Setup Prototipe

### Prasyarat
- PHP >= 8.1
- Composer
- MySQL / MariaDB
- (Opsional) Node Ethereum atau Infura/Alchemy untuk real blockchain

### Instalasi
```bash
# Clone
git clone https://github.com/itlogilink/blockchain_bl.git
cd blockchain_bl

# Install dependency
composer install

# Copy env
cp .env.example .env
php artisan key:generate

# Setup database di .env, lalu:
php artisan migrate

# Jalankan
php artisan serve
```

### Konfigurasi Blockchain (opsional)
Di `.env`:
```
BLOCKCHAIN_MODE=local          # atau 'ethereum'
WEB3_PROVIDER=https://sepolia.infura.io/v3/YOUR_KEY
CONTRACT_ADDRESS=0x...
```

## Struktur Utama
- `app/Services/BlockchainService.php` - Core blockchain logic
- `app/Models/BillOfLading.php` & `Block.php`
- `app/Http/Controllers/BillOfLadingController.php`
- `database/migrations/` - Schema

## Fitur Prototipe
- [x] Create / Issue eBL
- [x] List & Detail eBL
- [x] Transfer ownership (dengan pencatatan di chain)
- [x] View blockchain history (blocks)
- [x] Verifikasi integrity (hash check)
- [ ] Full smart contract deployment (siap dikembangkan)
- [ ] Wallet integration (MetaMask)

## Pengembangan Selanjutnya
1. Deploy smart contract Solidity (ERC-721 like untuk eBL)
2. Integrasi penuh dengan web3.php untuk signed transactions
3. Multi-role auth (Carrier, Shipper, Consignee, Bank)
4. API untuk sistem lain (DCSA standard)
5. Frontend modern (Livewire atau Vue)

---

**Dibuat untuk:** Prototipe Electronic Bill of Lading dengan Blockchain  
**Stack:** PHP Laravel  
**Repo:** [blockchain_bl](https://github.com/itlogilink/blockchain_bl)
