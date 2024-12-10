<div>
    <x-form-input-label for='zone' value='Select Zone' />
    <x-form-list-input-field id="zone" name="zone" wire:model.live='selectedZone' :options="$zones" required/>
    @error('zone') <span class="text-red-500">{{ $message }}</span> @enderror  

    @if (!empty($schools))
        <div class="mt-4">
            <x-form-input-label for="school" value="Select School" />
            <select 
                id="school" 
                name="school" 
                wire:model="selectedSchool" 
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            >
                <option value="">Choose a school...</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </select>
            
        </div>
    @endif
    @error('school') <span class="text-red-500">{{ $message }}</span> @enderror
</div>
