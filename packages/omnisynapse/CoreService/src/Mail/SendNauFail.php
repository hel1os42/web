<?php

namespace OmniSynapse\CoreService\Mail;

use App\Models\NauModels\Transact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNauFail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Transact */
    private $transaction;

    /**
     * Create a new message instance.
     *
     * @param Transact $transaction
     */
    public function __construct(Transact $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.coreServiceFails.sendNau', [
            'transaction' => $this->transaction
        ]);
    }
}
