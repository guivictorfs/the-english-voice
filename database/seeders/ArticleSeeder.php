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
        'title' => 'Como Aprender Inglês do Zero',
        'content' => 'Dicas práticas para quem está começando a estudar inglês e quer desenvolver uma base sólida.',
        'status' => 'Aprovado',
        'approved_by' => 4,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Vocabulário Essencial para Viagens',
        'content' => 'Palavras e frases úteis para se comunicar em aeroportos, hotéis, restaurantes e passeios.',
        'status' => 'Aprovado',
        'approved_by' => 4,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Como Melhorar a Pronúncia em Inglês',
        'content' => 'Exercícios e técnicas para treinar a pronúncia de sons típicos do inglês.',
        'status' => 'Em revisão',
        'approved_by' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Gramática: Present Simple vs Present Continuous',
        'content' => 'Entenda as diferenças e usos dos tempos verbais mais comuns do inglês.',
        'status' => 'Aprovado',
        'approved_by' => 3,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Expressões Idiomáticas do Cotidiano',
        'content' => 'Aprenda expressões idiomáticas que são usadas no dia a dia por falantes nativos.',
        'status' => 'Aprovado',
        'approved_by' => 4,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Como Estudar para Provas de Inglês',
        'content' => 'Estratégias para se preparar para exames como TOEFL, IELTS e outros testes de proficiência.',
        'status' => 'Pendente',
        'approved_by' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Listening: Como Treinar a Compreensão Auditiva',
        'content' => 'Sugestões de podcasts, músicas e vídeos para melhorar o listening em inglês.',
        'status' => 'Aprovado',
        'approved_by' => 4,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Erros Comuns de Brasileiros no Inglês',
        'content' => 'Veja quais são os deslizes mais frequentes e como evitá-los.',
        'status' => 'Recusado',
        'approved_by' => 3,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Como Aprender Inglês com Séries e Filmes',
        'content' => 'Dicas para aproveitar o entretenimento para expandir vocabulário e compreensão.',
        'status' => 'Aprovado',
        'approved_by' => 3,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
    [
        'title' => 'Como Montar um Plano de Estudos de Inglês',
        'content' => 'Passo a passo para organizar sua rotina e evoluir no idioma.',
        'status' => 'Aprovado',
        'approved_by' => 4,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ],
]);
    }
}
