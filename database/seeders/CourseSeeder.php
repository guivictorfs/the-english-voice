<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // Adicionar os cursos existentes
        Course::create(['course_name' => 'Análise e Desenvolvimento de Sistemas']);
        Course::create(['course_name' => 'Design de Mídias Digitais']);
        Course::create(['course_name' => 'Gestão Comercial']);
        Course::create(['course_name' => 'Gestão da Produção Industrial']);
        Course::create(['course_name' => 'Gestão da Tecnologia da Informação']);
        Course::create(['course_name' => 'Gestão Empresarial']);
        Course::create(['course_name' => 'Gestão Financeira']);
        Course::create(['course_name' => 'Logística']);
        Course::create(['course_name' => 'Professor']);
        Course::create(['course_name' => 'Administrador']);
    }
}
