<div>
    <form wire:submit.prevent="generateReports" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-3" enctype="multipart/form-data">
        @csrf
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="authority" id="authority" :options="$authorityList" wire:model.live="authority" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="class" id="class" :options="$classList" wire:model.live="class" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="density" id="density" :options="$densityList" wire:model.live="density" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="ethnicity" id="ethnicity" :options="$ethnicityList" wire:model.live="ethnicity" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="facility" id="facility" :options="$facilityList" wire:model.live="facility" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="gender" id="gender" :options="$genderList" wire:model.live="gender" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="language" id="language" :options="$languageList" wire:model.live="language" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-list-input-field name="religion" id="religion" :options="$religionList" wire:model.live="religion" required/>
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
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Authority</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Density</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Ethincity</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Facility</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Language</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Religion</th>
                        @endif      
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    
                        @foreach ($results as $index => $result)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $results->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->schoolName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->authority }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->class }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->density }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->ethnicity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->facility }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->language }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->religion }}</td>
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

