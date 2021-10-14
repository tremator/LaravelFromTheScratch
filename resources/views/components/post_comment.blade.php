@props(['comment'])

<article class= "flex bg-gray-100 p-6 border border-gray-200 rounded-xl space-x-4" >

    <div class="flex-shrink-0">

        <img src="https://i.pravatar.cc/60?u={{$comment->id}}" alt="" width="60" height="60" class="rounded-xl">
        

    </div>
    <header class="mb-4">
        <h3 class="font-bold mr-3">
            {{$comment->author->username}}
        </h3>

        <p class="text-xs ml-3" > Posted <time> {{ $comment->created_at }}</time> </p>    
        
    </header>

        <p>
            {{$comment->body}}
        </p>
           
    <div>



    </div>

</article>    