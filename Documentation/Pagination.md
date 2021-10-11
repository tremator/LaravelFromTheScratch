[Go to index](../README.md)

## Pagination

### Simple Pagination


para esto simplemente vamos a ir a nuestro modelo de Post y haremos lo siguiente:

```php
 public function index(){

        return view('posts',[
            'posts' => Post::latest()->filter(request(['search','category','author']))->paginate(6)->withQueryString() ,
            
        ]);
    }

```

con esto, estamos obtenion o ordenando nuestros resultados por paginas, y laravel tiene ya una forma de  manejar esto, por lo tanto, simplemente agregamos lo siguiente
en los ligares que queramos la paginacion:

```html


<x-layout>
    @include('_posts_header')

    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">

        {{$posts->links()}}

        @if ($posts->count())
            
        
        <x-post_feature_card :post="$posts[0]"></x-post_feature_card>
        </article>

        <div class="lg:grid lg:grid-cols-6">

            @if ($posts->count()>1)
                            
                <x-posts_grid :posts="$posts"/>
               
            @endif
            
        </div>

        @else

        <p>There are not any posts yet</p>

        @endif
        
    </main>



</x-layout>

```