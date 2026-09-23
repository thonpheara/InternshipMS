@props(['status'])

@php
    $normalized = strtolower(trim((string)$status));

    $config = match($normalized) {
        'approved', 'accepted', 'eligible', 'active', 'verified', 'completed' => [
            'classes' => 'bg-emerald-50 text-emerald-800 border-emerald-200 ring-emerald-500/10',
            'dot' => 'bg-[#059669]',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'pending', 'pending_approval', 'under_review', 'submitted' => [
            'classes' => 'bg-amber-50 text-amber-800 border-amber-200 ring-amber-500/10',
            'dot' => 'bg-amber-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'shortlisted', 'interviewed' => [
            'classes' => 'bg-[#D1FAE5] text-[#065F46] border-[#A7F3D0] ring-emerald-500/10',
            'dot' => 'bg-[#059669]',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'rejected', 'ineligible', 'terminated' => [
            'classes' => 'bg-rose-50 text-rose-800 border-rose-200 ring-rose-500/10',
            'dot' => 'bg-rose-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'revision_requested' => [
            'classes' => 'bg-purple-50 text-purple-800 border-purple-200 ring-purple-500/10',
            'dot' => 'bg-purple-500',
            'label' => 'Revision Needed',
        ],
        default => [
            'classes' => 'bg-gray-100 text-gray-700 border-gray-200 ring-gray-500/10',
            'dot' => 'bg-gray-400',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ring-1 ring-inset {{ $config['classes'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>
