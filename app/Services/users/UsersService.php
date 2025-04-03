<?php

namespace App\Services\users;

use App\Models\User;
use App\Mail\BasicMail;
use Illuminate\Support\Facades\Mail;

class UsersService
{
    function sendRegisterEmail(User $user, $password)
    {
        $message = get_static_option('user_register_message') ?? __('You have successfully registered as a freelancer');
        $message = str_replace(["@name", "@email", "@username", "@password"], [$user->first_name . ' ' . $user->last_name, $user->email, $user->username, $password], $message);
        Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
            'subject' => get_static_option('user_register_subject') ?? __('New User Register Email'),
            'message' => $message
        ]));
    }

    function sendWelcomeEmail(User $user, $password)
    {
        $message = get_static_option('user_register_welcome_message') ?? __('Your registration successfully completed.');
        $message = str_replace(["@name", "@email", "@username", "@password", "@userType"], [$user->first_name . ' ' . $user->last_name, $user->email, $user->username, $password, 'freelancer'], $message);
        Mail::to($user->email)->send(new BasicMail([
            'subject' => get_static_option('user_register_welcome_subject') ?? __('User Register Welcome Email'),
            'message' => $message
        ]));
    }

    function sendOTPEmail($email, $email_verify_tokn)
    {
        Mail::to($email)->send(new BasicMail([
            'subject' =>  __('Otp Email'),
            'message' => __('Your otp code') . ' ' . $email_verify_tokn
        ]));
    }
}
