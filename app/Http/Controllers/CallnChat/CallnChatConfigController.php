<?php

namespace App\Http\Controllers\CallnChat;

use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Company\CompanyProfile;
use Illuminate\Http\Request;

class CallnChatConfigController extends Controller
{
    public function __invoke(Request $request, $companyId)
    {
        $company = Company::where('status','approved')->findOrFail($companyId);
        $companyProfile = CompanyProfile::where('company_id',$company->id)->select(['embed_web'])->firstOrFail();
        if(!$companyProfile->embed_web){
            return "<script>console.log('CallnChat Is not enable')</script>";
        }
        return response(view('callnchat.config-embed', [
            'company_username' => $company->username,
        ]))->header('Content-Type', 'application/javascript');
    }
}