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

namespace WPPF\CLI\Command;

use WPPF\CLI\Static\CliUtil;
use WPPF\CLI\Static\HelperBundle;
use WPPF\CLI\Static\StyleUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * A command to gather information from the user and construct a plugin main file for the user in the CWD.
 */
#[AsCommand(
	description: 'Create a base plugin file with a series of prompts.',
	name: 'make:plugin'
)]
final class CreatePluginCommand extends Command
{
	/**
	 * Set up the helper variables, control user message flow.
	 * 
	 * @param InputInterface $input The terminal input interface.
	 * @param OutputInterface $output The terminal output interface.
	 * 
	 * @return int The command success status.
	 */
	protected function execute( InputInterface $input, OutputInterface $output ): int
	{
		// Set up command variables
		$slug = basename( getcwd() );
		$plugin_class_name = CliUtil::plugin_class_name( $slug );
		$bundle = new HelperBundle( new QuestionHelper, $input, $output );

		// Ensure the plugin file doesn't exist
		if ( self::check_plugin_exists( $slug ) ) {
			$output->writeln( StyleUtil::error( 'Error: A plugin file already exists in this directory.' ) );
			return Command::FAILURE;
		}

		// Informational output (say hello)
		self::informational_output( $output, $plugin_class_name, $slug );

		// Gather user information
		$template_data = self::gather_plugin_information( $bundle, $plugin_class_name );

		// Apply the data to the template
		try {
			$template = CliUtil::apply_template( 'Plugin', $template_data );
		} catch ( \RuntimeException $e ) {
			throw $e;
		}

		// Write file
		if ( ! self::create_plugin_file( $template, $slug ) ) {
			$output->writeln( StyleUtil::error( "There was an error writing out the plugin file to disk." ) );
			return Command::FAILURE;
		}

		// Success!
		return Command::SUCCESS;
	}

	/**
	 * Ask the user what the plugin name should be, but it cannot be empty.
	 * 
	 * @param HelperBundle $bundle The bundle containing the question and IO interfaces.
	 * 
	 * @return string The plugin name entered by the user.
	 */
	private static function ask_plugin_name( HelperBundle $bundle ): string
	{
		$plugin_name_question = new Question( "Plugin Name: " );

		$plugin_name_question->setValidator( function ( $value ) use ( $bundle ): string {
			if ( $value === null || trim( $value ) === '' ) {
				throw new \RuntimeException( "The plugin name cannot be empty." );
			}

			return $value;
		} );

		return $bundle->helper->ask( $bundle->input, $bundle->output, $plugin_name_question );
	}

	/**
	 * Ask the user what the plugin URI is.
	 * 
	 * @param HelperBundle $bundle The bundle containing the question and IO interfaces.
	 * 
	 * @return string|null The plugin URI entered by the user.
	 */
	private static function ask_plugin_uri( HelperBundle $bundle ): ?string
	{
		$question = sprintf( "Plugin URI %s: ", StyleUtil::optional( "(optional)" ) );
		$plugin_uri_question = new Question( $question, null );

		return $bundle->helper->ask( $bundle->input, $bundle->output, $plugin_uri_question );
	}

	/**
	 * Ask the user what the plugin description is.
	 * 
	 * @param HelperBundle $bundle The bundle containing the question and IO interfaces.
	 * 
	 * @return string|null The plugin description entered by the user.
	 */
	private static function ask_plugin_description( HelperBundle $bundle ): ?string
	{
		$question = sprintf( "Description %s: ", StyleUtil::optional( "(optional)" ) );
		$plugin_description_question = new Question( $question, null );

		return $bundle->helper->ask( $bundle->input, $bundle->output, $plugin_description_question );
	}

	/**
	 * Ask the user what the author name is, but it cannot be empty.
	 * 
	 * @param HelperBundle $bundle The bundle containing the question and IO interfaces.
	 * 
	 * @return string The author name entered by the user.
	 */
	private static function ask_author_name( HelperBundle $bundle ): string
	{
		$author_name_question = new Question( 'Author: ' );

		$author_name_question->setValidator( function ( $value ) use ( $bundle ): string {
			if ( $value === null || trim( $value ) === '' ) {
				throw new \RuntimeException( "The author name cannot be empty." );
			}

			return $value;
		} );

		return $bundle->helper->ask( $bundle->input, $bundle->output, $author_name_question );
	}

	/**
	 * Ask the user what the author's URI is.
	 * 
	 * @param HelperBundle $bundle The bundle containing the question and IO interfaces.
	 * 
	 * @return string|null The author URI entered by the user.
	 */
	private static function ask_author_uri( HelperBundle $bundle ): ?string
	{
		$question = sprintf( "Author URI %s: ", StyleUtil::optional( "(optional)" ) );
		$author_uri_question = new Question( $question, null );

		return $bundle->helper->ask( $bundle->input, $bundle->output, $author_uri_question );
	}

	/**
	 * Check if an expected plugin file already exists.
	 * 
	 * @param string $slug The lower-dash-case slug pulled from the folder name.
	 * 
	 * @return bool Returns true if the plugin file exists, false if it does not.
	 */
	private static function check_plugin_exists( string $slug ): bool
	{
		$output_file = sprintf( '%s/%s.php', getcwd(), $slug );
		return file_exists( $output_file );
	}

	/**
	 * Create the plugin file from the completed template string.
	 * 
	 * @param string $template The file contents to write out.
	 * @param string $slug The lower-dash-case slug to use for the file name.
	 * 
	 * @return bool The status of the file write operation.
	 */
	private static function create_plugin_file( string $template, string $slug ): bool
	{
		$output_file = sprintf( '%s/%s.php', getcwd(), $slug );
		return file_put_contents( $output_file, $template );
	}

	/**
	 * Prompt for the plugin and author information from the user.
	 * 
	 * @param HelperBundle $bundle The question helper and IO interfaces for interactive user data collection.
	 * @param string $plugin_class_name The Upper_Underscored_Case name of the plugin class.
	 * 
	 * @return array An associative array with plugin information for a template.
	 */
	private static function gather_plugin_information( HelperBundle $bundle, string $plugin_class_name ): array
	{
		$plugin_name = self::ask_plugin_name( $bundle );
		$plugin_uri = self::ask_plugin_uri( $bundle );
		$plugin_description = self::ask_plugin_description( $bundle );
		$author_name = self::ask_author_name( $bundle );
		$author_uri = self::ask_author_uri( $bundle );

		// Call template with user information
		return [
			'{{plugin_name}}' => $plugin_name,
			'{{plugin_uri}}' => $plugin_uri,
			'{{description}}' => $plugin_description,
			'{{author}}' => $author_name,
			'{{author_uri}}' => $author_uri,
			'{{plugin_class_name}}' => $plugin_class_name,
		];
	}

	/**
	 * A helper function to move the informational text out of the execution context.
	 * 
	 * @param OutputInterface $output The terminal output interface.
	 * @param string $plugin_class_name The Upper_Underscore_Case class name for informational output.
	 * @param string $slug The reference slug for the plugin naming.
	 */
	private static function informational_output( OutputInterface $output, string $plugin_class_name, string $slug ): void
	{
		$output->writeln( "🚀~~~✨" );
		$output->writeln( "Thanks for using my plugin framework! I hope you get as much use out of it as I have!" );
		$output->writeln( "Follow the prompts below to create your main plugin file." );
		$output->writeln( "✨~~~🚀" );

		$output->writeln(
			StyleUtil::color(
				sprintf( 'Creating plugin class %s (%s)', $plugin_class_name, $slug ),
				'gray'
			)
		);
	}
}
