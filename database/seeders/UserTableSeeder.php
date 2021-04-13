<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
           [
               'name' => 'Jack',
               'email' => 'jack@c6digital.io',
               'password' => bcrypt('jack-sends-a-postcard')
           ],
            [
                'name' => 'Christoph',
                'email' => 'christoph@christoph-rumpel.com',
                'password' => bcrypt('christoph-sends-a-postcard')
            ]
        ]);
    }
}
