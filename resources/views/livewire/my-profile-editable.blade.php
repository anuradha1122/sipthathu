<dl>
    <div class="relative flex flex-col w-full h-full text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">
        <div class="p-6 px-0 overflow-scroll">
            <table class="w-full mt-4 text-left table-auto min-w-max">
                <thead>
                    <tr>
                        <x-table-heading heading="Heading" />
                        <x-table-heading heading="Value" />
                        <x-table-heading heading="Status" />
                        <x-table-heading heading="Action" />
                    </tr>
                </thead>
                <tbody>

                    <x-profile-data

                        heading="Name"
                        :values="[
                            'name' => $user->name,
                            'nameWithInitials' => $user->nameWithInitials
                        ]"
                        d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                        link="profile.myprofileedit"
                        :params="['category' => 'name']"
                        action="0"
                    />

                    <x-profile-data
                        heading="Contact Info"
                        :values="[
                            'Permenent Address' => [
                                $user->permAddressLine1,
                                $user->permAddressLine2,
                                $user->permAddressLine3,
                            ],
                            'Temporary Address' => [
                                $user->tempAddressLine1,
                                $user->tempAddressLine2,
                                $user->tempAddressLine3,
                            ],
                            'Mobile' => $user->mobile1,
                            'Whatsapp' => $user->mobile2,
                            'Email' => $user->email,
                        ]"
                        d="M12 2C8.13 2 5 5.13 5 8c0 2.47 1.73 5.04 4.14 7.3L12 22l2.86-6.7C17.27 13.04 19 10.47 19 8c0-2.87-3.13-6-7-6z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'contact']"
                        action="0"
                    />


                    <x-profile-data
                        heading="Personal Info"
                        :values="[
                            'Race' => $user->race,
                            'Religion' => $user->religion,
                            'Civil Status' => $user->civilStatus,
                            'Birthday' => $user->birthDay,
                            'Gender' => $user->gender,
                        ]"
                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 2c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8zm0 1.75a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM12 9a1 1 0 110-2 1 1 0 010 2zm0 4.75c-2.347 0-4.444 1.003-5.716 2.574-.24.292-.051.676.351.676h10.73c.402 0 .591-.384.351-.676C16.444 14.753 14.347 13.75 12 13.75z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'personal']"
                        action="0"
                    />


                    <x-profile-data
                        heading="Location Info"
                        :values="[
                            'Education Division' => $user->educationDivision,
                            'GN Division' => $user->gnDivision,
                            'DS Division' => $user->dsDivision,
                            'District' => $user->district,
                            'Province' => $user->province,
                        ]"
                        d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'location']"
                        action="0"
                    />

                    <x-profile-data
                        heading="Service Info"
                        :values="array_filter([
                            'Previous Services' => $previousServices,
                            'Previous Services Ranks' => $previousServiceRanks,
                            'Current Service' => $currentService,
                            'Current Service Ranks' => $currentServiceRanks,
                        ], function($value) {
                            return !empty($value); // Only include non-empty arrays
                        })"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'service']"
                        action="0"
                    />


                    {{-- <x-profile-data
                        heading="Appointment Info"
                        :values="array_filter([
                            'Previous Appointments' => $previousAppointments,
                            'Previous Attched Appointments' => $previousAttachAppointments,
                            'Current Appointments' => $currentAppointments,
                            'Current Attached Appointment' => $currentAttachAppointment,
                        ], function($value) {
                            return !empty($value); // Only include non-empty arrays
                        })"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'appointment']"
                        action="0"
                    /> --}}

                    <x-profile-data
                        heading="Rank Info"
                        :values="array_filter([
                            'Previous Service Ranks' => $previousServiceRanks,
                            'Current Service Ranks' => $currentServiceRanks,
                        ], function($value) {
                            return !empty($value); // Only include non-empty arrays
                        })"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'rank']"
                        action="0"
                    />

                    <x-profile-data
                        heading="Education Info"
                        :values="[
                            'Education Qualifications' => $educationQualifications,
                            'Professional Qualification' => $professionalQualifications,
                        ]"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'qualification']"
                        action="0"
                    />

                    <x-profile-data
                        heading="Familly Info"
                        :values="[
                            'Family Members' => $family,
                        ]"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'family']"
                        action="0"
                    />

                    <x-profile-data
                        heading="Login Info"
                        :values="[
                            'User Name' => $user->nic,
                            'Password' => '******',
                        ]"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                        link="profile.myprofileedit"
                        :params="['id' => $user->cryptedId, 'category' => 'login']"
                        action="1"
                    />

                </tbody>
            </table>
        </div>
    </div>
</dl>
