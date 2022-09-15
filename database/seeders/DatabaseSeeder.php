<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Animal;
use App\Models\User;
use App\Models\Type;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 取消外鍵約束
        Schema::disableForeignKeyConstraints();
        Animal::truncate();
        User::Truncate();
        Type::Truncate();

        Type::factory(10)->create();
        User::factory(5)->create();
        Animal::factory(10000)->create();

        Schema::enableForeignKeyConstraints();
    }
}
