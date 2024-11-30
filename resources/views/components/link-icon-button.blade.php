<a href="{{ route($link) }}" class="align-middle select-none font-sans font-bold text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-1 px-1 rounded-lg bg-gradient-to-tr {{ $background }} {{ $textcolor }} shadow-md shadow-red-900/10 hover:shadow-lg hover:shadow-red-900/20 active:opacity-[0.85] flex items-center gap-3">
    {{ $text }}
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
    </svg>
</a>