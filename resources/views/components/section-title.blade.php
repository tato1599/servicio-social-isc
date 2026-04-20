<div class="md:col-span-1 flex justify-between">
    <div class="px-4 sm:px-0">
        <h3 class="text-lg font-bold text-gray-900 leading-tight tracking-tight">{{ $title }}</h3>

        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            {{ $description }}
        </p>
    </div>

    <div class="px-4 sm:px-0">
        {{ $aside ?? '' }}
    </div>
</div>
