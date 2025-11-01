<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupsSeeder extends Seeder
{
    /**
     * Seed base para relação de grupos associativos a exames e pacotes
     */
    public function run(): void
    {
        $grupos = [
            [
                // "id" => 1,
                "name" => "Individual"
            ],
            [
                // "id" => 2,
                "name" => "Grupo 1"
            ],
            [
                // "id" => 3,
                "name" => "Grupo 2"
            ],
            [
                // "id" => 4,
                "name" => "Grupo 3"
            ],
            [
                // "id" => 5,
                "name" => "Grupo 4"
            ],
            [
                // "id" => 6,
                "name" => "Grupo 5"
            ]
        ];

        foreach ($grupos as $key => $grupo) {
            DB::table('groups')->updateOrInsert(
                [
                    'id' => ($key + 1)
                ],
                [
                    'name' => trim(strip_tags($grupo['name'])),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
