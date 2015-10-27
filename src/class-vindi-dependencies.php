<?php

class Vindi_Dependencies
{

	private static $active_plugins;

  /**
   * Init Vindi_Dependencies.
   */
	public static function init()
  {
		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}

  /**
   * WooCommerce fallback notice.
   * @return  boolean
   */
	public static function check()
  {
		$plugin = strtolower($plugin);

		if ( ! self::$active_plugins ) self::init();

		if ( ! self::is_woocommerce_activated() ) {
			add_action( 'admin_notices', 'Vindi_Dependencies::woocommerceMissingNotice' );
			return false;
		}
	}

  /**
   * WooCommerce fallback notice.
   * @return  string
   */
  public static function woocommerceMissingNotice()
  {
      echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Vindi Gateway depende da última versão do %s para funcionar!', VINDI_IDENTIFIER ), '<a href="https://wordpress.org/extend/plugins/woocommerce/">' . __( 'WooCommerce', VINDI_IDENTIFIER ) . '</a>' ) . '</p></div>';
  }

  /**
   * WooCommerce Extra Checkout Fields for Brazil fallback notice.
   * @return  string
   */
  public static function extraCheckoutMissingNotice()
  {
    echo '<div class="error"><p>' . sprintf( __( 'WooCommerce Vindi Gateway depende da última versão do %s para funcionar!', VINDI_IDENTIFIER ), '<a href="https://wordpress.org/extend/plugins/woocommerce-extra-checkout-fields-for-brazil/">' . __( 'WooCommerce Extra Checkout Fields for Brazil', VINDI_IDENTIFIER ) . '</a>' ) . '</p></div>';
  }

	/**
	 * @return boolean
	 **/
	public function is_woocommerce_activated()
	{
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
	}
}
