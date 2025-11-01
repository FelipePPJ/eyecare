<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\GroupsSeeder;
use Database\Seeders\ExamsSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Realiza cadastro de grupos de impressão iniciais
         */
        $this->call(GroupsSeeder::class);

        /**
         * Realiza cadastro inicial de relação de exames
         */
        $this->call(ExamsSeeder::class);
    }
}
