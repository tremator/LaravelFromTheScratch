<x-layout>


    <article>
        <h1>{{$post->title}}</h1>
        
            {!!$post->body!!}
        
    </article>
    <a href="/">Go Back</a>
</x-layout>