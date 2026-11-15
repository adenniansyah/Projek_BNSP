<?php
// fetch_covers.php - Versi Direct Download (Tanpa API)

$books = [
    'Laskar Pelangi' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1327872146i/13624125.jpg',
    'Bumi Manusia' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1388267050i/176210.jpg',
    'Sapiens' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1420585954i/23692271.jpg',
    'Rich Dad Poor Dad' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1388211992i/69571.jpg',
    'Atomic Habits' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1535115320i/40121378.jpg',
    'Harry Potter' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1474154022i/3.jpg',
    'Filosofi Teras' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1570023189i/48855230.jpg',
    'Clean Code' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1436202607i/3735293.jpg',
    'Start With Why' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1360024039i/7108725.jpg',
    'Psychology of Money' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1581527774i/41881472.jpg',
    'Perahu Kertas' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1348687111i/13533991.jpg',
    'Laravel Web' => 'https://m.media-amazon.com/images/I/51w+2yX-7CL._SX379_BO1,204,203,200_.jpg'
];

// Mapping nama file sesuai Seeder
$fileMap = [
    'Laskar Pelangi' => 'laskar_pelangi.jpg',
    'Bumi Manusia' => 'bumi_manusia.jpg',
    'Sapiens' => 'sapiens.jpg',
    'Rich Dad Poor Dad' => 'rich_dad.jpg',
    'Atomic Habits' => 'atomic_habits.jpg',
    'Harry Potter' => 'harry_potter.jpg',
    'Filosofi Teras' => 'filosofi_teras.jpg',
    'Clean Code' => 'clean_code.jpg',
    'Start With Why' => 'start_with_why.jpg',
    'Psychology of Money' => 'psychology_money.jpg',
    'Perahu Kertas' => 'perahu_kertas.jpg',
    'Laravel Web' => 'laravel_web.jpg'
];

$targetDir = __DIR__ . '/storage/app/public/books/';
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
    echo "Folder books dibuat.\n";
}

foreach ($books as $title => $url) {
    $fileName = $fileMap[$title];
    echo "Mengunduh: $title ...\n";
    
    // Gunakan cURL jika tersedia, fallback ke file_get_contents
    $imgData = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // Menyamar sebagai browser
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL error di XAMPP lokal
        $imgData = curl_exec($ch);
        curl_close($ch);
    } else {
        // Fallback jika cURL tidak aktif
        $context = stream_context_create(['http' => ['header' => "User-Agent: Mozilla/5.0\r\n"]]);
        $imgData = @file_get_contents($url, false, $context);
    }

    if ($imgData && strlen($imgData) > 1000) { // Cek apakah data gambar valid (>1KB)
        file_put_contents($targetDir . $fileName, $imgData);
        echo "✅ BERHASIL: $fileName\n";
    } else {
        echo "❌ GAGAL: $title (Link diblokir atau kosong)\n";
    }
}

echo "\nSelesai! Silakan cek folder storage/app/public/books/\n";