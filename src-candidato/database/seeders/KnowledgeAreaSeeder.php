<?php

namespace Database\Seeders;

use App\Models\Process\KnowledgeArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KnowledgeAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('knowledge_areas')->insert(['id' => 1, 'name' => 'Linguagens, Códigos e suas Tecnologias']);
        DB::table('knowledge_areas')->insert(['id' => 2, 'name' => 'Matemática']);
        DB::table('knowledge_areas')->insert(['id' => 3, 'name' => 'Ciências da Natureza e suas Tecnologias ']);
        DB::table('knowledge_areas')->insert(['id' => 4, 'name' => 'Ciências Humanas e suas Tecnologias']);

    }
}
