<?php

namespace Yoast\WP\SEO\Tests\WP\Formatter;

use WPSEO_Metabox_Formatter;
use WPSEO_Post_Metabox_Formatter;
use WPSEO_Utils;
use Yoast\WP\SEO\Tests\WP\TestCase;

/**
 * Unit Test Class.
 */
final class Metabox_Formatter_Test extends TestCase {

	/**
	 * Test with getting the values.
	 *
	 * @covers WPSEO_Metabox_Formatter::__construct
	 * @covers WPSEO_Metabox_Formatter::get_values
	 * @covers WPSEO_Metabox_Formatter::get_defaults
	 * @covers WPSEO_Metabox_Formatter::get_translations
	 *
	 * @return void
	 */
	public function test_getting_the_values() {
		$class_instance = new WPSEO_Metabox_Formatter(
			new WPSEO_Post_Metabox_Formatter(
				$this->factory->post->create_and_get(),
				[],
				''
			)
		);

		$result = $class_instance->get_values();

		$this->assertTrue( \array_key_exists( 'translations', $result ) );
		$this->assertTrue( \is_array( $result['translations'] ) );
	}

	/**
	 * Test with getting the values from the language file, because we hadn't one in our test.
	 *
	 * @covers WPSEO_Metabox_Formatter::__construct
	 * @covers WPSEO_Metabox_Formatter::get_values
	 * @covers WPSEO_Metabox_Formatter::get_defaults
	 * @covers WPSEO_Metabox_Formatter::get_translations
	 *
	 * @return void
	 */
	public function test_with_fake_language_file() {
		$file_name = \WPSEO_PATH . 'languages/wordpress-seo-' . \get_user_locale() . '.json';

		// Make sure the folder exists.
		\wp_mkdir_p( \WPSEO_PATH . 'languages' );
		\file_put_contents(
			$file_name,
			WPSEO_Utils::format_json_encode( [ 'key' => 'value' ] )
		);

		$class_instance = new WPSEO_Metabox_Formatter(
			new WPSEO_Post_Metabox_Formatter(
				$this->factory->post->create_and_get(),
				[],
				''
			)
		);

		$result = $class_instance->get_values();

		$this->assertTrue( \array_key_exists( 'translations', $result ) );
		$this->assertTrue( \is_array( $result['translations'] ) );
		$this->assertEquals( [ 'key' => 'value' ], $result['translations'] );

		\unlink( $file_name );

		$result = $class_instance->get_values();

		$this->assertEquals( $result['translations'], [] );
	}
}
