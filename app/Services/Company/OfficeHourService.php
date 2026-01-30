<?php

namespace App\Services\Company;

use App\Models\Company\CompanyProfile;
use App\Models\Company\OfficeHour;

class OfficeHourService
{
     public function __construct(
          public $model = OfficeHour::class
     ) {
     }

     public function findCompanyOfficeHour($companyId)
     {
          $officeHour = null;
          $companyProfile = CompanyProfile::where('company_id', $companyId)->select('office_hour_type')->first();
          if ($companyProfile->office_hour_type == 'custom') {
               $officeHour = $this->model::select([
                    'id',
                    'company_id',
                    'sunday',
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday'
               ])
                    ->where('company_id', $companyId)
                    ->first();
               if ($officeHour) {
                    $officeHour->sunday = $officeHour->sunday ? json_decode($officeHour->sunday) : null;
                    $officeHour->monday = $officeHour->monday ? json_decode($officeHour->monday) : null;
                    $officeHour->tuesday = $officeHour->tuesday ? json_decode($officeHour->tuesday) : null;
                    $officeHour->wednesday = $officeHour->wednesday ? json_decode($officeHour->wednesday) : null;
                    $officeHour->thursday = $officeHour->thursday ? json_decode($officeHour->thursday) : null;
                    $officeHour->friday = $officeHour->friday ? json_decode($officeHour->friday) : null;
                    $officeHour->saturday = $officeHour->saturday ? json_decode($officeHour->saturday) : null;
               }
          }
          if (!$officeHour) {
               $objectHour = json_encode([
                    'start' => '00:00',
                    'end' => '23:59'
               ]);
               $officeHour = new OfficeHour();
               $officeHour->sunday = $objectHour;
               $officeHour->monday = $objectHour;
               $officeHour->tuesday = $objectHour;
               $officeHour->wednesday = $objectHour;
               $officeHour->thursday = $objectHour;
               $officeHour->friday = $objectHour;
               $officeHour->saturday = $objectHour;
          }
          return $officeHour;
     }
}