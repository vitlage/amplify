<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use App\Library\Log as MailLog;
use App\Library\ExtendedSwiftMessage;
use App\Model\Setting;
use Illuminate\Notifications\Notifiable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;
use App;

class User extends Authenticatable implements IShopModel
{
    use HasFactory, Notifiable;
    use ShopModel;
    const ASSET_DIR = 'files';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
/**
     * Associations.
     *
     * @var object | collect
     */
    public function customer()
    {
        return $this->hasOne('App\Model\Customer');
    }

    public function admin()
    {
        return $this->hasOne('App\Model\Admin');
    }

    public function systemJobs()
    {
        return $this->hasMany('App\Model\SystemJob')->orderBy('created_at', 'desc');
    }

    /**
     * Get authenticate from file.
     *
     * @return string
     */
    public static function getAuthenticateFromFile()
    {
        $path = base_path('.authenticate');

        if (file_exists($path)) {
            $content = \File::get($path);
            $lines = explode("\n", $content);
            if (count($lines) > 1) {
                $demo = session()->get('demo');
                if (!isset($demo) || $demo == 'backend') {
                    return ['email' => $lines[0], 'password' => $lines[1]];
                } else {
                    return ['email' => $lines[2], 'password' => $lines[3]];
                }
            }
        }

        return ['email' => '', 'password' => ''];
    }

    /**
     * Send regitration activation email.
     *
     * @return string
     */
    public function sendActivationMail($name = null)
    {
        $layout = \App\Model\Layout::where('alias', 'registration_confirmation_email')->first();
        $token = $this->getToken();

        $layout->content = str_replace('{ACTIVATION_URL}', join_url(config('app.url'), action('UserController@activate', ['token' => $token], false)), $layout->content);
        $layout->content = str_replace('{CUSTOMER_NAME}', $name, $layout->content);

        $name = is_null($name) ? trans('messages.to_email_name') : $name;

        // build the message
        $message = new ExtendedSwiftMessage();
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setContentType('text/html; charset=utf-8');

        $message->setSubject($layout->subject);
        $message->setTo([$this->email => $name]);
        $message->setReplyTo(Setting::get('mail.reply_to'));
        $message->addPart($layout->content, 'text/html');

        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception($result['error']);
        }

        MailLog::info('Sent activation email to '.$this->email);
    }

    /**
     * User activation.
     *
     * @return string
     */
    public function userActivation()
    {
        return $this->hasOne('App\Model\userActivation');
    }

    /**
     * Create activation token for user.
     *
     * @return string
     */
    public function getToken()
    {
        $token = \App\Model\UserActivation::getToken();

        $userActivation = $this->userActivation;

        if (!is_object($userActivation)) {
            $userActivation = new \App\Model\UserActivation();
            $userActivation->user_id = $this->id;
        }

        $userActivation->token = $token;
        $userActivation->save();

        return $token;
    }

    /**
     * Set user is activated.
     *
     * @return bool
     */
    public function setActivated()
    {
        $this->activated = true;
        $this->save();
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (User::where('uid', '=', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;

            // Add api token
            $item->api_token = str_random(60);
        });
    }

    public static function findByUid($uid)
    {
        return self::where('uid', '=', $uid)->first();
    }

    /**
     * Check if user has admin account.
     */
    public function isAdmin()
    {
        return is_object($this->admin);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPassword($token, url('password/reset', $token)));

        $resetPasswordUrl = url('password/reset', $token);
        $htmlContent = '<p>Please click the link below to reset your password:<br><a href="'.$resetPasswordUrl.'">'.$resetPasswordUrl.'</a>';

        // build the message
        $message = new ExtendedSwiftMessage();
        $message->setEncoder(new \Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));
        $message->setContentType('text/html; charset=utf-8');

        $message->setSubject('Password Reset');
        $message->setTo($this->email);
        $message->setReplyTo(Setting::get('mail.reply_to'));
        $message->addPart($htmlContent, 'text/html');

        $mailer = App::make('xmailer');
        $result = $mailer->sendWithDefaultFromAddress($message);

        if (array_key_exists('error', $result)) {
            throw new \Exception($result['error']);
        }
    }

    public function getStoragePath($path = '')
    {
        $base = storage_path('app/users/'.$this->uid);

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getLockPath($path)
    {
        $base = $this->getHomePath('locks');

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function getHomePath($path = '')
    {
        $base = $this->getStoragePath('home');

        if (!\File::exists($base)) {
            \File::makeDirectory($base, 0777, true, true);
        }

        return join_paths($base, $path);
    }

    public function uploadAsset($file)
    {
        // store to storage/app/users/{uid}/home/files/
        $assetdir = $this->getHomePath(self::ASSET_DIR);
        $newname = $file->getClientOriginalName();
        $newpath = join_paths($assetdir, $newname);

        // In case file name conflicts
        $i = 1;
        while (file_exists($newpath)) {
            $newname = $file->getClientOriginalName()."_".$i;
            $newpath = join_paths($assetdir, $newname);
            ++$i;
        }

        // Move uploaded file
        $file->move(
            $assetdir,
            $newname
        );

        return $newname;
    }

    /**
     * Generate one time token.
     */
    public function generateOneTimeToken()
    {
        $this->one_time_api_token = generateRandomString(32);
        $this->save();
    }

    /**
     * Clear one time token.
     */
    public function clearOneTimeToken()
    {
        $this->one_time_api_token = null;
        $this->save();
    }

}
