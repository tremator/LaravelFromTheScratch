
@props(['trigger'])




    <div x-data="{ open: false }" @click.away="open=false" class="w-auto">


        <div @click="open = ! open">
            {{$trigger}}
        </div>

        <div x-show="open" class="bg-gray-100 py-2 absolute w-full mt-2 rounded-xl z-50 overflow-auto max-h-52" style="display: none">
            {{$slot}}
            
        </div>

    </div>
</div>