<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = config('app');

        return view('admin.settings.index', compact('settings'));
    }

    public function general()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
        ];

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'timezone' => 'required|string',
            'locale' => 'required|in:ar,en',
        ]);

        // TODO: Update .env file or settings table
        // This is a simplified implementation

        return redirect()->back()->with('success', 'General settings updated successfully');
    }

    public function payment()
    {
        $gateways = \App\Models\PaymentGateway::all();

        return view('admin.settings.payment', compact('gateways'));
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'gateway_id' => 'required|exists:payment_gateways,id',
            'enabled' => 'boolean',
            'config' => 'nullable|array',
        ]);

        // TODO: Update payment gateway configuration

        return redirect()->back()->with('success', 'Payment settings updated successfully');
    }

    public function email()
    {
        $settings = [
            'mail_mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];

        return view('admin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => 'required|string',
            'mail_port' => 'required|integer',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        // TODO: Update email configuration

        return redirect()->back()->with('success', 'Email settings updated successfully');
    }

    public function security()
    {
        return view('admin.settings.security');
    }

    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'session_timeout' => 'nullable|integer|min:5',
            'max_login_attempts' => 'nullable|integer|min:1',
            'password_min_length' => 'nullable|integer|min:6',
        ]);

        // TODO: Update security settings

        return redirect()->back()->with('success', 'Security settings updated successfully');
    }
}
