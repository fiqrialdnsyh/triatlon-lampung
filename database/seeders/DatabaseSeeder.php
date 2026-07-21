<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin (role dipakai untuk cek isAdmin(), bukan email)
        User::updateOrCreate(

        );
    }
}
