<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            [
                'nama'   => 'Rizki Amelia',
                'kota'   => 'Jakarta Selatan',
                'menu'   => 'Tteokbokki',
                'ulasan' => 'Tteokbokkinya juara banget! Pedasnya pas, kenyal, dan sausnya kaya rasa. Serasa lagi di pojok jalan Myeongdong.',
                'rating' => 5,
            ],
            [
                'nama'   => 'Bagas Pratama',
                'kota'   => 'Bandung',
                'menu'   => 'Bulgogi BBQ',
                'ulasan' => 'Bulgogi BBQ-nya autentik banget. Bumbunya meresap sempurna, dagingnya empuk. Langsung jadi resto Korea favorit saya!',
                'rating' => 5,
            ],
            [
                'nama'   => 'Nadia Kusuma',
                'kota'   => 'Surabaya',
                'menu'   => 'Bibimbap',
                'ulasan' => 'Suasananya bikin betah, staffnya ramah, dan makanannya konsisten enak setiap kunjungan. Highly recommended!',
                'rating' => 5,
            ],
            [
                'nama'   => 'Dimas Aryo',
                'kota'   => 'Yogyakarta',
                'menu'   => 'Sundubu Jjigae',
                'ulasan' => 'Sundubu Jjigae-nya maknyus! Kuahnya gurih, tahu sutranya lembut banget. Selalu minta yang level pedas paling tinggi!',
                'rating' => 5,
            ],
            [
                'nama'   => 'Siska Wulandari',
                'kota'   => 'Depok',
                'menu'   => 'Bibimbap',
                'ulasan' => 'Udah 5x balik ke sini dan gak pernah kecewa. Bibimbapnya selalu segar dan porsinya besar. Worth every rupiah!',
                'rating' => 5,
            ],
            [
                'nama'   => 'Farhan Maulana',
                'kota'   => 'Bekasi',
                'menu'   => 'Kimbap',
                'ulasan' => 'Kimbapnya enak dan fresh, isinya bervariasi. Suka banget sama dekorasi restonya yang instagramable. Bakal balik lagi!',
                'rating' => 4,
            ],
            [
                'nama'   => 'Ayu Ramadhani',
                'kota'   => 'Tangerang',
                'menu'   => 'Dakgalbi',
                'ulasan' => 'Dakgalbinya wow banget, ayamnya tender dan saus gochujangnya nendang! Cocok banget makan rame-rame sama teman.',
                'rating' => 5,
            ],
            [
                'nama'   => 'Kevin Santoso',
                'kota'   => 'Jakarta Utara',
                'menu'   => 'Japchae',
                'ulasan' => 'Japchae-nya luar biasa! Mie kacanya kenyal dengan sayuran yang masih segar. Bisa nambah dua kali karena terlalu enak.',
                'rating' => 5,
            ],
        ];

        foreach ($reviews as $r) {
            Review::create($r);
        }
    }
}