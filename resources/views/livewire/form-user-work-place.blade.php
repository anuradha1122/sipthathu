<div class="mt-4">
    <x-form-input-label for="workPlace" value="Select workPlace" />
        <select
            id="workPlace"
            name="workPlace"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        >
            <option value="">Choose a work place...</option>
            @foreach($workPlaces as $workPlace)
                <option value="{{ $workPlace->id }}">{{ $workPlace->name }}</option>
            @endforeach
        </select>
        @error('workPlace') <span class="text-red-500">{{ $message }}</span> @enderror
</div>
