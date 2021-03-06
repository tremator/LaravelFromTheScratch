[Go to index](../README.md)

## Comments

### Escribir el Markup para un Comentario

El primer paso va a ser crear un cascaron o un componente que nos sirva para mostarr los comentarios:

```html
<article class= "flex bg-gray-100 p-6 border border-gray-200 rounded-xl space-x-4" >

    <div class="flex-shrink-0">

        <img src="https://i.pravatar.cc/60" alt="" width="60" height="60" class="rounded-xl">
        

    </div>
    <header class="mb-4">
        <h3 class="font-bold mr-3">
            jhon Doe
        </h3>

        <p class="text-xs ml-3" > Posted <time>8 months ago</time> </p>    
        
    </header>

        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
            Nunc scelerisque viverra mauris in aliquam sem fringilla ut morbi. Cras fermentum odio eu feugiat pretium nibh ipsum consequat nisl. 
        </p>
           
    <div>



    </div>

</article>    
```

y esto lo vamos  a referenciar en la vista que muestra un unico post:

```html
<x-layout>
    <x-post_card :post="$post"></x-post_card_blade>

    <section class="col-span-8 col-start-5 mt-10 space-y-6"> 
        <x-post_comment></x-post_comment>
     

    </section>    
</x-layout>
```


### Consistencia de tablas y restricciones de llaves foraneas

vamos a usar un commando para crear la migracion el modelo el factory y el controller para los comentarios:

php artisan make:model Comment -mfc

y vamos a trabajar en la migracion para que refleje las relaciones que tiene, ademas de esto agregar las reglas para que cuendo sus padres sean eliminados este tambien:

```php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
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
        Schema::dropIfExists('comments');
    }
}


```

### Hacer los comentarios dinamicos

para esto vamos a trabajar en el factory para poder crear commentarios y sus relaciones:\

```php
<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_id'=> Post::factory(),
            'user_id' => User::factory(),
            'body' => $this->faker->paragraph()
        ];
    }
}

```

ademas de esto es necesario agregar dichas relaciones en el modelo:

```php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function post(){

        return $this->belongsTo(Post::class);

    }

    public function author(){
        return $this->belongsTo(User::class,'user_id');

    }
}
 

```

y de igual forma en sus clases padre hay que agregar la relcion:

```php

public function comments(){
    return $this->hasMany(Comment::class);

}

```


```php

public function comments(){
       return $this->hasMany(Comment::class);
   }

```

y por ultimo adaptar nuestro html de forma que sea dinamico


### Dise??ar el Formulario para los comentarios

Vamos a crear el formulario el cual le permitira a los usuarios crear sus comentarios en un post:

```html
     <form method="POST" action="/posts/{{$post->slug}}/comments" class="border border-gray-200 rounded-xl p-6" style="width: 30%">
            @csrf

            <header class="flex items-center">
                <img src="https://i.pravatar.cc/40?u={{auth()->id()}}" alt="" width="60" height="60" class="rounded-full mr-3">
                <h2>
                    Want to Participate??
                </h2>
            </header>

            <div class="mt-6">
                <textarea name="body" rows="5" class="w-full text-sm focus:outline-none focus:ring" placeholder="Text Something"></textarea>
            </div>

            <div class="flex justify-end mt-6 border-t border-fray-300 pt-6">

                <button type="submit" class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
                    Post
                </button>

            </div>

        </form>
```


### Activar el formulario de comentarios

Para esto vamos a crear un nuevo controller llamado PostCommentController y en ese controller crearemos el metodo para guardar los commentarios, 
relacionandolos con su post y usuario respectivos:

```php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function store(Post $post){


        request()->validate([
            'body' => 'required'
        ]);

        $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => request('body')
        ]);

        return back();

    }
}

```

tambien deberemos agregar la propiedad guarded y ponerla vacia en el modelo, para que nos permita guardar los datos en la base datos, depues hay que hacer la ruta:

```php

Route::post('posts/{post:slug}/comments',[PostCommentController::class,'store']);

```

y modificar el formulario para que responda a esta ruta, ademas de agregar una validacion para que solo los usuarios logeados, puedan hacer comentarios:


```html

 @auth
        <form method="POST" action="/posts/{{$post->slug}}/comments" class="border border-gray-200 rounded-xl p-6" style="width: 30%">
            @csrf

            <header class="flex items-center">
                <img src="https://i.pravatar.cc/40?u={{auth()->id()}}" alt="" width="60" height="60" class="rounded-full mr-3">
                <h2>
                    Want to Participate??
                </h2>
            </header>

            <div class="mt-6">
                <textarea name="body" rows="5" class="w-full text-sm focus:outline-none focus:ring" placeholder="Text Something"></textarea>
            </div>

            <div class="flex justify-end mt-6 border-t border-fray-300 pt-6">

                <button type="submit" class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
                    Post
                </button>

            </div>

        </form>
            
        @else
        <p>
            <a href="/login">Log in to leave a comment</a>
        </p>
        @endauth



```
