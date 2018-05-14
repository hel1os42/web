<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlaceComplaint extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Complaint
     */
    private $complaint;

    /**
     * PlaceComplaint constructor.
     *
     * @param Complaint $complaint
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * @return $this
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function build()
    {
        $this->to(config('nau.support_email'), 'NAU place complaint');
        $this->complaint->update(['status' => Complaint::STATUS_SENT]);

        return $this->view('mail.complaint.message', $this->complaint->fresh(['user', 'place'])->toArray());
    }
}
