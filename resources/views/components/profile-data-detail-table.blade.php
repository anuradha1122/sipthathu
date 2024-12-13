<ul role="list" class="divide-y divide-gray-100 border-t border-gray-200">
    <li class="flex justify-between gap-x-6 py-1">
    <div class="flex min-w-0 gap-x-4">
        <div class="min-w-0 flex-auto">
        <p class="mt-1 truncate text-xs/5 text-gray-500">{{ $heading }}</p>
        @foreach($detail as $row)
            {!! $row."<br>" !!}
        @endforeach
        {{-- <p class="text-sm/6  text-gray-900">{!! $detail !!}</p> --}}
        </div>
    </div>
    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
        <p class="mt-1 text-sm text-gray-500">Edit</p>
    </div>
    </li>
</ul>