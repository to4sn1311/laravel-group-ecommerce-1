@props(['name', 'options', 'selected' => null, 'class' => ''])

<select name="{{ $name }}" id="{{ $name }}" {{ $attributes->merge([
    'class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm' . ' ' . $class
]) }}>
    @foreach($options as $value => $label)
    <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
        {{ $label }}
    </option>
    @endforeach
</select>