<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSB_Shortcode {
	private static $schemas = array();
	private static $used_shortcode = false;

	public static function init() {
		add_shortcode( 'faq_schema_block', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_action( 'wp_footer', array( __CLASS__, 'output_schema' ), 99 );
	}

	public static function register_assets() {
		wp_register_style( 'faqora-faq-schema-blocks', FSB_PLUGIN_URL . 'assets/css/frontend.css', array(), FSB_VERSION );
	}

	public static function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'        => '',
				'title'     => '',
				'schema'    => '',
				'accordion' => '',
			),
			$atts,
			'faq_schema_block'
		);

		$post_id = absint( $atts['id'] );
		if ( ! $post_id || FSB_CPT::POST_TYPE !== get_post_type( $post_id ) ) {
			return '<p class="fsb-error">' . esc_html__( 'FAQ block not found.', 'faqora-faq-schema-blocks' ) . '</p>';
		}

		$items = get_post_meta( $post_id, '_fsb_items', true );
		if ( ! is_array( $items ) || empty( $items ) ) {
			return '<p class="fsb-error">' . esc_html__( 'This FAQ block has no questions yet.', 'faqora-faq-schema-blocks' ) . '</p>';
		}

		$settings = FSB_Settings::get_settings();

		if ( '1' === $settings['enable_css'] ) {
			wp_enqueue_style( 'faqora-faq-schema-blocks' );
		}

		$schema_enabled = ( '' !== $atts['schema'] ) ? filter_var( $atts['schema'], FILTER_VALIDATE_BOOLEAN ) : ( '1' === $settings['enable_schema'] );
		$accordion      = ( '' !== $atts['accordion'] ) ? filter_var( $atts['accordion'], FILTER_VALIDATE_BOOLEAN ) : ( '1' === $settings['accordion_mode'] );
		$heading_tag    = $settings['heading_tag'];
		$block_title    = ( '' !== trim( $atts['title'] ) ) ? sanitize_text_field( $atts['title'] ) : get_the_title( $post_id );

		if ( $schema_enabled ) {
			self::add_schema( $items );
		}

		self::$used_shortcode = true;

		ob_start();
		?>
		<div class="fsb-faq-block" data-fsb-id="<?php echo esc_attr( $post_id ); ?>">
			<?php if ( '' !== $block_title ) : ?>
				<h2 class="fsb-title"><?php echo esc_html( $block_title ); ?></h2>
			<?php endif; ?>

			<?php foreach ( $items as $item ) :
				$question = isset( $item['question'] ) ? trim( $item['question'] ) : '';
				$answer   = isset( $item['answer'] ) ? trim( $item['answer'] ) : '';
				if ( '' === $question || '' === wp_strip_all_tags( $answer ) ) {
					continue;
				}
				?>
				<div class="fsb-faq-item">
					<?php if ( $accordion ) : ?>
						<details class="fsb-details">
							<summary class="fsb-question"><?php echo esc_html( $question ); ?></summary>
							<div class="fsb-answer"><?php echo wp_kses_post( wpautop( $answer ) ); ?></div>
						</details>
					<?php else : ?>
						<?php echo '<' . esc_attr( $heading_tag ) . ' class="fsb-question">' . esc_html( $question ) . '</' . esc_attr( $heading_tag ) . '>'; ?>
						<div class="fsb-answer"><?php echo wp_kses_post( wpautop( $answer ) ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	private static function add_schema( $items ) {
		foreach ( $items as $item ) {
			$question = isset( $item['question'] ) ? trim( wp_strip_all_tags( $item['question'] ) ) : '';
			$answer   = isset( $item['answer'] ) ? trim( wp_strip_all_tags( $item['answer'] ) ) : '';

			if ( '' === $question || '' === $answer ) {
				continue;
			}

			self::$schemas[] = array(
				'@type'          => 'Question',
				'name'           => $question,
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $answer,
				),
			);
		}
	}

	public static function output_schema() {
		if ( empty( self::$schemas ) ) {
			return;
		}

		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => array_values( self::$schemas ),
		);

		echo "\n" . '<script type="application/ld+json" class="faqora-faq-schema-blocks-jsonld">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
