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


                    <x-profile-data
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
                    />

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

                </tbody>
            </table>
        </div>
    </div>
</dl>
