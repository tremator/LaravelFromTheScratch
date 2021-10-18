<x-layout>

    <section class="px-6 py-8">
        <div class="border border-gray-200 rounded-xl p-6 max-w-4xl mx-auto">

            <h1 class="text-lg font-bold mb-4">
                Manage Posts
            </h1>

            <div class="flex">

                <aside class="w-48">

                    <ul>
                        <li>
                            <a href="/admin/posts" class="{{ request()->is('admin/posts') ? 'text-blue-500' : '' }}">
                                All
                                Posts</a>
                        </li>

                        <li>
                            <a href="/admin/posts/create"
                                class="{{ request()->is('admin/posts/create') ? 'text-blue-500' : '' }}"> New Post</a>
                        </li>

                    </ul>

                </aside>

            </div>

            <main class="flex-1">

                <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">

                                    <tbody class="bg-white divide-y divide-gray-200">

                                        @foreach ($posts as $post)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">


                                                <div class="text-sm font-medium text-gray-900">

                                                    <a href="/posts/{{$post->slug}}">
                                                        {{$post->title}}
                                                    </a>
                                                    
                                                </div>


                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="/admin/posts/{{$post->id}}/edit" class="text-blue-500 hover:text-indigo-900">Edit</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <form action="/admin/posts/{{$post->id}}" method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button class="text-blue-500 hover:text-indigo-900">Delete</button>

                                            </form>
                                        </td>
                                    </tr>
                                        @endforeach
                                        
                                        

                                        <!-- More people... -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </section>

</x-layout>
