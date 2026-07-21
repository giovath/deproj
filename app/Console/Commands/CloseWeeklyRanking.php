<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeeklyRankingService;

class CloseWeeklyRanking extends Command
{
    protected $signature = 'ranking:close';

    protected $description = 'Fecha o ranking semanal e salva os vencedores';


    public function handle(
        WeeklyRankingService $service
    ) {

        $service->close();


        $this->info(
            'Ranking semanal fechado com sucesso.'
        );


        return Command::SUCCESS;
    }
}
