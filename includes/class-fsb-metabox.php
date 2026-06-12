<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSB_Metabox {
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . FSB_CPT::POST_TYPE, array( __CLASS__, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );
	}

	public static function add_meta_boxes() {
		add_meta_box(
			'fsb_faq_items',
			__( 'FAQ Questions and Answers', 'faqora-faq-schema-blocks' ),
			array( __CLASS__, 'render_metabox' ),
			FSB_CPT::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'fsb_shortcode_help',
			__( 'Shortcode', 'faqora-faq-schema-blocks' ),
			array( __CLASS__, 'render_shortcode_box' ),
			FSB_CPT::POST_TYPE,
			'side',
			'default'
		);
	}

	public static function enqueue_admin_assets( $hook ) {
		$screen = get_current_screen();
		if ( ! $screen || FSB_CPT::POST_TYPE !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'fsb-admin', FSB_PLUGIN_URL . 'assets/js/admin.js', array(), FSB_VERSION, true );
		wp_enqueue_style( 'fsb-admin', FSB_PLUGIN_URL . 'assets/css/admin.css', array(), FSB_VERSION );
	}

	public static function render_shortcode_box( $post ) {
		if ( empty( $post->ID ) ) {
			echo '<p>' . esc_html__( 'Publish or save this FAQ block to get its shortcode.', 'faqora-faq-schema-blocks' ) . '</p>';
			return;
		}

		echo '<p>' . esc_html__( 'Copy and paste this shortcode into any post, page, widget, or custom post type:', 'faqora-faq-schema-blocks' ) . '</p>';
		echo '<code>[faq_schema_block id=&quot;' . esc_attr( $post->ID ) . '&quot;]</code>';
	}

	public static function render_metabox( $post ) {
		wp_nonce_field( 'fsb_save_items', 'fsb_nonce' );
		$items = get_post_meta( $post->ID, '_fsb_items', true );
		if ( ! is_array( $items ) || empty( $items ) ) {
			$items = array( array( 'question' => '', 'answer' => '' ) );
		}
		?>
		<div id="fsb-items">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="fsb-item">
					<p>
						<label><strong><?php esc_html_e( 'Question', 'faqora-faq-schema-blocks' ); ?></strong></label>
						<input type="text" class="widefat" name="fsb_items[<?php echo esc_attr( $index ); ?>][question]" value="<?php echo esc_attr( $item['question'] ?? '' ); ?>" />
					</p>
					<p>
						<label><strong><?php esc_html_e( 'Answer', 'faqora-faq-schema-blocks' ); ?></strong></label>
						<textarea class="widefat" rows="5" name="fsb_items[<?php echo esc_attr( $index ); ?>][answer]"><?php echo esc_textarea( $item['answer'] ?? '' ); ?></textarea>
					</p>
					<button type="button" class="button fsb-remove-item"><?php esc_html_e( 'Remove', 'faqora-faq-schema-blocks' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<p><button type="button" class="button button-primary" id="fsb-add-item"><?php esc_html_e( 'Add Question', 'faqora-faq-schema-blocks' ); ?></button></p>

		<script type="text/html" id="fsb-item-template">
			<div class="fsb-item">
				<p>
					<label><strong><?php esc_html_e( 'Question', 'faqora-faq-schema-blocks' ); ?></strong></label>
					<input type="text" class="widefat" name="fsb_items[__INDEX__][question]" value="" />
				</p>
				<p>
					<label><strong><?php esc_html_e( 'Answer', 'faqora-faq-schema-blocks' ); ?></strong></label>
					<textarea class="widefat" rows="5" name="fsb_items[__INDEX__][answer]"></textarea>
				</p>
				<button type="button" class="button fsb-remove-item"><?php esc_html_e( 'Remove', 'faqora-faq-schema-blocks' ); ?></button>
			</div>
		</script>
		<?php
	}

	public static function save( $post_id ) {
		if ( ! isset( $_POST['fsb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fsb_nonce'] ) ), 'fsb_save_items' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized recursively by self::sanitize_items_input().
		$raw_items = isset( $_POST['fsb_items'] ) ? self::sanitize_items_input( wp_unslash( $_POST['fsb_items'] ) ) : array();
		$items     = array();

		foreach ( $raw_items as $raw_item ) {
			$question = isset( $raw_item['question'] ) ? $raw_item['question'] : '';
			$answer   = isset( $raw_item['answer'] ) ? $raw_item['answer'] : '';

			if ( '' === $question && '' === trim( wp_strip_all_tags( $answer ) ) ) {
				continue;
			}

			$items[] = array(
				'question' => $question,
				'answer'   => $answer,
			);
		}

		update_post_meta( $post_id, '_fsb_items', $items );
	}

	/**
	 * Sanitize submitted FAQ items.
	 *
	 * @param mixed $items Raw submitted items.
	 * @return array
	 */
	private static function sanitize_items_input( $items ) {
		if ( ! is_array( $items ) ) {
			return array();
		}

		$sanitized = array();

		foreach ( $items as $item ) {
			if ( ! is_array( $item ) ) {
				continue;
			}

			$sanitized[] = array(
				'question' => isset( $item['question'] ) ? sanitize_text_field( $item['question'] ) : '',
				'answer'   => isset( $item['answer'] ) ? wp_kses_post( $item['answer'] ) : '',
			);
		}

		return $sanitized;
	}

}
