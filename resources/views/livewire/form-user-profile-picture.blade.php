<div class="mb-4">
    <x-form-input-label for="photo" value="Profile Picture" />
    <x-form-file-input-field id="photo" name="photo" wire:model.blur="photo" />
    @if ($photo)
        <div class="mt-2">
            <img class="h-20 w-20 flex-none  bg-gray-50" src="{{ $photoPreview }}" alt="Photo Preview">
        </div>
    @endif
    @error('photo') <span class="error">{{ $message }}</span> @enderror
</div>
