<?php

/**
 * SendingServerElasticEmailSmtp class.
 *
 * Abstract class for SendGrid API sending server
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
use App\Library\ExtendedSmtpTransport;

class SendingServerElasticEmailSmtp extends SendingServerElasticEmail
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

            $transport = ExtendedSmtpTransport::newInstance($this->host, (int) $this->smtp_port, $this->smtp_protocol);
            $transport->setUsername($this->smtp_username);
            $transport->setPassword($this->smtp_password);

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            // Actually send
            $sent = $mailer->send($message);

            if ($sent && $transport->getMessageId()) {
                MailLog::info('Sent!');

                return array(
                    'runtime_message_id' => $transport->getMessageId(),
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
