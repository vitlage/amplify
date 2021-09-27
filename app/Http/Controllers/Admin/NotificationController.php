<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\Plan;
use App\Model\Setting;
use App\Model\SendingServer;
use App\Model\Notification;

class NotificationController extends Controller
{

    /**
     * Notification index.
     */
    public function index(Request $request)
    {
        return view('admin.notifications.index');
    }

    /**
     * Notification listing.
     */
    public function listing(Request $request)
    {
        $notifications = Notification::search($request)->paginate($request->per_page);

        return view('admin.notifications.listing', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        if (isSiteDemo()) {
            echo trans('messages.operation_not_allowed_in_demo');
            return;
        }

        $notifications = Notification::whereIn('uid', explode(',', $request->uids));
        $count = $notifications->count();

        foreach ($notifications->get() as $notification) {
            $notifications->delete();
        }

        // Redirect to my lists page
        echo trans('messages.notifications.deleted', ['number' => $count]);
    }
}
