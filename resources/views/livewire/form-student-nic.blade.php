<div>
    <x-form-input-label for='nic' value='NIC (optional)' />
    <x-form-text-input-field id="nic" name="nic" wire:model.live='nic' />
    @error('nic') <span class="text-red-500">{{ $message }}</span> @enderror
</div>
