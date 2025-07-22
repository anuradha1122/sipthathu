<div>
    <span class="sm:col-span-6">2. Expected Schools to be Transferred ස්ථාන මාරුවීම් අපේක්ෂිත පාසල් :</span>

    <x-form-input-label for="school1" value="School1" />
    <x-form-list-input-field name="province1" id="province1" wire:model.live="selectedProvince1" :options="$provinceList" required/>
    @error('province1') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-list-input-field name="school1" id="school1" :options="$provinceSchoolList1" required/>
    @error('school1') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-number-input-section size="sm:col-span-6" name="distance1" id="distance1" label="Distance from permanent residence to the requiested school ස්ථිර පදිංචියේ සිට පාසලට ඇති දුර (km):" value="{{ old('distance1') }}" />
    </br>

    <x-form-input-label for="school2" value="School2" />
    <x-form-list-input-field name="province2" id="province2" wire:model.live="selectedProvince2" :options="$provinceList" required/>
    <x-form-list-input-field name="school2" id="school2" :options="$provinceSchoolList2" required/>
    @error('school2') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-number-input-section size="sm:col-span-6" name="distance2" id="distance2" label="Distance from permanent residence to the requiested school ස්ථිර පදිංචියේ සිට පාසලට ඇති දුර (km):" value="{{ old('distance2') }}" />
    </br>

    <x-form-input-label for="school3" value="School3" />
    <x-form-list-input-field name="province3" id="province3" wire:model.live="selectedProvince3" :options="$provinceList" required/>
    <x-form-list-input-field name="school3" id="school3" :options="$provinceSchoolList3" required/>
    @error('school3') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-number-input-section size="sm:col-span-6" name="distance3" id="distance3" label="Distance from permanent residence to the requiested school ස්ථිර පදිංචියේ සිට පාසලට ඇති දුර (km):" value="{{ old('distance3') }}" />
    </br>

    <x-form-input-label for="school4" value="School4" />
    <x-form-list-input-field name="province4" id="province4" wire:model.live="selectedProvince4" :options="$provinceList" required/>
    <x-form-list-input-field name="school4" id="school4" :options="$provinceSchoolList4" required/>
    @error('school4') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-number-input-section size="sm:col-span-6" name="distance4" id="distance4" label="Distance from permanent residence to the requiested school ස්ථිර පදිංචියේ සිට පාසලට ඇති දුර (km):" value="{{ old('distance4') }}" />
    </br>

    <x-form-input-label for="school5" value="School5" />
    <x-form-list-input-field name="province5" id="province5" wire:model.live="selectedProvince5" :options="$provinceList" required/>
    <x-form-list-input-field name="school5" id="school5" :options="$provinceSchoolList5" required/>
    @error('school5') <span  class="text-red-500">{{ $message }}</span> @enderror
    <x-form-number-input-section size="sm:col-span-6" name="distance5" id="distance5" label="Distance from permanent residence to the requiested school ස්ථිර පදිංචියේ සිට පාසලට ඇති දුර (km):" value="{{ old('distance5') }}" />

</div>
