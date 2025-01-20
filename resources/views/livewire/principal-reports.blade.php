<div>
    <form wire:submit.prevent="generateReports" class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-4" enctype="multipart/form-data">
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
            <x-form-input-label for="schoolAuthority" value="School Authority" />
            <x-form-list-input-field name="schoolAuthority" id="schoolAuthority" :options="$schoolAuthorityList" wire:model.live="schoolAuthority" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolEthnicity" value="School Ethnicity" />
            <x-form-list-input-field name="schoolEthnicity" id="schoolEthnicity" :options="$schoolEthnicityList" wire:model.live="schoolEthnicity" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolClass" value="School Classes" />
            <x-form-list-input-field name="schoolClass" id="schoolClass" :options="$schoolClassList" wire:model.live="schoolClass" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolDensity" value="School Densities" />
            <x-form-list-input-field name="schoolDensity" id="schoolDensity" :options="$schoolDensityList" wire:model.live="schoolDensity" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolFacility" value="School Facilities" />
            <x-form-list-input-field name="schoolFacility" id="schoolFacility" :options="$schoolFacilityList" wire:model.live="schoolFacility" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolGender" value="School Gender" />
            <x-form-list-input-field name="schoolGender" id="schoolGender" :options="$schoolGenderList" wire:model.live="schoolGender" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolLanguage" value="School Languages" />
            <x-form-list-input-field name="schoolLanguage" id="schoolLanguage" :options="$schoolLanguageList" wire:model.live="schoolLanguage" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="race" value="Race" />
            <x-form-list-input-field name="race" id="race" :options="$raceList" wire:model.live="race" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="religion" value="Religion" />
            <x-form-list-input-field name="religion" id="religion" :options="$religionList" wire:model.live="religion" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="civilStatus" value="Civil Status" />
            <x-form-list-input-field name="civilStatus" id="civilStatus" :options="$civilStatusList" wire:model.live="civilStatus" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="gender" value="Gender" />
            <x-form-list-input-field name="gender" id="gender" :options="$genderList" wire:model.live="gender" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="birthDay" value="Birth Day Start From" />
            <x-form-date-input-field name="birthDay" id="birthDay" wire:model.live="birthDayStart" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="birthDay" value="Birth Day To" />
            <x-form-date-input-field name="birthDay" id="birthDay" wire:model.live="birthDayEnd" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="service" value="Service Start From" />
            <x-form-date-input-field name="service" id="service" wire:model.live="serviceStart" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="service" value="Service To" />
            <x-form-date-input-field name="service" id="service" wire:model.live="serviceEnd" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolAppoint" value="School Appoint Start From" />
            <x-form-date-input-field name="schoolAppoint" id="schoolAppoint" wire:model.live="schoolAppointStart" required/>
        </div>
        <div class="sm:col-span-1 px-1">
            <x-form-input-label for="schoolAppoint" value="School Appoint To" />
            <x-form-date-input-field name="schoolAppoint" id="schoolAppoint" wire:model.live="schoolAppointEnd" required/>
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
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">School</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Zone</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Race</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Religion</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Civil Status</th>
                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">Gender</th>
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->workPlaceName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->zoneName }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->race }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->religion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->civilStatus }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $result->gender }}</td>
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
