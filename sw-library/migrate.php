<?php
// Import script config MongoDB
require_once 'sw-config.php';

/**
 * Script Migrasi Data MariaDB ke MongoDB
 * 
 * Gunakan script ini untuk memindahkan data dari database MariaDB ke MongoDB Atlas
 * Pastikan Anda sudah menginstall ekstensi PHP MongoDB dan mengonfigurasi koneksi
 * di file sw-config.php
 */

// Koneksi ke MariaDB untuk mengambil data
$mariadb_host = 'localhost'; // Ganti dengan host MariaDB Anda
$mariadb_user = 'root'; // Ganti dengan username MariaDB Anda
$mariadb_pass = ''; // Ganti dengan password MariaDB Anda
$mariadb_db = 'siqar_db'; // Ganti dengan nama database MariaDB Anda

$mariadb = new mysqli($mariadb_host, $mariadb_user, $mariadb_pass, $mariadb_db);

if ($mariadb->connect_error) {
    die("Koneksi ke MariaDB gagal: " . $mariadb->connect_error);
}

echo "Memulai migrasi data dari MariaDB ke MongoDB Atlas...\n";

// Daftar tabel yang akan dimigrasi
$tables = [
    'building',
    'business_card',
    'cuty',
    'employees',
    'holiday',
    'permission',
    'position',
    'presence',
    'present_status',
    'shift',
    'sw_site',
    'user',
    'user_level'
];

// Lakukan migrasi untuk setiap tabel
foreach ($tables as $table) {
    echo "Migrasi tabel $table... ";
    
    // Buat koleksi di MongoDB jika belum ada
    try {
        $mongo_db->createCollection($table);
    } catch (Exception $e) {
        // Koleksi mungkin sudah ada, abaikan error
    }
    
    $collection = $mongo_db->$table;
    
    // Hapus data yang mungkin sudah ada di koleksi (opsional)
    $collection->deleteMany([]);
    
    // Ambil semua data dari tabel MariaDB
    $result = $mariadb->query("SELECT * FROM $table");
    
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        // Konversi ID numerik ke string untuk MongoDB
        foreach ($row as $key => $value) {
            if ($key === 'id' || $key === "{$table}_id") {
                $row[$key] = (string)$value;
            }
            
            // Konversi nilai null ke string kosong untuk MongoDB
            if ($value === null) {
                $row[$key] = '';
            }
        }
        
        // Tambahkan data ke MongoDB
        $collection->insertOne($row);
        $count++;
    }
    
    echo "Berhasil migrasi $count dokumen.\n";
}

echo "Migrasi selesai!\n";

// Tutup koneksi MariaDB
$mariadb->close();