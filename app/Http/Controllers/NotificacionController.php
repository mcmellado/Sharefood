<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificacionController extends Controller
{
    public function mostrarNotificaciones()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
    }
}
