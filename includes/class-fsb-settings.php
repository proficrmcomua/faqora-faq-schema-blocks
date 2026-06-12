<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class FSB_Settings {
	const OPTION = 'fsb_settings';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function defaults() {
		return array(
			'enable_css'     => '1',
			'enable_schema'  => '1',
			'heading_tag'    => 'h3',
			'accordion_mode' => '0',
		);
	}

	public static function get_settings() {
		$options = get_option( self::OPTION, array() );
		return wp_parse_args( is_array( $options ) ? $options : array(), self::defaults() );
	}

	public static function add_settings_page() {
		add_submenu_page(
			'edit.php?post_type=' . FSB_CPT::POST_TYPE,
			__( 'FAQ Schema Settings', 'faqora-faq-schema-blocks' ),
			__( 'Settings', 'faqora-faq-schema-blocks' ),
			'manage_options',
			'fsb-settings',
			array( __CLASS__, 'render_settings_page' )
		);
	}

	public static function register_settings() {
		register_setting(
			'fsb_settings_group',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize' ),
				'default'           => self::defaults(),
			)
		);
	}

	public static function sanitize( $input ) {
		$input = is_array( $input ) ? $input : array();
		$heading_tag = isset( $input['heading_tag'] ) && in_array( $input['heading_tag'], array( 'h2', 'h3', 'h4', 'div' ), true ) ? $input['heading_tag'] : 'h3';

		return array(
			'enable_css'     => ! empty( $input['enable_css'] ) ? '1' : '0',
			'enable_schema'  => ! empty( $input['enable_schema'] ) ? '1' : '0',
			'heading_tag'    => $heading_tag,
			'accordion_mode' => ! empty( $input['accordion_mode'] ) ? '1' : '0',
		);
	}

	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = self::get_settings();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Faqora FAQ Schema Blocks Settings', 'faqora-faq-schema-blocks' ); ?></h1>
			<p><?php esc_html_e( 'Create reusable FAQ blocks, display them with shortcodes, and automatically add FAQPage JSON-LD schema markup.', 'faqora-faq-schema-blocks' ); ?></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'fsb_settings_group' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Frontend CSS', 'faqora-faq-schema-blocks' ); ?></th>
						<td>
							<label><input type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[enable_css]" value="1" <?php checked( $settings['enable_css'], '1' ); ?> /> <?php esc_html_e( 'Load default plugin styles', 'faqora-faq-schema-blocks' ); ?></label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'FAQPage schema', 'faqora-faq-schema-blocks' ); ?></th>
						<td>
							<label><input type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[enable_schema]" value="1" <?php checked( $settings['enable_schema'], '1' ); ?> /> <?php esc_html_e( 'Output JSON-LD FAQPage schema', 'faqora-faq-schema-blocks' ); ?></label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="fsb-heading-tag"><?php esc_html_e( 'Question heading tag', 'faqora-faq-schema-blocks' ); ?></label></th>
						<td>
							<select id="fsb-heading-tag" name="<?php echo esc_attr( self::OPTION ); ?>[heading_tag]">
								<?php foreach ( array( 'h2', 'h3', 'h4', 'div' ) as $tag ) : ?>
									<option value="<?php echo esc_attr( $tag ); ?>" <?php selected( $settings['heading_tag'], $tag ); ?>><?php echo esc_html( strtoupper( $tag ) ); ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Accordion mode', 'faqora-faq-schema-blocks' ); ?></th>
						<td>
							<label><input type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[accordion_mode]" value="1" <?php checked( $settings['accordion_mode'], '1' ); ?> /> <?php esc_html_e( 'Use native details/summary accordion markup', 'faqora-faq-schema-blocks' ); ?></label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
