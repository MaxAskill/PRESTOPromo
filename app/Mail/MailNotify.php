<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailNotify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    function build(){

        if($this->data["status"] == "approved"){
            $this->approved();
        }else if($this->data["status"] == "endorsement"){
            $this->endorsement();
        }else if($this->data["status"] == "Denied"){
            $this->denied();
        }else if($this->data["status"] == "Unprocessed"){
            $this->unprocessed();
        }else if($this->data["status"] == "verifyEmail"){
            $this->verifyEmail();
        }
    }

    function approved(){
        return $this->from("helpdesk@everydayproductscorp.com")->subject("Approved Pull Out")
            ->view($this->data["viewEmail"])->with('data', $this->data);
    }
    function endorsement(){
        return $this->from("helpdesk@everydayproductscorp.com")->subject("For Approval Pull Out")
            ->view("EmailEndorsement")->with('data', $this->data);
    }
    function denied(){
        return $this->from("helpdesk@everydayproductscorp.com")->subject("Denied Pull Out")
            ->view('EmailDenied')->with('data', $this->data);
    }
    function unprocessed(){
        return $this->from("helpdesk@everydayproductscorp.com")->subject("Unprocessed Pull Out")
                ->view('EmailUnprocessed')->with('data', $this->data);
    }
    function verifyEmail(){
        return $this->from("helpdesk@everydayproductscorp.com")->subject("Verification Code")
                ->view('EmailOTP')->with('data', $this->data);
    }
    // /**
    //  * Get the message envelope.
    //  */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Mail Notify',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
