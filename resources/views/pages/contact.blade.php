<x-layouts.app :title="__('site.nav.contact')"
               :description="__('site.contact.meta_description')">

    <x-page-hero
        :eyebrow="__('site.contact.hero_eyebrow')"
        :title="__('site.contact.hero_title')"
        :lead="__('site.contact.hero_lead')"
        :breadcrumbs="[__('site.nav.contact') => null]">
        <a href="{{ route('consultancy.create') }}" class="btn-primary">
            {{ __('site.actions.request_consultancy') }} <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
        </a>
    </x-page-hero>

    <section class="bg-white py-16 sm:py-20">
        <div class="container-rich grid gap-10 lg:grid-cols-[1.25fr_0.75fr] lg:gap-14">

            {{-- ---------------- Message form ---------------- --}}
            <div id="message-form" class="scroll-mt-28">
                @if (session('contact_sent'))
                    <div class="reveal mb-6 flex items-start gap-4 rounded-[1.5rem] border border-brand-200 bg-brand-50 p-6" role="status">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-brand-600 text-white">
                            <x-ui-icon name="check" class="h-5 w-5" stroke="2.5" />
                        </span>
                        <div>
                            <p class="font-display text-[16px] font-bold text-brand-800">{{ __('site.contact.sent_title') }}</p>
                            <p class="mt-1 text-[14px] text-brand-700">{{ __('site.contact.sent_body') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="reveal mb-6 flex items-start gap-4 rounded-[1.5rem] border border-red-200 bg-red-50 p-6" role="alert">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <x-ui-icon name="x" class="h-5 w-5" stroke="2.5" />
                        </span>
                        <div>
                            <p class="font-display text-[16px] font-bold text-red-700">{{ __('site.forms.errors_title') }}</p>
                            <ul class="mt-2 space-y-1 text-[14px] text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>· {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST"
                      class="reveal overflow-hidden rounded-[2rem] border border-ink-100 bg-white shadow-[0_30px_70px_-50px_rgba(7,20,38,0.45)]"
                      x-data="{ chars: {{ mb_strlen((string) old('message')) }} }">
                    @csrf

                    {{-- Honeypot: a real person never fills this in. --}}
                    <div class="absolute -left-[9999px]" aria-hidden="true">
                        <label for="website">{{ __('site.forms.website') }}</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="border-b border-ink-100 bg-ink-50/60 px-6 py-5 sm:px-8">
                        <h2 class="font-display text-[19px] font-bold text-ink-950">{{ __('site.contact.form_title') }}</h2>
                        <p class="mt-1 text-[13.5px] muted">{{ __('site.contact.form_note') }}</p>
                    </div>

                    <div class="space-y-6 px-6 py-7 sm:px-8">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <x-form.field name="name" :label="__('site.forms.full_name')" icon="users" required autocomplete="name" />
                            <x-form.field name="email" :label="__('site.forms.email')" type="email" icon="mail" required autocomplete="email" />
                            <x-form.field name="phone" :label="__('site.forms.phone')" type="tel" icon="phone" autocomplete="tel" />
                            <x-form.field name="organization" :label="__('site.forms.organization')" icon="building" autocomplete="organization" />
                        </div>

                        <x-form.field name="subject" :label="__('site.forms.subject')" icon="document" />

                        <div>
                            <label for="message" class="block text-[13.5px] font-semibold text-ink-800">
                                {{ __('site.contact.message_label') }} <span class="text-brand-600">*</span>
                            </label>
                            <textarea id="message" name="message" rows="6" required maxlength="5000"
                                      x-model="chars" x-init="chars = $el.value"
                                      @input="chars = $event.target.value"
                                      placeholder="{{ __('site.contact.message_placeholder') }}"
                                      class="mt-2 w-full rounded-2xl border border-ink-200 bg-white p-4 text-[14.5px] leading-relaxed text-ink-900 placeholder:text-ink-400 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-100">{{ old('message') }}</textarea>
                            <p class="mt-2 text-right text-[12px] muted"><span x-text="chars.length">0</span> / 5000</p>
                        </div>

                        <button type="submit" class="btn-primary group w-full sm:w-auto">
                            {{ __('site.actions.send_message') }}
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>
                    </div>
                </form>
            </div>

            {{-- ---------------- Office details ---------------- --}}
            <aside class="space-y-5 lg:sticky lg:top-28 lg:self-start">
                <div class="reveal rounded-[2rem] border border-ink-100 bg-white p-6 sm:p-7">
                    <h2 class="font-display text-[18px] font-bold text-ink-950">{{ __('site.contact.office') }}</h2>

                    <dl class="mt-5 space-y-4 text-[14px]">
                        @foreach ([
                            ['map-pin', __('site.contact.address'), $site->get('contact_address'), null],
                            ['mail', __('site.forms.email'), $site->get('contact_email'), 'mailto:'],
                            ['phone', __('site.forms.phone'), $site->get('contact_phone'), 'tel:'],
                            ['clock', __('site.contact.office_hours'), $site->get('contact_hours'), null],
                        ] as [$icon, $label, $value, $scheme])
                            @if ($value)
                                <div class="flex items-start gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                                        <x-ui-icon :name="$icon" class="h-4 w-4" />
                                    </span>
                                    <div class="min-w-0">
                                        <dt class="text-[11px] font-semibold uppercase tracking-[0.14em] text-ink-400">{{ $label }}</dt>
                                        <dd class="mt-0.5 text-ink-800">
                                            @if ($scheme)
                                                <a href="{{ $scheme.($scheme === 'tel:' ? preg_replace('/[^\d+]/', '', $value) : $value) }}" class="transition hover:text-brand-700">{{ $value }}</a>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </div>

                {{-- Consultancy sits behind its own form, so point people at it. --}}
                <div class="reveal relative isolate overflow-hidden rounded-[2rem] bg-navy-700 p-6 text-white sm:p-7">
                    <div class="pointer-events-none absolute inset-0 -z-10 text-white grid-overlay opacity-20" aria-hidden="true"></div>
                    <div class="pointer-events-none absolute -right-16 -top-16 -z-10 h-44 w-44 rounded-full bg-brand-600/50" aria-hidden="true"></div>

                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-navy-700">
                        <x-ui-icon name="briefcase" class="h-5 w-5" />
                    </span>
                    <h2 class="mt-5 font-display text-[18px] font-bold !text-white">{{ __('site.contact.consultancy_title') }}</h2>
                    <p class="mt-2 text-[13.5px] leading-relaxed text-white/75">
                        {{ __('site.contact.consultancy_body') }}
                    </p>
                    <a href="{{ route('consultancy.create') }}" class="btn-invert mt-6 w-full">
                        {{ __('site.actions.request_consultancy') }} <x-ui-icon name="arrow-up-right" class="h-4 w-4" />
                    </a>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.app>
