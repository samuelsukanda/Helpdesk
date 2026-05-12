@php
    $config = [
        'low' => ['bg-green-100 text-green-700', 'Low'],
        'medium' => ['bg-blue-100 text-blue-700', 'Medium'],
        'high' => ['bg-orange-100 text-orange-700', 'High'],
        'critical' => ['bg-red-100 text-red-700', 'Critical'],
    ];

    [$class, $label] = $config[$priority] ?? ['bg-gray-100 text-gray-600', ucfirst($priority)];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
    {{ $label }}
</span>
