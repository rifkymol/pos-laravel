<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'adminkyut',
            'email' => 'Adminkyut@mail.com',
            'password' => bcrypt('123123'),
            'status' => true
        ]);
    }
}
