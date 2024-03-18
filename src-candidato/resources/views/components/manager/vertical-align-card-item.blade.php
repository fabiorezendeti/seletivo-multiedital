<div class="md:flex md:w-1/2 lg:w-1/3 px-2 py-2 ">                    
    <div class="md:flex-1 p-4 rounded shadow-lg bg-white shadow-md border-b-4 border-blue-700 hover:border-blue-500 rounded">
        <h2 class="font-bold text-xl mb-2">{{$title}}</h2>                
        <p class="text-gray-700 text-base">
            {{$text}}
        </p>
        {{$slot}}
    </div>
</div>   