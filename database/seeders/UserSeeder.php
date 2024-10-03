<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'telephone' => '085735544336',
            'email' => 'persandian@admin.com',
            'password' => bcrypt('Admin123#'),
            'role_id' => '1',
        ]);
    }
}
