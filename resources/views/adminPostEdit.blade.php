<x-layout>

    <section class="px-6 py-8">
        <div class="border border-gray-200 rounded-xl p-6 max-w-4xl mx-auto">

            <h1 class="text-lg font-bold mb-4">
                <p>{{'Edit Post: ' . $post->title}}</p>  
            </h1>

            <div class="flex">

                <aside class="w-48">

                    <ul>
                        <li>
                            <a href="/admin/dashboard" class="{{request()->is('admin/dashboard') ? 'text-blue-500' : ''}}"> Dasboard</a>
                        </li>

                        <li>
                            <a href="/admin/posts/create" class="{{request()->is('admin/posts/create') ? 'text-blue-500' : ''}}"> New Post</a>
                        </li>

                    </ul>

                </aside>

            </div>

            <main class="flex-1">
                <form action="/admin/posts/{{$post->id}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @method('PATCH')

                    <div>

                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5"
                            for="title">Title</label>
                        <input class="border border-gray-400 p-2 w-full" type="text" name="title" id="title" required
                            value="{{  old('title', $post->title)}}">

                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="slug">Slug</label>
                        <input class="border border-gray-400 p-2 w-full" type="text" name="slug" id="slug" required
                            value="{{ old('slug',$post->slug) }}">

                        @error('slug')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5"
                            for="thumpnail">Thumbnail</label>
                        <input class="border border-gray-400 p-2 w-full" type="file" name="thumpnail" id="thumpnail"
                             value="{{ old('thumpnail', $post->thumpnail) }}">

                        @error('thumbnail')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5"
                            for="excerpt">Excerpt</label>
                        <input class="border border-gray-400 p-2 w-full" type="text" name="excerpt" id="excerpt"
                            required value="{{ old('excerpt', $post->excerpt) }}">

                        @error('excerpt')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="body">Body</label>
                        <textarea class="border border-gray-400 p-2 w-full" name="body" id="body" required
                        >

                            {{old('body',$post->body)}}
                    </textarea>

                        @error('body')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5"
                            for="category_id">category</label>
                        <select class="border border-gray-400 p-2 w-full" name="category_id" id="category_id" required
                            value="{{ old('category_id', $post->category_id) }}">

                            @php
                                $categories = \App\Models\Category::all();
                            @endphp

                            @foreach ($categories as $category)

                                <option value="{{ $category->id }}"> {{ $category->name }} </option>

                            @endforeach

                        </select>

                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6 mt-10">
                        <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                            Submit
                        </button>

                    </div>

                </form>
            </main>
        </div>
    </section>

</x-layout>
