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
 * A static class to hold styling utilities for console text.
 */
final class StyleUtil
{
    /**
	 * Create a string surrounded by a specified terminal-compatible color.
	 * 
	 * @param string $message The message to colorize.
	 * @param string $color The terminal-compatible color for the message.
	 * 
	 * @return string The color-wrapped message.
	 */
	public static function color( string $message, string $color ): string
	{
		return sprintf( '<fg=%s>%s</>', $color, $message );
	}

    /**
     * Create a string with styling for an error message.
     * 
     * @param string $message The message to style.
     * 
     * @return string The error-stylized message.
     */
    public static function error( string $message ): string
    {
        return sprintf( '<bg=red;fg=white>%s</>', $message );
    }

	/**
	 * The shared styling function for optional text components.
	 * 
	 * @param string $message The message to apply optional styling to.
	 * 
	 * @return string The message with styling applied for optional text components.
	 */
	public static function optional( string $message ): string
	{
		return self::color( $message, 'gray' );
	}
}
