<?php

namespace App\Console\Commands;

use App\Http\Helpers\CANNEXHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CANNEXEvaluate extends Command {
    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:quote {analysis_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches a real-time 0% return illustration using the CANNEX Evaluate web service.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=black> CANNEX Quote Generator </> v%s', $this->version ) . PHP_EOL );

        $analysis_id = $this->argument( 'analysis_id' );

        if ( ( !empty( $analysis_id ) ) && ( !empty( $premium ) ) && ( !empty( $age ) ) && ( !empty( $type ) ) ) {

            $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

            return Command::SUCCESS;
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Some required parameters missing.' ) . PHP_EOL );

            return Command::FAILURE;
        }
    }
}
