<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="isolate bg-white px-6 py-10 sm:py-10 lg:px-8">
                <x-form-heading heading="ANNUAL TEACHER TRANSFER - 2025" subheading="වාර්ෂික ගුරු මාරු සඳහා අයදුම් කිරීමට බලාපොරොත්තුවෙන් සිටින්න." />
                <x-form-heading heading="" subheading="Expect to apply for the annual teacher transfer." />
                <x-form-success message="{{ session('success') }}" />

            </div>
        </div>
    </div>
    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
