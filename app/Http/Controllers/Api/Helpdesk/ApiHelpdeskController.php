<?php

namespace App\Http\Controllers\Api\Helpdesk;

use App\Http\Controllers\ApiController;
use App\Http\Resources\Api\Helpdesk\AgentCompanyResource;
use App\Http\Resources\Api\Helpdesk\OfficeHourItemResource;
use App\Services\Company\CompanyBillingService;
use App\Services\Company\CompanyHelpdeskService;
use App\Services\Company\OfficeHourService;
use Illuminate\Http\Request;

/**
 * @group Contact Helpdesk
 */
class ApiHelpdeskController extends ApiController
{
    public function __construct(
        private CompanyHelpdeskService $companyHelpdeskService,
        private CompanyBillingService $companyBillingService,
        private OfficeHourService $officeHourService,
    ) {
    }
    /**
     * List Company Helpdesk
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required company id
     * 
     * @response {
     *       "status": 200,
     *       "message": "success",
     *       "data": [{
     *               "id": "99ca741a-d45c-43d4-972f-711c66b3f22c",
     *               "parent_id": null,
     *               "name": "Testing",
     *               "sub": []
     *           },
     *           {
     *               "id": "99c0b88b-ffec-492a-b680-8a8e3ada9baa",
     *               "parent_id": null,
     *               "name": "Information Technologi",
     *               "sub": [{
     *                   "id": "99c0b99d-9b0a-4477-9f63-dc617c4d95ac",
     *                   "name": "Marketing",
     *                   "parent_id": "99c0b88b-ffec-492a-b680-8a8e3ada9baa"
     *               }]
     *           }
     *       ]
     *   }
     */
    public function index(Request $request, $companyId)
    {
        $items = $this->companyHelpdeskService->findAllHelpdesk($companyId);
        return $this->sendSuccess($items);
    }

    /**
     * List Agent Helpdesk
     * 
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required company id
     * 
     * @queryParam helpdesk_category_id[0] string required
     * @queryParam helpdesk_category_id[1] string optional
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": [
     *       {
     *       "id": "99c87ee3-abd7-4b52-9b4d-b42199f69ed5",
     *       "name": "Dr. Haley Reichert",
     *       "email": "langworth.santos@example.net",
     *       "profile": "http://localhost:8000/uploads/profile/KnBTjqx79w22GLBV0JjKxDrdxZtTRl7reZjBvLLS.jpg"
     *       }
     *   ]
     * }
     */
    public function agent(Request $request, $companyId)
    {
        $this->validates([
            'helpdesk_category_id' => 'required|array'
        ]);
        $items = [];
        $billing = $this->companyBillingService->findActiveBilling($companyId);
        $officeHour = $this->officeHourService->findCompanyOfficeHour($companyId);

        if ($this->companyHelpdeskService->isHelpdeskAvailable($officeHour, $billing, $companyId)) {
            $items = $this->companyHelpdeskService->findAllAgentByHelpdesk($companyId, $request->get('helpdesk_category_id', []));;
        }

        return $this->sendSuccess(AgentCompanyResource::collection($items));
    }


    /**
     * Office Hour
     * 
     * @authenticated
     * @defaultParam
     * 
     * @pathParam id string required company id
     * 
     * @response {
     *   "status": 200,
     *   "message": "success",
     *   "data": {
     *       "sunday": "Closed",
     *       "monday": "10:00 - 20:00",
     *       "tuesday": "08:00 - 17:00",
     *       "wednesday": "Closed",
     *       "thursday": "10:00 - 19:00",
     *       "friday": "10:00 - 19:00",
     *       "saturday": "Closed"
     *   }
     * }
     */
    public function officeHour(Request $request, $companyId)
    {
        $officeHour = $this->officeHourService->findCompanyOfficeHour($companyId);
        return $this->sendSuccess(new OfficeHourItemResource($officeHour));
    }
}
