@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100 shadow-sm focus:border-cyan-400 focus:ring-cyan-400']) }}>
