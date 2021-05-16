<?php
/**
 * PHPUnit Test file for Dynamic_Block
 *
 * @package Dynamic_Block
 */

namespace XWP\BlockScaffolding;

use WP_Mock;
use Mockery;

/**
 * Sample test case.
 */
class Test_Dynamic_Block extends WP_Mock\Tools\TestCase {
	/**
	 * Instance of plugin.
	 *
	 * @var $instance
	 */
	public $instance;

	/**
	 * Setup class from WP_Mock.
	 */
	public function setUp(): void {
		parent::setUp();
		WP_Mock::setUp();
		$plugin         = new Plugin( dirname( dirname( __FILE__ ) ) );
		$this->instance = new Dynamic_Block( $plugin );
	}

	/**
	 * Test constructor
	 */
	public function test_construct() {
		$this->assertEquals( __NAMESPACE__ . '\\Dynamic_Block', get_class( $this->instance ) );
	}

	/**
	 * Test constructor
	 */
	public function test_init() {
		WP_Mock::expectActionAdded( 'enqueue_block_editor_assets', array( $this->instance, 'wp_enqueue_scripts' ) );
		WP_Mock::expectActionAdded( 'rest_api_init', array( $this->instance, 'register_rest_api' ) );
		WP_Mock::expectActionAdded( 'wp_loaded', array( $this->instance, 'init_xwp' ) );
		$this->instance->init();
	}

	/**
	 * Test Register Block
	 */
	public function test_init_xwp() {
		WP_Mock::userFunction( 'register_block_type' )
			->once()
			->with( Dynamic_Block::BLOCK_NAME, Mockery::type( 'array' ) );

		$this->instance->init_xwp();
	}

	/**
	 * Test Register Block
	 */
	public function test_amp_validation_data() {
		Mockery::mock( 'WP_Query' )
		->shouldReceive( 'have_posts' )
		->andReturn( true );
	}

	/**
	 * Test Register Rest API.
	public function test_register_rest_api() {
	WP_Mock::userFunction( 'register_rest_route' )
	->once()
	->with( Dynamic_Block::BLOCK_NAME, Mockery::type( 'array' ) );

	$this->instance->register_rest_api();
	}
	 */


	/**
	 * Gets the test data for test_render_block().
	 *
	 * @return array The test data.
	public function get_render_block_data() {
	$content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
	$amp_template_data = 'Amp Validation Data.';

	return [
	'no_attribute' => [
	[],
	'',
	],
	'empty_text'   => [
	[ 'text' => '' ],
	'',
	],
	'text_exists'  => [
	compact( 'content', 'amp_template_data' ),
	$content, $amp_template_data,
	],
	];
	}
	 */


	/**
	 * Test render_block.
	 *
	 * @dataProvider get_render_block_data
	 * @covers \XWP\BlockScaffolding\Dynamic_Block::render_block()
	 *
	 * @param array       $attributes The block attributes.
	 * @param string|null $expected   The expected return value.
	public function test_render_block( $attributes, $expected ) {
	$this->assertEquals(
	$expected,
	trim( $this->instance->render( $attributes ) )
	);
	}
	 */

	/**
	 * TearDown function from WP_Mock.
	 */
	public function tearDown(): void {
		WP_Mock::tearDown();
		Mockery::close();
		parent::tearDown();
	}
}
