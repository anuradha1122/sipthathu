<div class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-3">
    <!-- Province Selection -->
    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="birthProvince" value="" />
        <x-form-input-label for="birthProvince" value="Birth Province" />
        <x-form-list-input-field
            id="birthProvince"
            name="birthProvince"
            :options="$provinces"
            wire:model.live="selectedProvince"
            autofocus
        />
    </div>

    <!-- District Selection -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="birthDistrict" value="Birth District" />
        <x-form-list-input-field
            id="birthDistrict"
            name="birthDistrict"
            :options="$districts"
            wire:model.live="selectedDistrict"
        />
    </div>


    <!-- DS Division Selection -->

        <div class="mb-4 sm:col-span-1 px-1 py-1">
            <x-form-input-label for="birthDsDivision" value="Birth DS Division" />
            <x-form-list-input-field
                id="birthDsDivision"
                name="birthDsDivision"
                :options="$dsDivisions"
                wire:model.live="selectedDsDivision"
            />
        </div>

</div>
