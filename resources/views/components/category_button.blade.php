
@props(['category'])

<div class="space-x-2">
    <a href="/categories/{{$category->slug}}"
       class="px-3 py-1 border border-blue-300 rounded-full text-blue-300 text-xs uppercase font-semibold"
       style="font-size: 10px">{{$category->name}}</a>
    <a href="#"
       class="px-3 py-1 border border-red-300 rounded-full text-red-300 text-xs uppercase font-semibold"
       style="font-size: 10px">Updates</a>
</div>