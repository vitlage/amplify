<?php

/**
 * SendingServerMailgunSMTP class.
 *
 * Abstract class for Mailgun SMTP sending server
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

class SendingServerMailgunSmtp extends SendingServerMailgun
{
    protected $table = 'sending_servers';

    /**
     * Send the provided message.
     *
     * @return bool
     *
     * @param message
     */
    public function send($message, $params = array())
    {
        try {
            $this->setupWebhooks();

            $transport = new \Swift_SmtpTransport($this->host, (int) $this->smtp_port, $this->smtp_protocol);
            $transport->setUsername($this->smtp_username);
            $transport->setPassword($this->smtp_password);

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            // Actually send
            $sent = $mailer->send($message);

            if ($sent) {
                MailLog::info('Sent!');

                return array(
                    //'runtime_message_id' => $sent['MessageId'],
                    'status' => self::DELIVERY_STATUS_SENT,
                );
            } else {
                MailLog::warning('Sending failed');

                return array(
                    'status' => self::DELIVERY_STATUS_FAILED,
                    'error' => 'Unknown SMTP error',
                );
            }
        } catch (\Exception $e) {
            MailLog::warning('Sending failed');
            MailLog::warning($e->getMessage());

            return array(
                'status' => self::DELIVERY_STATUS_FAILED,
                'error' => $e->getMessage(),
            );
        }
    }
}
