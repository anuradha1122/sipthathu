<div class="{{ $size }}">
    <div class="mt-2.5 px-4">
        <x-form-text-input-field wire:model.live="search" id="search" name="search" autofocus/>
    </div>
    @if(session('error'))
    <div class="alert alert-danger px-4">
        {{ session('error') }}
    </div>
@endif
    @if (!empty($searchResults))
    <ul role="list" class="divide-y divide-gray-100 px-4">
        @foreach ($searchResults as $school)
            <li class="flex justify-between gap-x-6 py-2">
                <div class="flex min-w-0 gap-x-4">
                    <div class="min-w-0 flex-auto">
                    <p class="text-sm font-semibold leading-6 @if ($school->active == 0)text-red-500 @else text-gray-900 @endif">{{ $school->name }}</p>
                    <p class="truncate text-xs leading-5 text-gray-500">{{ $school->authority }}</br>
                    {{ $school->zone }}</p>
                    </div>
                </div>
                <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                    <p class="text-sm leading-6 text-gray-900">{{ $school->province }}</p>
                    <a href="{{ route('school.profile', ['id' => $school->id ]) }}" class="text-xs leading-5 text-gray-500">profile view</a>
                </div>
            </li>
        @endforeach
    </ul>
    @endif
</div>


