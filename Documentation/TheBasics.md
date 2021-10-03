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