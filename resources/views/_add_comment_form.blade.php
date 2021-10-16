@auth
        <form method="POST" action="/posts/{{$post->slug}}/comments" class="border border-gray-200 rounded-xl p-6" style="width: 30%">
            @csrf

            <header class="flex items-center">
                <img src="https://i.pravatar.cc/40?u={{auth()->id()}}" alt="" width="60" height="60" class="rounded-full mr-3">
                <h2>
                    Want to Participate??
                </h2>
            </header>

            <div class="mt-6">
                <textarea name="body" rows="5" class="w-full text-sm focus:outline-none focus:ring" placeholder="Text Something" required></textarea>

                @error('body')
                    <span class="text-xs text-red-500">{{$message}}</span>
                @enderror
            </div>

            <div class="flex justify-end mt-6 border-t border-fray-300 pt-6">

                <button type="submit" class="bg-blue-500 text-white uppercase font-semibold text-xs py-2 px-10 rounded-2xl hover:bg-blue-600">
                    Post
                </button>

            </div>

        </form>
            
        @else
        <p>
            <a href="/login">Log in to leave a comment</a>
        </p>
        @endauth