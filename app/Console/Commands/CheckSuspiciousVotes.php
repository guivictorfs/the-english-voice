<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSuspiciousVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'votes:check-suspicious';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detecta comportamentos suspeitos de votação';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $thresholdVotes = 2;
        $timeWindow = 10;

        $now = now('America/Sao_Paulo');
        $start = $now->copy()->subMinutes($timeWindow);

        $this->info("Iniciando verificação de votos 1 entre {$start} e {$now}");

        // Regra 1 - Muitos votos 1 em pouco tempo
        $suspects = \App\Models\Avaliacao::where('nota', 1)
            ->where('created_at', '>=', $start)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as total')
            ->having('total', '>', $thresholdVotes)
            ->get();

        if ($suspects->isEmpty()) {
            $this->info("Nenhum usuário com mais de {$thresholdVotes} votos 1 encontrados.");
        } else {
            foreach ($suspects as $suspect) {
                $userId = $suspect->user_id;
                $this->info("Detectado comportamento suspeito: usuário {$userId} com {$suspect->total} notas 1");

                $created = \App\Models\SuspiciousActivity::firstOrCreate([
                    'user_id' => $userId,
                    'type' => 'many_low_votes',
                    'description' => "Usuário deu mais de {$thresholdVotes} notas 1 nos últimos {$timeWindow} minutos.",
                ]);

                Log::info("Registro criado ou existente:", $created->toArray());
            }
        }

        // Regra 2 - Média do usuário muito abaixo da média global
        $globalAvg = \App\Models\Avaliacao::avg('nota');
        $this->info("Média global: {$globalAvg}");

        $users = \App\Models\User::all();
        foreach ($users as $user) {
            $userAvg = \App\Models\Avaliacao::where('user_id', $user->id)->avg('nota');

            if ($userAvg !== null && ($globalAvg - $userAvg) >= 2) {
                $this->info("Usuário {$user->id} tem média {$userAvg}, abaixo da global");

                $created = \App\Models\SuspiciousActivity::firstOrCreate([
                    'user_id' => $user->id,
                    'type' => 'low_avg_vote',
                    'description' => "Média de votos do usuário ($userAvg) está muito abaixo da média global ($globalAvg).",
                ]);
            }
        }

        // Regra 3 - Alunos que denunciaram muitos artigos em 7 dias
        $thresholdReports = 5; // Mais de 5 denúncias
        $daysWindow = 7;
        $startDate = now('America/Sao_Paulo')->subDays($daysWindow);

        $alunosDenunciantes = \App\Models\ArticleReport::where('created_at', '>=', $startDate)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as total')
            ->having('total', '>', $thresholdReports)
            ->get();

        foreach ($alunosDenunciantes as $denunciante) {
            $exists = \App\Models\SuspiciousActivity::where('user_id', $denunciante->user_id)
                ->where('type', 'muitas_denuncias')
                ->whereDate('created_at', '>=', $startDate)
                ->exists();

            if (!$exists) {
                \App\Models\SuspiciousActivity::create([
                    'user_id' => $denunciante->user_id,
                    'type' => 'muitas_denuncias',
                    'description' => "Usuário fez {$denunciante->total} denúncias de artigos nos últimos {$daysWindow} dias.",
                ]);
            }
        }

        $this->info('Verificação de votos suspeitos concluída.');
    }
}
