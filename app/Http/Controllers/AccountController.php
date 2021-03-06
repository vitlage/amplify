<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as LaravelLog;

class AccountController extends Controller
{

    /**
     * Update user profile.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function profile(Request $request)
    {
        // Get current user
        $customer = $request->user()->customer;
        $customer->getColorScheme();

        // Authorize
        if (!$request->user()->customer->can('profile', $customer)) {
            return $this->notAuthorized();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            // Prenvent save from demo mod
            if ($this->isDemoMode()) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            $this->validate($request, $customer->rules());

            // Update user account for customer
            $user = $customer->user;
            $user->email = $request->email;
            // Update password
            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            // Save current user info
            $customer->fill($request->all());

            // Upload and save image
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    // Remove old images
                    $customer->removeImage();
                    $customer->image = $customer->uploadImage($request->file('image'));
                }
            }

            // Remove image
            if ($request->_remove_image == 'true') {
                $customer->removeImage();
                $customer->image = '';
            }

            if ($customer->save()) {
                $request->session()->flash('alert-success', trans('messages.profile.updated'));
            }
        }

        if (!empty($request->old())) {
            $customer->fill($request->old());
            // User info
            $customer->user->fill($request->old());
        }

        return view('account.profile', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update customer contact information.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     **/
    public function contact(Request $request)
    {
        // Get current user
        $customer = $request->user()->customer;
        $contact = $customer->getContact();

        // Create new company if null
        if (!is_object($contact)) {
            $contact = new \App\Model\Contact();
        }

        // save posted data
        if ($request->isMethod('post')) {
            // Prenvent save contact
            if (isset($contact->id)) {
                return $this->notAuthorized();
            }

            // Prenvent save from demo mod
            if ($this->isDemoMode()) {
                return view('somethingWentWrong', ['message' => trans('messages.operation_not_allowed_in_demo')]);
            }

            $this->validate($request, \App\Model\Contact::$rules);

            $contact->fill($request->all());

            // Save current user info
            if ($contact->save()) {
                if (is_object($contact)) {
                    $customer->contact_id = $contact->id;
                    $customer->save();
                }
                $request->session()->flash('alert-success', trans('messages.customer_contact.updated'));
            }
        }

        return view('account.contact', [
            'customer' => $customer,
            'contact' => $contact->fill($request->old()),
        ]);
    }

    /**
     * User logs.
     */
    public function logs(Request $request)
    {
        $logs = $request->user()->customer->logs;

        return view('account.logs', [
            'logs' => $logs,
        ]);
    }

    /**
     * Logs list.
     */
    public function logsListing(Request $request)
    {
        $logs = \App\Model\Log::search($request)->paginate($request->per_page);

        return view('account.logs_listing', [
            'logs' => $logs,
        ]);
    }

    /**
     * Quta logs.
     */
    public function quotaLog(Request $request)
    {
        return view('account.quota_log');
    }

    /**
     * Quta logs 2.
     */
    public function quotaLog2(Request $request)
    {
        return view('account.quota_log_2');
    }

    /**
     * Api token.
     */
    public function api(Request $request)
    {
        return view('account.api');
    }

    /**
     * Api token.
     */
    public function subscription(Request $request)
    {
        $customer = $request->user()->customer;

        // Get current subscription
        $subscription = $customer->getCurrentSubscriptionIgnoreStatus();

        // Get next subscription
        $nextSubscription = $customer->getNextSubscription();

        // If has no subscription
        if (!$subscription) {
            return redirect()->action('AccountController@subscriptionNew');
        }

        return view('account.subscription', [
            'subscription' => $subscription,
            'nextSubscription' => $nextSubscription,
        ]);
    }

    /**
     * Api token.
     */
    public function subscriptionNew(Request $request)
    {
        $customer = $request->user()->customer;

        $subscription = new \App\Model\Subscription();
        $subscription->customer_id = $customer->id;

        if ($request->plan_uid) {
            $subscription->fillAttributes($request->all());
        }

        // authorize
        if (!$request->user()->customer->can('create', $subscription)) {
            return $this->notAuthorized();
        }

        if (!empty($request->old())) {
            $subscription->fillAttributes($request->old());
        }

        return view('account.subscription_new', [
            'subscription' => $subscription
        ]);
    }

    /**
     * Renew api token.
     */
    public function renewToken(Request $request)
    {
        $user = $request->user();

        $user->api_token = str_random(60);
        $user->save();

        // Redirect to my lists page
        $request->session()->flash('alert-success', trans('messages.user_api.renewed'));

        return redirect()->action('AccountController@api');
    }

    /**
     * Billing.
     */
    public function billing(Request $request)
    {
        return view('account.billing', [
            'customer' => $request->user()->customer,
        ]);
    }

    /**
     * Edit billing address.
     */
    public function editBillingAddress(Request $request)
    {
        $customer = $request->user()->customer;
        $billingAddress = $customer->getDefaultBillingAddress();

        // has no address yet
        if (!$billingAddress) {
            $billingAddress = $customer->newBillingAddress();
        }

        // copy from contacy
        if ($request->same_as_contact == 'true') {
            $billingAddress->copyFromContact();
        }

        // Save posted data
        if ($request->isMethod('post')) {
            list($validator, $billingAddress) = $billingAddress->updateAll($request);

            // redirect if fails
            if ($validator->fails()) {
                return response()->view('account.editBillingAddress', [
                    'billingAddress' => $billingAddress,
                    'errors' => $validator->errors(),
                ], 400);
            }

            $request->session()->flash('alert-success', trans('messages.billing_address.updated'));

            return;
        }

        return view('account.editBillingAddress', [
            'billingAddress' => $billingAddress,
        ]);
    }

    /**
     * Remove payment method
     */
    public function removePaymentMethod(Request $request)
    {
        $customer = $request->user()->customer;

        $customer->removePaymentMethod();
    }

    /**
     * Edit payment method
     */
    public function editPaymentMethod(Request $request)
    {
        // Save posted data
        if ($request->isMethod('post')) {
            $gateway = \App\Model\Setting::getPaymentGateway($request->payment_method);

            return redirect()->away($gateway->getConnectUrl($request->return_url));
        }

        return view('account.editPaymentMethod', [
            'redirect' => $request->redirect,
        ]);
    }
}
