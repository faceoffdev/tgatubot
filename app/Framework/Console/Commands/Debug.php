<?php

namespace App\Framework\Console\Commands;

use App\Models\Sticker;
use Illuminate\Console\Command;

class Debug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $d = Sticker::whereTelegramId(888545)
            ->whereRaw('(make_tsvector(tags) @@ plainto_tsquery(?))', 'бомба мед')
            ->get(['sticker_id']);
    }
}
