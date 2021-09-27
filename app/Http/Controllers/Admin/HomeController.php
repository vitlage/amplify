<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Notification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        parent::__construct();

        // Trigger admin monitoring events when admin is logged in
        event(new \App\Events\AdminLoggedIn());
    }

    /**
     * Show the application admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::top();
        return view('admin.dashboard', ['notifications' => $notifications]);
    }
}
