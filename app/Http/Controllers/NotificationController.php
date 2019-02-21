<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function createNewDocuNotification()
    {
        return auth()->user()->unreadNotifications()->limit(5)->get()->toArray();
    }

    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route("home");
    }
}
