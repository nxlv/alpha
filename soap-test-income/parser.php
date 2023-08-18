<?php

    require 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

    $schema = [
        /**
         *
         * Income Benefit Profiles
         * file: anty_ds_ib_prfl-[x].[y].xml
         *
         */
        'data' => [
            'node' => 'text',
            'filename' => 'anty_ds_text-1.1.xml',
            'table_name' => 'texts'
        ],
        'fields' => [
            'text_id' => [ 'type' => 'attribute', 'target' => '_root', 'key' => 'text_id' ],
            'contents' => [ 'type' => 'node', 'node' => '_self' ],
        ]
    ];

    $directory = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'xsd' . DIRECTORY_SEPARATOR . 'anty' . DIRECTORY_SEPARATOR;

    $document = simplexml_load_file( $directory . $schema[ 'data' ][ 'filename' ] );

    if ( property_exists( $document, $schema[ 'data' ][ 'node' ] ) ) {
        echo '<pre>';

        foreach ( $document->{ $schema[ 'data' ][ 'node' ] } as $instance ) {
            $element = [];

            $element = parse_element( $schema, $instance, $instance, $instance );

            print_r( $element );
            echo '-----------------------------------------------------------------------------' . PHP_EOL;
        }

        echo '</pre>';
    }

    function retarget_element( $pointer, $target, $parent, $root ) {
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

    function parse_element( $_schema, $_element, $_parent = null, $_root = null, $_level = 0 ) {
        $result = [];

        $result[ 'table' ] = $_schema[ 'data' ][ 'table_name' ];
        $result[ 'data' ] = [];

        foreach ( $_schema[ 'fields' ] as $field_column => $field_data ) {
            $_pointer = $_element;

            if ( ( isset( $field_data[ 'target' ] ) ) && ( !empty( $field_data[ 'target' ] ) ) ) {
                $_pointer = retarget_element( $_pointer, $field_data[ 'target' ], $_parent, $_root );
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
            }
        }

        //echo 'level = ' . $_level . PHP_EOL;
        //echo 'checking schema (' . $_schema[ 'data' ][ 'table_name' ] . ') for child tables...' . PHP_EOL;

        echo str_pad( ' ', $_level * 2 ) . '- ' . $_schema[ 'data' ][ 'table_name' ] . PHP_EOL;

        if ( $_schema[ 'data' ][ 'table_name' ] == 'products_instances_strategies' ) {
            echo str_pad( ' ', $_level * 2 ) . '  - FOUND!' . PHP_EOL;
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
                            $result[ 'children' ][] = parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                        }
                    } else {
                        /**
                         * otherwise, we just execute on the element target itself, as it's probably a list of duplicate node types
                         * mixed in with other content, instead of wrapped in an iterator element.
                         **/
                        foreach ( $_element->{$node_data[ 'data' ][ 'element' ] } as $node_child ) {
                            $result[ 'children' ][] = parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                        }
                    }
                } elseif ( ( isset( $node_data[ 'data' ][ 'node' ] ) ) && ( isset( $_element->{ $node_data[ 'data' ][ 'node' ] } ) ) && ( !empty( $_element->{ $node_data[ 'data' ][ 'node' ] } ) ) ) {
                    foreach ( $_element->{$node_data[ 'data' ][ 'node' ] } as $node_child ) {
                        $result[ 'children' ][] = parse_element( $node_data, $node_child, $_element, $_root, ( $_level + 1 ) );
                    }
                }
            }
        }

        return $result;
    }
