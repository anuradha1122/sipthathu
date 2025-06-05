<div class="grid grid-cols-6 gap-x-8 gap-y-6 sm:grid-cols-6">
    <div class="sm:col-span-3">
        <x-form-input-label for='province' value='Select Province' />
        <x-form-list-input-field id="province" name="province" wire:model.live='selectedProvince' :options="$provinces" required/>
        @error('province') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
    <div class="sm:col-span-3">
        <x-form-input-label for='district' value='Select District' />
        <x-form-list-input-field id="district" name="district" wire:model.live='selectedDistrict' :options="$districts" required/>
        @error('district') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
    <div class="sm:col-span-3">
        <x-form-input-label for='zone' value='Select Zone' />
        <x-form-list-input-field id="zone" name="zone" wire:model.live='selectedZone' :options="$zones" required/>
        @error('zone') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
    <div class="sm:col-span-3">
        <x-form-input-label for='school' value='Select School' />
        <x-form-list-input-field id="school" name="school" wire:model.live='selectedSchool' :options="$schools" required/>
        @error('school') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>
</div>
