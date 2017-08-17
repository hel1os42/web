<?php

namespace App\Mail;

use App\Models\NauModels\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferUpdatedFail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Offer */
    private $offer;

    /**
     * Create a new message instance.
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.coreServiceFails.offerUpdated', [
            'offer' => $this->offer
        ]);
    }
}
