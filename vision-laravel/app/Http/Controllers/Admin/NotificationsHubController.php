<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardOrder;
use App\Models\TopupRequest;
use App\Models\Withdrawal;
use Illuminate\Contracts\View\View;

class NotificationsHubController extends Controller
{
    public function index(): View
    {
        return view('admin.notifications.index', [
            'counts' => [
                'pending_topups' => TopupRequest::query()->where('status', 'pending')->count(),
                'pending_withdrawals' => Withdrawal::query()->where('status', 'pending')->count(),
                'open_disputes' => CardOrder::query()->where('status', 'failed')->count(),
                'reported_cards' => Card::query()->whereIn('status', ['reported', 'disabled'])->count(),
            ],
        ]);
    }
}
