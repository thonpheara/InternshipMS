@props(['status'])

@php
    $normalized = strtolower(trim((string)$status));

    $config = match($normalized) {
        'approved', 'accepted', 'eligible', 'active', 'verified', 'completed' => [
            'classes' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80 ring-emerald-600/10',
            'dot' => 'bg-emerald-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'pending', 'pending_approval', 'under_review', 'submitted' => [
            'classes' => 'bg-amber-50 text-amber-700 border-amber-200/80 ring-amber-600/10',
            'dot' => 'bg-amber-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'shortlisted', 'interviewed' => [
            'classes' => 'bg-indigo-50 text-indigo-700 border-indigo-200/80 ring-indigo-600/10',
            'dot' => 'bg-indigo-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'rejected', 'ineligible', 'terminated' => [
            'classes' => 'bg-rose-50 text-rose-700 border-rose-200/80 ring-rose-600/10',
            'dot' => 'bg-rose-500',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
        'revision_requested' => [
            'classes' => 'bg-purple-50 text-purple-700 border-purple-200/80 ring-purple-600/10',
            'dot' => 'bg-purple-500',
            'label' => 'Revision Needed',
        ],
        default => [
            'classes' => 'bg-slate-100 text-slate-700 border-slate-200/80 ring-slate-600/10',
            'dot' => 'bg-slate-400',
            'label' => ucfirst(str_replace('_', ' ', $normalized)),
        ],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border ring-1 ring-inset {{ $config['classes'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>
