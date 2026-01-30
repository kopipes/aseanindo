<?php

namespace App\Http\Controllers\LandingPage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Route;

class LandingPageController extends Controller
{
    protected function getViewPath($viewName)
    {
        $locale = session('locale', 'id');
        return $locale === 'en' ? "en/landingpage/{$viewName}" : "landingpage/{$viewName}";
    }

    public function index(Request $request)
    {
        return view($this->getViewPath('index'));
    }

    public function about(Request $request)
    {
        return view($this->getViewPath('about'));
    }

    public function product(Request $request)
    {
        return view($this->getViewPath('product'));
    }
    public function selfServices(Request $request)
    {
        return view($this->getViewPath('product.self-services'));
    }
    public function contactCenter(Request $request)
    {
        return view($this->getViewPath('product.contact'));
    }
    public function TicketingManagement(Request $request)
    {
        return view($this->getViewPath('product.ticketing-management'));
    }
    public function WorkforcePerformance(Request $request)
    {
        return view($this->getViewPath('product.workforce-performance'));
    }

    public function FeaturesBenefits(Request $request)
    {
        return view($this->getViewPath('product.features-benefit'));
    }

    public function OmniChannel(Request $request)
    {
        return view($this->getViewPath('product.omnichannel'));
    }

    public function AutomationCustomerService(Request $request)
    {
        return view($this->getViewPath('product.automation-customer-service'));
    }

    public function SdmOutsourcing(Request $request)
    {
        return view($this->getViewPath('product.sdm-outsourcing'));
    }
    public function FAQ(Request $request)
    {
        return view($this->getViewPath('faq'));
    }

    public function Blog(Request $request)
    {
        return view($this->getViewPath('blog'));
    }

    public function SyaratKetentuan(Request $request)
    {
        $locale = session('locale', 'id');
        $viewName = $locale === 'en' ? 'terms-condition' : 'syarat-ketentuan';
        return view($this->getViewPath($viewName));
    }


    public function Contact(Request $request)
    {
        return view($this->getViewPath('contact'));
    }

    public function KebijakanPrivasi(Request $request)
    {
        return view($this->getViewPath('kebijakan-privasi'));
    }
    public function sendMessage(Request $request)
    {
        // Validate form inputs and reCAPTCHA response
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'country_code' => 'nullable|string|max:10',
            'number' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string',
            'g-recaptcha-response' => 'required'  // Validate reCAPTCHA response
        ]);

        // Verify reCAPTCHA response with Google
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => '6LeCaGgqAAAAACj-I1CXDgn5IyjKJkwen5Bok6DB',
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $responseData = $response->json();

        if (!$responseData['success']) {
            return redirect()->back()->withErrors(['captcha' => 'reCAPTCHA validation failed. Please try again.']);
        }

        // Send the email using the collected form data
        Mail::send('contact', [
            'name' => $request->name,
            'email' => $request->email,
            'country_code' => $request->country_code,
            'number' => $request->number,
            'company' => $request->company,
            'userMessage' => $request->message,
        ], function ($message) use ($request) {
            $message->to('sales@kontakami.com')
                ->subject('New Message - Contact Us');
        });


        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function setLang($locale, $slug = null)
{
    Session::put('locale', $locale);
    $previousUrl = url()->previous();
    $currentPath = str_replace(url('/'), '', $previousUrl);
    if ($slug) {
        $blogController = app(BlogController::class);
        $currentArticleData = $blogController->getArticleData($slug, session('locale', 'id'));
        if ($currentArticleData) {
            $targetArticleData = $blogController->getArticleDataById($currentArticleData['id'], $locale);
            if ($targetArticleData) {
                if ($locale === 'en') {
                    return redirect()->route('blog.show.en', ['slug' => basename($targetArticleData['link'])]);
                } else {
                    return redirect()->route('blog.show.id', ['slug' => basename($targetArticleData['link'])]);
                }
            }
        }
    }
    if ($locale === 'en' && strpos($currentPath, '/en') === false) {
        return redirect('/en' . $currentPath);
    } elseif ($locale === 'id' && strpos($currentPath, '/en') !== false) {
        return redirect(str_replace('/en', '', $currentPath));
    }
    return redirect()->back();
}
}
