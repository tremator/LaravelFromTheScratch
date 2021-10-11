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