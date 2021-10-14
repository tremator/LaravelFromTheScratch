<x-layout>
    <x-post_card :post="$post"></x-post_card_blade>

    <section class="col-span-8 col-start-5 mt-10 space-y-6"> 


        @foreach ($post->comments as $comment)
            <x-post_comment :comment="$comment"></x-post_comment>
        @endforeach
        
     

    </section>    
</x-layout>