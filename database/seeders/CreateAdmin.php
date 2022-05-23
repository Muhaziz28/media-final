<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateAdmin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@media.com',
            'role' => 'admin',
            'password' => bcrypt('admin'),
            // 'rememberToken' => Str::random(10),
        ]);
    }
}
