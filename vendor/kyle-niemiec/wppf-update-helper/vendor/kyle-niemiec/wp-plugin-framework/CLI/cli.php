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

use WPPF\CLI\Command\CreatePluginCommand;
use WPPF\CLI\Command\FrameworkVersionUpgradeCommand;
use Symfony\Component\Console\Application;

$application = new Application( 'WP Plugin Framework CLI', '0.0.1' );

// Register commands
$application->add( new CreatePluginCommand );
$application->add( new FrameworkVersionUpgradeCommand );

// Run the CLI
$application->run();
