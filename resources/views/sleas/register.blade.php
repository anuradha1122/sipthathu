<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :list="$option" />
            <div class="isolate bg-white px-6 py-10 sm:py-10 lg:px-8">
                <x-form-heading heading="SLEAS REGISTRATION" subheading="SLEAS Registration Form" />
                <x-form-success message="{{ session('success') }}" />
                <form method="POST" action="{{ route('sleas.register') }}" class="mx-auto mt-8 max-w-xl sm:mt-8" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-6">
                        {{-- @livewire('library-book-search') --}}

                        {{-- @error('book') <span class="text-red-500">{{ $message }}</span> @enderror --}}

                        <x-form-text-input-section size="sm:col-span-6" name="name" id="name" label="Full Name" />
                        <x-form-text-input-section size="sm:col-span-6" name="addressLine1" id="addressLine1" label="Address Line 1" />
                        <x-form-text-input-section size="sm:col-span-3" name="addressLine2" id="addressLine2" label="Address Line 2" />
                        <x-form-text-input-section size="sm:col-span-3" name="addressLine3" id="addressLine3" label="Address Line 3" />
                        <span class="sm:col-span-3">@livewire('formUserNic')</span>
                        <x-form-text-input-section size="sm:col-span-3" name="mobile" id="mobile" label="Mobile" />
                        {{-- <x-form-text-input-section size="sm:col-span-1" name="nic" id="nic" label="NIC" /> --}}

                        <span class="sm:col-span-3">@livewire('formUserSchool')</span>

                        <x-form-date-input-section size="sm:col-span-3" name="birthDay" id="birthDay" label="Birth Day" />
                        <x-form-list-input-section size="sm:col-span-3" name="rank" id="rank" :options="$ranks" label="Service Appointment Rank" />
                        <x-form-date-input-section size="sm:col-span-3" name="serviceDate" id="serviceDate" label="Service Appointed Date" />
                        <span class="sm:col-span-4">@livewire('formUserProfilePicture')</span>
                    </div>
                    <div class="mt-10">
                        <x-form-button-primary size="" text="Register" modelBinding=""/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
