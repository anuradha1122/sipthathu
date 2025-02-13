<div class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-2">
    <!-- Province Selection -->
    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="province" value="Select Province" />
        <x-form-list-input-field
            id="province"
            name="province"
            :options="$provinces"
            wire:model.live="selectedProvince"
            autofocus
        />
    </div>

    <!-- District Selection -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="district" value="Select District" />
        <x-form-list-input-field
            id="district"
            name="district"
            :options="$districts"
            wire:model.live="selectedDistrict"
        />
    </div>


    <!-- DS Division Selection -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="ds-division" value="Select DS Division" />
        <x-form-list-input-field
            id="ds-division"
            name="ds_division"
            :options="$dsDivisions"
            wire:model.live="selectedDsDivision"
        />
    </div>


    <!-- GN Division Selection -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="gn-division" value="Select GN Division" />
        <x-form-list-input-field
            id="gnDivision"
            name="gnDivision"
            :options="$gnDivisions"
        />
    </div>

</div>
