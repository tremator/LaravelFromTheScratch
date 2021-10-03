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
