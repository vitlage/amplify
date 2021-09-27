<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Paid status
    const ACTION_PAID = 'paid';
    const ACTION_UNPAID = 'unpaid';

    // Status
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CASH_MANUAL_CONFIRMATION = 'cash_manual_confirmation';

    public $fillable = ['item_number', 'transaction_id', 'currency_code', 'payment_status'];

    public function paymentMethod()
    {
        return $this->belongsTo('App\Model\PaymentMethod');
    }

    public function subscription()
    {
        return $this->belongsTo('App\Model\Subscription');
    }

    /**
     * Get payment method name.
     *
     * @return string
     */
    public function getPaymentMethodName()
    {
        return $this->payment_method_name ? $this->payment_method_name : trans('messages.payment_manual');
    }

    /**
     * Get payment method name.
     *
     * @return string
     */
    public function getOrderID()
    {
        return $this->order_id;
    }

    /**
     * Get errors messages.
     *
     * @return string
     */
    public function getErrorMessages()
    {
        $messages = [];
        $result = unserialize($this->data);

        if ($this->paymentMethod->type == \App\Model\PaymentMethod::TYPE_BRAINTREE_PAYPAL ||
            $this->paymentMethod->type == \App\Model\PaymentMethod::TYPE_BRAINTREE_CREDIT_CARD) {
            if (count($result->errors->deepAll()) > 0) {
                foreach ($result->errors->deepAll() as $error) {
                    $messages[] = $error->code.': '.$error->message;
                }
            }
        }

        if ($this->paymentMethod->type == \App\Model\PaymentMethod::TYPE_PAYPAL) {
            if (isset($result->error)) {
                $messages[] = $result->error;
            }
        }

        return $messages;
    }
}
