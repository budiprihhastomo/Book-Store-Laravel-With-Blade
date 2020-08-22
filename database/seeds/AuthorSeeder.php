<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AuthorSeeder extends Seeder
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
            DB::table('authors')->insert([
                'first_name' => $faker->firstName,
                'middle_name' => $faker->firstNameMale,
                'last_name' => $faker->lastName,
                'created_at' => $faker->date()
            ]);
        }
    }
}
