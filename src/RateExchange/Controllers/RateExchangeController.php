<?php

namespace MSKMCWP\RateExchange\Controllers;

use MSKMCWP\Illuminate\Message\MessageFactory;
use WP_REST_Request;

class RateExchangeController {
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'registerRouter' ] );
		add_action('wp_ajax_' . MSKMC_PREFIX . 'getMenuWP', [$this, 'ajaxGetMenuWP']);
	}
	public function ajaxGetMenuWP()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$this->getRateExchanges($oRequest);
	}
	public function registerRouter() {
		register_rest_route( MSKMC_REST, 'rate-exchanges', [
			[
				'methods' => 'GET',
				'permission_callback' => '__return_true',
				'callback' => [ $this, 'getRateExchanges' ]
			]
		] );
	}

	public function getRateExchanges( WP_REST_Request $oRequest ) {
		header( 'content-type: text/javascript; charset=utf-8' );
		$response = wp_remote_retrieve_body(
			wp_remote_get( '//multicurrency.myshopkit.app/vge/mskmc/v1/rate-exchanges' )
		);
		echo esc_html( $response);
		die();
	}
}
