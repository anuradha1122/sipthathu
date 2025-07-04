<div  class="">
    <div class="sm:col-span-3">
        <x-form-input-label for="type" value="1. Type of the Transfer මාරුවීම් වර්ගය" />
        <x-form-list-input-field id="type" name="type" wire:model.live="selectedType" :options="$transferTypes" />
        @error('type') <span  class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <span class="sm:col-span-3">2. Reason for the transfer ස්ථානමාරුව ඉල්ලුම් කිරීමට හේතු</span>
    <x-form-list-input-section size="sm:col-span-3" name="reason" id="reason" :options="$transferReasons" label="" />
    @if($typeId == 1 OR $typeId == 2)
        <div class="col-span-6">
            <span class="sm:col-span-6">3. Expected Schools to be Transferred ස්ථාන මාරුවීම් අපේක්ෂිත පාසල් :</span>
            <x-form-input-label for="school1" value="School1" />
            <x-form-list-input-field name="school1" id="school1" :options="$schoolList" required/>
            @error('school1') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school2" value="School2" />
            <x-form-list-input-field name="school2" id="school2" :options="$schoolList" required/>
            @error('school2') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school3" value="School3" />
            <x-form-list-input-field name="school3" id="school3" :options="$schoolList" required/>
            @error('school3') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school4" value="School4" />
            <x-form-list-input-field name="school4" id="school4" :options="$schoolList" required/>
            @error('school4') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school5" value="School5" />
            <x-form-list-input-field name="school5" id="school5" :options="$schoolList" required/>
            @error('school5') <span  class="text-red-500">{{ $message }}</span> @enderror
        </div>
    @elseif ($typeId == 3)
        <div class="col-span-6">
            <span class="sm:col-span-6">3. Expected Schools to be Transferred ස්ථාන මාරුවීම් අපේක්ෂිත පාසල් :</span>
            <x-form-input-label for="school1" value="School1" />
            <x-form-list-input-field name="school1" id="school1" :options="$schoolList" required/>
            @error('school1') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school2" value="School2" />
            <x-form-list-input-field name="school2" id="school2" :options="$schoolList" required/>
            @error('school2') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school3" value="School3" />
            <x-form-list-input-field name="school3" id="school3" :options="$schoolList" required/>
            @error('school3') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school4" value="School4" />
            <x-form-list-input-field name="school4" id="school4" :options="$schoolList" required/>
            @error('school4') <span  class="text-red-500">{{ $message }}</span> @enderror
            <x-form-input-label for="school5" value="School5" />
            <x-form-list-input-field name="school5" id="school5" :options="$schoolList" required/>
            @error('school5') <span  class="text-red-500">{{ $message }}</span> @enderror
        </div>
    @endif
</div>
