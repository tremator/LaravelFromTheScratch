[Go to index](../README.md)

## Pagination

### Construir Y Registrar un Usuario


primero, vamos a crear un controller que nos ayude con esto, utilizaremos el comando php artisan make:controller RegisterController,
lo cual nos creara un archivo, en el cual crearemos nuestros metodos para redirigir a la vista que contendra el form y otro metodo para la accion de guardar: 

```php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class RegisterController extends Controller
{
    public function create(){
        return view('register.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);


        User::create($attributes); 
    }
}


```

lo siguiente es crear las rutas que respondan a estos metodos:

```php
Route::get('register', [RegisterController::class, 'create']);
Route::post('register', [RegisterController::class, 'store']);
```

notese que ambas estan en la misma ruta, pero con metodos diferentes.

y por ultimo la vista que contendra el form:

```html
<x-layout>

    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-10 bg-gray-100 border border border-gray-200 p-6 rounded-xl">
            <h1 class="text-center font-bold text-xl">Register </h1>
            <form method="POST" action="/register" class="mt-10">

                @csrf

                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="name">Name</label>
                <input class="border border-gray-400 p-2 w-full" type="text" name="name" id="name" required>

                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="username">UserName</label>
                <input class="border border-gray-400 p-2 w-full" type="text" name="username" id="username" required>

                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="email">Email</label>
                <input class="border border-gray-400 p-2 w-full" type="email" name="email" id="email" required>

                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="password">Password</label>
                <input class="border border-gray-400 p-2 w-full" type="password" id="password" required>

                <div class="mb-6 mt-10">
                    <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                        Submit
                    </button>

                </div>

            </form>

        </main>
    </section>


</x-layout>
```

### Hash Automático de Contraseñas con Mutadores

para esto simplemente vamos a agregar un mutator en nuestra clase User, de la siguiente forma:

```php
public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }

```

con esto logramos que antes de que la contraseña sea guardada se incripte.

### Validaciones Fallidas y Data Antigua en inputs

para esto vamos a ir a nuestro controller y vamos a agregar unas cuantas validaciones, las cuales deberan ser cumplidas para que el usuario pueda ser registrado:

```php

public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        $attributes['password'] = bcrypt($attributes['password']);

        User::create($attributes); 
    }

```

y para reflejar estos errores en caso de que no se pase la validacion, hay que modificar el html de nuestro form, lo cual es sencillo ya que esta informacion 
esta a la mano en laravel:

```html
<form method="POST" action="/register" class="mt-10">

                @csrf
                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="name">Name</label>
                    <input class="border border-gray-400 p-2 w-full" type="text" name="name" id="name" required value="{{old('name')}}">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>


                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="username">UserName</label>
                    <input class="border border-gray-400 p-2 w-full" type="text" name="username" id="username" required value="{{old('username')}}">

                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="email">Email</label>
                    <input class="border border-gray-400 p-2 w-full" type="email" name="email" id="email" required value="{{old('email')}}">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="password">Password</label>
                    <input class="border border-gray-400 p-2 w-full" type="password" id="password" required>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-6 mt-10">
                    <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                        Submit
                    </button>

                </div>

            </form>
```

ademas de el manejo de errores, se agrego un value el cual corresponde a lo ultimo que tenia ese input



### Mostar Mensaje de Exito

para hacer esto, vamos a agregar un mensaje en el redirect de nuestro register controller:

```php
   public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

       

        User::create($attributes); 

        session()->flash('success','Your account has been created.');

        return redirect('/');
    }

```

y en nuestro layout vamos a agregar un poco de html para reflejar este mensaje:

```html

    @if (session()->has('success'))
        <div x-data="{show: true}" x-init="setTimeout(()=> show = false, 4000)"  x-show="show" class="fixed bg-blue-500 text-white py-2 px-4 rounded-xl bottom-3 right-3 text-sm">
            <p>{{session('success')}}</p>
        </div>
    @endif

```
### Ingresar y registrarse
Ya podemos crear nuestro usuario, pero nuestra pagina todavia no sabe que ya hay un usuario activo, por lo tanto vamos a grgar la logica que nos permitira hacer esto, para esto 
vamos a nuestro RegisterController y agregaremos lo siguiente:

```php
public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

       

        $user = User::create($attributes); 


        Auth::login($user);
        session()->flash('success','Your account has been created.');

        return redirect('/');
    }

```

con esoo estamos guardando o iniciando nuestro usuario y con esto podremos revisar y manejar si ya tenemos un usuario ingresado:

```html
<section class="px-6 py-8">
        <nav class="md:flex md:justify-between md:items-center">
            <div>
                <a href="/">
                    <img src="/images/logo.svg" alt="Laracasts Logo" width="165" height="16">
                </a>
            </div>
            
            <div class="mt-8 md:mt-0 flex items-center">
                @guest
                    <a href="/register" class="text-xs font-bold uppercase">Register</a>

                    <a href="/login" class="text-xs font-bold uppercase ml-6">Log In</a>


                @else
                    <span class="text-xs font-bold uppercase">{{auth()->user()->name}}</span>

                    <form method="POST" action="/logout" class="text-xs font-semibold text-blue-500 ml-6">
                        @csrf
                        <button type="submit"> LogOut</button>
                    
                    </form>
                @endguest
                

                <a href="#" class="bg-blue-500 ml-3 rounded-full text-xs font-semibold text-white uppercase py-3 px-5">
                    Subscribe for Updates
                </a>
            </div>
        </nav>
```
y en nuestras rutas tambien podemos hacerlo, de forma que solo respondan si no hay un usuario, como lo es el caso de la ruta register:

```php
Route::get('register', [RegisterController::class, 'create'])->middleware('guest');
Route::post('register', [RegisterController::class, 'store'])->middleware('guest');
```

### Contruir la pagina de log in

Esto es basicamente lo mismo que para registrarse asi que, vamos a crear una vista para esto:

```html

<x-layout>

    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-10 bg-gray-100 border border border-gray-200 p-6 rounded-xl">
            <h1 class="text-center font-bold text-xl">LogIn </h1>
            <form method="POST" action="/login" class="mt-10">

                @csrf
                

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="email">Email</label>
                    <input class="border border-gray-400 p-2 w-full" type="email" name="email" id="email" required value="{{old('email')}}">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="password">Password</label>
                    <input class="border border-gray-400 p-2 w-full" type="password" id="password" name="password" required>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-6 mt-10">
                    <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                        Log In
                    </button>

                </div>

            </form>

        </main>
    </section>


</x-layout>

```

agregaremos los metodos necesarios en nuestro controller:

```php

public function create(){
        return view('sessions.create');
    }

    public function store(){

        $attributes = request()->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($attributes)) {
            return redirect('/')->with('success', 'Welcome Back');
        }

        return back()->withInput()->withErrors(['email' => 'Your Provided Credentials could not be verified']);

    }

```

y por ultimo las rutas:

```php

Route::get('/login', [SessionController::class, 'create'])->middleware('guest');
Route::post('login', [SessionController::class, 'store'])->middleware('guest');

```