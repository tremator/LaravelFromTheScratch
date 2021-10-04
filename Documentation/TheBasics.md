[Go to index](../README.md)

## The Basics

### Como una Ruta Carga una Vista?

En el folder de rutas podremos encontrar un archivo que se llama web.php, el cual contiene la definicion de las rutas de nuestra pagina
las cuales se muestran de la siguiente forma

```php
Route::get('/', function () {
    return view('welcome');
});
```

Puedes cambiar la ruta simplemente cambiando el contido que se encuentra entre comillas, en la primera parte de la funcion, de la siguiente forma
```php
Route::get('/hello', function () {
    return view('welcome');
});
```

Esta funcion en estos momentos esta retornando una vista, pero puede retornar muchas cosas, como un simple mensaje

```php
Route::get('/helloWorld', function () {
    return "Hello World";
});
```

o un Json
```php
Route::get('/json', function () {
    return ['message'=> 'Hello World'];
});
```

### Inluir CSS y JavaScript

Primero vamos a cambiar el contenido de la vista welcome, por un html sencillo

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1> Hello World </h1>
</body>
</html>
```

Ahora en el folder public, vamos a crear un archivo .css, en el cual agregaremos los estilos que deseamos
```css
body{
    background: navy;
    color: white;
}
</html>
```
con esto listo podremos agregar una etiqueta link, en nuestro html para referenciar a nuestro archivo .css

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href ="/app.css">
</head>
<body>
    <h1> Hello World </h1>
</body>
</html>
```
El mismo proceso aplica para los JavaScript

### Hacer una ruta y vincularla

Primero vamos a modificar el html y css, para crear unos posts, luego de esto vamos a crear una nueva ruta que nos lleve a la vista de un post en especifico
```php
Route::get('/post', function () {
    return view('post');;
});
```

seguidamente en el titulo de nuestro primer post vamos a agregar un ancla para referenciar a la nueva ruta

```html
<article>
        <h1> <a href="/post">My First Post</a> </h1>
        <p>
            Lorem ipsum, dolor sit amet consectetur adipisicing elit. Provident eligendi quibusdam voluptatum quod sit, possimus doloribus exercitationem architecto voluptatibus iste quisquam? Facilis quasi eos facere laboriosam, tempora quas maxime saepe.

        </p>
</article>
```
evidentemente no podemos accesar a la vista, ya que esta no existe, por lo tanto nos mostrara un error.
Para esto vamos a ir al folder de vistas y vamos a crear un nuevo archivo llamado post.blade.php y agregaremos el html necesario para mostrar un post

### Almacenar Blog Posts Como Archivos HTML
para lograr hacer que nuestra vista post sea un poco mas dinamica, tenemos que lograr pasar variables desde nuestra ruta, hacia la vista, esto se 
logra utilizando el segundo parametro de view(), de la siguiente forma:

```php
Route::get('/post', function () {
    return view('post',[
        'post' => '<h1> Hello World </h1>'
    ]);;
});
```
una vez tengamos esto, podemos crear unos archivos html, que contengan los datos de cada uno de los posts, para lugo cargar esos archivos segun 
nos indiquen las peticiones, de la seiguiente manera:
```php
Route::get('/posts/{post}', function ($slug) {
    $path = __DIR__ . "/../resources/posts/{$slug}.html";
    if(! file_exists($path)){
        dd('File dosnt exists');

    }

    $post = file_get_contents();
    return view('post',[
        'post' => $post
    ]);;
});
```

### Restricciones de Rutas

Ahora tenemos el problema de que en la variable post de nuestro archivo de rutas, nos pueden pasar cualquier cosa, y eso no lo queremos, por lo tanto tenemos que 
solcionar este problema, esto lo haremos de la siguiente manera:

```php
Route::get('/posts/{post}', function ($slug) {
    $path = __DIR__ . "/../resources/posts/{$slug}.html";
    if(! file_exists($path)){
        dd('File dosnt exists');

    }

    $post = file_get_contents($path);
    return view('post',[
        'post' => $post
    ]);;
})->whereAlpha('post');
```
al final del metodo podemos agregar restricciones


### Utilizar la Cache para Operaciones Grandes

El siguiente problema que se presenta es que si tuvieramos a millones de personas accesando a la pagina de posts, este tendria que hacer el proceso de cargar un archivo 
un millon de veces, lo cual no sirve. Para solucionar esto, vamos a utilizar la memoria cache de ka siguiente forma:

```php
Route::get('/posts/{post}', function ($slug) {
    $path = __DIR__ . "/../resources/posts/{$slug}.html";
    if(! file_exists($path)){
        dd('File dosnt exists');

    }

    $post =  cache()->remember("posts.{$slug}", now()->addHour(), function() use ($path){
        var_dump('file gets content');
        return file_get_contents($path);
    });

    
    return view('post',[
        'post' => $post
    ]);;
})->whereAlpha('post');
```

de esta forma le estamos diciendo que guarde en cache el archivo durante una hora.

### Usar la Clase Filesystem para leer un directorio

ahora vamos a dividir un poco el codigo, para lo cual vamos a crear una nueva clase en el folder Models, que se encuentra dentro del folder app, crearemos 
un archivo .php y trasladremos todo el codigo que teniamos en nuestra ruta, a esta clase, de la siguiente forma:

```php
<?php
    namespace App\Models;
    class Post{

        public static function find($slug)
        {
            $path = resource_path("/posts/{$slug}.html");
            if(! file_exists($path)){
               abort(404);
            }

            $post =  cache()->remember("posts.{$slug}", now()->addHour(), function() use ($path){
                var_dump('file gets content');
                return file_get_contents($path);
            });
            
            return $post;
        }

    }
```

y nuestro metodo de enrutamiento quedara de la siguiente forma:

```php
Route::get('/posts/{post}', function ($slug) {
    
    return view('post',[
        'post' => Post::find($slug)
    ]);;
})->whereAlpha('post');
```
Importando la clase que acabamos de crear, en mi caso Post, podremos utilizar el metodo que escribimos que contiene toda la logica para realizar la operacion.


Ahora vamos a agregar un metodo en la clase Post que nos permita cargar todos los posts que son mostrados en la pagina principal, de la siguiente forma:
```php
static public function all()
        {
            $files = File::files(resource_path("posts/"));

            return array_map(function($file){
                return $file->getContents();
            }, $files);
        }
```

y modificaremos el metodo de la ruta base, para que pueda consumir este metodo y mostrar de una forma mas dinamica los posts:

```php
Route::get('/', function () {
    return view('posts',[
        'posts' => Post::all()
    ]);
});
```