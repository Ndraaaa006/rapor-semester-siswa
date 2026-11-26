@props(['predicate' => null, 'finalized' => false])

<div class="flex items-center gap-2">
    @if ($predicate)
        <span class="inline-flex h-6 w-6 items-center justify-center rounded-md bg-emerald-50 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
            {{ $predicate }}
        </span>
    @else
        <span class="text-xs text-slate-400">-</span>
    @endif

    @if ($finalized)
        <span class="inline-flex rounded-full bg-slate-900 px-2.5 py-0.5 text-[11px] font-semibold text-white">Final</span>
    @else
        <span class="inline-flex rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">Draft</span>
    @endif
</div>
