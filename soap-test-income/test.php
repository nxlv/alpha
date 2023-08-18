<?php

$handle = fopen( 'pdf-b64.txt', 'r' );

if ( $handle ) {
	$contents = fread( $handle, filesize( 'pdf-b64.txt' ) );

	fclose( $handle );

	echo strlen( $contents );

	$output = fopen( 'test.pdf', 'w' );

	fwrite( $output, base64_decode( $contents ) );
	fclose( $output );
}
