<div class="w-full grid grid-cols-1 gap-y-1 sm:grid-cols-3">
    <!-- Province Dropdown -->
    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="province" value="Select Province(For Residential Edu Division)" />
        <x-form-list-input-field id="province" name="province" :options="$provinces" wire:model.live="selectedProvince" autofocus />
    </div>

    <!-- Zone Dropdown -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="district" value="Select District(For Residential Edu Division)" />
        <x-form-list-input-field id="district" name="district" :options="$districts" wire:model.live="selectedDistrict" autofocus />
    </div>


    <!-- Division Dropdown -->

    <div class="mb-4 sm:col-span-1 px-1 py-1">
        <x-form-input-label for="division" value="Residential Education Division" />
        <x-form-list-input-field id="division" name="division" :options="$divisions"  autofocus />
    </div>

</div>

