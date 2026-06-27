# 🏆 Algebra Leaderboard API (Laravel)

API kecil untuk papan pendahulu global game **Algebra Mission**.
Guna **SQLite** (tiada MySQL/kata laluan) — fail DB disimpan di luar folder
release supaya data kekal selepas setiap deploy.

## Endpoint
- `GET  /api/scores?limit=5` — senarai Top N
- `POST /api/scores` — simpan skor `{ name, score, total, xp, timeSec, levelName, levelEmoji, modeTitle, modeEmoji }`

---

## Cara deploy ke Laravel Forge

### 1. DNS — subdomain untuk API
Di pengurus DNS `elhumaira.com`, tambah:
- **Type:** `A` · **Name:** `skor` · **Value:** `146.190.192.119`

(boleh guna nama lain, cth `api` — tapi kemas kini `API_BASE` di game ikut subdomain itu)

### 2. Cipta site Laravel di Forge
- Server **server-A** → **New site → Laravel** (atau PHP)
- **Domain:** `skor.elhumaira.com`
- **Repository:** repo API ini (cth `aqmy706/algebra-api`), branch `main`
- Biarkan Root directory `/` dan Web directory `/public` (default Laravel)
- **Create site** → tunggu deploy pertama

### 3. Tetapkan Environment (.env)
Site → **Environment** → pastikan/isi:
```
APP_NAME="Algebra Leaderboard"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://skor.elhumaira.com

DB_CONNECTION=sqlite
DB_DATABASE=/home/forge/algebra-db/database.sqlite
```
(Pastikan `APP_KEY=` sudah berisi. Jika kosong, jalankan di **Commands**: `php artisan key:generate --force`)

### 4. Deploy Script
Site → **Deployments → Deploy Script** → salin kandungan [`forge-deploy.sh`](forge-deploy.sh).

### 5. Deploy & SSL
- Klik **Deploy Now** → tunggu hijau (composer install + migrate)
- Tab **SSL → Let's Encrypt → Obtain Certificate** (perlu HTTPS supaya game boleh panggil)

### 6. Uji
Buka `https://skor.elhumaira.com/api/scores` → sepatutnya pulang `[]` (array kosong).

---

Selesai! Game di `anak2.elhumaira.com` akan automatik guna API ini
(rujuk `API_BASE` dalam `src/utils/api.js` projek game).
