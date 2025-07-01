<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TheEnglishVoiceSeeder extends Seeder
{
    public function run(): void
    {
        // Usuários
        DB::table('users')->insert([
            [
                'name' => 'Alice Santos',
                'email' => 'alice@example.com',
                'password' => Hash::make('123456'),
                'role' => 'Aluno',
                'ra' => 'RA001',
                'course_id' => 1
            ],
            [
                'name' => 'Bruno Lima',
                'email' => 'bruno@example.com',
                'password' => Hash::make('123456'),
                'role' => 'Aluno',
                'ra' => 'RA002',
                'course_id' => 2
            ],
            [
                'name' => 'Carla Moura',
                'email' => 'carla@example.com',
                'password' => Hash::make('123456'),
                'role' => 'Professor',
                'ra' => 'PROF001',
                'course_id' => 1
            ],
            [
                'name' => 'Daniel Rocha',
                'email' => 'daniel@example.com',
                'password' => Hash::make('123456'),
                'role' => 'Admin',
                'ra' => 'ADM001',
                'course_id' => 3
            ],
        ]);

        // Artigos
        DB::table('article')->insert([
            [
                'title' => 'Como programar em Laravel',
                'content' => 'Conteúdo do artigo 1',
                'status' => 'Aprovado',
                'approved_by' => 4,
            ],
            [
                'title' => 'Design Responsivo com Bootstrap',
                'content' => 'Conteúdo do artigo 2',
                'status' => 'Aprovado',
                'approved_by' => 4,
            ],
            [
                'title' => 'Gestão de Projetos Ágeis',
                'content' => 'Conteúdo do artigo 3',
                'status' => 'Pendente',
                'approved_by' => null,
            ],
        ]);

        // Autores
        DB::table('article_author')->insert([
            ['article_id' => 1, 'id' => 1, 'author_type' => 'Principal'],
            ['article_id' => 1, 'id' => 2, 'author_type' => 'Secundário'],
            ['article_id' => 2, 'id' => 2, 'author_type' => 'Principal'],
        ]);

        // Palavras-chave
        DB::table('keyword')->insert([
            ['name' => 'Laravel'],
            ['name' => 'Bootstrap'],
            ['name' => 'Gestão Ágil'],
        ]);

        // Associação artigo x palavra-chave
        DB::table('article_keyword')->insert([
            ['article_id' => 1, 'keyword_id' => 1],
            ['article_id' => 2, 'keyword_id' => 2],
            ['article_id' => 3, 'keyword_id' => 3],
        ]);

        // Votos
        DB::table('vote')->insert([
            ['article_id' => 1, 'id' => 1, 'rating' => 5],
            ['article_id' => 1, 'id' => 2, 'rating' => 4],
            ['article_id' => 2, 'id' => 1, 'rating' => 3],
        ]);

        // Favoritos
        DB::table('favorite')->insert([
            ['user_id' => 1, 'article_id' => 1],
            ['user_id' => 2, 'article_id' => 1],
            ['user_id' => 2, 'article_id' => 2],
        ]);

        // Avaliações
        DB::table('avaliacoes')->insert([
            ['user_id' => 1, 'artigo_id' => 1, 'nota' => 5],
            ['user_id' => 2, 'artigo_id' => 1, 'nota' => 4],
            ['user_id' => 1, 'artigo_id' => 2, 'nota' => 3],
        ]);
    }
}
