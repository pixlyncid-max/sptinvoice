-- ============================================================
-- SCRIPT SQL UPDATE HOSTINGER MYSQL: DATA GAA & ROLE KARYAWAN
-- ============================================================

-- 1. Buat Tabel gaa_data
CREATE TABLE IF NOT EXISTS `gaa_data` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama_perusahaan` varchar(255) NOT NULL,
  `npwp` varchar(50) DEFAULT NULL,
  `kpp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password_email` varchar(255) DEFAULT NULL,
  `djp_user` varchar(255) DEFAULT NULL,
  `djp_password` varchar(255) DEFAULT NULL,
  `user_npwp_16` varchar(255) DEFAULT NULL,
  `pic_nik` varchar(50) DEFAULT NULL,
  `pic_nama` varchar(255) DEFAULT NULL,
  `coretax_password` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `checklist_coretax` varchar(50) NOT NULL DEFAULT 'Belum',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Pastikan kolom 'role' pada tabel users dapat menampung role 'karyawan'
-- (Jika kolom role sudah ada sebagai VARCHAR, query ini memastikan nilainya fleksibel)
ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(255) NOT NULL DEFAULT 'admin';

-- Selesai!
