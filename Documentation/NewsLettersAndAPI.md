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

### Hacer que el formulario funcione

Para esto vamos a crear una ruta que nos sirva para crear o agregar nuevos integrantes:

```php

Route::post('newsletter', function () {

    request()->validate([
        'email'
    ]);

    $mailchimp = new \MailchimpMarketing\ApiClient();

    $mailchimp->setConfig([
        'apiKey' => config('services.mailchimp.key'),
        'server' => 'us5'
    ]);


    
    try {
        $response = $mailchimp->lists->addListMember('2d6341977a',[
            'email_address' => request('email'),
            'status' => 'subscribed'
        ]);
        return redirect('/')->with('success','You are now signed up for our news letter');
    } catch (\Exception $th) {
        return redirect('/')->with('success','Something went wrong');
    }
    
    
    
});
```

y tambien vamos a modificar el form del footer de nuestro archivo layout para que pueda usar esta ruta:

```html

<form method="POST" action="/newsletter" class="lg:flex text-sm">
                        @csrf
                        <div class="lg:py-3 lg:px-5 flex items-center">
                            <label for="email" class="hidden lg:inline-block">
                                <img src="/images/mailbox-icon.svg" alt="mailbox letter">
                            </label>

                            <input id="email" type="email" placeholder="Your email address" name="email"
                                   class="lg:bg-transparent py-2 lg:py-0 pl-4 focus-within:outline-none" >
                        </div>

                        <button type="submit"
                                class="transition-colors duration-300 bg-blue-500 hover:bg-blue-600 mt-4 lg:mt-0 lg:ml-3 rounded-full text-xs font-semibold text-white uppercase py-3 px-8"
                        >
                            Subscribe
                        </button>
                    </form>

```