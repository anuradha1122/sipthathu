<div>
    <form wire:submit.prevent="generateReports" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-3" enctype="multipart/form-data">
        @csrf
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="race" id="race" :options="$raceList" wire:model.live="race" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="religion" id="religion" :options="$religionList" wire:model.live="religion" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="civilStatus" id="civilStatus" :options="$civilStatusList" wire:model.live="civilStatus" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-date-input-field name="birthDay" id="birthDay" wire:model.live="birthDayStart" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-date-input-field name="birthDay" id="birthDay" wire:model.live="birthDayEnd" required/>
        </div>
    </form>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        @if (!empty($results))
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">#</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Name With Initials</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Race</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Religion</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Civil Status</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Birth Day</th>
                        @endif      
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    
                        @foreach ($results as $index => $result)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $results->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->userName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->nameWithInitials }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->race }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->religion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->civilStatus }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->birthDay }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="mt-4 text-start">
            @if (!empty($results))
            {{ $results->links() }}
            @endif   
        </div>
    </div>
</div>
