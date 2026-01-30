<?php

namespace App\Http\Controllers\Api\Util;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Util\Setting;
use Illuminate\Http\Request;

/**
 * @group Utilities
 */
class ApiSettingController extends ApiController
{
    /**
     * Configuration
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "max_ringing_call": "30",
     *       "customer_play_store": "https://play.google.com/store/apps/details?id=com.yelow.customer&hl=id",
     *       "customer_apps_store": "https://apps.apple.com/us/app/haloyelow-customer/id6444608099"
     *   }
     * }
     */
    public function index(Request $request)
    {
        $configurations = Setting::keys([
            'max_ringing_call', 'customer_play_store', 'customer_apps_store'
        ]);

        return $this->sendSuccess($configurations);
    }


    /**
     * HTML Document
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam key string required <code>term-condition | privacy-policies | about-us</code>
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": "<p>html-string-code</p>"
     * }
     */
    public function document(Request $request, $key)
    {
        $keySetting = null;
        switch ($key) {
            case 'term-condition':
                $keySetting = 'term_condition_en';
                break;
            case 'privacy-policies':
                $keySetting = 'privacy_policies_en';
                break;
            case 'about-us':
                $keySetting = 'about_us';
                break;
        }
        $html = $keySetting ? Setting::findValue($keySetting) : "";
        return $this->sendSuccess($html);
    }
}
