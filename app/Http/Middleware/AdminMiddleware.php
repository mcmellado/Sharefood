<?php

namespace App\Http\Middleware;
namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.panel_admin');
    }
}

