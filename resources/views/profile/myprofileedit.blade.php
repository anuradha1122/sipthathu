<x-app-layout>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-breadcrumb :list="$option" />
            <div class="isolate bg-white px-6 py-10 sm:py-10 lg:px-8">
                <x-profile-heading heading="{{ $user->nameWithInitials }}" subHeading="{{ $user->nic }}" image="{{ $user->profilePicture }}" />

                <x-form-success message="{{ session('success') }}" />
                <form method="POST" action="{{ route('teacher.register') }}" class="mx-auto mt-8 max-w-xl sm:mt-8" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-6 gap-x-8 gap-y-6 sm:grid-cols-6">
                        @if ($category == 'name')
                            <x-profile-edit-data

                                heading="Name"
                                :values="[
                                    'name' => $user->name,
                                    'nameWithInitials' => $user->nameWithInitials
                                ]"
                                d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                            />
                            <x-form-text-input-section size="sm:col-span-6" name="name" id="name" label="Full Name" value="{{ old('name') }}" />
                        @endif

                        @if ($category == 'contact')

                            <x-profile-edit-data

                                heading="Contacts"
                                :values="[
                                    'Permenent Address' => $contact_infos->permAddressLine1.', '.$contact_infos->permAddressLine2.', '.$contact_infos->permAddressLine3,
                                    'Temporary Address' => $contact_infos->tempAddressLine1.', '.$contact_infos->tempAddressLine2.', '.$contact_infos->tempAddressLine3,
                                    'Phone' => $contact_infos->mobile1,
                                    'Whatsapp' =>  $contact_infos->mobile2,
                                    'Email' => $user->email,
                                ]"
                                d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                            />
                            <p class="sm:col-span-6">Permenent Address</p>
                            <hr class="sm:col-span-6">
                            <x-form-text-input-section size="sm:col-span-6" name="permAddressLine1" id="permAddressLine1" label="Permenent Address Line 1" value="{{ old('permAddressLine1') }}" />
                            <x-form-text-input-section size="sm:col-span-3" name="permAddressLine2" id="permAddressLine2" label="Permenent Address Line 2" value="{{ old('permAddressLine2') }}" />
                            <x-form-text-input-section size="sm:col-span-3" name="permAddressLine3" id="permAddressLine3" label="Permenent Address Line 3" value="{{ old('permAddressLine3') }}" />

                            <p class="sm:col-span-6">Residential Address</p>
                            <hr class="sm:col-span-6">
                            <x-form-text-input-section size="sm:col-span-6" name="tempAddressLine1" id="tempAddressLine1" label="Temporary Address Line 1" value="{{ old('tempAddressLine1') }}" />
                            <x-form-text-input-section size="sm:col-span-3" name="tempAddressLine2" id="tempAddressLine2" label="Temporary Address Line 2" value="{{ old('tempAddressLine2') }}" />
                            <x-form-text-input-section size="sm:col-span-3" name="tempAddressLine3" id="tempAddressLine3" label="Temporary Address Line 3" value="{{ old('tempAddressLine3') }}" />

                            <p class="sm:col-span-6">Telephone & E-mail</p>
                            <hr class="sm:col-span-6">

                            <x-form-text-input-section size="sm:col-span-3" name="mobile1" id="mobile1" label="Mobile" value="{{ old('mobile1') }}" />
                            <x-form-text-input-section size="sm:col-span-3" name="mobile2" id="mobile2" label="Whatsapp" value="{{ old('mobile2') }}" />
                            <x-form-text-input-section size="sm:col-span-6" name="email" id="email" label="E-mail" value="{{ old('email') }}" />

                        @endif

                        @if($category == 'personal')

                            <x-profile-edit-data

                                heading="Personal Info"
                                :values="[
                                    'Race' => $personal_infos->race,
                                    'Religion' => $personal_infos->religion,
                                    'Civil Status' => $personal_infos->civil_status,
                                    'Birth Date' => $personal_infos->birthDay,
                                    'Blood Group' => $personal_infos->blood_group,
                                    'Illness' => $personal_infos->illness,

                                ]"
                                d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                            />
                            <x-form-list-input-section size="sm:col-span-3" name="race" id="race" :options="$races" label="Race" />
                            <x-form-list-input-section size="sm:col-span-3" name="religion" id="religion" :options="$religions" label="Religion" />
                            <x-form-list-input-section size="sm:col-span-3" name="civilStatus" id="civilStatus" :options="$civilStatuses" label="Civil Status" />
                            <x-form-date-input-section size="sm:col-span-3" name="birthDay" id="birthDay" label="Birth Day" value="{{ old('birthDay') }}" />
                            <x-form-list-input-section size="sm:col-span-3" name="bloodGroup" id="bloodGroup" :options="$bloodGroups" label="Blood Group" />
                            <x-form-list-input-section size="sm:col-span-3" name="illness" id="illness" :options="$illnesses" label="Ilnesses" />
                        @endif

                        @if($category == 'location')
                            <x-profile-edit-data

                                heading="Location Info"
                                :values="[
                                    'Education Division' => $location_info_educations->division,
                                    'Education Zone' => $location_info_educations->zone,
                                    'Education Department' => $location_info_educations->department,
                                    'GN Division' => $location_info_positions->gn_division,
                                    'DS Division' => $location_info_positions->ds_division,
                                    'District' => $location_info_positions->district,
                                    'Province' => $location_info_positions->province,
                                ]"
                                d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                            />
                            <span class="sm:col-span-6">@livewire('formEducationDivision')</span>
                            <span class="sm:col-span-6">@livewire('formGnDivision')</span>
                        @endif

                        @if($category == 'service')
                            <p class="sm:col-span-6">New Service</p>
                            <hr class="sm:col-span-6">
                            <x-form-list-input-section size="sm:col-span-3" name="new_service" id="new_service" :options="$services" label="New Service" />
                            <x-form-date-input-section size="sm:col-span-3" name="startDay" id="startDay" label="Start Day" value="{{ old('startDay') }}" />
                            <p class="sm:col-span-6">Delete or Update Service</p>
                            <hr class="sm:col-span-6">
                            <x-form-list-input-section size="sm:col-span-3" name="current_service" id="current_service" :options="$current_services" label="Services" />
                            <x-form-date-input-section size="sm:col-span-3" name="startDay" id="startDay" label="Start Day" value="{{ old('startDay') }}" />
                            <label class="sm:col-span-3 flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    name="service_delete[]"
                                    value="your_value_here"
                                    class="text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                >
                                <span>Delete Service Record</span>
                            </label>

                        @endif

                        @if($category == 'appointment')
                            <p class="sm:col-span-6">Add New Appointment</p>
                            <hr class="sm:col-span-6">
                            <span class="sm:col-span-3">@livewire('formUserSchool')</span>
                            <x-form-date-input-section size="sm:col-span-3" name="newAppointmentStartDay" id="newAppointmentStartDay" label="Start Day" value="{{ old('newAppointmentStartDay') }}" />
                            <p class="sm:col-span-6">Add Previous Appointment</p>
                            <hr class="sm:col-span-6">
                            <span class="sm:col-span-6">@livewire('formProvinceSchool')</span>
                            <x-form-date-input-section size="sm:col-span-3" name="previousAppointmentStartDay" id="previousAppointmentStartDay" label="Start Day" value="{{ old('previousAppointmentStartDay') }}" />
                            <x-form-date-input-section size="sm:col-span-3" name="previousAppointmentEndDay" id="previousAppointmentEndDay" label="End Day" value="{{ old('previousAppointmentEndDay') }}" />

                            <p class="sm:col-span-6">Delete Appointment</p>
                            <hr class="sm:col-span-6">
                            <x-form-list-input-section size="sm:col-span-6" name="appointment_list" id="appointment_list" :options="$appointment_lists" label="Appointments" />
                        @endif

                        @if($category == 'qualification')
                            <x-form-list-input-section size="sm:col-span-3" name="educationQualification" id="educationQualification" :options="$educationQualifications" label="Highest Education Qualification" />
                            <x-form-date-input-section size="sm:col-span-3" name="eduEffectiveDay" id="eduEffectiveDay" label="Effective Day" value="{{ old('eduEffectiveDay') }}" />
                            <hr class="sm:col-span-6">
                            <x-form-list-input-section size="sm:col-span-3" name="Highest Professional Qualification" id="professionalQualification" :options="$professionalQualifications" label="Highest Professional Qualification" />
                            <x-form-date-input-section size="sm:col-span-3" name="profEffectiveDay" id="profEffectiveDay" label="Effective Day" value="{{ old('profEffectiveDay') }}" />
                        @endif

                        @if ($category == 'rank')
                            <x-form-list-input-section size="sm:col-span-3" name="rank" id="rank" :options="$ranks" label="Rank List" />
                            <x-form-date-input-section size="sm:col-span-3" name="rankedDate" id="rankedDate" label="Ranked Date" value="{{ old('rankedDate') }}" />
                            <label class="sm:col-span-3 flex items-center space-x-2">
                                <input
                                    type="checkbox"
                                    name="rank_delete[]"
                                    value="your_value_here"
                                    class="text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                >
                                <span>Delete Rank Record</span>
                            </label>
                        @endif

                        @if($category == 'family')
                            <x-form-list-input-section size="sm:col-span-3" name="rank" id="rank" :options="$ranks" label="Rank List" />
                        @endif

                    </div>
                    <div class="mt-10">
                        <x-form-button-primary size="" text="Submit" modelBinding=""/>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
