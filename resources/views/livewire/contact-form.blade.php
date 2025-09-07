<div>
    <div>
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            <p>{{ session('message') }}</p>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input wire:model.live="name" id="name" name="name" type="text"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <input wire:model.live="email" id="email" name="email" type="email"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
            <input wire:model.live="subject" id="subject" name="subject" type="text"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Message -->
        <div>
            <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
            <textarea wire:model.live="message" id="message" name="message" rows="5"
                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit"
                class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200 gap-2">
                <span>Send Message</span>
                <i data-lucide="send-horizontal"></i>
            </button>
        </div>
    </form>
</div>
</div>
