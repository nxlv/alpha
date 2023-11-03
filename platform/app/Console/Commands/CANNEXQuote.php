<?php

namespace App\Console\Commands;

use App\Http\Helpers\CANNEXHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class CANNEXQuote extends Command {
    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:quote {analysis_id} {premium} {deferral} {age} {gender} {type} {state?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches a real-time quote for guaranteed income based on the parameters specified.';

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

        $transaction_id = uuid_create();

        $analysis_id = $this->argument( 'analysis_id' );
        $premium = preg_replace( '/[^0-9.]/', '', $this->argument( 'premium' ) );
        $deferral = $this->argument( 'deferral' );
        $age = $this->argument( 'age' );
        $gender = $this->argument( 'gender' );
        $type = $this->argument( 'type' );
        $state = $this->argument( 'state' );

        if ( ( !empty( $analysis_id ) ) && ( !empty( $premium ) ) && ( !empty( $age ) ) && ( !empty( $type ) ) ) {
            $this->line( sprintf( '  <bg=blue;fg=black> PARAMETERS </> ID: %s | Inv: %f | Def: %d | Age: %d | Gender: %s | Type: %s | State: %s', $analysis_id, $premium, $deferral, $age, $gender, $type, $state ) . PHP_EOL );

            $targets = [ $analysis_id ];

            $annuitant = [
                'state_cd' => $state,
                'contract_cd' => $type,
                'premium' => number_format( $premium, 2, '.', '' ),
                'purchase_date' => gmdate( 'Y-m-d' ),
                'gender_cd_primary' => 'M',
                'purchase_age_primary' => $age,
                'income_start_age_primary' => $age + $deferral
            ];

            if ( $profile_id = CANNEXHelper::create_annuitant_profile( $transaction_id, $annuitant, 0, $targets ) ) {
                $this->line( sprintf( '  <fg=black;bg=blue> NOTICE </> Profile ID created (%s / %s), marital status: %s.', $profile_id, $transaction_id, $type ) . PHP_EOL );
                $this->line( sprintf( '[+] Analysis IDs: %s', implode( ', ', $targets ) ) . PHP_EOL );

                $results = CANNEXHelper::get_guaranteed_rates( $profile_id, $transaction_id );

                if ( ( isset( $results->income_request_data ) ) && ( isset( $results->income_response_data ) ) ) {
                    if ( isset( $results->income_response_data->income_data ) ) {
                        $this->line( sprintf( PHP_EOL . '  <fg=black;bg=green> INCOME </> Guaranteed (Init/Low/High): %f/%f/%f', floatval( $results->income_response_data->income_data->initial_income ), floatval( $results->income_response_data->income_data->lowest_income ), floatval( $results->income_response_data->income_data->highest_income ) ) . PHP_EOL );
                    }
                }
            }

            $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

            return Command::SUCCESS;
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Some required parameters missing.' ) . PHP_EOL );

            return Command::FAILURE;
        }
    }
}
