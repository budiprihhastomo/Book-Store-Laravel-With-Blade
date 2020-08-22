<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Initialize Faker
        $faker = Faker::create('id_ID');
        for ($i = 0; $i < 10; $i++) {
            DB::table('books')->insert([
                'title' => $faker->sentence(3),
                'total_pages' => $faker->numberBetween(100, 150),
                'rating' => $faker->numberBetween(1, 5),
                'isbn' => $faker->isbn13,
                'published_date' => $faker->date()
            ]);
        }
    }
}
