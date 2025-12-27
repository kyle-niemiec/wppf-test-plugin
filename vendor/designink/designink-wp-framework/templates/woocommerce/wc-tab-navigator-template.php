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

?>

<div class="panel-wrap">
	<ul class="wc-tabs">

		<?php // Render tabs ?>
		<?php foreach ( $Navigator->get_tabs() as $tab ) : ?>
			<li class="<?php echo $tab['id']; ?> <?php echo array_key_exists( 'active', $tab ) && $tab['active'] ? 'active' : ''; ?>" data-tab-id="<?php echo $tab['id']; ?>">
				<a href="javascript:void(0);">
					<?php echo $tab['label']; ?>
				</a>
			</li>
		<?php endforeach; ?>

	</ul>

	<?php // Render content ?>
	<?php foreach ( $Navigator->get_tabs() as $tab ) : ?>
		<div class="panel woocommerce_options_panel <?php echo $tab['id']; ?>">
			<?php if ( is_callable( $tab['render'] ) ) : call_user_func_array( $tab['render'], array( $tab['render_args'] ) ); endif; ?>
		</div>
	<?php endforeach; ?>

</div>
