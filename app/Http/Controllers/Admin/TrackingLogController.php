<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrackingLogController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->admin->getPermission('report_tracking_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \App\Model\TrackingLog::getAll();

        return view('admin.tracking_logs.index', [
            'items' => $items,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        if ($request->user()->admin->getPermission('report_tracking_log') == 'no') {
            return $this->notAuthorized();
        }

        $items = \App\Model\TrackingLog::search($request)->paginate($request->per_page);

        return view('admin.tracking_logs._list', [
            'items' => $items,
        ]);
    }
}
