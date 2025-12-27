'use strict';

// Gulp-specific requires
import gulp from 'gulp';
import { Chalk } from 'chalk';
import prompt from 'gulp-prompt';
import jeditor from 'gulp-json-editor';
import replace from 'gulp-replace';

const chalk = new Chalk( { level: 3 } );

// NPM requires
import { sprintf } from 'sprintf-js';
import compareVersions from 'compare-versions';

// Process requires
import fs from 'node:fs';


/**
 * Find and replace 
 *
 * @param {function} cb The Gulp callback to return to other tasks in the pipeline.
 */
export function upgrade_version( cb ) {
	const packageJSON = fs.readFileSync( 'package.json' );
	const packageInfo = JSON.parse( packageJSON );
	const version = packageInfo.version;

	console.log( chalk.yellow( sprintf( 'Current package version: %s', version ) ) );

	// Start with version prompt
	gulp.src( 'gulpfile.js' )
		.pipe( prompt.prompt(
			{
				type: 'input',
				name: 'version',
				message: chalk.cyan( 'Enter the version to bump to (e.g. "1.0.0"):' )
			},
			function( res ) {
				// Limit subversions to 3 numbers with 3 digits each
				const numberFormat = /^\d{1,3}\.\d{1,3}\.\d{1,3}$/g;

				// Test version format
				if ( numberFormat.test( res.version ) ) {

					let newerVersion = false;

					if ( packageInfo.version ) {
						newerVersion = compareVersions(res.version, packageInfo.version) === 1;
					} else {
						newerVersion = true;
					}

					// Check if version is newer
					if ( newerVersion ) {
						console.log( chalk.green( res.version ) );

						/**
						 * Find/replace all version instances
						 */

						// Replace package.json version
						gulp.src( 'package.json' )
							.pipe( jeditor( {
								"version": res.version
							} ) )
							.pipe( gulp.dest( './' ) );

						// Replace composer.json version
						gulp.src( 'composer.json' )
							.pipe( jeditor( {
								"version": res.version
							} ) )
							.pipe( gulp.dest( './' ) );

						// Replace Framework PHP class version
						gulp.src( 'includes/classes/class-framework.php' )
							.pipe( replace( /const VERSION = '[\d\.]+';/, sprintf( 'const VERSION = \'%s\';', res.version ) ) )
							.pipe( gulp.dest( './includes/classes' ) );

						// Replace PHP file versions
						gulp.src( '**/*.php' )
							.pipe( replace( /DesignInk\\WordPress\\Framework(\\v[0-9_]+)?/g, sprintf( 'DesignInk\\WordPress\\Framework\\v%s', res.version.replace( /\./g, '_' ) ) ) )
							.pipe( gulp.dest( './' ) );

						cb();

					} else {
						const message = 'Provided version is not newer than the old version.';
						console.log( chalk.red( message ) );
						cb( new RangeError( message ) );
					}

				} else {
					const message = 'Provided version is not in the correct format.';
					console.log( chalk.red( message ) );
					cb( new TypeError( message ) );
				}

			}
		) );
}
