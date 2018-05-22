<?php

namespace App\Observers;

use App\Mail\PlaceComplaint;
use App\Models\Complaint;
use Illuminate\Support\Facades\Mail;

class ComplaintObserver
{
    /**
     * @param Complaint $complaint
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function saved(Complaint $complaint)
    {
        if ($complaint->getStatus() == Complaint::STATUS_INBOX) {
            $complaint->update(['status' => Complaint::STATUS_SENDING]);
            Mail::queue(new PlaceComplaint($complaint));
        }

    }
}
