<?php

namespace Theme\FashionTheme\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Spatie\Newsletter\Facades\Newsletter;

class NewsletterController extends Controller
{
    /**
     ** Newsletter Subscriber Store
     * @param \Illuminate\Http\Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email  = $request->email;

        $ApiKey = env('NEWSLETTER_API_KEY');
        $ListId = env('NEWSLETTER_LIST_ID');

        if (empty($ApiKey) || empty($ListId)) {
            return response()->json([
                'success' => false,
                'message' => translate('Mailchimp Api key Or List id Missing.'),
            ], 422);
        }

        try {
            if (Newsletter::isSubscribed($email)) {
                return response()->json([
                    'success' => true,
                    'message' => translate('This Email is already Subscribed'),
                ]);
            }


            Newsletter::subscribePending($email);

            return response()->json([
                'success' => true,
                'message' => translate('Please Check your Email to confirm your Subscription'),
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => app()->has('config') && config('app.debug')
                    ? $e->getMessage()
                    : translate('Unable to process your subscription right now. Please try again later.'),
            ], 500);
        }
    }
}
