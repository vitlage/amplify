<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Subscriber;
use App\Model\EmailVerificationServer;
use App\Library\Log as MailLog;
use App\Model\MailList;

class SubscriberController extends Controller
{

    /**
     * Search items.
     */
    public function search($list, $request)
    {
        $subscribers = \App\Model\Subscriber::search($request)
            ->where('mail_list_id', '=', $list->id);

        return $subscribers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        return view('subscribers.index', [
            'list' => $list
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('read', $list)) {
            return;
        }

        $subscribers = $this->search($list, $request);
        // $total = distinctCount($subscribers);
        $total = $subscribers->count();
        $subscribers->with(['mailList', 'subscriberFields']);
        $subscribers = \optimized_paginate($subscribers, $request->per_page, null, null, null, $total);

        $fields = $list->getFields->whereIn('uid', explode(',', $request->columns));

        return view('subscribers._list', [
            'subscribers' => $subscribers,
            'total' => $total,
            'list' => $list,
            'fields' => $fields,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \App\Model\Subscriber();
        $subscriber->mail_list_id = $list->id;

        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->noMoreItem();
        }

        // Get old post values
        $values = [];
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.create', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = $request->user()->customer;
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $subscriber = new \App\Model\Subscriber();
        $subscriber->mail_list_id = $list->id;
        $subscriber->status = 'subscribed';

        // authorize
        if (\Gate::denies('create', $subscriber)) {
            return $this->noMoreItem();
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $this->validate($request, $subscriber->getRules());

            // Save subscriber
            $subscriber->email = $request->EMAIL;
            $subscriber->save();
            // Update field
            $subscriber->updateFields($request->all());

            // update MailList cache
            event(new \App\Events\MailListUpdated($subscriber->mailList));

            // Log
            $subscriber->log('created', $customer);

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.created'));

            return redirect()->action('SubscriberController@index', $list->uid);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $subscriber = \App\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // Get old post values
        $values = [];
        foreach ($list->getFields as $key => $field) {
            $values[$field->tag] = $subscriber->getValueByField($field);
        }
        if (null !== $request->old()) {
            foreach ($request->old() as $key => $value) {
                if (is_array($value)) {
                    $values[str_replace('[]', '', $key)] = implode(',', $value);
                } else {
                    $values[$key] = $value;
                }
            }
        }

        return view('subscribers.edit', [
            'list' => $list,
            'subscriber' => $subscriber,
            'values' => $values,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $customer = $request->user()->customer;
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $subscriber = \App\Model\Subscriber::findByUid($request->uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // validate and save posted data
        if ($request->isMethod('patch')) {
            $this->validate($request, $subscriber->getRules());

            // Upload
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $subscriber->uploadImage($request->file('image'));
                }
            }
            // Remove image
            if ($request->_remove_image == 'true') {
                $subscriber->removeImage();
            }

            // Update field
            $subscriber->updateFields($request->all());

            event(new \App\Events\MailListUpdated($subscriber->mailList));

            // Log
            $subscriber->log('updated', $customer);

            // Redirect to my lists page
            $request->session()->flash('alert-success', trans('messages.subscriber.updated'));

            return redirect()->action('SubscriberController@index', $list->uid);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
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
        $customer = $request->user()->customer;
        $uids = $request->uids;

        if (!is_array($request->uids)) {
            $uids = explode(',', $request->uids);
        }
        $subscribers = \App\Model\Subscriber::whereIn('uid', $uids);
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // get related mail lists to update the cached information
        $lists = $subscribers->get()->map(function ($e) {
            return \App\Model\MailList::find($e->mail_list_id);
        })->unique();

        // actually delete the subscriber
        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('delete', $subscriber)) {
                $subscriber->delete();

                // Log
                $subscriber->log('deleted', $customer);
            }
        }

        foreach ($lists as $list) {
            event(new \App\Events\MailListUpdated($list));
        }

        // Redirect to my lists page
        return response()->json([
            "status" => 'success',
            "message" => trans('messages.subscribers.deleted'),
        ]);
    }

    /**
     * Subscribe subscriber.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $customer = $request->user()->customer;

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        } else {
            $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('subscribe', $subscriber)) {
                $subscriber->status = 'subscribed';
                $subscriber->save();
                // update MailList cache
                event(new \App\Events\MailListUpdated($subscriber->mailList));

                // Log
                $subscriber->log('subscribed', $customer);
            }
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.subscribed');
    }

    /**
     * Unsubscribe subscriber.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function unsubscribe(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $customer = $request->user()->customer;

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        } else {
            $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('unsubscribe', $subscriber)) {
                $subscriber->status = 'unsubscribed';
                $subscriber->save();

                // Log
                $subscriber->log('unsubscribed', $customer);

                // update MailList cache
                event(new \App\Events\MailListUpdated($subscriber->mailList));
            }
        }

        // Redirect to my lists page
        echo trans('messages.subscribers.unsubscribed');
    }

    /**
     * Import from file.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $customer = $request->user()->customer;
        $list = \App\Model\MailList::findByUid($request->list_uid);

        $system_jobs = $list->importJobs();

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            if ($request->hasFile('file')) {
                // Start system job
                $job = new \App\Jobs\ImportSubscribersJob($list, $request->user()->customer, $request->file('file')->path());
                $this->dispatch($job);

                // Action Log
                $list->log('import_started', $request->user()->customer);
            } else {
                // @note: use try/catch instead
                echo "max_file_upload";
            }
        } else {
            return view('subscribers.import', [
                'list' => $list,
                'system_jobs' => $system_jobs
            ]);
        }
    }

    /**
     * Check import proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function importProccess(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->current_list_uid);
        $system_job = $list->getLastImportJob();

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        if (!is_object($system_job)) {
            return "none";
        }

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        // Messages
        $message = \App\Helpers\ImportSubscribersHelper::getMessage($system_job);

        return response()->json([
            "job" => $system_job,
            "data" => json_decode($system_job->data),
            "timer" => $system_job->runTime(),
            "message" => $message,
        ]);
    }

    /**
     * Download import log.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @todo move this to the MailList controller
     */
    public function downloadImportLog(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        // @todo: should be the exact MailList here
        $log = $list->getLastImportLog();
        // @todo what if log does not exist (removed)?
        return response()->download($log);
    }

    /**
     * Display a listing of subscriber import job.
     *
     * @return \Illuminate\Http\Response
     */
    public function importList(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('import', $list)) {
            return $this->notAuthorized();
        }

        $system_jobs = $list->importJobs();
        $system_jobs = $system_jobs->orderBy($request->sort_order, $request->sort_direction);
        $system_jobs = $system_jobs->paginate($request->per_page);

        return view('subscribers._import_list', [
            'system_jobs' => $system_jobs,
            'list' => $list
        ]);
    }

    /**
     * Export to csv.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        $system_jobs = $list->exportJobs();

        $customer = $request->user()->customer;

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {

            // Start system job
            $job = new \App\Jobs\ExportSubscribersJob($list, $request->user()->customer);
            $this->dispatch($job);

            // Action Log
            $list->log('export_started', $request->user()->customer);
        } else {
            return view('subscribers.export', [
                'list' => $list,
                'system_jobs' => $system_jobs
            ]);
        }
    }

    /**
     * Check export proccessing.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function exportProccess(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->current_list_uid);
        $system_job = $list->getLastExportJob();

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        if (!is_object($system_job)) {
            return "none";
        }

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        return response()->json([
            "job" => $system_job,
            "data" => json_decode($system_job->data),
            "timer" => $system_job->runTime(),
        ]);
    }

    /**
     * Download exported csv file after exporting.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadExportedCsv(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        $system_job = $list->getLastExportJob();

        return response()->download(storage_path('job/'.$system_job->id.'/data.csv'));
    }

    /**
     * Display a listing of subscriber import job.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportList(Request $request)
    {
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // authorize
        if (\Gate::denies('export', $list)) {
            return $this->notAuthorized();
        }

        $system_jobs = $list->exportJobs();
        $system_jobs = $system_jobs->orderBy($request->sort_order, $request->sort_direction);
        $system_jobs = $system_jobs->paginate($request->per_page);

        return view('subscribers._export_list', [
            'system_jobs' => $system_jobs,
            'list' => $list
        ]);
    }

    /**
     * Copy subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $request)
    {
        $from_list = \App\Model\MailList::findByUid($request->from_uid);
        $to_list = \App\Model\MailList::findByUid($request->to_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request)->select('subscribers.*');
        } else {
            $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('update', $to_list)) {
                $subscriber->copy($to_list, $request->type);
            }
        }

        // Trigger updating related campaigns cache
        event(new \App\Events\MailListUpdated($to_list));

        // Log
        $to_list->log('copied', $request->user()->customer, [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $to_list->uid,
            'from_name' => $from_list->name,
            'to_name' => $to_list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.copied');
    }

    /**
     * Move subscribers to lists.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function move(Request $request)
    {
        $from_list = \App\Model\MailList::findByUid($request->from_uid);
        $to_list = \App\Model\MailList::findByUid($request->to_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request)->select('subscribers.*');
        } else {
            $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        }

        foreach ($subscribers->get() as $subscriber) {
            // authorize
            if (\Gate::allows('update', $to_list)) {
                $subscriber->move($to_list, $request->type);
            }
        }

        // Trigger updating related campaigns cache
        event(new \App\Events\MailListUpdated($from_list));
        event(new \App\Events\MailListUpdated($to_list));

        // Log
        $to_list->log('moved', $request->user()->customer, [
            'count' => $subscribers->count(),
            'from_uid' => $from_list->uid,
            'to_uid' => $to_list->uid,
            'from_name' => $from_list->name,
            'to_name' => $to_list->name,
        ]);

        // Redirect to my lists page
        echo trans('messages.subscribers.moved');
    }

    /**
     * Copy Move subscribers form.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function copyMoveForm(Request $request)
    {
        $from_list = \App\Model\MailList::findByUid($request->from_uid);

        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($from_list, $request);
        } else {
            $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        }

        return view('subscribers.copy_move_form', [
            'subscribers' => $subscribers,
            'from_list' => $from_list
        ]);
    }

    /**
     * Start the verification process
     *
     */
    public function startVerification(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->uid);
        $server = EmailVerificationServer::findByUid($request->email_verification_server_id);
        try {
            $subscriber->verify($server);

            // success message
            $request->session()->flash('alert-success', trans('messages.verification.finish'));

            // update MailList cache
            event(new \App\Events\MailListUpdated($subscriber->mailList));

            return redirect()->action('SubscriberController@edit', ['list_uid' => $request->list_uid, 'uid' => $subscriber->uid]);
        } catch (\Exception $e) {
            MailLog::error(sprintf("Something went wrong while verifying %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage()));
            return view('somethingWentWrong', ['message' => sprintf("Something went wrong while verifying %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage())]);
        }
    }

    /**
     * Reset the verification data
     *
     */
    public function resetVerification(Request $request)
    {
        $subscriber = Subscriber::findByUid($request->uid);

        try {
            MailLog::info(sprintf("Cleaning up verification data for %s (%s)", $subscriber->email, $subscriber->id));
            $subscriber->emailVerification->delete();
            // success message
            $request->session()->flash('alert-success', trans('messages.verification.reset'));

            MailLog::info(sprintf("Finish cleaning up verification data for %s (%s)", $subscriber->email, $subscriber->id));
            return redirect()->action('SubscriberController@edit', ['list_uid' => $request->list_uid, 'uid' => $subscriber->uid]);
        } catch (\Exception $e) {
            MailLog::error(sprintf("Something went wrong while cleaning up verification data for %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage()));
            return view('somethingWentWrong', ['message' => sprintf("Something went wrong while cleaning up verification data for %s (%s). Error message: %s", $subscriber->email, $subscriber->id, $e->getMessage())]);
        }
    }

    /**
     * Render customer image.
     */
    public function avatar(Request $request)
    {
        // Get current customer
        if ($request->uid != '0') {
            $subscriber = \App\Model\Subscriber::findByUid($request->uid);
        } else {
            $subscriber = new \App\Model\Subscriber();
        }
        if (!empty($subscriber->imagePath())) {
            $img = \Image::make($subscriber->imagePath());
        } else {
            $img = \Image::make(public_path('assets/images/placeholder.jpg'));
        }

        return $img->response();
    }

    /**
     * Resend confirmation email.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resendConfirmationEmail(Request $request)
    {
        $subscribers = \App\Model\Subscriber::whereIn('uid', explode(',', $request->uids));
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // Launch re-sending job
        dispatch_now(new \App\Jobs\SendConfirmationEmailJob($subscribers->get(), $list));

        // Redirect to my lists page
        echo trans('messages.subscribers.resend_confirmation_email.being_sent');
    }

    /**
     * Update tags.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateTags(Request $request, $list_uid, $uid)
    {
        $list = MailList::findByUid($list_uid);
        $subscriber = Subscriber::findByUid($uid);

        // authorize
        if (\Gate::denies('update', $subscriber)) {
            return $this->notAuthorized();
        }

        // saving
        if ($request->isMethod('post')) {
            $subscriber->updateTags($request->tags);

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.subscriber.tagged', [
                    'subscriber' => $subscriber->getFullName(),
                ]),
            ], 201);
        }

        return view('subscribers.updateTags', [
            'list' => $list,
            'subscriber' => $subscriber,
        ]);
    }

    /**
     * Automation remove contact tag.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function removeTag(Request $request, $list_uid, $uid)
    {
        $list = MailList::findByUid($list_uid);
        $subscriber = Subscriber::findByUid($uid);

        // authorize
        if (\Gate::denies('delete', $subscriber)) {
            return $this->notAuthorized();
        }

        $subscriber->removeTag($request->tag);

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.automation.contact.tag.removed', [
                'tag' => $request->tag,
            ]),
        ], 201);
    }

    /**
     * Bulk remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDelete(Request $request)
    {
        // init
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // validate and save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), ['emails' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.bulkDelete', [
                    'list' => $list,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // get all emails
            $emails = array_unique(preg_split("/[\s,\r\n]+/", $request->emails));
            $subscribers = $list->subscribers()->whereIn('email', $emails)->get();

            //
            return view('subscribers.bulkDeleteConfirm', [
                'list' => $list,
                'emails' => $emails,
                'subscribers' => $subscribers,
            ]);
        }

        return view('subscribers.bulkDelete', [
            'list' => $list,
        ]);
    }

    /**
     * Bulk remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function bulkDeleteConfirm(Request $request)
    {
        // init
        $list = \App\Model\MailList::findByUid($request->list_uid);

        // validate and save posted data
        if ($request->isMethod('post')) {
            // make validator
            $validator = \Validator::make($request->all(), ['emails' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.bulkDelete', [
                    'list' => $list,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // get all emails
            $emails = preg_split("/[\s,\r\n]+/", $request->emails);

            //
            return view('subscribers.bulkDeleteConfirm', [
                'list' => $list,
                'emails' => $emails,
            ]);
        }

        return view('subscribers.bulkDelete', [
            'list' => $list,
        ]);
    }

    /**
     * Bulk assign values to subscribers.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function assignValues(Request $request, $list_uid)
    {
        // init
        $list = \App\Model\MailList::findByUid($request->list_uid);
        $subscribers = \App\Model\Subscriber::whereIn('uid', $request->uids);

        // Select all items
        if ($request->select_tool == 'all_items') {
            $subscribers = $this->search($list, $request);
        }

        // validate and save posted data
        if ($request->isMethod('post')) {
            $validator = \App\Model\Subscriber::assginValues($subscribers, $request);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('subscribers.assignValues', [
                    'list' => $list,
                    'subscribers' => $subscribers,
                    'errors' => $validator->errors(),
                ], 400);
            }

            return response()->json([
                'status' => 'success',
                'message' => trans('messages.subscribers.values_assigned'),
            ]);
        }

        return view('subscribers.assignValues', [
            'list' => $list,
            'subscribers' => $subscribers,
        ]);
    }
}
