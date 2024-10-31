<?php

namespace MSKMCWP\Store\Controllers;


use Exception;
use MSKMCWP\Illuminate\Message\MessageFactory;
use MSKMCWP\Illuminate\Prefix\AutoPrefix;
use MSKMCWP\Shared\Json;
use WP_REST_Request;


class StoreController
{
	protected $optionKey = '';

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'registerRouter']);
		$this->optionKey = AutoPrefix::namePrefix('currency_settings');
		add_action('wp_ajax_' . MSKMC_PREFIX . 'getMeSettings', [$this, 'ajaxGetMeSettings']);
		add_action('wp_ajax_' . MSKMC_PREFIX . 'saveSettings', [$this, 'ajaxSaveSettings']);
		add_action('wp_ajax_' . MSKMC_PREFIX . 'updateSettings', [$this, 'ajaxUpdateSettings']);
	}

	public function ajaxGetMeSettings()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->getSettings($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function ajaxSaveSettings()
	{
		if (isset($_POST['params']) && !empty($_POST['params'])) {
			$aParams = $_POST['params'];
		} else {
			$aData = json_decode(file_get_contents('php://input'), true);
			$aParams = $aData['params'];
		}
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->createSettings($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function ajaxUpdateSettings()
	{
		if (isset($_POST['params']) && !empty($_POST['params'])) {
			$aParams = $_POST['params'];
		} else {
			$aData = json_decode(file_get_contents('php://input'), true);
			$aParams = $aData['params'];
		}
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->updateSettings($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function registerRouter()
	{
		register_rest_route(MSKMC_REST, 'me/settings', [
			[
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => [$this, 'createSettings']
			],
			[
				'methods'             => 'DELETE',
				'permission_callback' => '__return_true',
				'callback'            => [$this, 'deleteSettings']
			],
			[
				'methods'             => 'PUT',
				'permission_callback' => '__return_true',
				'callback'            => [$this, 'updateSettings']
			],
			[
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => [$this, 'getSettings']
			]
		]);
	}

	public function createSettings(WP_REST_Request $oRequest)
	{
		try {
			$this->isShopLoggedIn();
			$aSettings = $oRequest->get_param('settings');
			if (empty($aSettings)) {
				throw new Exception(esc_html__('The settings is required', 'myshopkit-multi-currency-converter-wp'),
					400);
			}

			if (!is_array($aSettings)) {
				throw new Exception(esc_html__('Invalid setting format data', 'myshopkit-multi-currency-converter-wp'),
					400);
			}
			update_option($this->optionKey, Json::encode($aSettings));
			return MessageFactory::factory('rest')
				->success(esc_html__('Congrats, Your settings have been saved successfully',
					'myshopkit-multi-currency-converter-wp'),
					[]);
		}
		catch (Exception $oException) {
			return MessageFactory::factory('rest')->error($oException->getMessage(), $oException->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	private function isShopLoggedIn()
	{
		if (empty(get_current_user_id())) {
			throw new Exception(esc_html__('You must log into the site', 'myshopkit-multi-currency-converter-wp'), 403);
		}
		if (!current_user_can('manage_options')) {
			throw new Exception(esc_html__('You do not have permission to access this area',
				'myshopkit-multi-currency-converter-wp'),
				403);
		}
	}

	public function deleteSettings(WP_REST_Request $oRequest)
	{
		try {
			$this->isShopLoggedIn();
			update_option($this->optionKey, '');
			return MessageFactory::factory('rest')
				->success(esc_html__('Congrats, The data has been deleted successfully',
					'myshopkit-multi-currency-converter-wp'));
		}
		catch (Exception $oException) {
			return MessageFactory::factory('rest')->error($oException->getMessage(), $oException->getCode());
		}
	}

	public function updateSettings(WP_REST_Request $oRequest)
	{
		try {
			$this->isShopLoggedIn();
			if ($aSettings = $oRequest->get_param('settings')) {
				if (!is_array($aSettings)) {
					throw new Exception(esc_html__('Invalid setting format data',
						'myshopkit-multi-currency-converter-wp'), 400);
				}
			}
			update_option($this->optionKey, Json::encode($aSettings));
			return MessageFactory::factory('rest')
				->success(esc_html__('Congrats, Your settings have been saved successfully',
					'myshopkit-multi-currency-converter-wp'));
		}
		catch (Exception $oException) {
			return MessageFactory::factory('rest')->error($oException->getMessage(), $oException->getCode());
		}
	}

	public function getSettings(WP_REST_Request $oRequest)
	{
		try {
			$this->isShopLoggedIn();
			$settings = get_option($this->optionKey);
			$aSettings = empty($settings) ? [] : Json::decode($settings);

			return MessageFactory::factory('rest')->success(esc_html__('Congrats, The data has been got 
            successfully', 'myshopkit-multi-currency-converter-wp'), [
				'settings' => empty($aSettings) ? null : $aSettings
			]);
		}
		catch (Exception $oException) {
			return MessageFactory::factory('rest')->error($oException->getMessage(), $oException->getCode());
		}
	}
}
