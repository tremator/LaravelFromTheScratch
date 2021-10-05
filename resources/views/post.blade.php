<x-layout>


    <article>
        <h1>{{$post->title}}</h1>
        <p>
            By <a href="">{{$post->user->name}}</a>
        </p>
            {!!$post->body!!}
            <p>
                <a href="/categories/{{$post->category->slug}}">
                    {{$post->category->slug}}
                </a>
            </p>
    </article>
    <a href="/">Go Back</a>
</x-layout>