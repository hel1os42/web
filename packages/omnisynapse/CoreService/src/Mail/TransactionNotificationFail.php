<?php

namespace App\Mail;

use App\Models\NauModels\Transact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransactionNotificationFail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var Transact */
    private $transaction;

    /**
     * Create a new message instance.
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
        return $this->view('emails.coreServiceFails.transactionNotification', [
            'transaction' => $this->transaction
        ]);
    }
}
