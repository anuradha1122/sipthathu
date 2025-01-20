<div>
    <!-- Province Selection -->
    <div class="mb-4">
        <x-form-input-label for="province" value="Grama Niladhari Division" />
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
    @if ($districts)
        <div class="mb-4">
            <x-form-input-label for="district" value="Select District" />
            <x-form-list-input-field 
                id="district" 
                name="district" 
                :options="$districts" 
                wire:model.live="selectedDistrict" 
            />
        </div>
    @endif

    <!-- DS Division Selection -->
    @if ($dsDivisions)
        <div class="mb-4">
            <x-form-input-label for="ds-division" value="Select DS Division" />
            <x-form-list-input-field 
                id="ds-division" 
                name="ds_division" 
                :options="$dsDivisions" 
                wire:model.live="selectedDsDivision" 
            />
        </div>
    @endif

    <!-- GN Division Selection -->
    @if ($gnDivisions)
        <div class="mb-4">
            <x-form-input-label for="gn-division" value="Select GN Division" />
            <x-form-list-input-field 
                id="gnDivision" 
                name="gnDivision" 
                :options="$gnDivisions" 
            />
        </div>
    @endif
</div>
