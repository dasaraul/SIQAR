<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'sw-library/sw-config.php';

try {
    // Tes koneksi
    echo "Mencoba koneksi ke MongoDB Atlas...<br>";
    $collections = $mongo_db->listCollections();
    
    echo "Koneksi berhasil! Daftar koleksi:<br>";
    foreach ($collections as $collection) {
        echo "- " . $collection->getName() . "<br>";
    }
    
    // Tes query
    echo "<br>Mencoba query data sw_site...<br>";
    $site_data = $mongo_db->sw_site->findOne();
    if ($site_data) {
        echo "Data ditemukan:<br>";
        echo "<pre>";
        print_r($site_data);
        echo "</pre>";
    } else {
        echo "Tidak ada data di koleksi sw_site";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}