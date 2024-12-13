{{-- <div class="flex items-center">
    <span>
        {{ $detail }}
    </span>
    @if ($function <> '')
        <a href="javascript:void(0)" wire:click.prevent="{{ $function }}" style="margin-left: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="green" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </a>
    @endif
</div> --}}

<ul role="list" class="divide-y divide-gray-100 border-t border-gray-200">
    <li class="flex justify-between gap-x-6 py-1">
    <div class="flex min-w-0 gap-x-4">
        <div class="min-w-0 flex-auto">
        <p class="mt-1 truncate text-xs/5 text-gray-500">{{ $heading }}</p>
        <p class="text-sm/6  text-gray-900">{!! $detail !!}</p>
        </div>
    </div>
    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
        <p class="mt-1 text-sm text-gray-500">Edit</p>
    </div>
    </li>
</ul>