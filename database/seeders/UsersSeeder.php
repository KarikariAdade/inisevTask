<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collection = collect(
            [
                [
                    'name' => 'John Doe',
                    'email' => 'johndoe@gmail.com',
                    'code' => mt_rand(1111, 9999),
                    'created_at' => now()
                ],
                [
                    'name' => 'Anita Doe',
                    'email' => 'anitadoe@gmail.com',
                    'code' => mt_rand(1111, 9999),
                    'created_at' => now()
                ],
                [
                    'name' => 'Jane Doe',
                    'email' => 'janedoe@gmail.com',
                    'code' => mt_rand(1111, 9999),
                    'created_at' => now()
                ]
            ]
        );

        DB::table('users')->insert($collection->toArray());
    }
}
