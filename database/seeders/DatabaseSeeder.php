<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
           'name' => 'fady',
           'email' => 'fadyreda1212@gmail.com',
           'type' => 'admin',
        ]);
        User::factory(10)->create([
            'type'=>'user',
        ]);
        Category::factory(20)->create([
            'parent_id'=>0,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
