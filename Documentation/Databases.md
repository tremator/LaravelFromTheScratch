[Go to index](../README.md)

## Trabajando con Bases De Datos


### Archivos de Ambiente y Conexiones a Bases De Datos

lo primero sera modificar el arvhico .env, para que corresponda con las credenciales de nuestra base de datos, una vez realizado esto
correremos el comando php artisan migrate, para crear una tablas en la base de datos de nuestro proyecto

### Las bases de Base de datos

para manejar o crear bases de datos podemos utilizar los archivos de migraciones, los cuales al correr el comando de php artisan:fresh, se veran reflejados
en la base de datos, con el inconveniente de que se borra toda la data que tenia la base de datos

### Eloquent y el Patron de Registro Activo

### Crear un Modelo Post y una migracion

para esto vamos a eliminar nuestro archivo de la clase Post, y despues correremos el comando  php artisan make:migration create_post_table, con esto
habremos creado un archivo de migracion, el cual podremos modificar para que corresponda con los datos que necesitamos que tengan los post:

```php
    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreatePostTable extends Migration
    {
        /**
        * Run the migrations.
        *
        * @return void
        */
        public function up()
        {
            Schema::create('post', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('excerpt');
                $table->text('body');
                $table->timestamps();
                $table->timestamp('published_at')->nullable();
            });
        }

        /**
        * Reverse the migrations.
        *
        * @return void
        */
        public function down()
        {
            Schema::dropIfExists('post');
        }
    }

```

Lo siguiente es crear nuestro modelo, para lo cual utilizaremos el comando php artisan make:model Post, el cual creara un model llamado Post:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
}


```

una vez creados estos archivos, simplemente vamos a modificar nuestras vistas para que puedan consumir o enviar los datos necesarios para acceder a la base de datos:
```html
<x-layout>

    @foreach($posts as $post)
        <article>
        <h1><a href="posts/<?=$post->id;?>"> {{$post->title}} </a></h1>
        <p>
            {!!$post->excerpt!!}
        </p>
        </article>
    @endforeach
</x-layout>
```

```html
<x-layout>


    <article>
        <h1>{{$post->title}}</h1>
        <p>
            {{$post->body}}
        </p>
    </article>
    <a href="/">Go Back</a>
</x-layout>
```

y nuestro archivo de enrutamiento:

```php

Route::get('/posts/{post}', function ($id) {
    
    return view('post',[
        'post' => Post::findOrFail($id)
    ]);;
});

```

### Actualizaciones Eloquent y HTML Escaping

para actualizar lso datos en la base de datos podemos utilizar el siguiente comando php artisan tinker, dentro de este ambiente
 vamos a crear una variable para guardar uno de los objetos creados $post = Post::first(); y luego vamos a modificar el body de la siguiente 
 manera $post->body= '<p>' . $post->body . '</p>', esto se vera reflejado en nuestra pagina.

 ### Tres formas de mitigar las vulnerabilidades de las asignaciones masivas

 La manera de proteger nuestro programa de este problema, es crear lo siguiente protected $fillable = ['title'], con eso estamos permitiendo ese campo,
  si queremos proteger mas, simplemente los agregamos, otra forma es utilizar en lugar de fillable, usar guard, lo cual hace lo contrario, proteje ese campo


### Vinculo de Modelo a Rutas

para lograr esto, simplemente hayq ue modificar nuestro archivo de rutas de la siguiente manera:
```php
Route::get('/posts/{post:slug}', function (Post $post) {
    
    return view('post',[
        'post' => $post
    ]);;
});
```
de esta forma, estamos pasando el slug correspiendiente a nuestro modelo de base de datos y pasando el resultado a nuestra vista

### Primer relacion Eloquent

Vamos generar una nueva migracion y un nuevo modelo para administrar categorias, para esto utilizaremos el siguiente comando php artisan make:model Category -m,
seguidamente, vamos a modificar el archivo de migracion de categorias para que contenga los datos que necesitamos y modificaremos el de post, para agregar la relacion

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

```


```php
<?php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('title');
            $table->text('excerpt');
            $table->text('body');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}


```
y actualizaremosla base de datos con el comando php artisan migrate:fresh, y crearemos o insertaremos categorias en la base de datos, para luego insertar post que tengan la relacion.

Muy bien, ahora para acceder a la categoria de nuestros post, vamos a agregar un metodo que nos permitira crear esta relacion:

```php
public function category(){
      
           return $this->belongsTo(Category::class);
       
   }
```
ahora cuando consultemos por el atributo category, nos devolvera la categoria a la que esta asociado el post.

### Mostrar Los Posts Asociados a Una Categoria

para esto vamos a agregar la relacion en el modelo de categorias:

```php
 public function posts(){
      
        return $this->hasMany(Post::class);
    
    }
```

seguidamente vamos a agregar una ruta nueva que nos muestre los posts de una categoria, la cual va a ser seleccionada por el slug:

```php
Route::get('/categories/{category:slug}', function (Category $category) {
    return view('posts',[
        'posts' => $category->posts
    ]);
});
```

