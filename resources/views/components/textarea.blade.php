<!-- @props(['value' => '', 'disabled' => false, 'rows' => 4])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 
    focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm', ]) }}>
</textarea> -->

@props([
'id' => '',
'name',
'rows' => 4,
'value' => '',
'required' => false,
'class' => '',
])

<textarea
    id="{{ $id ?? $name }}"
    name="{{ $name }}"
    rows="{{ $rows }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm' . $class]) }}>{{ old($name, $value) }}</textarea>