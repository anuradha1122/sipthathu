{{-- <div class="px-4 py-3 bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
    <x-profile-data-heading heading="{{ $heading }}" />
    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 display: flex">
        @if(!$edit)
            <x-profile-data-detail  detail="{{ $detail }}" function="{{ $function }}" />
        @else
            <form wire:submit.prevent="{{ $formFunction }}" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-2" enctype="multipart/form-data">
                @csrf
                <div class="sm:col-span-2">
                    <x-profile-data-input-text inputWire="{{ $inputWire }}" name="{{ $name }}" id="{{ $id }}" />
                </div>
                <div class="sm:col-span-2">
                    <x-form-button-primary size="" text="Update" modelBinding="click" name="{{ $name }}" />
                </div>
            </form>
        @endif
    </dd>
    
</div> --}}

<div >
    @if(!$edit)
        <x-profile-data-detail heading="{{ $heading }}" :detail="$fullName . '<br>' . $nameWithInitials" function="{{ $function }}" />
    @else
    <form wire:submit.prevent="{{ $formFunction }}" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-2" enctype="multipart/form-data">
        @csrf
        <div class="sm:col-span-2">
            {{-- <x-profile-data-input-text inputWire="{{ $inputWire }}" name="{{ $name }}" id="{{ $id }}" /> --}}
        </div>
        <div class="sm:col-span-2">
            <x-form-button-primary size="" text="Update" modelBinding="click" name="{{ $name }}" />
        </div>
    </form>
    @endif
</div>
