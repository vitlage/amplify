<?php

namespace App\Library;

use Swift_Message;

class ExtendedSwiftMessage extends Swift_Message
{
    public $extAttachments = [];
}
