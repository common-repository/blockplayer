<?php
/**
 * Plugin Name:       Blockplayer
 * Plugin URI:        https://gutenplayer.com/
 * Author:            Gutenplayer
 * Author URI:        https://gutenplayer.com/
 * Description:       WordPress video player for WordPress gutenberg blocks and WordPress gutenberg template library - Blockplayer
 * Tags: gutenberg video player, block, video player, gutenberg video player, gutenberg, block player, video player for wordpress blocks
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Tested up to:      6.0
 * Version:           1.1.0
 * Domain Path:       /languages
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       blockplayer
 *
 * @package           blockplayer
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
function blockplayer_blocks_register_block_type($blockname, $options = array()){
    register_block_type(
        'blockplayer/' . $blockname,
        array_merge(
            array(
                'api_version' => 2,
                'editor_script'   => 'blockplayer-all-script',
                'editor_style'    => 'blockplayer-editor-style',
                'script'          => 'blockplayer-client-script',
                'style'           => 'blockplayer-frontend-style',
            ),
            $options
        )
    );
}
function blockplayer_blocks_register(){

    $asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
    $client_asset_file = include( plugin_dir_path( __FILE__ ) . 'build/client.asset.php');

	wp_register_script(
		'blockplayer-all-script',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_register_script(
		'blockplayer-client-script',
		plugins_url( 'build/client.js', __FILE__ ),
		$client_asset_file['dependencies'],
		$client_asset_file['version']
	);

    wp_register_style(
        'blockplayer-editor-style',
        plugins_url( 'build/index.css', __FILE__ ),
    );

    wp_register_style(
        'blockplayer-frontend-style',
        plugins_url( 'build/style-index.css', __FILE__ ),
    );

    wp_enqueue_style('blockplayer-frontend-style');
    wp_enqueue_script('blockplayer-client-script');
    blockplayer_blocks_register_block_type('blockplayer');
    blockplayer_blocks_register_block_type('popup-player');
}
add_action( 'init', 'blockplayer_blocks_register');


add_filter( 'block_categories_all', 'blockplayer_plugin_block_categories', 10, 2 );
function blockplayer_plugin_block_categories( $categories, $post ) {
    $blockplayer_cat = array(
        'slug'  => 'blockplayer',
        'title' => __( 'Blockplayer', 'blockplayer' ),
    );
    array_unshift( $categories, $blockplayer_cat );
    return $categories;
}

// blockplayer