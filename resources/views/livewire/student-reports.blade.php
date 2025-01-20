<div>
    <form wire:submit.prevent="generateReports" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-3" enctype="multipart/form-data">
        @csrf
        @if (session('ministryId') == 1)
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="province" value="Province" />
                <x-form-list-input-field name="province" id="province" :options="$provinceList" wire:model.live="selectedProvince" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="district" value="District" />
                <x-form-list-input-field name="district" id="district" :options="$districtList" wire:model.live="selectedDistrict" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="zone" value="Zone" />
                <x-form-list-input-field name="zone" id="zone" :options="$zoneList" wire:model.live="selectedZone" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="division" value="Division" />
                <x-form-list-input-field name="division" id="division" :options="$divisionList" wire:model.live="selectedDivision" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="school" value="School" />
                <x-form-list-input-field name="school" id="school" :options="$schoolList" wire:model.live="selectedSchool" required/>
            </div>
        @elseif (session('officeId') && session('officeTypeId') == 1)
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="district" value="District" />
                <x-form-list-input-field name="district" id="district" :options="$districtList" wire:model.live="selectedDistrict" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="zone" value="Zone" />
                <x-form-list-input-field name="zone" id="zone" :options="$zoneList" wire:model.live="selectedZone" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="division" value="Division" />
                <x-form-list-input-field name="division" id="division" :options="$divisionList" wire:model.live="selectedDivision" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="school" value="School" />
                <x-form-list-input-field name="school" id="school" :options="$schoolList" wire:model.live="selectedSchool" required/>
            </div>
        @elseif (session('officeId') && session('officeTypeId') == 2)
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="division" value="Division" />
                <x-form-list-input-field name="division" id="division" :options="$divisionList" wire:model.live="selectedDivision" required/>
            </div>
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="school" value="School" />
                <x-form-list-input-field name="school" id="school" :options="$schoolList" wire:model.live="selectedSchool" required/>
            </div>
        @elseif (session('officeId') && session('officeTypeId') == 3)
            <div class="sm:col-span-1 px-1">
                <x-form-input-label for="school" value="School" />
                <x-form-list-input-field name="school" id="school" :options="$schoolList" wire:model.live="selectedSchool" required/>
            </div>
        @endif
         
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="race" value="Race" />
            <x-form-list-input-field name="race" id="race" :options="$raceList" wire:model.live="race" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="religion" value="Religion" />
            <x-form-list-input-field name="religion" id="religion" :options="$religionList" wire:model.live="religion" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="bloodGroup" value="Blood Group" />
            <x-form-list-input-field name="bloodGroup" id="bloodGroup" :options="$bloodGroupList" wire:model.live="bloodGroup" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="gender" value="Gender" />
            <x-form-list-input-field name="gender" id="gender" :options="$genderList" wire:model.live="gender" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="grade" value="Grade" />
            <x-form-list-input-field name="grade" id="grade" :options="$gradeList" wire:model.live="grade" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="birthDayStart" value="Birthday Start From" />
            <x-form-date-input-field name="birthDay" id="birthDay" wire:model.live="birthDayStart" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="birthDayEnd" value="Birthday End To" />
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
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Blood Group</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Gender</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Birth Day</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Grade/Class</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">School</th>
                        @endif      
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    
                        @foreach ($results as $index => $result)
                        <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $results->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->nameWithInitials }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->race }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->religion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->bloodGroup }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->birthDay }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->class }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->school }}</td>
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
