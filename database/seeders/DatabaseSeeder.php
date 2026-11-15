<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Data Lama (Aman untuk Foreign Key)
        \App\Models\OrderItem::query()->delete();
        \App\Models\Order::query()->delete();
        \App\Models\CartItem::query()->delete();
        Book::query()->delete();
        Category::query()->delete();
        User::where('email', '!=', 'admin@bookstore.com')->delete();

        // 2. Buat Akun Admin & User
        User::create([
            'name' => 'Admin Arjuanda',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
        ]);

        User::create([
            'name' => 'Budi Pelanggan',
            'email' => 'budi@example.com',
            'password' => Hash::make('123456'),
            'role' => 'user',
            'phone' => '081987654321',
            'address' => 'Jl. Pelanggan No. 12, Bandung',
        ]);

        // 3. Buat Kategori
        $fiksi = Category::create(['name' => 'Fiksi & Novel', 'description' => 'Kumpulan novel terbaik']);
        $nonFiksi = Category::create(['name' => 'Non-Fiksi', 'description' => 'Buku pengetahuan & biografi']);
        $teknologi = Category::create(['name' => 'Teknologi & IT', 'description' => 'Buku pemrograman & komputer']);
        $bisnis = Category::create(['name' => 'Bisnis & Ekonomi', 'description' => 'Buku manajemen & keuangan']);
        $pengembangan = Category::create(['name' => 'Pengembangan Diri', 'description' => 'Motivasi & self improvement']);

        // 4. Tambahkan 12 Buku (SEMUA MENGGUNAKAN FILE LOKAL)
        // PENTING: Pastikan nama file di folder storage/app/public/books/ SAMA PERSIS dengan ini!
        
        Book::create([
            'category_id' => $fiksi->id,
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'price' => 89000,
            'stock' => 25,
            'image' => 'books/laskar_pelangi.jpg', // File Lokal
            'description' => 'Kisah inspiratif perjuangan anak-anak Belitung dalam mengejar pendidikan.',
        ]);

        Book::create([
            'category_id' => $fiksi->id,
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'price' => 110000,
            'stock' => 15,
            'image' => 'books/bumi_manusia.jpg', // File Lokal
            'description' => 'Novel sejarah tentang pergerakan nasional Indonesia di era kolonial.',
        ]);

        Book::create([
            'category_id' => $nonFiksi->id,
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'author' => 'Yuval Noah Harari',
            'price' => 135000,
            'stock' => 30,
            'image' => 'books/sapiens.jpg', // File Lokal
            'description' => 'Eksplorasi mendalam tentang bagaimana Homo Sapiens menguasai dunia.',
        ]);

        Book::create([
            'category_id' => $teknologi->id,
            'title' => 'Pemrograman Web Modern dengan Laravel',
            'author' => 'Tim Penerbit Informatika',
            'price' => 150000,
            'stock' => 20,
            'image' => 'books/laravel_web.jpg', // File Lokal
            'description' => 'Panduan lengkap membangun aplikasi web modern menggunakan framework Laravel.',
        ]);

        Book::create([
            'category_id' => $bisnis->id,
            'title' => 'Rich Dad Poor Dad',
            'author' => 'Robert T. Kiyosaki',
            'price' => 75000,
            'stock' => 50,
            'image' => 'books/rich_dad.jpg', // File Lokal
            'description' => 'Pelajaran finansial dari dua ayah yang berbeda pola pikir.',
        ]);

        Book::create([
            'category_id' => $pengembangan->id,
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'price' => 98000,
            'stock' => 40,
            'image' => 'books/atomic_habits.jpg', // File Lokal
            'description' => 'Cara mudah dan terbukti untuk membentuk kebiasaan baik.',
        ]);

        Book::create([
            'category_id' => $fiksi->id,
            'title' => 'Harry Potter and the Sorcerer\'s Stone',
            'author' => 'J.K. Rowling',
            'price' => 120000,
            'stock' => 35,
            'image' => 'books/harry_potter.jpg', // File Lokal
            'description' => 'Petualangan ajaib seorang penyihir muda di Hogwarts.',
        ]);

        Book::create([
            'category_id' => $nonFiksi->id,
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'price' => 85000,
            'stock' => 28,
            'image' => 'books/filosofi_teras.jpg', // File Lokal
            'description' => 'Penerapan filsafat Stoa untuk kehidupan modern yang lebih tenang.',
        ]);

        Book::create([
            'category_id' => $teknologi->id,
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'price' => 180000,
            'stock' => 12,
            'image' => 'books/clean_code.jpg', // File Lokal
            'description' => 'Handbook of Agile Software Craftsmanship untuk menulis kode yang rapi.',
        ]);

        Book::create([
            'category_id' => $bisnis->id,
            'title' => 'Start With Why',
            'author' => 'Simon Sinek',
            'price' => 95000,
            'stock' => 22,
            'image' => 'books/start_with_why.jpg', // File Lokal
            'description' => 'Bagaimana pemimpin besar menginspirasi semua orang untuk bertindak.',
        ]);

        Book::create([
            'category_id' => $pengembangan->id,
            'title' => 'The Psychology of Money',
            'author' => 'Morgan Housel',
            'price' => 88000,
            'stock' => 45,
            'image' => 'books/psychology_money.jpg', // File Lokal
            'description' => 'Pelajaran abadi tentang kekayaan, keserakahan, dan kebahagiaan.',
        ]);

        Book::create([
            'category_id' => $fiksi->id,
            'title' => 'Perahu Kertas',
            'author' => 'Dewi Lestari',
            'price' => 78000,
            'stock' => 18,
            'image' => 'books/perahu_kertas.jpg', // File Lokal
            'description' => 'Kisah cinta dua insan kreatif dengan mimpi besar.',
        ]);
    }
}