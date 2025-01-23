<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order_id;
    public $type;

    public function __construct($last_order_id,$type)
    {
        $this->order_id = $last_order_id;
        $this->type = $type;
    }

    public function build()
    {
        $order_id = $this->order_id;
        $type = $this->type;
        return $this->from(get_static_option('site_global_email'), get_static_option('site_title'))->view('mail.order-mail',compact(['order_id','type']));

    }
}
