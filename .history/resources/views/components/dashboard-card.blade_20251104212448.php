@props(['color', 'icon', 'title', 'value'])

<div class="bg-white rounded-lg shadow p-4 border-l-4 border-{{ $color }}-500">
    <div class="flex items-center">
        <div class="p-2 bg-{{ $color }}-100 rounded-lg text-xl">{{ $icon }}</div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>
