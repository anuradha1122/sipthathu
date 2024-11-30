<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :list="$option" />
            <div class="isolate bg-white px-6 py-10 sm:py-10 lg:px-8">
                <x-form-heading heading="TEACHER REGISTRATION" subheading="Teacher Registration Form" />
                <form method="POST" action="{{ route('teacher.register') }}" class="mx-auto mt-8 max-w-xl sm:mt-8" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                        {{-- @livewire('library-book-search') --}}
                        
                        {{-- @error('book') <span class="text-red-500">{{ $message }}</span> @enderror --}}
                        
                        <x-form-text-input-section size="sm:col-span-2" name="name" id="name" label="Full Name" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine1" id="addressLine1" label="Address Line 1" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine2" id="addressLine2" label="Address Line 2" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine3" id="addressLine3" label="Address Line 3" />
                        <x-form-text-input-section size="sm:col-span-1" name="mobile1" id="mobile1" label="Mobile" />
                        <x-form-text-input-section size="sm:col-span-1" name="mobile2" id="mobile2" label="Whatsapp" />
                        <x-form-text-input-section size="sm:col-span-2" name="email" id="email" label="Email" />
                        <x-form-text-input-section size="sm:col-span-1" name="nic" id="nic" label="NIC" />
                        <x-form-list-input-section size="sm:col-span-1" name="race" id="race" :options="$races" label="Race" />
                        <x-form-list-input-section size="sm:col-span-1" name="religion" id="religion" :options="$religions" label="Religion" />
                        <x-form-list-input-section size="sm:col-span-1" name="civilStatus" id="civilStatus" :options="$civilStatuses" label="Civil Status" />
                        <x-form-date-input-section size="sm:col-span-1" name="serviceDate" id="serviceDate" label="Service Appointed Date" />
                        {{-- <x-form-list-input-section size="" name="condition" id="condition" :options="$conditions" label="Condition" /> --}}
                        <x-form-text-input-section size="sm:col-span-2" name="description" id="description" label="Description" />
                    </div>
                    <div class="mt-10">
                        <x-form-button-primary size="" text="Register" modelBinding=""/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>