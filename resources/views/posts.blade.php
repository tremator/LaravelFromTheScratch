
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