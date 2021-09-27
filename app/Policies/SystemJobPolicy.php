<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Model\User;
use App\Model\SystemJob;

class SystemJobPolicy
{
    use HandlesAuthorization;

    public $jobs = [
        'App\Jobs\ImportSubscribersJob',
        'App\Jobs\ExportSubscribersJob',
        'App\Jobs\ExportSegmentsJob',
    ];

    public function delete(User $user, SystemJob $item)
    {
        if (in_array($item->name, $this->jobs)) {
            $data = json_decode($item->data);
            $list = \App\Model\MailList::findByUid($data->mail_list_uid);

            return $list->customer_id == $user->customer->id && !$item->isRunning();
        }

        return false;
    }

    public function downloadImportLog(User $user, SystemJob $item)
    {
        $data = json_decode($item->data);
        $list = \App\Model\MailList::findByUid($data->mail_list_uid);

        return $list->customer_id == $user->customer->id &&
            $item->name == 'App\Jobs\ImportSubscribersJob' &&
            $data->status == 'done';
    }

    public function downloadExportCsv(User $user, SystemJob $item)
    {
        $data = json_decode($item->data);
        $list = \App\Model\MailList::findByUid($data->mail_list_uid);

        return $list->customer_id == $user->customer->id &&
            ($item->name == 'App\Jobs\ExportSubscribersJob' || $item->name == 'App\Jobs\ExportSegmentsJob') &&
            $data->status == 'done';
    }

    public function cancel(User $user, SystemJob $item)
    {
        if (in_array($item->name, $this->jobs)) {
            $data = json_decode($item->data);
            $list = \App\Model\MailList::findByUid($data->mail_list_uid);

            return $list->customer_id == $user->customer->id &&
                ($item->isRunning() || $item->isNew());
        }

        return false;
    }
}
