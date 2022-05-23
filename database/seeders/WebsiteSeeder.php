<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
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
                    'name' => 'Blog 1',
                    'url' => 'http://blog1.com',
                    'code' => 'WS'.mt_rand(1111, 9999),
                    'created_at' => now()
                ],
                [
                    'name' => 'Blog 2',
                    'url' => 'http://blog2.com',
                    'code' => 'WS'.mt_rand(1111, 9999),
                    'created_at' => now()
                ],
                [
                    'name' => 'Blog 3',
                    'url' => 'http://blog3.com',
                    'code' => 'WS'.mt_rand(1111, 9999),
                    'created_at' => now()
                ]
            ]
        );

        DB::table('websites')->insert($collection->toArray());
    }

}