por ultimo, vamos a modificar nuestras vistas para que tengan este nuevo link:

```html
<x-layout>

    @foreach($posts as $post)
        <article>
        <h1><a href="posts/<?=$post->slug;?>"> {{$post->title}} </a></h1>
        
            {!!$post->excerpt!!}

            <p>
                <a href="/categories/{{$post->category->slug}}">
                    {{$post->category->slug}}
                </a>
            </p>
        
        </article>
    @endforeach
</x-layout>
```

### ClockWork y el problema del N+1

para solucionar este problema vamos a modificar de la siguiente forma nuestro archivo de rutas, modificando la forma en que realizamos la consulta:

```php
Route::get('/', function () {
    return view('posts',[
        'posts' => Post::with('category')->get()
    ]);
});

```
### Almacen de Semillas Para Base De Datos

con esta funcion vamos a poder crear datos por default en nuestra base de datos, para lo cual iremos al archivo llamado DatabaseSeeder y modificaremos su contenido para
 que quede de la siguiente forma:

 ```php
 <?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;





use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Category::truncate();
        Post::truncate();

         $user = User::factory()->create();
         $category = Category::create([
            'name' => "Personal",
            'slug' => "personal"
         ]);

         Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title'=>"My First Post",
            'slug'=>"my-first-post",
            'excerpt' => 'lorem ipsum',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum'
         ]);

    }
}

 ```
 seguidamente en nuestra terminal refrescamos la base de datos y corremos el siguiente comando php artisan db:seed y esto cargara las semillas que hicimos en la base de datos.

 ### Arranque Rapido con Factory

 para crear factorys y simplificar nuestro codigo en el archivo de seeds, primero vamos a generarlos con el siguiente comando  php artisan make:factory PostFactory.

 de igual forma haremos uno para las categorias, para luego modificarlas para que creen datos a nuestro gusto:

 ```php
<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'excerpt' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
        ];
    }
}
 ```

 ```php
<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slug' => $this->faker->slug
        ];
    }
}


 ```

 y con esto listo, podremos simplificar mucho nuestros archivo seed:

 ```php
 <?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;




use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Category::truncate();
        Post::truncate();

        Post::factory()->create();

    }
}


 ```

 ### Ver los Post de un Autor

 para esto vamos a modificar la migracion de user y el factory para agregar un username el cual nos servira para hacer la busqueda, luego vamos a modificar 
 nuestro archivo de rutas para agregar la nueva ruta:

 ```php
Route::get('/authors/{author:username}', function (User $author) {
    return view('posts',[
        'posts' => $author->posts
    ]);
});

 ```
una vez agregado esto y modificado la base de datos, nos resta actualizar nuestras vistas para que puedan consumir esto:

```html

<x-layout>

    @foreach($posts as $post)
        <article>
        <h1><a href="posts/<?=$post->slug;?>"> {{$post->title}} </a></h1>

        <p>
            By <a href="authors/{{$post->author->username}}">{{$post->author->name}}</a>
        </p>
        
            {!!$post->excerpt!!}

            <p>
                <a href="/categories/{{$post->category->slug}}">
                    {{$post->category->slug}}
                </a>
            </p>
        
        </article>
    @endforeach
</x-layout>
```
```html
<x-layout>


    <article>
        <h1>{{$post->title}}</h1>
        <p>
            By <a href="authors/{{$post->author->username}}">{{$post->author->name}}</a>
        </p>
            {!!$post->body!!}
            <p>
                <a href="/categories/{{$post->category->slug}}">
                    {{$post->category->slug}}
                </a>
            </p>
    </article>
    <a href="/">Go Back</a>
</x-layout>
```
### Eager Load Relantionships on an existing model

ahora vamos a arreglar un poco el problema de N+1, pero de una forma diferente, vamos a ir a la clase Post y vamos a agregar el 
atributo with:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{   
    use HasFactory;

    protected $with = ['category','author'];

   // protected $fillable = ['title'];
   public function category(){
      
        return $this->belongsTo(Category::class);
       
   }
   public function author(){
       return $this->belongsTo(User::class,'user_id');
   
   }
}


```

y con esto simplemente acabamos de simplicar nuestro archivo de rutas, simplemente vamos a hacer el siguiente cambio:

```php
Route::get('/', function () {
    return view('posts',[
        'posts' => Post::latest()->get()
    ]);
});

Route::get('/hello', function () {
    return view('welcome');
});

Route::get('/helloWorld', function () {
    return "Hello World";
});

Route::get('/json', function () {
    return ['message' => 'Hello World'];
});

Route::get('/posts/{post:slug}', function (Post $post) {
    
    return view('post',[
        'post' => $post
    ]);;
});

Route::get('/categories/{category:slug}', function (Category $category) {
    return view('posts',[
        'posts' => $category->posts
    ]);

});

Route::get('/authors/{author:username}', function (User $author) {
    return view('posts',[
        'posts' => $author->posts
    ]);

});

```


