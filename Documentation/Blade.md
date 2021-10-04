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