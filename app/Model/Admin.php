<?php

/**
 * Admin class.
 *
 * Model class for admin
 *
 * LICENSE: This product includes software developed at
 * the Acelle Co., Ltd. (http://acellemail.com/).
 *
 * @category   MVC Model
 *
 * @author     N. Pham <n.pham@acellemail.com>
 * @author     L. Pham <l.pham@acellemail.com>
 * @copyright  Acelle Co., Ltd
 * @license    Acelle Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://acellemail.com
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Subscription;

class Admin extends Model
{
    const STATUS_ACTIVE = 'active';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'first_name', 'last_name', 'timezone', 'language_id', 'color_scheme', 'text_direction'
    ];

    /**
     * The rules for validation.
     *
     * @var array
     */
    public function rules()
    {
        $rules = array(
            'email' => 'required|email|unique:users,email,'.$this->user_id.',id',
            'first_name' => 'required',
            'last_name' => 'required',
            'timezone' => 'required',
            'language_id' => 'required',
        );

        if (isset($this->id)) {
            $rules['password'] = 'confirmed|min:5';
        } else {
            $rules['password'] = 'required|confirmed|min:5';
        }

        return $rules;
    }

    /**
     * Admin email.
     *
     * @return string
     */
    public function email()
    {
        return is_object($this->user) ? $this->user->email : '';
    }

    /**
     * Find item by uid.
     *
     * @return object
     */
    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Associations.
     *
     * @var object | collect
     */
    public function contact()
    {
        return $this->belongsTo('App\Model\Contact');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\User');
    }

    public function adminGroup()
    {
        return $this->belongsTo('App\Model\AdminGroup');
    }

    public function customers()
    {
        return $this->hasMany('App\Model\Customer');
    }

    public function templates()
    {
        return $this->hasMany('App\Model\Template');
    }

    public function language()
    {
        return $this->belongsTo('App\Model\Language');
    }

    public function creator()
    {
        return $this->belongsTo('App\Model\User', 'creator_id');
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            $item->uid = uniqid();
        });
    }

    /**
     * Display admin name: first_name last_name.
     *
     * @var string
     */
    public function displayName()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Upload and resize avatar.
     *
     * @var void
     */
    public function uploadImage($file)
    {
        $path = 'app/admins/';
        $upload_path = storage_path($path);

        if (!file_exists($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $filename = 'avatar-'.$this->id.'.'.$file->getClientOriginalExtension();

        // save to server
        $file->move($upload_path, $filename);

        // create thumbnails
        $img = \Image::make($upload_path.$filename);
        $img->fit(120, 120)->save($upload_path.$filename.'.thumb.jpg');

        return $path.$filename;
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function imagePath()
    {
        if (!empty($this->image) && !empty($this->id)) {
            return storage_path($this->image).'.thumb.jpg';
        } else {
            return '';
        }
    }

    /**
     * Get image thumb path.
     *
     * @var string
     */
    public function removeImage()
    {
        if (!empty($this->image) && !empty($this->id)) {
            $path = storage_path($this->image);
            if (is_file($path)) {
                unlink($path);
            }
            if (is_file($path.'.thumb.jpg')) {
                unlink($path.'.thumb.jpg');
            }
        }
    }

    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
    }

    /**
     * Items per page.
     *
     * @var array
     */
    public static $itemsPerPage = 25;

    /**
     * Filter items.
     *
     * @return collect
     */
    public static function filter($request)
    {
        $query = self::select('admins.*')
                        ->join('users', 'users.id', '=', 'admins.user_id')
                        ->leftJoin('admin_groups', 'admin_groups.id', '=', 'admins.admin_group_id');

        // Keyword
        if (!empty(trim($request->keyword))) {
            foreach (explode(' ', trim($request->keyword)) as $keyword) {
                $query = $query->where(function ($q) use ($keyword) {
                    $q->orwhere('admins.first_name', 'like', '%'.$keyword.'%')
                        ->orWhere('admin_groups.name', 'like', '%'.$keyword.'%')
                        ->orWhere('admins.last_name', 'like', '%'.$keyword.'%');
                });
            }
        }

        // filters
        $filters = $request->filters;
        if (!empty($filters)) {
            if (!empty($filters['admin_group_id'])) {
                $query = $query->where('admins.admin_group_id', '=', $filters['admin_group_id']);
            }
        }

        if (!empty($request->creator_id)) {
            $query = $query->where('admins.creator_id', '=', $request->creator_id);
        }

        return $query;
    }

    /**
     * Search items.
     *
     * @return collect
     */
    public static function search($request)
    {
        $query = self::filter($request);

        if (!empty($request->sort_order)) {
            $query = $query->orderBy($request->sort_order, $request->sort_direction);
        }

        return $query;
    }

    /**
     * Get admin setting.
     *
     * @return string
     */
    public function getOption($name)
    {
        return $this->adminGroup->getOption($name);
    }

    /**
     * Get admin permission.
     *
     * @return string
     */
    public function getPermission($name)
    {
        return $this->adminGroup->getPermission($name);
    }

    /**
     * Get user's color scheme.
     *
     * @return string
     */
    public function getColorScheme()
    {
        if (!empty($this->color_scheme)) {
            return $this->color_scheme;
        } else {
            return \App\Model\Setting::get('backend_scheme');
        }
    }

    /**
     * Color array.
     *
     * @return array
     */
    public static function colors($default)
    {
        return [
            ['value' => '', 'text' => trans('messages.system_default')],
            ['value' => 'blue', 'text' => trans('messages.blue')],
            ['value' => 'green', 'text' => trans('messages.green')],
            ['value' => 'brown', 'text' => trans('messages.brown')],
            ['value' => 'pink', 'text' => trans('messages.pink')],
            ['value' => 'grey', 'text' => trans('messages.grey')],
            ['value' => 'white', 'text' => trans('messages.white')],
        ];
    }

    /**
     * Disable admin.
     *
     * @return bool
     */
    public function disable()
    {
        $this->status = 'inactive';

        return $this->save();
    }

    /**
     * Enable admin.
     *
     * @return bool
     */
    public function enable()
    {
        $this->status = 'active';

        return $this->save();
    }

    /**
     * Get recent resellers.
     *
     * @return collect
     */
    public function getAllCustomers()
    {
        $query = \App\Model\Customer::getAll();

        if (!$this->user->can('readAll', new \App\Model\Customer())) {
            $query = $query->where('customers.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get recent resellers.
     *
     * @return collect
     */
    public function recentCustomers()
    {
        return $this->getAllCustomers()->orderBy('created_at', 'DESC')->limit(5)->get();
    }

    /**
     * Get all admin's subcriptions.
     *
     * @return collect
     */
    public function getAllSubscriptions()
    {
        if ($this->user->can('readAll', new \App\Model\Customer())) {
            $query = Subscription::select('subscriptions.*')->leftJoin('customers', 'customers.uid', '=', 'subscriptions.user_id');
        } else {
            $query = Subscription::select('subscriptions.*')
                ->join('customers', 'customers.uid', '=', 'subscriptions.user_id')
                ->where('customers.admin_id', '=', $this->id);
            /* ERROR
            $query = $query->where(function ($q) {
                $q->orwhere('customers.admin_id', '=', $this->id)
                    ->orWhere('subscriptions.admin_id', '=', $this->id);
            });
            */
        }

        return $query;
    }

    /**
     * Get subscription notification count.
     *
     * @return collect
     */
    public function subscriptionNotificationCount()
    {
        $query = $this->getAllSubscriptions()
            ->where('subscriptions.ends_at', '>=', \Carbon\Carbon::now()->endOfDay())
            ->count();

        return $query == 0 ? '' : $query;
    }

    /**
     * Get recent subscriptions.
     *
     * @return collect
     */
    public function recentSubscriptions($number = 5)
    {
        $query = $this->getAllSubscriptions()
            ->whereNull('ends_at')->orWhere('ends_at', '>=', \Carbon\Carbon::now())
            ->orderBy('subscriptions.created_at', 'desc')->limit($number);

        return $query->get();
    }

    /**
     * Get admin language code.
     *
     * @return string
     */
    public function getLanguageCode()
    {
        return is_object($this->language) ? $this->language->code : null;
    }

    /**
     * Get customer language code.
     *
     * @return string
     */
    public function getLanguageCodeFull()
    {
        $region_code = $this->language->region_code ? strtoupper($this->language->region_code) : strtoupper($this->language->code);
        return is_object($this->language) ? ($this->language->code.'-'.$region_code) : null;
    }

    /**
     * Get admin logs of their customers.
     *
     * @return string
     */
    public function getLogs()
    {
        $query = \App\Model\Log::select('logs.*')->join('customers', 'customers.id', '=', 'logs.customer_id')
            ->leftJoin('admins', 'admins.id', '=', 'customers.admin_id');

        if (!$this->user->can('readAll', new \App\Model\Customer())) {
            $query = $query->where('admins.id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Create customer account.
     */
    public function createCustomerAccount($admin)
    {
        if (!$this->hasCustomerAccount()) {
            // Create customer
            $customer = new \App\Model\Customer();
            $customer->user_id = $this->user_id;
            $customer->admin_id = $this->id;
            $customer->language_id = $this->language_id;
            $customer->first_name = $this->first_name;
            $customer->last_name = $this->last_name;
            $customer->image = $this->image;
            $customer->timezone = $this->timezone;
            $customer->status = $this->status;
            $customer->save();
        }
    }

    /**
     * Check if admin has customer account.
     *
     * @return bool
     */
    public function hasCustomerAccount()
    {
        return is_object($this->user) && is_object($this->user->customer);
    }

    /**
     * Check if customer is disabled.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status == Customer::STATUS_ACTIVE;
    }

    /**
     * Custom can for admin.
     *
     * @return bool
     */
    public function can($action, $item=null)
    {
        if ($item) {
            return $this->user->can($action, [$item, 'admin']);
        } else {
            return $this->user->can($action, ['admin']);
        }
    }

    /**
     * Destroy admin.
     *
     * @return bool
     */
    public function deleteRecursive()
    {
        // unset all customers
        $this->customers()->update(['admin_id' => null]);

        // Delete admin and user
        $user = $this->user;
        $this->delete();
        $user->delete();
    }

    /**
     * Get all subscription count by plan.
     *
     * @return int
     */
    public function getAllSubscriptionsByPlan($plan)
    {
        return $this->getAllSubscriptions()->where('subscriptions.plan_id', '=', $plan->uid);
    }

    /**
     * Get all plans.
     *
     * @return int
     */
    public function getAllPlans()
    {
        return \App\Model\Plan::getAllActive($this);
    }

    /**
     * Get all payment methods.
     *
     * @return int
     */
    public function getAllPaymentMethods()
    {
        $query = \App\Model\PaymentMethod::getAll()
            ->where('payment_methods.status', '=', \App\Model\PaymentMethod::STATUS_ACTIVE);

        if (!$this->can('readAll', new \App\Model\PaymentMethod())) {
            $query = $query->where('payment_methods.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get all admin.
     *
     * @return int
     */
    public function getAllAdmins()
    {
        $query = \App\Model\Admin::getAll()
            ->where('admins.status', '=', \App\Model\Admin::STATUS_ACTIVE);

        if (!$this->can('readAll', new \App\Model\Admin())) {
            $query = $query->where('admins.creator_id', '=', $this->user_id);
        }

        return $query;
    }

    /**
     * Get all admin.
     *
     * @return int
     */
    public function getAllAdminGroups()
    {
        $query = \App\Model\AdminGroup::getAll();

        if (!$this->can('readAll', new \App\Model\AdminGroup())) {
            $query = $query->where('admin_groups.creator_id', '=', $this->user_id);
        }

        return $query;
    }

    /**
     * Get all sending servers.
     *
     * @return int
     */
    public function getAllSendingServers()
    {
        $query = \App\Model\SendingServer::getAll();

        if (!$this->can('readAll', new \App\Model\SendingServer())) {
            $query = $query->where('sending_servers.admin_id', '=', $this->id);
        }

        // remove customer sending servers
        $query = $query->whereNull('customer_id');

        return $query;
    }

    /**
     * Get all sending servers.
     *
     * @return int
     */
    public function getAllSendingDomains()
    {
        $query = \App\Model\SendingDomain::getAll();

        if (!$this->can('readAll', new \App\Model\SendingDomain())) {
            $query = $query->where('sending_domains.admin_id', '=', $this->id);
        }

        // remove customer sending servers
        $query = $query->whereNull('customer_id');

        return $query;
    }

    /**
     * Get all campaigns.
     *
     * @return collect
     */
    public function getAllCampaigns()
    {
        $query = \App\Model\Campaign::getAll();

        if (!$this->can('readAll', new \App\Model\Customer())) {
            $query = $query->leftJoin('customers', 'customers.id', '=', 'campaigns.customer_id')
                ->where('customers.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get all lists.
     *
     * @return collect
     */
    public function getAllLists()
    {
        $query = \App\Model\MailList::getAll();

        if (!$this->can('readAll', new \App\Model\Customer())) {
            $query = $query->leftJoin('customers', 'customers.id', '=', 'mail_lists.customer_id')
                ->where('customers.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get all automations.
     *
     * @return collect
     */
    public function getAllAutomations()
    {
        $query = \App\Model\Automation2::query();

        if (!$this->can('readAll', new \App\Model\Customer())) {
            $query = $query->leftJoin('customers', 'customers.id', '=', 'automation2s.customer_id')
                ->where('customers.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get all automations.
     *
     * @return collect
     */
    public function getAllSubscribers()
    {
        $query = \App\Model\Subscriber::getAll();

        if (!$this->can('readAll', new \App\Model\Customer())) {
            $query = $query->leftJoin('mail_lists', 'mail_lists.id', '=', 'subscribers.mail_list_id')
                ->leftJoin('customers', 'customers.id', '=', 'mail_lists.customer_id')
                ->where('customers.admin_id', '=', $this->id);
        }

        return $query;
    }

    /**
     * Get import jobs.
     *
     * @return number
     */
    public function getImportBlacklistJobs()
    {
        return \App\Model\SystemJob::where('name', '=', "App\Jobs\ImportBlacklistJob")
            ->where('data', 'like', '%"admin_id":'.$this->id.'%');
    }

    /**
     * Get running import jobs.
     *
     * @return number
     */
    public function getActiveImportBlacklistJobs()
    {
        return $this->getImportBlacklistJobs()
            ->where('status', '!=', \App\Model\SystemJob::STATUS_DONE)
            ->where('status', '!=', \App\Model\SystemJob::STATUS_FAILED)
            ->where('status', '!=', \App\Model\SystemJob::STATUS_CANCELLED);
    }

    /**
     * Get last import black list job.
     *
     * @return number
     */
    public function getLastActiveImportBlacklistJob()
    {
        return $this->getActiveImportBlacklistJobs()
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * Add email to admin blacklist.
     */
    public function addEmaillToBlacklist($email)
    {
        $email = trim(strtolower($email));

        if (\App\Library\Tool::isValidEmail($email)) {
            $exist = \App\Model\Blacklist::whereNull('customer_id')->where('email', '=', $email)->count();
            if (!$exist) {
                $blacklist = new \App\Model\Blacklist();
                $blacklist->admin_id = $this->id;
                $blacklist->email = $email;
                $blacklist->save();
            }
        }
    }

    /**
     * Get sub-account sending servers.
     *
     * @return int
     */
    public function getSubaccountSendingServers()
    {
        $query = $this->getAllSendingServers();

        $query = $query->whereIn('type', \App\Model\SendingServer::getSubAccountTypes());

        return $query;
    }

    /**
     * Get sub-account sending servers options.
     *
     * @return int
     */
    public function getSubaccountSendingServersSelectOptions()
    {
        $options = [];

        foreach ($this->getSubaccountSendingServers()->get() as $server) {
            $options[] = ['value' => $server->uid, 'text' => $server->name];
        }

        return $options;
    }

    /**
     * Get system notification.
     *
     * @return int
     */
    public function notifications()
    {
        return Notification::orderBy('created_at', 'desc');
    }
}
