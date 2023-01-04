<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CANNEXImport extends Command {
    protected $version = '1.1';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cannex:import {schema_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start an import of static CANNEX XML data files.';

    /**
     * Unique table names for specified import
     *
     * @var array
     */
    private $table_uniques = [];

    /**
     * Stored variable array
     * Used during commit() iterations
     *
     * @var array
     */
    private $stored_vars = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        if ( Storage::exists( 'banner-cannex.asc' ) ) {
            $this->line( '<fg=blue>' . Storage::get( 'banner-cannex.asc' ) . '</>' );
        }

        $this->line( sprintf( '  <bg=blue;fg=white> CANNEX Data Importer </> v%s', $this->version ) . PHP_EOL );

        $schemas = Config::get( 'canx.cannex' );
        $requested_schema = $this->argument( 'schema_name' );

        if ( ( !empty( $requested_schema ) ) && ( isset( $schemas[ 'lookup' ][ $requested_schema ] ) ) ) {
            $directory = Storage::disk( 'local' )->path( 'public/import/' );
            $schema = Config::get( sprintf( 'canx.models.%s', $schemas[ 'lookup' ][ $requested_schema ] ) );

            $this->line( sprintf( '  <bg=white;fg=black> ENUMERATING </> Enumerating all possible database tables included in schema <bg=magenta;fg=white> %s </> ...', $requested_schema ) );
            $this->enumerate_tables( $schema );

            if ( ( !empty( $this->table_uniques ) ) && ( count( $this->table_uniques ) ) ) {
                $this->line( sprintf( '  <bg=magenta;fg=white> TRUNCATING </> Truncating all tables in schema <bg=magenta;fg=white> %s </> ...', $requested_schema ) . PHP_EOL );

                $this->output->progressStart( count( $this->table_uniques ) );

                foreach ( $this->table_uniques as $table_name ) {
                    DB::table( $table_name )->truncate();

                    $this->output->progressAdvance();
                }

                $this->output->progressFinish();
            }

            $document = simplexml_load_file( $directory . $schema[ 'data' ][ 'filename' ] );

            if ( property_exists( $document, $schema[ 'data' ][ 'node' ] ) ) {
                $this->line( sprintf( '  <bg=cyan;fg=black> IMPORTING </> Importing <bg=white;fg=black> %d </> records from schema <bg=magenta;fg=white> %s </> ...', count( $document->{ $schema[ 'data' ][ 'node' ] } ), $requested_schema ) . PHP_EOL );
                $this->output->progressStart( count( $document->{ $schema[ 'data' ][ 'node' ] } ) );

                foreach ( $document->{ $schema[ 'data' ][ 'node' ] } as $instance ) {
                    $this->stored_vars = [];

                    $row = $this->parse_element( $schema, $instance, $instance, $instance );

                    if ( !empty( $row ) ) {
                        $this->commit( $row );
                    }

                    $this->output->progressAdvance();
                }

                $this->output->progressFinish();
            }
        } else {
            $this->line( sprintf( '  <bg=red;fg=white> ERROR </> Requested schema <bg=red;fg=white> %s </> was not found.', $requested_schema ) . PHP_EOL );
            $this->line( sprintf( '  <bg=blue;fg=white> VALID SCHEMAS </> %s', implode( ', ', array_keys( $schemas[ 'lookup' ] ) ) ) . PHP_EOL );

            return Command::FAILURE;
        }

        $this->line( '  <bg=cyan;fg=black> DONE! </>' . PHP_EOL );

        return Command::SUCCESS;
    }

    private function commit( $row ) {
        if ( ( isset( $row[ 'data' ] ) ) && ( isset( $row[ 'table' ] ) ) && ( !empty( $row[ 'data' ] ) ) && ( !empty( $row[ 'table' ] ) ) ) {
            foreach ( $row[ 'data' ] as $column_key => $column_data ) {
                if ( ( stripos( $column_data, '@', 0 ) !== false ) && ( isset( $this->stored_vars[ $column_data ] ) ) && ( !empty( $this->stored_vars[ $column_data ] ) ) && ( intval( $this->stored_vars[ $column_data ] ) ) ) {
                    $row[ 'data' ][ $column_key ] = $this->stored_vars[ $column_data ];
                }
            }

            $inserted_id = DB::table( $row[ 'table' ] )->insertGetId( array_merge( $row[ 'data' ], [ 'created_at' => Carbon::now() ] ) );

            $this->stored_vars[ '@id:' . $row[ 'table' ] ] = $inserted_id;
        }

        if ( ( isset( $row[ 'children' ] ) ) && ( !empty( $row[ 'children' ] ) ) ) {
            foreach ( $row[ 'children' ] as $child ) {
                $this->commit( $child );
            }
        }
    }

    private function retarget_element( $pointer, $target, $parent, $root ) {
        $_targets = explode( ':', $target );

        foreach ( $_targets as $_target ) {
            switch ( $_target ) {
                case '_parent' :
                    if ( $pointer === $parent ) {
                        $pointer = $parent->xpath( 'parent::*' )[ 0 ];
                    } else {
                        $pointer = $parent;
                    }
                    break;

                case '_root' :
                    $pointer = $root;
                    break;

                case '_self' :
                    // do nothing
                    break;

                default :
                    $pointer = $pointer->{ $_target };
                    break;
            }
        }

        return $pointer;
    }

    private function parse_element( $_schema, $_element, $_parent = null, $_root = null, $_level = 0 ) {
        $result = [];

        $result[ 'table' ] = $_schema[ 'data' ][ 'table_name' ];
        $result[ 'data' ] = [];

        foreach ( $_schema[ 'fields' ] as $field_column => $field_data ) {
            $_pointer = $_element;

            if ( ( isset( $field_data[ 'target' ] ) ) && ( !empty( $field_data[ 'target' ] ) ) ) {
                $_pointer = $this->retarget_element( $_pointer, $field_data[ 'target' ], $_parent, $_root );
            }

            switch ( $field_data[ 'type' ] ) {
                case 'node' :
                    if ( !isset( $field_data[ 'key' ] ) ) {
                        $field_data[ 'key' ] = '';
                    }

                    switch ( $field_data[ 'key' ] ) {
                        case '_self' :
                            $result[ 'data' ][ $field_column ] = ( string ) $_pointer;
                            break;

                        default :
                            if ( ( isset( $field_data[ 'key' ] ) ) && ( !empty( $field_data[ 'key' ] ) ) ) {
                                $result[ 'data' ][ $field_column ] = ( string ) ( ( isset( $_pointer->{ $field_data[ 'key' ] } ) ) ? $_pointer->{ $field_data[ 'key' ] } : '' );
                            } else {
                                $result[ 'data' ][ $field_column ] = ( string ) $_pointer;
                            }
                            break;
                    }
                    break;

                case 'attribute' :
                    if ( !isset( $field_data[ 'node' ] ) ) {
                        $field_data[ 'node' ] = '';
                    }

                    switch ( $field_data[ 'node' ] ) {
                        case '_root' :
                            $result[ 'data' ][ $field_column ] = ( ( isset( $_root->attributes()->{ $field_data[ 'key' ] } ) ) ? ( string ) $_root->attributes()->{ $field_data[ 'key' ] } : '' );
                            break;

                        case '_self' :
                            $result[ 'data' ][ $field_column ] = ( ( isset( $_pointer->attributes()->{ $field_data[ 'key' ] } ) ) ? ( string ) $_pointer->attributes()->{ $field_data[ 'key' ] } : '' );
                            break;

                        case '_iterator' :
                            $result[ 'data' ][ $field_column ] = ( string ) $_pointer->xpath( 'parent::*' )[ 0 ]->attributes()->{ $field_data[ 'key' ] };
                            break;

                        default :
                            if ( ( isset( $field_data[ 'node' ] ) ) && ( !empty( $field_data[ 'node' ] ) ) ) {
                                $result[ 'data' ][ $field_column ] = ( ( ( isset( $_pointer->{ $field_data[ 'node' ] } ) ) && ( isset( $_pointer->{ $field_data[ 'node' ] }->attributes()->{ $field_data[ 'key' ] } ) ) ) ? ( string ) $_pointer->{ $field_data[ 'node' ] }->attributes()->{ $field_data[ 'key' ] } : '' );
                            } else {
                                $result[ 'data' ][ $field_column ] = ( ( isset( $_pointer->attributes()->{ $field_data[ 'key' ] } ) ) ? ( string ) $_pointer->attributes()->{ $field_data[ 'key' ] } : '' );
                            }
                            break;
                    }
                    break;

                case 'variable' :
                    $result[ 'data' ][ $field_column ] = $field_data[ 'variable' ];
                    break;
            }

            // final data checks
            if ( ( $field_data[ 'type' ] !== 'variable' ) && ( isset( $field_data[ 'data_type' ] ) ) ) {
                switch ( true ) {
                    case ( $field_data[ 'data_type' ] === 'date' ) :
                        if ( empty( $result[ 'data' ][ $field_column ] ) ) {
                            $result[ 'data' ][ $field_column ] = gmdate( 'Y-m-d' );
                        }
                        break;

                    case ( $field_data[ 'data_type' ] === 'smallInteger' ) :
                    case ( $field_data[ 'data_type' ] === 'bigInteger' ) :
                    case ( $field_data[ 'data_type' ] === 'float' ) :
                    case ( stripos( $field_data[ 'data_type' ], 'decimal' ) !== false ) :
                        if ( !is_numeric( $result[ 'data' ][ $field_column ] ) ) {
                            $result[ 'data' ][ $field_column ] = 0;
                        }
                        break;
                }
            }
        }

        if ( isset( $_schema[ 'data' ][ 'child_tables' ] ) ) {
            if ( !isset( $result[ 'children' ] ) ) {
                $result[ 'children' ] = [];
            }

            foreach ( $_schema[ 'data' ][ 'child_tables' ] as $node_data ) {
                // TODO: Refactor

                if ( ( isset( $node_data[ 'data' ][ 'element' ] ) ) && ( isset( $_element->{ $node_data[ 'data' ][ 'element' ] } ) ) && ( !empty( $_element->{ $node_data[ 'data' ][ 'element' ] } ) ) ) {
                    if ( ( isset( $node_data[ 'data' ][ 'node' ] ) ) && ( !empty( $node_data[ 'data' ][ 'node' ] ) ) ) {
                        /**
                         * ^ specificity: if we have a node and an element target, then we execute this block
                         **/
                        foreach ( $_element->{ $node_data[ 'data' ][ 'element' ] }->{ $node_data[ 'data' ][ 'node' ] } as $node_child ) {
                            $result[ 'children' ][] = $this->parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                        }
                    } else {
                        /**
                         * otherwise, we just execute on the element target itself, as it's probably a list of duplicate node types
                         * mixed in with other content, instead of wrapped in an iterator element.
                         **/
                        foreach ( $_element->{$node_data[ 'data' ][ 'element' ] } as $node_child ) {
                            $result[ 'children' ][] = $this->parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                        }
                    }
                } elseif ( ( isset( $node_data[ 'data' ][ 'node' ] ) ) && ( isset( $_element->{ $node_data[ 'data' ][ 'node' ] } ) ) && ( !empty( $_element->{ $node_data[ 'data' ][ 'node' ] } ) ) ) {
                    foreach ( $_element->{$node_data[ 'data' ][ 'node' ] } as $node_child ) {
                        $result[ 'children' ][] = $this->parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                    }
                }
            }
        }

        return $result;
    }

    private function enumerate_tables( $schema ) {
        if ( ( isset( $schema[ 'data' ][ 'table_name' ] ) ) && ( !empty( $schema[ 'data' ][ 'table_name' ] ) ) ) {
            if ( in_array( $schema[ 'data' ][ 'table_name' ], $this->table_uniques ) === false ) {
                array_push( $this->table_uniques, $schema[ 'data' ][ 'table_name' ] );
            }
        }

        if ( ( isset( $schema[ 'data' ][ 'child_tables' ] ) ) && ( !empty( $schema[ 'data' ][ 'child_tables' ] ) ) && ( is_array( $schema[ 'data' ][ 'child_tables' ] ) ) ) {
            foreach ( $schema[ 'data' ][ 'child_tables' ] as $child ) {
                $this->enumerate_tables( $child );
            }
        }
    }
}
