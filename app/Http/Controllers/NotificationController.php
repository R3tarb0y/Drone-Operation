<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use App\Models\Receive;
use App\Models\Transfer;
use App\Models\Estimation;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $pendingRequests = Requests::where('status', 'pending')->get();
        $pendingReceives = Receive::where('status', 'pending')->get();
        $pendingTransfers = Transfer::where('status', 'pending')->get();
        $pendingEstimations = Estimation::where('status', 'pending')->get();

        $notifications = [
            'requests' => $pendingRequests,
            'receives' => $pendingReceives,
            'transfers' => $pendingTransfers,
            'estimations' => $pendingEstimations,
        ];

        return $notifications; // Atau bisa dikirim ke view
    }
}
