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