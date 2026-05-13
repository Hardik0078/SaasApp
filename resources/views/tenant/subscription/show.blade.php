<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-white">Billing & Subscription</h2>
    </x-slot>

    <div class="grid gap-8 lg:grid-cols-[1fr,0.8fr]">
        <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
            <h3 class="text-lg font-semibold text-white">Current subscription</h3>
            <p class="mt-3 text-slate-300">Plan: {{ $subscription?->plan?->name ?? 'Trial' }}</p>
            <p class="text-slate-300">Status: {{ $subscription?->status ?? tenant()->status }}</p>
            <p class="text-slate-300">Ends: {{ optional($subscription?->ends_at)->toDateString() ?? optional(tenant()->trial_ends_at)->toDateString() }}</p>
            <p class="text-slate-300">Billing cadence: {{ strtoupper($subscription?->billing_period ?? 'trial') }}</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-6">
            <form method="POST" action="{{ route('tenant.subscription.update', tenant()) }}" class="space-y-4">
                @csrf
                <h3 class="text-lg font-semibold text-white">Manual subscription activation</h3>
                <select name="plan_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->currency }} {{ $plan->price }}</option>
                    @endforeach
                </select>
                <select name="billing_period" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
                <x-primary-button>Activate plan</x-primary-button>
            </form>

            <form method="POST" action="{{ route('tenant.subscription.checkout', tenant()) }}" class="space-y-4 border-t border-white/10 pt-6">
                @csrf
                <h3 class="text-lg font-semibold text-white">Stripe checkout</h3>
                <p class="text-sm text-slate-400">Uses configured Stripe credentials and the selected plan's `stripe_price_id`.</p>
                <select name="plan_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->currency }} {{ $plan->price }}</option>
                    @endforeach
                </select>
                <select name="billing_period" class="w-full rounded-xl border border-white/10 bg-slate-900 text-slate-100">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
               <x-primary-button :disabled="!$stripeConfigured">
    Pay with Stripe
</x-primary-button>
                @unless ($stripeConfigured)
                    <p class="text-sm text-amber-300">Set `STRIPE_KEY` and `STRIPE_SECRET` in `.env` to enable Stripe checkout.</p>
                @endunless
            </form>
        </div>
    </div>

    <div class="mt-8 rounded-2xl border border-white/8 bg-white/4 overflow-hidden">

    {{-- Header --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-white/8">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-400/10 border border-cyan-400/20">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-white tracking-tight">Invoices</h3>
        </div>
        <span class="text-xs text-slate-500 font-mono">{{ $invoices->count() }} {{ Str::plural('invoice', $invoices->count()) }}</span>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-left">
            <thead>
                <tr class="bg-white/3">
                    <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6">Invoice</th>
                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6">Plan</th>
                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6">Status</th>
                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6 text-right">Amount</th>
                    <th class="px-4 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6">Issued</th>
                    <th class="px-6 py-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500 border-b border-white/6 text-center">PDF</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">

                @forelse ($invoices as $invoice)

                    @php
                        $status = strtolower($invoice->status);
                        $badgeClasses = match ($status) {
                            'paid'     => 'bg-emerald-400/10 text-emerald-400 ring-1 ring-emerald-400/20',
                            'pending'  => 'bg-amber-400/10 text-amber-400 ring-1 ring-amber-400/20',
                            'overdue'  => 'bg-red-400/10 text-red-400 ring-1 ring-red-400/20',
                            'void'     => 'bg-slate-500/10 text-slate-400 ring-1 ring-slate-500/20',
                            default    => 'bg-slate-500/10 text-slate-400 ring-1 ring-slate-500/20',
                        };
                        $dotClass = match ($status) {
                            'paid'    => 'bg-emerald-400',
                            'pending' => 'bg-amber-400',
                            'overdue' => 'bg-red-400',
                            default   => 'bg-slate-500',
                        };
                    @endphp

                    <tr class="group transition-colors duration-150 hover:bg-white/3">

                        {{-- Invoice # --}}
                        <td class="px-6 py-4">
                            <span class="font-mono text-[11px] text-slate-400 group-hover:text-slate-300 transition-colors">
                                {{ $invoice->invoice_number }}
                            </span>
                        </td>

                        {{-- Plan --}}
                        <td class="px-4 py-4">
                            <span class="text-sm font-medium text-slate-200">
                                {{ $invoice->subscription?->plan?->name ?? '—' }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium {{ $badgeClasses }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>

                        {{-- Amount --}}
                        <td class="px-4 py-4 text-right">
                            <span class="font-mono text-sm font-medium text-slate-200">
                                {{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2) }}
                            </span>
                        </td>

                        {{-- Issued --}}
                        <td class="px-4 py-4">
                            <span class="text-xs text-slate-400 tabular-nums">
                                {{ optional($invoice->issued_at)->format('M d, Y') ?? '—' }}
                            </span>
                        </td>

                        {{-- PDF --}}
                        <td class="px-6 py-4 text-center">
                            <a
                                href="{{ route('tenant.subscription.invoices.pdf', ['tenant' => tenant(), 'invoice' => $invoice]) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-[11px] font-medium text-cyan-400 bg-cyan-400/8 border border-cyan-400/15 hover:bg-cyan-400/15 hover:border-cyan-400/30 transition-all duration-150"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                                Download
                            </a>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-400">No invoices yet</p>
                                    <p class="text-xs text-slate-600 mt-0.5">Invoices will appear here once generated.</p>
                                </div>
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    @if ($invoices->isNotEmpty())
    <div class="flex items-center justify-between px-6 py-3 border-t border-white/6 bg-white/2">
        <span class="text-xs text-slate-600">Showing {{ $invoices->count() }} {{ Str::plural('invoice', $invoices->count()) }}</span>
        <span class="text-xs font-mono text-slate-400">
            Total &nbsp;
            <span class="text-slate-200 font-medium">
                {{ $invoices->first()?->currency }}
                {{ number_format($invoices->sum(fn($i) => (float) $i->amount), 2) }}
            </span>
        </span>
    </div>
    @endif

</div>
</x-app-layout>
