<?php

namespace MSKMCWP\Illuminate\Prefix;

class AutoPrefix {
	public static function namePrefix( $name ) {
		return strpos( $name, MSKMC_PREFIX ) === 0 ? $name : MSKMC_PREFIX . $name;
	}

	public static function removePrefix( string $name ): string {
		if ( strpos( $name, MSKMC_PREFIX ) === 0 ) {
			$name = str_replace( MSKMC_PREFIX, '', $name );
		}

		return $name;
	}
}
