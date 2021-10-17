<?php
    namespace App\services;

use MailchimpMarketing\ApiClient;

class Newsletter{

        public function subscribe($email, string $list = null){ 

            $list ??= config('services.mailchimp.lists.subscribers');

            request()->validate([
                'email'
            ]);
        
            
        
            $mailchimp = $this->client();
            
            
            return $mailchimp->lists->addListMember($list,[
                'email_address' => $email,
                'status' => 'subscribed'
            ]);
            return redirect('/')->with('success','You are now signed up for our news letter');
            
        }

        protected function client(){

            $mailchimp = new ApiClient();
        
            return $mailchimp->setConfig([
                'apiKey' => config('services.mailchimp.key'),
                'server' => 'us5'
            ]);

        }
    }