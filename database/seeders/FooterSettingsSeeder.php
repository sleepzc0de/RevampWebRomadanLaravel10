<?php

namespace Database\Seeders;

use App\Models\backend\MenuPengaturan\ContactInfoModel;
use App\Models\backend\MenuPengaturan\FooterLinkModel;
use Illuminate\Database\Seeder;

class FooterSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds. Idempotent — aman dijalankan berkali-kali.
     */
    public function run(): void
    {
        ContactInfoModel::firstOrCreate([], [
            'email' => 'kemenkeu.prime@kemenkeu.go.id',
            'whatsapp' => '0813-1000-4134',
            'address' => 'Gedung Djuanda 2 Lt. 16–17, Jl. Dr. Wahidin Raya No. 1',
        ]);

        if (FooterLinkModel::count() > 0) {
            return;
        }

        $defaults = [
            ['label' => 'Layanan', 'url' => '/layanan', 'sort_order' => 1],
            ['label' => 'Informasi Publik', 'url' => '/informasi-publik', 'sort_order' => 2],
            ['label' => 'Publikasi', 'url' => '/publikasi', 'sort_order' => 3],
            ['label' => 'FAQ', 'url' => '/faq', 'sort_order' => 4],
            ['label' => 'Tentang Kami', 'url' => '/profile/tentang-kami', 'sort_order' => 5],
        ];

        foreach ($defaults as $link) {
            FooterLinkModel::create($link + ['is_active' => true]);
        }
    }
}
