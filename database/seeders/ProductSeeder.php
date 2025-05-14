<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Animate Perfect Glowing Barrier Series 5in1',
            'description' => 'Animate Perfect Glowing Barrier Series 5in1 adalah produk perawatan wajah yang bermanfaat membantu merawat lapisan kulit dengan advance formula memberi nutrisi pada tiap lapisan kulit dan dapat membantu membuat kulit lebih cerah 1 tingkat. Mengandung Complete Active Repair yaitu : Verryberry White, Tranexamic Acid, 10X Ceramide, 11x Hyaluronan, Panthenol, Avocado Oil, Aloe Vera Extract yang dapat merawat dan memperbaiki lapisan kulit serta mencerahkan kulit agar tampak lebih bercahaya.',
            'price' => 97000.00,
            'image_url' => 'https://down-id.img.susercontent.com/file/id-11134207-7rasf-m50xyarf7a3795.webp'
        ]);
        Product::create([
            'name' => 'Animate Paket Intense Acne Solution Series 5in1',
            'description' => '"Animate Intense Acne Solution Series 5in1 BPOM NKIT230001065, Animate Intense Acne Solution Series 5in1 adalah produk perawatan wajah yang bermanfaat membantu merawat kulit berjerawat untuk melawan bakteri penyebab jerawat dan menenangkan kulit yang meradang. Mengandung Intense Active Treats yaitu : Niacinamide, Salicylic Acid, Madecassoside, Mugwort Extract, Centella Asiatica, Calendula Extract',
            'price' => 132000.00,
            'image_url' => 'https://down-id.img.susercontent.com/file/sg-11134201-7rd5k-lxb0okkcy9n0ec@resize_w900_nl.webp'
        ]);
        Product::create([
            'name' => 'Animate Paket 5x Active Whitening Series 5in1',
            'description' => 'Terdiri dari : 1. Animate 5x Active Whitening Series 5in1 (BPOM NKIT230001064) 2. Sunscreen (BPOM NA18221701161)',
            'price' => 126000.00,
            'image_url' => 'https://down-id.img.susercontent.com/file/sg-11134201-7rd6p-lwe21oytilfa60@resize_w900_nl.webp'
        ]);
    }
}
