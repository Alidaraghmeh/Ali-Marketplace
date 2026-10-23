<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    

    public function markAsRead(string $notification){

   
/** @var \App\Models\User $user */
$user = Auth::user();

    $foundNotification = $user->notifications()
    ->findOrFail($notification);



     $foundNotification->markAsRead();

    return back()->with('success', 'Notification marked as read.');

    }
}
