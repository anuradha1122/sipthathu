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
                            'name' => $student->name,
                            'nameWithInitials' => $student->nameWithInitials
                        ]"
                        d="M12 12c2.28 0 4.5-.5 6-1.5C16.5 8.5 14.28 7 12 7c-2.28 0-4.5.5-6 1.5C7.5 11.5 9.72 12 12 12zM6 13c1.4-.8 3.05-1.3 6-1.3s4.6.5 6 1.3c2.56 1.45 4 3.49 4 5.5v1c0 .55-.45 1-1 1H2c-.55 0-1-.45-1-1v-1c0-2.01 1.44-4.05 4-5.5z"
                    />

                    <x-profile-data
                        heading="Contact Info"
                        :values="[
                            'Address' => [
                                $student->addressLine1,
                                $student->addressLine2,
                                $student->addressLine3,
                            ],
                            'Mobile' => $student->mobile,
                            'Email' => $student->email,
                        ]"
                        d="M12 2C8.13 2 5 5.13 5 8c0 2.47 1.73 5.04 4.14 7.3L12 22l2.86-6.7C17.27 13.04 19 10.47 19 8c0-2.87-3.13-6-7-6z"
                    />

                    <x-profile-data
                        heading="Guardian Info"
                        :values="[
                            'Name' => $student->guardianName,
                            'Relationship' => $student->guardianRelationship,
                            'Mobile' => $student->guardianMobile,
                            'Email' => $student->guardianEmail,
                        ]"
                        d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 2c2.623 0 5.023 1.002 6.828 2.636a3 3 0 00-3.535-.523A6.992 6.992 0 0012 7c-1.631 0-3.13-.523-4.293-1.415a3 3 0 00-3.535.523A9.956 9.956 0 0112 4zm-7 8a8 8 0 1116 0c0 1.336-.33 2.593-.915 3.682A4.998 4.998 0 0012 13a4.998 4.998 0 00-8.085 2.682A7.963 7.963 0 015 12zm2 6c.419 0 .828-.043 1.227-.125C7.806 17.318 7 16.105 7 14.75c0-1.519.884-2.843 2.227-3.409C9.085 11.597 9 10.813 9 10c0-2.485 2.018-4.5 4.5-4.5S18 7.515 18 10c0 .813-.085 1.597-.227 2.341C19.116 11.907 20 13.231 20 14.75c0 1.355-.806 2.568-2.227 3.125.399.082.808.125 1.227.125a6.962 6.962 0 001.85-.257 8.01 8.01 0 01-3.528 2.258 8.021 8.021 0 01-5.242 0 8.01 8.01 0 01-3.528-2.258A6.962 6.962 0 007 18z"
                    />

                    <x-profile-data
                        heading="Personal Info"
                        :values="[
                            'Race' => $student->race,
                            'Religion' => $student->religion,
                            'Blood Group' => $student->bloodGroup,
                            'Illness' => $student->illness,
                            'Birthday' => $student->birthDay,
                            'Birth Certificate No' => $student->birthCertificate,
                            'Birth DS Division' => $student->birthDsDivision,
                            'Gender' => $student->gender,
                        ]"
                        d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 2c4.411 0 8 3.589 8 8s-3.589 8-8 8-8-3.589-8-8 3.589-8 8-8zm0 1.75a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM12 9a1 1 0 110-2 1 1 0 010 2zm0 4.75c-2.347 0-4.444 1.003-5.716 2.574-.24.292-.051.676.351.676h10.73c.402 0 .591-.384.351-.676C16.444 14.753 14.347 13.75 12 13.75z"
                    />


                    <x-profile-data
                        heading="Location Info"
                        :values="[
                            'Education Division' => $student->educationDivision,
                            'GN Division' => $student->gnDivision,
                            'DS Division' => $student->dsDivision,
                            'District' => $student->district,
                            'Province' => $student->province,
                        ]"
                        d="M12 2a7 7 0 00-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 00-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"
                    />

                    <x-profile-data
                        heading="School Info"
                        :values="[
                            'School' => $student->school,
                            'Class' => $student->class,
                        ]"
                        d="M11.7 2.3a1 1 0 011.6 0l9 7a1 1 0 01-.3 1.7l-9 3a1 1 0 01-.7 0l-9-3a1 1 0 01-.3-1.7l9-7zM12 5.2L5.2 10 12 12.8 18.8 10 12 5.2zM4 12.9a1 1 0 011.3-.6L12 15l6.7-2.7a1 1 0 111 .8L12 17l-7.7-3.1a1 1 0 01-.6-1zm16.7 3.3a1 1 0 00-1.4-.4l-5.3 3.2a3 3 0 01-3 0l-5.3-3.2a1 1 0 10-1 1.8l5.3 3.2a5 5 0 005 0l5.3-3.2a1 1 0 00.4-1.4z"
                    />



                </tbody>
            </table>
        </div>
    </div>
</dl>
