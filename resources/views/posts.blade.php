
<x-layout>
    @include('_posts_header')

    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">


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