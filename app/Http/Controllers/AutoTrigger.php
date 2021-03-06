<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\AutoTrigger as AutoTriggerModel;
use App\Model\DeliveryAttempt;
use App\Model\Email;
use App\Model\SystemJob;
use Exception;

class AutoTrigger extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $trigger = AutoTriggerModel::find($request->id);
        $info = [];
        $info[] = sprintf('This is an auto trigger for automation {{ %s }}', $trigger->automation2->name);
        $info[] = sprintf('Subscriber {{ %s }}', $trigger->subscriber->email);

        $actions = [];
        $trigger->getActions(function ($a) use (&$actions, $trigger) {
            $description = "+ [". (($a->getLastExecuted()) ? "Executed" : "Waiting") . "] " .  $a->getId() . ": " . $a->getTitle();
            if ($a->isCondition()) {
                $description .= " (". $a->getEvaluationResult() .")";
            }

            if ($a->getType() == 'ElementAction' && $a->getLastExecuted()) {
                // Attempt
                $email = Email::findByUid($a->getOption('email_uid'));

                $attempt = DeliveryAttempt::where('email_id', $email->id)->where('auto_trigger_id', $trigger->id)->first();

                $id = $attempt->id;
                $description .= " (Attempt: ".$id.", ";

                // System Job
                $sysJob = SystemJob::where('object_name', get_class($attempt))->where('object_id', $attempt->id)->first();

                if (is_null($sysJob)) {
                    $description .= 'Something went wrong: DeliveryAttempt without SystemJob?';
                }

                if (is_null($sysJob->job_id) && empty($sysJob->last_error)) {
                    $description .= "SystemJob ID: ".$sysJob->id." (waiting...)";
                } elseif (!empty($sysJob->last_error)) {
                    $description .= "SystemJob ID: ".$sysJob->id.", ERROR: ".$sysJob->last_error;
                } else {
                    $description .= "SystemJob ID: ".$sysJob->id.", SENT OK";
                }
            }

            $actions[] = $description;
        });

        $info[] = implode('<br>', $actions);

        echo implode('<br>', $info);
    }
}
