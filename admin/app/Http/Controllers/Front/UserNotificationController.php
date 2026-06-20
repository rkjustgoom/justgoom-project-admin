<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\UserNotification;
use App\Services\Front\UserNotificationService;
use Illuminate\Http\Request;

class UserNotificationController extends Controller
{
    public function __construct(private UserNotificationService $notificationService)
    {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        return view('front.users.notifications', [
            'notifications' => $this->notificationService->listForUser($user),
            'stats' => $this->notificationService->statsForUser($user),
        ]);
    }

    public function show(Request $request, UserNotification $notification)
    {
        abort_unless($this->notificationService->belongsToUser($notification, $request->user()), 404);

        $this->notificationService->markAsRead($notification);

        return view('front.users.notification-view', [
            'notification' => $notification->fresh(),
        ]);
    }
}
