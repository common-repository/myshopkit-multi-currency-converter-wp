<?php

namespace MSKMCWP\Dashboard\Controllers;


use MSKMCWP\Dashboard\Shared\GeneralHelper;
use MSKMCWP\Illuminate\Prefix\AutoPrefix;

class DashboardController {
	use GeneralHelper;

	const MSKMC_GLOBAL = 'MSKMC_GLOBAL';


	public function __construct() {
		add_action( 'admin_menu', [ $this, 'registerMenu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScriptsToDashboard' ] );
		add_filter( 'wp_is_application_passwords_available', '__return_true', 9999 );
	}

	public function enqueueScriptsToDashboard( $hook ): bool {
		$pluginName = explode( '/', str_replace( WP_PLUGIN_DIR . '/', '', __DIR__ ) )[0];
		wp_localize_script( 'jquery', self::MSKMC_GLOBAL, [
			'url'               => admin_url( 'admin-ajax.php' ),
			'restBase'          => trailingslashit( rest_url( MSKMC_REST ) ),
//			'restBase'          => 'https://wookit.myshopkit.app/wp-json/ev/v1/',
			'email'             => get_option( 'admin_email' ),
			'clientSite'        => home_url( '/' ),
			'purchaseCode'      => $this->getToken(),
			'purchaseCodeLink'  => 'https://docs.wiloke.com/myshopkit-multi-currency-converter/how-can-i-unlock-premium-features',
			'productName'       => $pluginName,
			'youtubePreviewUrl' => ''
		] );

		wp_enqueue_style(
			AutoPrefix::namePrefix( 'dashboard-style' ),
			plugin_dir_url( __FILE__ ) . '../Assets/Css/Style.css',
			[],
			MSKMC_VERSION
		);

		if ( ( strpos( $hook, $this->getDashboardSlug() ) !== false ) ||
		     ( strpos( $hook, $this->getAuthSlug() ) !== false ) ) {
			// enqueue script
			wp_enqueue_script(
				AutoPrefix::namePrefix( 'dashboard-script' ),
				plugin_dir_url( __FILE__ ) . '../Assets/Js/Script.js',
				[ 'jquery' ],
				MSKMC_VERSION,
				true
			);
		}

		return false;
	}

	public function registerMenu() {
		add_menu_page(
			esc_html__( 'Multi Currency Dashboard', 'myshopkit-multi-currency-converter-wp' ),
			esc_html__( 'Multi Currency Dashboard', 'myshopkit-multi-currency-converter-wp' ),
			'administrator',
			$this->getDashboardSlug(),
			[ $this, 'renderSettings' ],
			plugin_dir_url( __FILE__) . '../Assets/Images/ico.png'
		);
	}

	public function renderSettings() {
		?>
        <div id="multi-currency-dashboard">
            <iframe id="currency-iframe" src="<?php echo esc_url( $this->getIframe() ); ?>"></iframe>
        </div>
		<?php
	}

	private function getIframe(): string {
//		return "https://wordpress-currency.netlify.app/";
	    return "https://currency-converter-dashboard.netlify.app/";
    }
}
