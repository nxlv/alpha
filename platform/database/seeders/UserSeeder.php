<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        if ( env( 'APP_ENV' ) != 'production' ) {
            \App\Models\User::factory()->create(
                [
                    'name' => 'Sample User',
                    'email' => 'test@test.com',
                    'password' => Hash::make( 'test1234' )
                ]
            );
        }
    }
}
