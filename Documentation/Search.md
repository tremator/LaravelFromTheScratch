[Go to index](../README.md)

## Search

### Buscdor (la forma sucia)

okey, para esta parte vamos a ver que en nuestro componente de header al final, tenemos un form de search, el cual tiene como metodo GET. Lo siguiente va a ser ir al 
archivo de rutas, y vamos a trabajar en la ruta base. y la modificaremos de la siguiente forma:

```php
Route::get('/', function () {

    $posts = Post::latest();

    if(request('search')){
        $posts->where('title', 'like', '%' . request('search') . '%');
    }


    return view('posts',[
        'posts' => $posts->get() ,
        'categories' => Category::all()
    ]);
})->name('home');

```

con esto, estamos preguntando si en el parametro search viene algo y si es asi, vamos a filtrar la busqueda en relacion a lo solicitado.

De la misma forma podemos agregar nuevas condiciones de busqueda, por ejemplo, podemos agregar el filtro para el body o el excerpt de los posts:

```php
Route::get('/', function () {

    $posts = Post::latest();

    if(request('search')){
        $posts->where('title', 'like', '%' . request('search') . '%')
        ->orWhere('body', 'like', '%' . request('search') . '%');
    }


    return view('posts',[
        'posts' => $posts->get() ,
        'categories' => Category::all()
    ]);
})->name('home');
```

lo siguiente, es agregar un pequeño detalle al form de busqueda, para lograr mostrar o guardar el parametro de busqueda:

```html

<div class="relative flex lg:inline-flex items-center bg-gray-100 rounded-xl px-3 py-2">
            <form method="GET" action="#">
                <input type="text" name="search" placeholder="Find something"
                       class="bg-transparent placeholder-black font-semibold text-sm"
                       value="{{request('search')}}"
                       >
            </form>
        </div>

```

### Buscador (La Forma Limpia)

para esto lo primero que vamos a hacer es ir a la terminal y correr el siguiente comando 'php artisan make:controller PostController' 
con este comando crearemos un controller llamado PostController, el cual se encuentra en app/http/controllers .

Lo siguiente se crear un metodo llamado index, en el cual pegaremos todo el codigo que tenia nuestra ruta:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(){
        $posts = Post::latest();

        if(request('search')){
            $posts->where('title', 'like', '%' . request('search') . '%')
            ->orWhere('body', 'like', '%' . request('search') . '%');
        }


        return view('posts',[
            'posts' => $posts->get() ,
            'categories' => Category::all()
        ]);
    }
    
}
```

y lo siguiente es modificar nuestra ruta, para que pueda hacer uso del controller:

```php
Route::get('/', [PostController::class, 'index'])->name('home');
```

lo siguiente que haremos es trabajar un poco en la ruta de post, con la cual realizamos filtros, para esto vamos a ir al modelo 
de post y haremos lo siguiente:

```php

public function scopeFilter($query, array $filters){

        if($filters['search'] ?? false){
            $query->where('title', 'like', '%' . request('search') . '%')
            ->orWhere('body', 'like', '%' . request('search') . '%');
        }
        
    }


```

seguidamente, modificaremos el controller para que haga toda la logica:

```php

public function show(Post $post){
        return view('post',[
            'post' => $post
        ]);
    }
```

y con esto listo, modificaremos nuestra ruta:

```php
Route::get('/posts/{post:slug}', [PostController::class, 'show']);


```

