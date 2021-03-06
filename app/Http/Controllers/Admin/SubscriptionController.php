<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log as LaravelLog;
use App\Cashier\Cashier;
use App\Model\Subscription;
use App\Model\SubscriptionLog;
use App\Model\Plan;
use App\Model\Customer;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // authorize
        if (!$request->user()->admin->can('read', new Subscription())) {
            return $this->notAuthorized();
        }

        // If admin can view all subscriptions of their customer
        if (!$request->user()->admin->can('readAll', new Subscription())) {
            $request->merge(array("customer_admin_id" => $request->user()->admin->id));
        }
        $subscriptions = Subscription::all();

        $plan = null;
        if ($request->plan_uid) {
            $plan = Plan::findByUid($request->plan_uid);
        }

        return view('admin.subscriptions.index', [
            'subscriptions' => $subscriptions,
            'plan' => $plan,
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(Request $request)
    {
        // authorize
        if (!$request->user()->admin->can('read', new Subscription())) {
            return $this->notAuthorized();
        }

        // If admin can view all subscriptions of their customer
        if (!$request->user()->admin->can('readAll', new Subscription())) {
            $request->merge(array("customer_admin_id" => $request->user()->admin->id));
        }

        $subscriptions = Subscription::select('subscriptions.*');

        if ($request->filters) {
            if (isset($request->filters["customer_uid"])) {
                $subscriptions = $subscriptions->where('user_id', $request->filters["customer_uid"]);
            }

            if (isset($request->filters["plan_uid"])) {
                $subscriptions = $subscriptions->where('plan_id', $request->filters["plan_uid"]);
            }
        }

        if (!empty($request->sort_order)) {
            $subscriptions = $subscriptions->orderBy($request->sort_order, $request->sort_direction);
        }

        $subscriptions = $subscriptions->paginate($request->per_page);

        return view('admin.subscriptions._list', [
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Approve pending subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function approve(Request $request, $id)
    {
        $subscription = Subscription::findByUid($request->id);

        // authorize
        if (!$request->user()->admin->can('approve', $subscription)) {
            return $this->notAuthorized();
        }

        try {
            // approve last claimed invoice ~ pay last invoice
            $subscription->claimedInvoice()->approve();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => trans('messages.subscription.set_active.success'),
        ]);
    }

    /**
     * Cancel subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request)
    {
        $subscription = Subscription::findByUid($request->id);

        if ($request->user()->admin->can('cancel', $subscription)) {
            $subscription->cancel();
        }

        echo trans('messages.subscription.cancelled');
    }

    /**
     * Cancel subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function resume(Request $request)
    {
        $subscription = Subscription::findByUid($request->id);

        if ($request->user()->admin->can('resume', $subscription)) {
            $subscription->resume();
        }

        echo trans('messages.subscription.resumed');
    }

    /**
     * Cancel now subscription at the end of current period.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelNow(Request $request)
    {
        $subscription = Subscription::findByUid($request->id);

        if ($request->user()->admin->can('cancelNow', $subscription)) {
            try {
                $subscription->cancelNow();
            } catch (\Exception $ex) {
                echo json_encode([
                    'status' => 'error',
                    'message' => $ex->getMessage(),
                ]);
                return;
            }
        }

        echo trans('messages.subscription.cancelled_now');
    }

    /**
     * Subscription invoices.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function invoices(Request $request, $id)
    {
        $subscription = Subscription::findByUid($request->id);

        return view('admin.subscriptions.invoices', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Reject subscription pending.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function rejectPending(Request $request, $id)
    {
        $subscription = Subscription::findByUid($request->id);

        // authorize
        if (!$request->user()->admin->can('rejectPending', $subscription)) {
            return $this->notAuthorized();
        }

        if ($request->isMethod('post')) {
            $validator = \Validator::make($request->all(), ['reason' => 'required']);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('admin.subscriptions.rejectPending', [
                    'subscription' => $subscription,
                    'gateway' => $gateway,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // try reject
            try {
                $subscription->claimedInvoice()->reject($request->reason);
            } catch (\Exception $ex) {
                $validator->errors()->add('reason', $ex->getMessage());

                return response()->view('admin.subscriptions.rejectPending', [
                    'subscription' => $subscription,
                    'gateway' => $gateway,
                    'errors' => $validator->errors(),
                ], 400);
            }

            // success
            return response()->json([
                'status' => 'success',
                'message' => trans('messages.subscription.reject_pending.success'),
            ]);
        }

        return view('admin.subscriptions.rejectPending', [
            'subscription' => $subscription,
        ]);
    }

    /**
     * Delete subscription.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Get current subscription
        $subscription = Subscription::findByUid($request->id);

        if ($request->user()->admin->can('delete', $subscription)) {
            $subscription->delete();
        }

        echo trans('messages.subscription.deleted');
    }
}
