[Go to index](../README.md)

## News Letters and APIS

### Mailchimp API Tinkering

Mailchimp es un end point en el cual podremos trabajar con correor y otras funciones, para esto lo primero es instalar mailchimp con el siguiente comando: composer require mailchimp/marketing. Ademas de esto es necesario crear una cuenta en la aplicacion, para poder tener las llaver de acceso al api las cuales guardaremos en nuestro archivo .env:

MAILCHIMP_KEY= Your Key

y despues lo agregaremos en nuestro archivo de configuracion en la parte de services:

'mailchimp' => [
        'key' => env('MAILCHIMP_KEY')
    ]


con esto podremos realizar algunas pruebas, para esto utilizaremos una ruta:

```php

Route::get('ping', function () {

    $mailchimp = new \MailchimpMarketing\ApiClient();

    $mailchimp->setConfig([
        'apiKey' => config('services.mailchimp.key'),
        'server' => 'us5'
    ]);


    

    $response = $mailchimp->lists->addListMember('2d6341977a',[
        'email_address' => 'dsolisa@est.utn.ac.cr',
        'status' => 'subscribed'
    ]);
    ddd($response);
});

```