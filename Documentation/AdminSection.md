[Go to index](../README.md)

## Admin Section

### Acceso limitado solo a Administradores

Para validar que solo un administrador pueda accesar a una pagina, vamos a hacer un middleware que nos ayudara a separa esta logica, usaremos el siguiente comando 
php artisan make:middleware AdminsOnly , y agregaremos lo siguiente en el archivo creado:

```php

 public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->username != 'Admin Name') {
            abort(Response::HTTP_FORBIDDEN);
        }
        return $next($request); 
    }

```

con eso podemos validar que unicamente el administrador pueda acceder a la ruta que estara protegida por el. Para poder activar este middleware, vamos a ir al archivo kernel para agregarlo:

```php

protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'admin' => AdminsOnly::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

```

y con esto estamos listos para crear la ruta protegida:

```php

Route::get('admin/posts/create',[PostController::class,'create'])->middleware('admin');

```