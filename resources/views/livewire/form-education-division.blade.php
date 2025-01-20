<div>
    <!-- Province Dropdown -->
    <div class="mb-4">
        <x-form-input-label for="province" value="Residential Education Division" />
        <x-form-input-label for="province" value="Select Province" />
        <x-form-list-input-field id="province" name="province" :options="$provinces" wire:model.live="selectedProvince" autofocus />
    </div>

    <!-- Zone Dropdown -->
    @if (!empty($districts))
        <div class="mb-4">
            <x-form-input-label for="district" value="Select District" />
            <x-form-list-input-field id="district" name="district" :options="$districts" wire:model.live="selectedDistrict" autofocus />
        </div>
    @endif

    <!-- Division Dropdown -->
    @if (!empty($divisions))
        <div class="mb-4">
            <x-form-input-label for="division" value="Select Division" />
            <x-form-list-input-field id="division" name="division" :options="$divisions"  autofocus />
        </div>
    @endif
</div>

