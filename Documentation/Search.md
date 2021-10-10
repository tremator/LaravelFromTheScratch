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