<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

/**
 * Class UserTableSeeder
 */
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $i) {
            User::create([
                'name' => $faker->name,
                'email' =>$faker->email,
                'password' => bcrypt('password'),
                'email_verified_at' => new \DateTime(),
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ]);
        }
        //api user to access records in supplier service
        User::create([
            'name' => 'API User',
            'email' => 'apiuser@roobeo.de',
            'password' => bcrypt('password'),
            'created_at' => new \DateTime(),
            'email_verified_at' => new \DateTime()
        ]);
    }
}
