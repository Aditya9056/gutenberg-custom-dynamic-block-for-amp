<?php
/**
 * Plugin Name: Dynamic Block
 * Description: Block Scaffolding for WordPress.
 * Version: 1.0.0
 * Author: XWP
 * Author URI: https://github.com/xwp/block-scaffolding-wp
 * Text Domain: block-scaffolding
 *
 * @package dynamic-block
 */

namespace XWP\BlockScaffolding;

/**
 * Class Dynamic_Block
 */

namespace XWP\BlockScaffolding;

/**
 * Class Dynamic_Block
 */
class Dynamic_Block {
	/**
	 * The name of the block.
	 *
	 * @var BLOCK_NAME
	 */
	const BLOCK_NAME = 'xwp/dynamic-block';

	/**
	 * The plugin.
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Loading assets and initialization.
	 *
	 * @param Dynamic_Block $plugin The instance of the plugin.
	 */
	public function __construct() {
//		$this->plugin = $plugin;
	}

	/**
	 * Loading assets and initialization.
	 */
	public function init() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_api' ) );
		add_action( 'wp_loaded', array( $this, 'init_xwp' ) );
	}

	/**
	 * Initialize modules.
	 */
	public function init_xwp() {
		register_block_type(
			'xwp/dynamic-block',
			array(
				'editor_script'   => 'js-dynamic-block',
				'render_callback' => array( $this, 'render' ),
				'attributes'      => array(
					'content'           => array(
						'type' => 'string',
					),
					'amp_template_data' => 'string',
				),
			)
		);
	}

	/**
	 * Getting validation data.
	 */
	public function amp_validation_data() {
		$query = new \WP_Query(
			array(
				'post_type'                   => 'amp_validated_url',
				'amp_validation_error_status' => array(
					0,
					1,
				),
				'update_post_meta_cache'      => false,
				'update_post_term_cache'      => false,
			)
		);

		$validated_urls = $query->found_posts;

		$validation_errors = wp_count_terms( 'amp_validation_error', array( 'group' => null ) );

		$option = get_option( 'amp-options' );

		return array( $validated_urls, $validation_errors, $option['theme_support'] );
	}

	/**
	 * Register Rest API.
	 */
	public function register_rest_api() {
		register_rest_route(
			'xwp/dynamic-block',
			'/amp_data/(?P<toggleSwitch>(true|false))',
			array(
				'method'   => 'GET',
				'callback' => array( $this, 'amp_data' ),
			)
		);
	}

	/**
	 * Render front-end.
	 *
	 * @param Dynamic_Block $data is the value coming from api call.
	 */
	public function amp_data( $data ) {
		if ( 'true' === $data['toggleSwitch'] ) {
			$option = get_option( 'amp-options' );
			return rest_ensure_response( $option['theme_support'] );
		} else {
			return rest_ensure_response( false );
		}
	}

	/**
	 * Enqueuing assets.
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_script(
			'js-dynamic-block',
			plugins_url( 'build/index.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-server-side-render' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
			true
		);

		wp_enqueue_style(
			'css-editor-dynamic-block',
			plugins_url( 'src/editor.css', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-server-side-render' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'src/editor.css' ),
			false
		);

		wp_enqueue_style(
			'css-front-dynamic-block',
			plugins_url( 'src/style.css', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-server-side-render' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'src/style.css' ),
			false
		);
	}

	/**
	 * Render front-end.
	 *
	 * @param Dynamic_Block $attributes contain all attributes to from register_block.
	 */
	public function render( $attributes ) {
		if ( ! isset( $attributes['content'] ) && ! isset( $attributes['amp_template_data'] ) ) {
			return;
		}

		$data = $this->amp_validation_data();

		if ( isset( $attributes['content'] ) ) {

			$content = '<h1>' . esc_html__( 'AMP Validation Statistics', 'block-scaffolding' ) . '</h1>';

			$content .=
				'<p>' . sprintf(
				/* translators: %s: search term */
					esc_html__( 'There are %s validated URLs.', 'block-scaffolding' ),
					$data[0]
				) . '</p>';

			$content .=
				'<p>' . sprintf(
				/* translators: %s: search term */
					esc_html__( 'There are %s validation errors.', 'block-scaffolding' ),
					$data[1]
				) . '</p>';

			// return ob_get_clean(); return ob_get_clean().
			return $content;
		}

		if ( isset( $attributes['amp_template_data'] ) ) {
			$amp_template = '<p>' . sprintf(
				/* translators: %s: search term */
					esc_html__( 'The template mode is %s.', 'block-scaffolding' ),
					$data[2]
				) . '</p> </div>';

			return $amp_template;
		}
	}
}

$obj = new Dynamic_Block();
$obj -> init();
