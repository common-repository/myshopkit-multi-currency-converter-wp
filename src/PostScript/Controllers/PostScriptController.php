<?php

namespace MSKMCWP\PostScript\Controllers;


use MSKMCWP\Illuminate\Prefix\AutoPrefix;
use MSKMCWP\Shared\Json;

class PostScriptController
{
    const Woocommerce = 'Woocommerce';
    private $urlJs = '//currency-converter-client.netlify.app/main.js';

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts(): bool
    {
        $currency = !function_exists('get_woocommerce_currency') ? 'USD' : get_woocommerce_currency();
        $currencyPos = str_replace('_space', '', get_option('woocommerce_currency_pos'));
        $aSettings = Json::decode(get_option(AutoPrefix::namePrefix('currency_settings')));
        wp_localize_script('jquery', self::Woocommerce, [
            'currency' => [
                'active'   => $currency,
                'position' => $currencyPos,
            ],
            'restBase' => trailingslashit(rest_url(MSKMC_REST)),
        ]);
        wp_localize_script('jquery', 'CURRENCY_SETTINGS', $aSettings);
        wp_enqueue_script(
            AutoPrefix::namePrefix('post-script'),
            $this->urlJs,
            ['jquery'],
            MSKMC_REST_VERSION,
            true
        );
        return true;
    }
}
