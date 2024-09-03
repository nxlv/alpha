<?php

namespace App\Console\Commands;

use App\Http\Helpers\ProductHelper;
use App\Http\Helpers\CANNEXHelper;
use App\Models\AnalysisCache;
use App\Models\Index;
use App\Models\Rule;
use App\Models\RulesState;
use App\Models\Product;
use App\Models\ProductsInstancesStrategy;
use App\Models\ProductsInstancesStrategiesRate;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use ZanySoft\Zip\Facades\Zip;

class CANNEXUpdate extends Command {
    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the latest data from the CANNEX FTP server.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        //$_param_dry_run = $this->option( 'dry-run' );

        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Cache Builder </> v%s', $this->version ) . PHP_EOL );

        /**
         * SFTP
         */
        $this->line( '  <fg=white;bg=blue> NOTICE </> Connecting to SFTP host.' . PHP_EOL );

        $files = Storage::disk( 'cannex' )->allFiles( 'NFSDIST' );

        if ( $files ) {
            $this->line( '  <fg=white;bg=blue> NOTICE </> Connected.  Enumerating files...' . PHP_EOL );

            $results = [];

            foreach ( $files as $file ) {
                $matches = [];

                preg_match( '/.+\/[A-Z0-9]+_([A-Z0-9]+)_([A-Z0-9]+)_([A-Z0-9]+)_([A-Z0-9]+)-[0-9\.]+.zip/', $file, $matches );

                if ( ( !empty( $matches ) ) && ( count( $matches ) === 5 ) ) {
                    $file_type = $matches[ 1 ];
                    $file_version = $matches[ 2 ];
                    $file_date = $matches[ 3 ];
                    $file_time = $matches[ 4 ];
                    $file_datetime = Carbon::parse( sprintf( '%s %s', $file_date, $file_time ) );

                    if ( $file_type === 'FIA' ) {
                        $results[ $file_datetime->getTimestamp() ] = [
                            'file' => $file,
                            'version' => $file_version,
                            'datetime' => $file_datetime->getTimestamp(),
                            'datetime_string' => $file_datetime->toDateTimeLocalString()
                        ];
                    }
                }
            }

            ksort( $results );

            $top = array_pop( $results );

            $this->line( sprintf( '  <fg=white;bg=green> FOUND </> version %s (%s).  Downloading...', $top[ 'version' ], $top[ 'datetime_string' ] ) );

            $contents = Storage::disk( 'cannex' )->get( $top[ 'file' ] );

            if ( !empty( $contents ) ) {
                if ( !Storage::disk( 'local' )->exists( 'versions' ) ) {
                    Storage::disk( 'local' )->makeDirectory( 'versions' );
                }

                Storage::disk( 'local' )->put( sprintf( 'versions/%s.zip', $top[ 'version' ] ), $contents );

                //if ( Storage::disk( 'local' )->exists( storage_path( sprintf( 'versions/%s.zip', $top[ 'version' ] ) ) ) ) {
                    $this->line( sprintf( '  <fg=white;bg=green> SAVED </> File version %s has been saved to disk.', $top[ 'version' ] ) );

                    $zip = Zip::open( storage_path( sprintf( 'app/versions/%s.zip', $top[ 'version' ] ) ) );

                    if ( $zip ) {
                        $this->line( sprintf( '  <fg=white;bg=blue> EXTRACTING </> Extracting version %s to the import directory...', $top[ 'version' ] ) );

                        $zip->extract( storage_path( 'app/public/import' ) );
                        $zip->close();

                        $this->line( sprintf( '  <fg=white;bg=green> EXTRACTED </> File version %s has been saved to the import directory.', $top[ 'version' ] ) );

                        // set global version
                        Storage::disk( 'config' )->put( 'cannex.php', sprintf( '<?php return [ \'version\' => \'%s\' ];', $top[ 'version' ] ) );
                    } else {
                        $this->line( sprintf( '  <fg=white;bg=red> ERROR </> File version %s could not be extracted to the import directory..', $top[ 'version' ] ) );
                    }
                //} else {
                    //$this->line( sprintf( '  <fg=white;bg=red> ERROR </> File version %s could not be saved to disk.', $top[ 'version' ] ) );
                //}
            } else {
                $this->line( sprintf( '  <fg=white;bg=red> ERROR </> File version %s could not be downloaded.', $top[ 'version' ] ) );
            }
        } else {
            $this->line( '  <fg=white;bg=red> NOTICE </> Failed to connect to SFTP host.' . PHP_EOL );
        }

        $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

        return Command::SUCCESS;
    }
}
