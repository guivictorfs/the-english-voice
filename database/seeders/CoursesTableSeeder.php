<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('course')->insert([
            ['course_name' => 'Análise e Desenvolvimento de Sistemas'],
            ['course_name' => 'Design de Mídias Digitais'],
            ['course_name' => 'Gestão Comercial'],
            ['course_name' => 'Gestão da Produção Industrial'],
            ['course_name' => 'Gestão da Tecnologia da Informação'],
            ['course_name' => 'Gestão Empresarial'],
            ['course_name' => 'Gestão Financeira'],
            ['course_name' => 'Logística'],
        ]);
    }
}
