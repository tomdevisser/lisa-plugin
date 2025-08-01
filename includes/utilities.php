<?php

function lisa_time_ago( string $iso8601 ): string {
	try {
		$time = new DateTimeImmutable( $iso8601 );
		$time = $time->setTimezone( wp_timezone() );
	} catch ( Exception $e ) {
		return '-';
	}

	$now  = new DateTimeImmutable( 'now', wp_timezone() );
	$diff = $now->getTimestamp() - $time->getTimestamp();

	if ( $diff < 60 ) {
		return __( 'Just now', 'lisa' );
	}

	$units = [
		'year'   => 365 * 24 * 60 * 60,
		'month'  => 30 * 24 * 60 * 60,
		'day'    => 24 * 60 * 60,
		'hour'   => 60 * 60,
		'minute' => 60,
	];

	foreach ( $units as $unit => $seconds ) {
		if ( $diff >= $seconds ) {
			$value = floor( $diff / $seconds );

			// Translate singular and plural forms of the unit label
			$unit_translated = _n(
				'%s ' . $unit,
				'%s ' . $unit . 's',
				$value,
				'lisa'
			);

			return sprintf( __( '%s ago', 'lisa' ), sprintf( $unit_translated, $value ) );
		}
	}

	return __( 'Just now', 'lisa' );
}
