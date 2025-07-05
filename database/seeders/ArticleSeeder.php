<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('article')->insert([
            [
                'title' => 'Introdução ao Laravel 2',
                'content' => 'Este artigo apresenta os fundamentos do Laravel.',
                'status' => 'Aprovado',
                'approved_by' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Componentes no Vue.js 2',
                'content' => 'Como criar e reutilizar componentes no Vue.js.',
                'status' => 'Aprovado',
                'approved_by' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'UX Design para Iniciantes 2',
                'content' => 'Dicas práticas para melhorar a experiência do usuário.',
                'status' => 'Em revisão',
                'approved_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Banco de Dados Relacional 2',
                'content' => 'Vantagens e desafios dos bancos relacionais.',
                'status' => 'Aprovado',
                'approved_by' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Versionamento com Git 2',
                'content' => 'Boas práticas no uso do Git e GitHub.',
                'status' => 'Aprovado',
                'approved_by' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Scrum no Dia a Dia 2',
                'content' => 'Como aplicar o framework Scrum em projetos reais.',
                'status' => 'Pendente',
                'approved_by' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Python para Análise de Dados 2',
                'content' => 'Bibliotecas essenciais como Pandas e Matplotlib.',
                'status' => 'Aprovado',
                'approved_by' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Segurança em Aplicações Web 2',
                'content' => 'Principais vulnerabilidades e como mitigá-las.',
                'status' => 'Recusado',
                'approved_by' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'NoSQL vs SQL 2',
                'content' => 'Comparação entre bancos relacionais e não relacionais.',
                'status' => 'Aprovado',
                'approved_by' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Como Criar APIs RESTful 2',
                'content' => 'Guia completo para criar APIs com Laravel.',
                'status' => 'Aprovado',
                'approved_by' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
