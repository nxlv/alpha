<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class CANNEXMigrations extends Command {
    protected $version = '1.0';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:migrations {schema_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates database migration(s) for a given CANNEX schema.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Migration Generator </> v%s', $this->version ) . PHP_EOL );

        $schemas = Config::get( 'canx.cannex' );
        $requested_schema = $this->argument( 'schema_name' );

        if ( ( !empty( $requested_schema ) ) && ( isset( $schemas[ 'lookup' ][ $requested_schema ] ) ) ) {
            $this->generate( Config::get( sprintf( 'canx.models.%s', $schemas[ 'lookup' ][ $requested_schema ] ) ) );
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Requested schema <bg=red;fg=white> %s </> was not found.', $requested_schema ) . PHP_EOL );
            $this->line( sprintf( '  <bg=blue;fg=white> VALID SCHEMAS </> %s', implode( ', ', array_keys( $schemas[ 'lookup' ] ) ) ) . PHP_EOL );

            return Command::FAILURE;
        }

        $this->line( PHP_EOL . '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

        return Command::SUCCESS;
    }

    public function generate( $schema ) {
        $schema_table = $schema[ 'data' ][ 'table_name' ];

        if ( $schema_table ) {
            $this->line( sprintf( '  <bg=magenta;fg=white> GENERATING </> Table: <fg=cyan>%s</> ...', $schema_table ) );

            $schema_defs = [];

            foreach ( $schema[ 'fields' ] as $field_name => $field_data ) {
                $schema_defs[] = sprintf( '%s:%s', $field_name, ( ( isset( $field_data[ 'data_type' ] ) ) ? $field_data[ 'data_type' ] : 'string' ) );
            }

            Artisan::call(
                'make:migration:schema',
                [
                    'name' => sprintf( 'create_%s_table', $schema_table ),
                    '--schema' => join( ', ', $schema_defs ),
                    '--model' => 'true'
                ]
            );
        }

        if ( ( isset( $schema[ 'data' ][ 'child_tables' ] ) ) && ( !empty( $schema[ 'data' ][ 'child_tables' ] ) ) ) {
            foreach ( $schema[ 'data' ][ 'child_tables' ] as $child_schema ) {
                $this->generate( $child_schema );
            }
        }
    }
}
