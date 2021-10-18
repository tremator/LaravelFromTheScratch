@props(['heading'])

<x-layout>

    <section class="px-6 py-8">
        <div class="border border-gray-200 rounded-xl p-6 max-w-sm mx-auto">

            <h1 class="text-lg font-bold mb-4">
                {{$heading}}
            </h1>
        {{$slot}}
    </div>
    </section>

</x-layout>