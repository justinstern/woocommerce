<?php
/**
 * WooCommerce General Settings
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Settings_General' ) ) :

/**
 * WC_Admin_Settings_General.
 */
class WC_Settings_General extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id    = 'general';
		$this->label = __( 'General', 'woocommerce' );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {

		$currency_code_options = get_woocommerce_currencies();

		foreach ( $currency_code_options as $code => $name ) {
			$currency_code_options[ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
		}

		$settings = apply_filters( 'woocommerce_general_settings', array(

			array( 'title' => __( 'General Options', 'woocommerce' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'    => __( 'Base Location', 'woocommerce' ),
				'desc'     => __( 'This is the base location for your business. Tax rates will be based on this country.', 'woocommerce' ),
				'id'       => 'woocommerce_default_country',
				'css'      => 'min-width:350px;',
				'default'  => 'GB',
				'type'     => 'single_select_country',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Selling Location(s)', 'woocommerce' ),
				'desc'     => __( 'This option lets you limit which countries you are willing to sell to.', 'woocommerce' ),
				'id'       => 'woocommerce_allowed_countries',
				'default'  => 'all',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'css'      => 'min-width: 350px;',
				'desc_tip' => true,
				'options'  => array(
					'all'        => __( 'Sell to All Countries', 'woocommerce' ),
					'all_except' => __( 'Sell to All Countries, Except For&hellip;', 'woocommerce' ),
					'specific'   => __( 'Sell to Specific Countries', 'woocommerce' ),
				),
			),

			array(
				'title'   => __( 'Sell to All Countries, Except For&hellip;', 'woocommerce' ),
				'desc'    => '',
				'id'      => 'woocommerce_all_except_countries',
				'css'     => 'min-width: 350px;',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'   => __( 'Sell to Specific Countries', 'woocommerce' ),
				'desc'    => '',
				'id'      => 'woocommerce_specific_allowed_countries',
				'css'     => 'min-width: 350px;',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'    => __( 'Shipping Location(s)', 'woocommerce' ),
				'desc'     => __( 'Choose which countries you want to ship to, or choose to ship to all locations you sell to.', 'woocommerce' ),
				'id'       => 'woocommerce_ship_to_countries',
				'default'  => '',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'desc_tip' => true,
				'options'  => array(
					''         => __( 'Ship to all countries you sell to', 'woocommerce' ),
					'all'      => __( 'Ship to all countries', 'woocommerce' ),
					'specific' => __( 'Ship to specific countries only', 'woocommerce' ),
					'disabled' => __( 'Disable shipping &amp; shipping calculations', 'woocommerce' ),
				),
			),

			array(
				'title'   => __( 'Ship to Specific Countries', 'woocommerce' ),
				'desc'    => '',
				'id'      => 'woocommerce_specific_ship_to_countries',
				'css'     => '',
				'default' => '',
				'type'    => 'multi_select_countries',
			),

			array(
				'title'    => __( 'Default Customer Location', 'woocommerce' ),
				'id'       => 'woocommerce_default_customer_address',
				'desc_tip' => __( 'This option determines a customers default location. The MaxMind GeoLite Database will be periodically downloaded to your wp-content directory if using geolocation.', 'woocommerce' ),
				'default'  => 'geolocation',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'options'  => array(
					''                 => __( 'No location by default', 'woocommerce' ),
					'base'             => __( 'Shop base address', 'woocommerce' ),
					'geolocation'      => __( 'Geolocate', 'woocommerce' ),
					'geolocation_ajax' => __( 'Geolocate (with page caching support)', 'woocommerce' ),
				),
			),

			array(
				'title'   => __( 'Enable Taxes', 'woocommerce' ),
				'desc'    => __( 'Enable taxes and tax calculations', 'woocommerce' ),
				'id'      => 'woocommerce_calc_taxes',
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'title'   => __( 'Store Notice', 'woocommerce' ),
				'desc'    => __( 'Enable site-wide store notice text', 'woocommerce' ),
				'id'      => 'woocommerce_demo_store',
				'default' => 'no',
				'type'    => 'checkbox',
			),

			array(
				'title'    => __( 'Store Notice Text', 'woocommerce' ),
				'desc'     => '',
				'id'       => 'woocommerce_demo_store_notice',
				'default'  => __( 'This is a demo store for testing purposes &mdash; no orders shall be fulfilled.', 'woocommerce' ),
				'type'     => 'textarea',
				'css'     => 'width:350px; height: 65px;',
				'autoload' => false,
			),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

			array( 'title' => __( 'Currency Options', 'woocommerce' ), 'type' => 'title', 'desc' => __( 'The following options affect how prices are displayed on the frontend.', 'woocommerce' ), 'id' => 'pricing_options' ),

			array(
				'title'    => __( 'Currency', 'woocommerce' ),
				'desc'     => __( 'This controls what currency prices are listed at in the catalog and which currency gateways will take payments in.', 'woocommerce' ),
				'id'       => 'woocommerce_currency',
				'css'      => 'min-width:350px;',
				'default'  => 'GBP',
				'type'     => 'select',
				'class'    => 'wc-enhanced-select',
				'desc_tip' => true,
				'options'  => $currency_code_options,
			),

			array(
				'title'    => __( 'Currency Position', 'woocommerce' ),
				'desc'     => __( 'This controls the position of the currency symbol.', 'woocommerce' ),
				'id'       => 'woocommerce_currency_pos',
				'css'      => 'min-width:350px;',
				'class'    => 'wc-enhanced-select',
				'default'  => 'left',
				'type'     => 'select',
				'options'  => array(
					'left'        => __( 'Left', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . '99.99)',
					'right'       => __( 'Right', 'woocommerce' ) . ' (99.99' . get_woocommerce_currency_symbol() . ')',
					'left_space'  => __( 'Left with space', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ' 99.99)',
					'right_space' => __( 'Right with space', 'woocommerce' ) . ' (99.99 ' . get_woocommerce_currency_symbol() . ')',
				),
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Thousand Separator', 'woocommerce' ),
				'desc'     => __( 'This sets the thousand separator of displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_thousand_sep',
				'css'      => 'width:50px;',
				'default'  => ',',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Decimal Separator', 'woocommerce' ),
				'desc'     => __( 'This sets the decimal separator of displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_decimal_sep',
				'css'      => 'width:50px;',
				'default'  => '.',
				'type'     => 'text',
				'desc_tip' => true,
			),

			array(
				'title'    => __( 'Number of Decimals', 'woocommerce' ),
				'desc'     => __( 'This sets the number of decimal points shown in displayed prices.', 'woocommerce' ),
				'id'       => 'woocommerce_price_num_decimals',
				'css'      => 'width:50px;',
				'default'  => '2',
				'desc_tip' => true,
				'type'     => 'number',
				'custom_attributes' => array(
					'min'  => 0,
					'step' => 1,
				),
			),

			array( 'type' => 'sectionend', 'id' => 'pricing_options' ),

		) );

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Output a colour picker input box.
	 *
	 * @param mixed $name
	 * @param string $id
	 * @param mixed $value
	 * @param string $desc (default: '')
	 */
	public function color_picker( $name, $id, $value, $desc = '' ) {
		echo '<div class="color_box">' . wc_help_tip( $desc ) . '
			<input name="' . esc_attr( $id ). '" id="' . esc_attr( $id ) . '" type="text" value="' . esc_attr( $value ) . '" class="colorpick" /> <div id="colorPickerDiv_' . esc_attr( $id ) . '" class="colorpickdiv"></div>
		</div>';
	}

	/**
	 * Save settings.
	 */
	public function save() {
		$settings = $this->get_settings();

		WC_Admin_Settings::save_fields( $settings );
	}
}

endif;

return new WC_Settings_General();
