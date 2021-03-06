<?php

namespace App\Mail;

use App\Models\Template;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SurveyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $template;
    public $token;
    public $id;

    public function __construct(Template $template, $token, $id)
    {
        //
        $this->template = $template;
        $this->token = $token;
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('surveys.email')
            ->with([
                'templateEmailMsg' => $this->template->email_msg,
                'templateID' => $this->template->id,
                'token' => $this->token,
                'id' => $this->id
            ]);
    }
}
