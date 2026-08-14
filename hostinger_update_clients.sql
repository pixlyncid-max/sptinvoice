-- ============================================================
-- SCRIPT SQL UPDATE HOSTINGER MYSQL: CLIENTS TABLE UPDATE
-- ============================================================

-- 1. Ubah kolom perusahaan menjadi REQUIRED (NOT NULL)
ALTER TABLE `clients` MODIFY COLUMN `perusahaan` VARCHAR(255) NOT NULL;

-- 2. Ubah kolom email menjadi OPTIONAL (NULLABLE)
ALTER TABLE `clients` MODIFY COLUMN `email` VARCHAR(255) NULL;

-- 3. Tambahkan kolom jenis_pekerjaan (Satuan, Bulanan, Tahunan)
ALTER TABLE `clients` ADD COLUMN `jenis_pekerjaan` ENUM('Satuan', 'Bulanan', 'Tahunan') NOT NULL DEFAULT 'Satuan' AFTER `alamat`;

-- 4. Tambahkan kolom status (Aktif, Non Aktif, Pending)
ALTER TABLE `clients` ADD COLUMN `status` ENUM('Aktif', 'Non Aktif', 'Pending') NOT NULL DEFAULT 'Aktif' AFTER `jenis_pekerjaan`;

-- Selesai!
