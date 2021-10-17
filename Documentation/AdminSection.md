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

### Crear el form para crear el post

para esto vamos a crear un forma en la vista:

```html

<form action="/admin/posts" method="POST" >
            @csrf

            <div>
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="title">Title</label>
                <input class="border border-gray-400 p-2 w-full" type="text" name="title" id="title" required value="{{old('title')}}">

                @error('tittle')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div>
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="slug">Slug</label>
                <input class="border border-gray-400 p-2 w-full" type="text" name="slug" id="slug" required value="{{old('slug')}}">

                @error('slug')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div>
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="excerpt">Excerpt</label>
                <input class="border border-gray-400 p-2 w-full" type="text" name="excerpt" id="excerpt" required value="{{old('excerpt')}}">

                @error('excerpt')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div>
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="body">Body</label>
                <textarea class="border border-gray-400 p-2 w-full"  name="body" id="body" required value="{{old('body')}}">
                </textarea>

                @error('body')
                    <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                @enderror
            </div>
            <div>
                <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="category_id">category</label>
                <select class="border border-gray-400 p-2 w-full"  name="category_id" id="category_id" required value="{{old('category_id')}}">

                    @php
                        $categories = \App\Models\Category::all();
                    @endphp

                    @foreach ($categories as $category)

                    <option value="{{$category->id}}"> {{$category->name}} </option>
                        
                    @endforeach

                </select>

                @error('category')
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

vamos a hacer la ruta que responda al request de este form:

```php

Route::post('admin/posts',[PostController::class,'store'])->middleware('admin');

```

y por ultimo el metodo en nuestro controller para crear el post

```php
public function store(){

        $attributes = request()->validate([
            'title' => 'required',
            'slug' => ['required', Rule::unique('posts','slug')],
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => ['required', Rule::exists('categories','id')]
        ]);

        $attributes['user_id'] = auth()->id();

        Post::create($attributes);

        return redirect('/');

    }

```