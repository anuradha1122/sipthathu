<div>
    <x-form-input-label for='email' value='E-mail (optional)' />
    <x-form-text-input-field id="email" name="email" wire:model.live='email' />
    @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
</div>
