<?php

namespace App\Http\Controllers\Api\Util;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Util\Banner;
use App\Models\Util\CompanyCategory;
use App\Models\Util\FAQ;
use App\Models\Util\ReportReason;
use Illuminate\Http\Request;


/**
 * @group Utilities
 */
class ApiUtilController extends ApiController
{
    /**
     * FAQ
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "question": "What is HaloYelow?",
     *       "answer": "<p>html-string-code</p>"
     *       }
     *   ]
     * }
     */
    public function faq(Request $request)
    {
        $items = FAQ::query()
            ->whereIn('role', ['all', 'customer'])
            ->where('lang', $request->header('lang') ?: 'end')
            ->oldest()
            ->get(['question', 'answer']);
        return $this->sendSuccess($items);
    }

    /**
     * Slider
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "name": "Hello",
     *       "image": "https://dww7oudtom3p3.cloudfront.net/1672030053.png"
     *       }
     *   ]
     * }
     */
    public function slider(Request $request)
    {
        $items = Banner::select(['name', 'image'])->get();
        return $this->sendSuccess($items);
    }

    /**
     * Company Category
     * 
     * @authenticated
     * @defaultParam
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "137f8bca-9ba7-11ec-aca5-e8f4089635a6",
     *       "name": "Insurance",
     *       "icon": null
     *       }
     *   ]
     * }
     */
    public function companyCategory(Request $request)
    {
        $items = CompanyCategory::select(['id', 'name', 'icon'])->get();
        return $this->sendSuccess($items);
    }


    /**
     * Report Reason 
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam type string required in product or account
     * 
     * @response {
     *       "status": 200,
     *       "message": "success",
     *       "data": [{
     *           "reason": "Fraud and embezzlement"
     *       }]
     * }
     */
    public function reportReason(Request $request,$type){
        $items = ReportReason::findAllByType($type);
        return $this->sendSuccess($items);
    }

}
