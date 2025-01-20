<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :list="$option" />
            <div class="isolate bg-white px-6 py-5 sm:py-5 lg:px-4">
                <x-form-heading heading="STUDENT REGISTRATION" subheading="Student Registration Form" />
                <x-form-success message="{{ session('success') }}" />
                <form method="POST" action="{{ route('student.register') }}" class="mx-auto mt-8 max-w-xl sm:mt-8" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-x-3 gap-y-3 sm:grid-cols-6">
                        
                        <x-form-text-input-section size="sm:col-span-6" name="name" id="name" label="Full Name" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine1" id="addressLine1" label="Address Line 1" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine2" id="addressLine2" label="Address Line 2" />
                        <x-form-text-input-section size="sm:col-span-2" name="addressLine3" id="addressLine3" label="Address Line 3" />
                        <x-form-list-input-section size="sm:col-span-2" name="class" id="class" :options="$classes" label="Class" />
                        <span class="sm:col-span-2">@livewire('formStudentNic')</span>
                        <span class="sm:col-span-2">@livewire('formStudentEmail')</span>
                        <x-form-text-input-section size="sm:col-span-2" name="mobile" id="mobile" label="Mobile (optional)" />
                        <x-form-list-input-section size="sm:col-span-2" name="gender" id="gender" :options="$genders" label="Gender" />
                        <x-form-list-input-section size="sm:col-span-2" name="race" id="race" :options="$races" label="Race" />
                        <x-form-list-input-section size="sm:col-span-2" name="religion" id="religion" :options="$religions" label="Religion" />
                        <x-form-list-input-section size="sm:col-span-2" name="bloodGroup" id="bloodGroup" :options="$bloodGroups" label="Blood Group (optional)" />
                        {{-- <x-form-text-input-section size="sm:col-span-2" name="nic" id="nic" label="NIC" /> --}}
                        
                        <x-form-date-input-section size="sm:col-span-2" name="birthDay" id="birthDay" label="Birth Day" />
                        <x-form-date-input-section size="sm:col-span-2" name="registerDate" id="registerDate" label="Registered Date" />
                        <x-form-text-input-section size="sm:col-span-6" name="guardianName" id="guardianName" label="Guardian Full Name" />
                        <x-form-list-input-section size="sm:col-span-2" name="guardianRelationship" id="guardianRelationship" :options="$guardianRelationships" label="Guardian Relationship" />
                        <x-form-text-input-section size="sm:col-span-2" name="Guardian Email" id="guardianEmail" label="guardianEmail (optional)" />
                        <x-form-text-input-section size="sm:col-span-2" name="guardianMobile" id="guardianMobile" label="Guardian Mobile" />
                        <span class="sm:col-span-4">@livewire('formStudentProfilePicture')</span>
                        <span class="sm:col-span-4">@livewire('formEducationDivision') </span>
                        <span class="sm:col-span-4">@livewire('formGnDivision')</span>
                    </div>
                    <div class="mt-10">
                        <x-form-button-primary size="" text="Register" modelBinding=""/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>