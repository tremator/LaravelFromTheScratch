<x-layout>

    <section class="px-6 py-8">
        <main class="max-w-lg mx-auto mt-10 bg-gray-100 border border border-gray-200 p-6 rounded-xl">
            <h1 class="text-center font-bold text-xl">Register </h1>
            <form method="POST" action="/register" class="mt-10">

                @csrf
                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="name">Name</label>
                    <input class="border border-gray-400 p-2 w-full" type="text" name="name" id="name" required value="{{old('name')}}">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>


                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="username">UserName</label>
                    <input class="border border-gray-400 p-2 w-full" type="text" name="username" id="username" required value="{{old('username')}}">

                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="email">Email</label>
                    <input class="border border-gray-400 p-2 w-full" type="email" name="email" id="email" required value="{{old('email')}}">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div>
                    <label class="block mb-2 uppercase font-bold text-xs text-gray-700 mt-5" for="password">Password</label>
                    <input class="border border-gray-400 p-2 w-full" type="password" id="password" required>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{$message}}</p>
                    @enderror
                </div>

                <div class="mb-6 mt-10">
                    <button type="submit" class="bg-blue-400 text-white rounded py-2 px-4 hover:bg-blue-500">
                        Submit
                    </button>

                </div>

            </form>

        </main>
    </section>


</x-layout>