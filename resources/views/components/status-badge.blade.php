@php
    $config = [
        'open' => ['bg-blue-100 text-blue-700', 'Open'],
        'in_progress' => ['bg-yellow-100 text-yellow-700', 'In Progress'],
        'on_hold' => ['bg-gray-100 text-gray-600', 'On Hold'],
        'resolved' => ['bg-green-100 text-green-700', 'Resolved'],
        'closed' => ['bg-gray-800 text-white', 'Closed'],
    ];
    [$class, $label] = $config[$status] ?? ['bg-gray-100 text-gray-600', ucfirst($status)];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
    {{ $label }}</span>
