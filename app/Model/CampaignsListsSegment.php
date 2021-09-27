<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CampaignsListsSegment extends Model
{
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function campaign()
    {
        return $this->belongsTo('App\Model\Campaign');
    }

    public function mailList()
    {
        return $this->belongsTo('App\Model\MailList');
    }

    public function segment()
    {
        return $this->belongsTo('App\Model\Segment');
    }

    /**
     * Get segment in the same campaign and mail list.
     *
     * @return collect
     */
    public function getRelatedSegments()
    {
        $segments = Segment::leftJoin('campaigns_lists_segments', 'campaigns_lists_segments.segment_id', '=', 'segments.id')
                        ->where('campaigns_lists_segments.campaign_id', '=', $this->campaign_id)
                        ->where('campaigns_lists_segments.mail_list_id', '=', $this->mail_list_id);

        return $segments->get();
    }
}
