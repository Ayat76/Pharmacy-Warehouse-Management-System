<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User::truncate();
        User::create([
            'name'=>'Admin',
            'phone'=>'0999999999',
            'Is_Admin'=>'1',
            'password'=>'iamTheAdmin',
        ]);
    }
}
