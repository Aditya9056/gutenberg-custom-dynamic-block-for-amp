<?php
/**
 * Class Block
 *
 * @package SsrDemo
 */

namespace SsrDemo;

/**
 * Class Block
 *
 * @package SsrDemo
 */
class Block {

	/**
	 * The name of the block.
	 *
	 * @var string
	 */
//	const BLOCK_NAME = 'ssr-demo/text';
	/**
	 * The plugin.
	 *
	 * @var Plugin
	 */
	public $plugin;

	/**
	 * Block constructor.
	 *
	 * @param Plugin $plugin The instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Inits the class.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Registers the block as a dynamic block, with a render_callback.
	 */
	public function register_block() {
		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				self::BLOCK_NAME,
				array(
					'attributes'      => array(
						'text' => array(
							'type' => 'string',
						),
					),
					'render_callback' => array( $this, 'render_block' ),
				)
			);
		}
	}

	/**
	 * Gets the markup of the dynamic 'AR Viewer' block, including its scripts.
	 *
	 * @param array $attributes The block attributes.
	 * @return string|null $markup The markup of the block.
	 */
	public function render_block( $attributes ) {
		if ( ! isset( $attributes['text'] ) ) {
			return;
		}

		return $attributes['text'];
	}
}
