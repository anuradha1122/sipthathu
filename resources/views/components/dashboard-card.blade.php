<div class="p-4 bg-white shadow-lg rounded-2xl flex-1">
    <div class="flex items-center">
        <span class="relative p-4 bg-purple-200 rounded-xl">
            <svg width="40" fill="currentColor" height="40" class="absolute h-4 text-purple-500 transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="{{ $icon }}"></path>
            </svg>
        </span>
        <p class="ml-2 text-black text-md">{{ $text }}</p>
    </div>
    <div class="flex flex-col justify-start">
        <p class="my-4 text-4xl font-bold text-center text-gray-700">{{ $number }}</p>
        <div class="flex items-center text-sm text-green-500">
            {{-- <svg width="20" height="20" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M1408 1216q0 26-19 45t-45 19h-896q-26 0-45-19t-19-45 19-45l448-448q19-19 45-19t45 19l448 448q19 19 19 45z"></path>
            </svg> --}}

            <span><a href="{{ route($link,['id' => $linkid]) }}">Staff List</a></span>
            <a href="{{ route($link,['id' => $linkid]) }}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M1408 1216q0 26-19 45t-45 19h-896q-26 0-45-19t-19-45 19-45l448-448q19-19 45-19t45 19l448 448q19 19 19 45z"></path>
            </svg>
            </a>
            {{-- <span class="text-gray-400">vs last month</span> --}}
        </div>
    </div>
</div>
