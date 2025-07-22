<div class="{{ $size }}">
    <div class="mt-2.5 px-4">
        <x-form-text-input-field wire:model.live="search" id="search" name="search" value="" autofocus/>
    </div>
    @if(session('error'))
    <div class="alert alert-danger px-4">
        {{ session('error') }}
    </div>
@endif
    @if (!empty($searchResults))
    <ul role="list" class="divide-y divide-gray-100 px-4">
        @foreach ($searchResults as $nonacademic)
            <li class="flex justify-between gap-x-6 py-5">
                <div class="flex min-w-0 gap-x-4">
                    @if ($nonacademic->profilePicture)
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="{{ $nonacademic->profilePicture }}" alt="">
                    @else
                        <div class="h-12 w-12 flex-none rounded-full bg-gray-200 flex items-center justify-center text-gray-700 font-semibold">
                            {{ strtoupper(substr($nonacademic->nameWithInitials, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 @if ($nonacademic->active == 0)text-red-500 @else text-gray-900 @endif">{{ $nonacademic->nameWithInitials }}</p>
                    <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ $nonacademic->nic }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm leading-6 text-gray-900">{{ $nonacademic->mobile }}</p>
                    <a href="{{ route('nonacademic.profile', ['id' => $nonacademic->usId ]) }}" class="mt-1 text-xs leading-5 text-gray-500">profile view</a>
                </div>
            </li>
        @endforeach
    </ul>
    @endif
</div>

