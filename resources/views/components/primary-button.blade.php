<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-full border border-transparent bg-cyan-400 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-950 transition ease-in-out duration-150 hover:bg-cyan-300 focus:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
