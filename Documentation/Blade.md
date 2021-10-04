[Go to index](../README.md)

## Blade


### Las Bases de Blade

La extencion blade en  los archivos de nustras vistas, nos permitiran realizar operaciones de forma que si no tuvieramos esta extension, simplemente no serian 
compiladas a php.

```html
<body>

    <?php foreach($posts as $post) : ?>
    <article>
        <h1><a href="posts/<?=$post->title;?>"> {{$post->title}} </a></h1>
        <p>
            {!!$post->body!!}
        </p>
    </article>
    <?php endforeach; ?>
</body>
```
En el title podemos ver un ejemplo, ya que eso sera compilado a php, gracias a la extension .blade, en el caso del body, ya que es un html, le pedimos que no lo compile como php.
otro ejemplo es la parte del foreach, podemos hacer que se vea mejor utilizando la sintaxis de blade:

```html
<body>

        @foreach($posts as $post)
        <article>
            <h1><a href="posts/<?=$post->title;?>"> {{$post->title}} </a></h1>
            <p>
                {!!$post->body!!}
            </p>
        </article>
        @endforeach
    </body>
```

### Layouts

Muy bien, ahora vamos a corregir un error o un problema que tenemos con nuestras vistas, en estos momentos todas nuestras vistas repiten el mismo esqueleto de html,
por lo tanto gracias a blade, podremos resumir esta parte, para esto crearemos un archivo en nuestras vistas llamado Layout.blade.html, y dentro pondremos lo siguiente:

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
   @yield('content')
</body>
</html>
```

con esto tenemos el esqueleto de html, y en el body una seccion que podremos llenar de forma dinamica segun la vista, por lo tanto veamos como consumir este esqueleto en otras vistas:
```html
@extends('Layout')

@section('content')


    @foreach($posts as $post)
        <article>
        <h1><a href="posts/<?=$post->title;?>"> {{$post->title}} </a></h1>
        <p>
            {!!$post->body!!}
        </p>
        </article>
    @endforeach
@endsection

```

otra forma es utilizando componentes, para esto modificaremos nuestro esqueleto o Layout de la siguiente forma:

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
   {{$slot}}
</body>
</html>

```

y nuestras vistas de la siguiente manera:
```html

<x-layout>

    @foreach($posts as $post)
        <article>
        <h1><a href="posts/<?=$post->title;?>"> {{$post->title}} </a></h1>
        <p>
            {!!$post->body!!}
        </p>
        </article>
    @endforeach
</x-layout>

```