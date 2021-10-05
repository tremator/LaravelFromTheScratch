
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