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