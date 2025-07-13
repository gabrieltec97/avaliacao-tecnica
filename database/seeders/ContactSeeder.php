<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');

        foreach (range(1, 5) as $i) {
            Contact::create([
                'name'         => $faker->name,
                'phone'        => $faker->numerify('###########'),
                'email'        => $faker->unique()->safeEmail,
                'cep'          => $faker->randomNumber(8),
                'address'      => $faker->streetAddress,
                'neighborhood' => $faker->streetName,
                'city'         => $faker->city,
                'state'        => $faker->stateAbbr,
            ]);
        }
    }
}
