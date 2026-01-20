<?php
/**
 * WordPress Plugin Framework
 *
 * Copyright (c) 2008–2026 DesignInk, LLC
 * Copyright (c) 2026 Kyle Niemiec
 *
 * This file is licensed under the GNU General Public License v3.0.
 * See the LICENSE file for details.
 *
 * @package WPPF
 */

namespace WPPF\CLI\Static;

/**
 * A static class to hold general utilities for console functions.
 */
final class CliUtil
{
	/**
	 * Fetch a desired component template and apply replacement variables to it.
	 * 
	 * @param string $component The capitalized name of the component to fetch a template for.
	 * @param array $replacements An associative array of keys and values which replace corresponding template variables with values.
	 * 
	 * @throws \RuntimeException Will throw an exception if the template is not found or a file error occurs.
	 * @return string The fully formed template with variables dropped in.
	 */
	public static function apply_template( string $component, array $replacements ): string
	{
		// Ensure the requested template exists
		$template_path = __DIR__ . "/../Template/{$component}Template.php.tpl";

		if ( ! file_exists( $template_path ) ) {
			throw new \RuntimeException( 'Plugin template file not found.' );
		}

		// Load template
		$template = file_get_contents( $template_path );

		// Apply replacements
		return str_replace(
			array_keys( $replacements ),
			array_values( $replacements ),
			$template
		);
	}

	/**
	 * Convert a lower-dash-case directory name to an Upper_Underscore_Case class name.
	 * 
	 * @param string $folder_name The name of the slugified folder to convert.
	 * 
	 * @return string The converted name of the class.
	 */
	public static function plugin_class_name( string $folder_name ): string
	{
		// Replace dashes with underscores
		$name = str_replace( '-', '_', $folder_name );

		// Capitalize each word separated by underscores
		$parts = explode( '_', $name );
		$parts = array_map( fn( $part ) => ucfirst( $part ), $parts );

		return implode( '_', $parts );
	}
}
