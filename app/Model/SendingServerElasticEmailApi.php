<?php

/**
 * SendingServerElasticEmailApi class.
 *
 * Abstract class for Mailjet API sending server
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

use App\Library\Log as MailLog;

class SendingServerElasticEmailApi extends SendingServerElasticEmail
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    // Inherit class to implementation of this method
    public function send($message, $params = array())
    {
        try {
            try {
                $this->setupWebhooks();
            } catch (\Exception $ex) {
                MailLog::warning('Cannot set up Elastic webhook: ' . $ex->getMessage());
            }

            $this->enableCustomHeaders();

            $result = $this->sendElasticEmailV2Fixed($message);

            MailLog::info('Sent!');

            return array(
                'runtime_message_id' => $result,
                'status' => self::DELIVERY_STATUS_SENT,
            );
        } catch (\Exception $e) {
            MailLog::warning('Sending failed');
            MailLog::warning($e->getMessage());

            throw $e;

            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
            );
        }
    }
}
