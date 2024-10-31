<?php

namespace MSKMCWP\Dashboard\Shared;


use MSKMCWP\Illuminate\Prefix\AutoPrefix;
use WilcityServiceClient\Helpers\GetSettings;

trait GeneralHelper
{
    protected $dashboardSlug = 'dashboard';
    protected $authSlug      = 'auth-settings';

    protected function getDashboardSlug(): string
    {
        return AutoPrefix::namePrefix($this->dashboardSlug);
    }

    protected function getAuthSlug(): string
    {
        return AutoPrefix::namePrefix($this->authSlug);
    }

    private function getToken()
    {
        $token = get_option('mskmc_purchase_code');
        if (!empty($token)) {
            return $token;
        }

        if (class_exists('\WilcityServiceClient\Helpers\GetSettings')) {
            return GetSettings::getOptionField('secret_token');
        }

        return '';
    }
}
