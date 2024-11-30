<div class="{{ $size }}">
    <x-form-input-label :for="$name" :value="$label" />
    <x-form-text-input-field :id="$id" :name="$name" />
    @error($name) <span  class="text-red-500">{{ $message }}</span> @enderror
</div>