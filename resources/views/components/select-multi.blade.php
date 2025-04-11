@props([
'id' => 'select-multi',
'label' => '',
'name',
'options' => [],
'selected' => [],
'placeholder' => 'Chọn danh mục',
'oldSelected' => true,
])

<div class="mb-4">
    @if ($label)
    <x-input-label :for="$id" :value="$label" />
    @endif

    <select
        id="{{ $id }}"
        name="{{ $name }}[]"
        multiple
        {{ $attributes->merge([
                'class' => 'select2 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md' 
            ]) }}>
        @foreach ($options as $option)
        <option value="{{ $option->id }}" {{$oldSelected && in_array($option->id, old($name, $selected)) ? 'selected' : '' }}>
            {{ $option->name }}
        </option>
        @endforeach
    </select>

    <x-input-error :messages="$errors->get($name)" class="mt-2" />
</div>