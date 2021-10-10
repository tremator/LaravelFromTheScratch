[Go to index](../README.md)

## Filtering

### Eloquent Querys Contraints Avansados

Ahora vamos a hacer el filtro para las categorias, lo cual es muy sencillo gracias al filtro que ya tenemos. Lo que haremos es, 
vamos a modificar nuestro controller de la siguiente manera:

```php
   public function index(){

        return view('posts',[
            'posts' => Post::latest()->filter(request(['search','category']))->get() ,
            'categories' => Category::all(),
            'currentCategory' => Category::where('slug', request('category'))->first()
        ]);
    }

```

modificaremos nuestra funcion index para que acepte los parametros de categoria y current category, seguidamente modificaremos nuestro modelo:

```php

public function scopeFilter($query, array $filters){

        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where('title', 'like', '%' . request('search') . '%')
            ->orWhere('body', 'like', '%' . request('search') . '%'));

        $query->when($filters['category'] ?? false, fn($query, $category) =>
            $query->whereHas('category', fn($query) => $query->where('slug', $category)));
        
    }


```
agregaremos a nuestro filtro la funcion que filtre los posts por su categoria. Por ultimo, hay que modificar nuestro archivo de rutas:

```php
Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/hello', function () {
    return view('welcome');
});



Route::get('/posts/{post:slug}', [PostController::class, 'show']);

 /*Route::get('/categories/{category:slug}', function (Category $category) {
    return view('posts',[
        'posts' => $category->posts,
        'currentCategory' => $category,
        'categories'=>Category::all()
    ]);

})->name('category');*/

Route::get('/authors/{author:username}', function (User $author) {
    return view('posts',[
        'posts' => $author->posts,
        'categories'=>Category::all()
    ]);

});

```

eliminamos nuestra ruta de categorias, ya que ahora todo se puede hacer desde la misma de los posts, pero tendremos que midificar nuestros posts, para que 
puedan seguir esta nueva ruta:

```html
<x-dropdown_item 
            
            :active="isset($currentCategory) && $currentCategory->is($category)"
            
            href="/?category={{$category->slug}}">{{$category->name}}</x-dropdown_item>
```


### Extraer Componente del Dropdown category

muy bien, para hacer esto, vamos a ir a la terminal y vamos a correr el siguiente comando, el cual nos ayudara a crear un nuevo componente: php artisan make:component CategoryDropdown, con esto tendremos nuestro componente, pero ademas tendremos otro archivo en nuestra carpeta de View/components, coneste archivo es con el que vamos a trabajar.

lo primero va a ser mover el dropdown a nuestro nuevo componente:

```html
<x-dropdown>

    <x-slot name="trigger">
        <button  class="py-2 pl-3 pr-9 text-sm font-semibold w-full lg:w-32 text-left flex lg:inline-flex">
            {{isset($currentCategory) ? $currentCategory->name : "Categories"}}
           
            <x-dropdown_icon class="absolute pointer-events-none" style="right: 12px"></x-dropdown_icon>

       </button>
    </x-slot>


    <x-dropdown_item href="/" :active="request()->routeIs('home')">All</x-dropdown_item>
    @foreach ($categories as $category)

    <x-dropdown_item 
    
    :active="isset($currentCategory) && $currentCategory->is($category)"
    
    href="/?category={{$category->slug}}">{{$category->name}}</x-dropdown_item>
        

    @endforeach

</x-dropdown>
```

seguidamente vamos a modificar nuestro nuevo archivo:

```php

<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;

class CategoryDropdown extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.category-dropdown',[
            'categories' => Category::all(),
            'currentCategory' => Category::where('slug', request('category'))->first()
        ]);
    }
}
```

y de esta forma, vamos a simplificar aun mas nuestras vistas, ya que no sera necesario pasarle las categorias, ya que ahora es este componente el que se encarga de eso,
por lo tanto, vamos a eliminar esa parte de nuestro controller:

```php
   public function index(){

        return view('posts',[
            'posts' => Post::latest()->filter(request(['search','category']))->get() ,
            
        ]);
    }


```
### Filtro de Autor

Para esto vamos a seguir el mismo procedimiento, vamos a modificar nuestra clase post en nuestro metodo filter:

```php
 public function scopeFilter($query, array $filters){

        $query->when($filters['search'] ?? false, fn($query, $search) =>
            $query->where('title', 'like', '%' . request('search') . '%')
            ->orWhere('body', 'like', '%' . request('search') . '%'));

        $query->when($filters['category'] ?? false, fn($query, $category) =>
            $query->whereHas('category', fn($query) => $query->where('slug', $category)));

        $query->when($filters['author'] ?? false, fn($query, $author) =>
            $query->whereHas('author', fn($query) => $query->where('username', $author)));
        
    }
```

y agregamos un nuevo filtro para el autor, seguidamente modificamos el controller:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(){

        return view('posts',[
            'posts' => Post::latest()->filter(request(['search','category','author']))->get() ,
            
        ]);
    }

    public function show(Post $post){
        return view('post',[
            'post' => $post
        ]);
    }
    
}

```

agregarmos el autor dentro de los parametros que espera y listo, solo nos queda actualizar las rutas en los componentes y actualizar loas rutas del archivo:

```php
Route::get('/', [PostController::class, 'index'])->name('home');


Route::get('/posts/{post:slug}', [PostController::class, 'show']);
```