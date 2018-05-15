<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

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
     */
    public function build()
    {
        $this->to(config('nau.support_email'), 'NAU place complaint');

        return $this->view('mail.complaint.message', $this->complaint->fresh(['user', 'place'])->toArray());
    }

    /**
     * @param Mailer $mailer
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function send(Mailer $mailer)
    {
        parent::send($mailer);

        if (0 === count(Mail::failures())) {
            $this->complaint->update(['status' => Complaint::STATUS_SENT]);
        }
    }
}
