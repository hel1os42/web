<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $email;
    public $link;
    public $locale;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $link)
    {
        $this->username = $user->name;
        $this->email    = $user->email;
        $this->link     = $link;
        $this->locale   = app()->getLocale();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'))
            ->to($this->email)
            ->subject(trans('mails.user.confirm.subject', [], $this->locale))
            ->markdown('mail.user.confirmation');
    }
}
