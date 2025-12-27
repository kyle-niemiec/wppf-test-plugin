<?php
/**
 * DesignInk WordPress Framework
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to answers@designinkdigital.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the plugin to newer
 * versions in the future. If you wish to customize the plugin for your
 * needs please refer to https://designinkdigital.com
 *
 * @package   DesignInk/WordPress/Framework
 * @author    DesignInk Digital
 * @copyright Copyright (c) 2008-2026, DesignInk, LLC
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

?>

<div class="notice notice-<?php echo $Notice->type ?> is-dismissible">
	<?php // Optional notice heading ?>
	<?php if ( ! empty( $Notice->options['header'] ) ) : ?>
		<h3><?php _e( $Notice->options['header'] ); ?></h3>
	<?php endif; ?>

	<?php // Notice message ?>
	<p><?php _e( $Notice->message ); ?></p>

	<?php // Optional status code ?>
	<?php if ( ! empty( $Notice->options['status_code'] ) ) : ?>
		<div>
			<sub>Status code: <?php echo $Notice->options['status_code']; ?></sub>
		</div>
	<?php endif; ?>

	<?php // Optional notice hint ?>
	<?php if ( ! empty( $Notice->options['hint'] ) ) : ?>
		<div>
			<sup>Hint: <?php _e( $Notice->options['hint'] ); ?></sup>
		</div>
	<?php endif; ?>
</div>
