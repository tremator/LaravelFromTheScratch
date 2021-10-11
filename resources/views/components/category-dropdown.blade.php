<x-dropdown>

    <x-slot name="trigger">
        <button  class="py-2 pl-3 pr-9 text-sm font-semibold w-full lg:w-32 text-left flex lg:inline-flex">
            {{isset($currentCategory) ? $currentCategory->name : "Categories"}}
           
            <x-dropdown_icon class="absolute pointer-events-none" style="right: 12px"></x-dropdown_icon>

       </button>
    </x-slot>


    <x-dropdown_item href="/" :active="request()->routeIs('home')">All</x-dropdown_item>
    @foreach ($categories as $category)

    <x-dropdown_item 
    
    :active="isset($currentCategory) && $currentCategory->is($category)"
    
    href="/?category={{$category->slug}}&{{http_build_query(request()->except('category','page'))}}">{{$category->name}}</x-dropdown_item>
        

    @endforeach

</x-dropdown>