<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSB_CPT {
	const POST_TYPE = 'fsb_faq_block';

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'column_content' ), 10, 2 );
	}

	public static function register_post_type() {
		$labels = array(
			'name'               => __( 'FAQ Blocks', 'faqora-faq-schema-blocks' ),
			'singular_name'      => __( 'FAQ Block', 'faqora-faq-schema-blocks' ),
			'add_new'            => __( 'Add New', 'faqora-faq-schema-blocks' ),
			'add_new_item'       => __( 'Add New FAQ Block', 'faqora-faq-schema-blocks' ),
			'edit_item'          => __( 'Edit FAQ Block', 'faqora-faq-schema-blocks' ),
			'new_item'           => __( 'New FAQ Block', 'faqora-faq-schema-blocks' ),
			'view_item'          => __( 'View FAQ Block', 'faqora-faq-schema-blocks' ),
			'search_items'       => __( 'Search FAQ Blocks', 'faqora-faq-schema-blocks' ),
			'not_found'          => __( 'No FAQ blocks found.', 'faqora-faq-schema-blocks' ),
			'menu_name'          => __( 'FAQ Schema', 'faqora-faq-schema-blocks' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => $labels,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => 'dashicons-editor-help',
				'supports'            => array( 'title' ),
				'capability_type'     => 'post',
				'has_archive'         => false,
				'exclude_from_search' => true,
			)
		);
	}

	public static function activate() {
		self::register_post_type();
		flush_rewrite_rules();
	}

	public static function columns( $columns ) {
		$columns['fsb_shortcode'] = __( 'Shortcode', 'faqora-faq-schema-blocks' );
		$columns['fsb_items']     = __( 'Questions', 'faqora-faq-schema-blocks' );
		return $columns;
	}

	public static function column_content( $column, $post_id ) {
		if ( 'fsb_shortcode' === $column ) {
			echo '<code>[faq_schema_block id=&quot;' . esc_attr( $post_id ) . '&quot;]</code>';
		}

		if ( 'fsb_items' === $column ) {
			$items = get_post_meta( $post_id, '_fsb_items', true );
			echo esc_html( is_array( $items ) ? count( $items ) : 0 );
		}
	}
}
