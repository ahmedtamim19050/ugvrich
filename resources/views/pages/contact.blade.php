<x-layouts.app title="Contact & Consultancy Request"
               description="Submit a consultancy request to UGV RICH, or contact the RICH office directly.">

    @php
        $selectedArea = old('service_category_id', optional($categories->firstWhere('slug', request('area')))->id);
    @endphp

    <x-page-hero
        eyebrow="Contact UGV RICH"
        title='Tell us the problem. We will find the <span class="text-accent">expertise</span>.'
        lead="Share your requirement and we will match it to the right faculty and professional expertise."
        :breadcrumbs="['Contact' => null]" />

    <section class="bg-white pb-20 pt-14 sm:pb-24 sm:pt-16">
        <div class="container-rich grid gap-10 lg:grid-cols-[1.4fr_0.6fr] lg:gap-12">

            {{-- ---------------- Consultancy request form ---------------- --}}
            <div id="request-form" class="scroll-mt-32">
                @if ($errors->any())
                    <div class="reveal mb-6 flex items-start gap-4 rounded-[1.5rem] border border-red-200 bg-red-50 p-6" role="alert">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <x-ui-icon name="x" class="h-5 w-5" stroke="2.5" />
                        </span>
                        <div>
                            <p class="font-display text-[16px] font-bold text-red-700">Please correct the following</p>
                            <ul class="mt-2 space-y-1 text-[14px] text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>· {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('consultancy.store') }}" method="POST" enctype="multipart/form-data"
                      class="reveal overflow-hidden rounded-[2rem] border border-ink-100 bg-white shadow-[0_30px_70px_-50px_rgba(11,15,24,0.45)]"
                      x-data="{ chars: {{ mb_strlen((string) old('requirement')) }}, file: null, area: '{{ $selectedArea }}' }">
                    @csrf

                    {{-- Honeypot --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- Card header --}}
                    <div class="relative isolate overflow-hidden border-b border-ink-100 bg-ink-50/70 px-6 py-6 sm:px-9 sm:py-7">
                        <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-40 [mask-image:linear-gradient(to_left,black,transparent_70%)]" aria-hidden="true"></div>
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h2 class="font-display text-2xl font-bold text-ink-950 sm:text-[28px]">Start an engagement</h2>
                            </div>
                            <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[12.5px] font-medium text-ink-600">
                                <x-ui-icon name="shield" class="h-4 w-4 text-brand-600" /> Confidential
                            </span>
                        </div>
                    </div>

                    <div class="space-y-10 px-6 py-8 sm:px-9 sm:py-9">
                        {{-- Step 1 --}}
                        <fieldset>
                            <legend class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 font-display text-[13px] font-bold text-white">1</span>
                                <span class="font-display text-[17px] font-bold text-ink-950">About you</span>
                            </legend>

                            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                                <x-form.field name="name" label="Full name" icon="users" required autocomplete="name" />
                                <x-form.field name="email" label="Email" type="email" icon="mail" required autocomplete="email" />
                                <x-form.field name="organization" label="Organization" icon="building" autocomplete="organization" />
                                <x-form.field name="designation" label="Designation" icon="briefcase" autocomplete="organization-title" />
                                <x-form.field name="phone" label="Phone" type="tel" icon="phone" autocomplete="tel" />
                            </div>
                        </fieldset>

                        {{-- Step 2 --}}
                        <fieldset>
                            <legend class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-brand-600 font-display text-[13px] font-bold text-white">2</span>
                                <span class="font-display text-[17px] font-bold text-ink-950">Your requirement</span>
                            </legend>

                            {{-- Area of interest as selectable tiles --}}
                            <p class="mb-3 mt-6 text-[13px] font-semibold text-ink-700">Area of interest</p>
                            <div class="grid gap-2.5 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($categories as $category)
                                    <label class="group relative flex cursor-pointer items-center gap-3 rounded-2xl border p-3 transition has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-brand-600"
                                           :class="area == '{{ $category->id }}' ? 'border-brand-400 bg-brand-50 ring-4 ring-brand-100' : 'border-ink-200 hover:border-ink-300 hover:bg-ink-50/60'">
                                        <input type="radio" name="service_category_id" value="{{ $category->id }}" x-model="area" class="sr-only"
                                               @checked((string) $selectedArea === (string) $category->id)>
                                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition-colors"
                                              :class="area == '{{ $category->id }}' ? 'bg-brand-600 text-white' : 'bg-ink-50 text-brand-600'">
                                            <x-ui-icon :name="$category->icon ?? 'grid'" class="h-4 w-4" />
                                        </span>
                                        <span class="text-[13.5px] font-medium leading-snug text-ink-800">{{ \Illuminate\Support\Str::before($category->name, ' Consultancy') }}</span>
                                    </label>
                                @endforeach
                                <label class="group relative flex cursor-pointer items-center gap-3 rounded-2xl border p-3 transition has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-brand-600"
                                       :class="area === '' ? 'border-brand-400 bg-brand-50 ring-4 ring-brand-100' : 'border-ink-200 hover:border-ink-300 hover:bg-ink-50/60'">
                                    <input type="radio" name="service_category_id" value="" x-model="area" class="sr-only" @checked(! $selectedArea)>
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition-colors"
                                          :class="area === '' ? 'bg-brand-600 text-white' : 'bg-ink-50 text-brand-600'">
                                        <x-ui-icon name="compass" class="h-4 w-4" />
                                    </span>
                                    <span class="text-[13.5px] font-medium leading-snug text-ink-800">Not sure yet</span>
                                </label>
                            </div>
                            @error('service_category_id')
                                <p class="mt-1.5 text-[13px] text-red-600">{{ $message }}</p>
                            @enderror

                            <div class="mt-6">
                                <div class="mb-2 flex items-end justify-between gap-3">
                                    <label for="requirement" class="text-[13px] font-semibold text-ink-700">
                                        Brief description of requirement <span class="text-brand-600">*</span>
                                    </label>
                                    <span class="text-[12px] tabular-nums transition-colors" :class="chars < 20 ? 'text-ink-400' : 'text-brand-600'">
                                        <span x-text="chars">{{ mb_strlen((string) old('requirement')) }}</span> / 5000
                                    </span>
                                </div>
                                <textarea id="requirement" name="requirement" rows="6" required maxlength="5000"
                                          @input="chars = $event.target.value.length"
                                          placeholder="What is the problem, what decision does it support, and what would a successful outcome look like?"
                                          @class([
                                              'w-full rounded-2xl border bg-ink-50/60 px-4 py-3.5 text-[15px] leading-relaxed text-ink-900 placeholder:text-ink-400 transition focus:bg-white focus:outline-none focus:ring-4',
                                              'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has('requirement'),
                                              'border-ink-200 hover:border-ink-300 focus:border-brand-400 focus:ring-brand-100' => ! $errors->has('requirement'),
                                          ])>{{ old('requirement') }}</textarea>
                                @error('requirement')
                                    <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- File upload --}}
                            <div class="mt-6">
                                <label for="document" class="mb-2 block text-[13px] font-semibold text-ink-700">Supporting document</label>
                                <label for="document"
                                       class="group flex cursor-pointer flex-col items-center gap-3 rounded-2xl border-2 border-dashed px-6 py-7 text-center transition sm:flex-row sm:text-left"
                                       :class="file ? 'border-brand-300 bg-brand-50/60' : 'border-ink-200 hover:border-brand-300 hover:bg-ink-50/60'">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl transition-colors"
                                          :class="file ? 'bg-brand-600 text-white' : 'bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white'">
                                        <x-ui-icon name="upload" class="h-5 w-5" x-show="! file" />
                                        <x-ui-icon name="document" class="h-5 w-5" x-show="file" x-cloak />
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate font-medium text-ink-950" x-text="file ?? 'Click to choose a file (optional)'">Click to choose a file (optional)</span>
                                        <span class="mt-0.5 block text-[12.5px] muted">PDF, Word, Excel, PowerPoint, ZIP or image · up to 10 MB</span>
                                    </span>
                                    <span class="rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[12.5px] font-semibold text-ink-700 transition group-hover:border-brand-300 group-hover:text-brand-700"
                                          x-text="file ? 'Change' : 'Browse'">Browse</span>
                                </label>
                                <input id="document" type="file" name="document" class="sr-only"
                                       @change="file = $event.target.files[0]?.name ?? null"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.png,.jpg,.jpeg">
                                @error('document')
                                    <p class="mt-1.5 text-[13px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </fieldset>
                    </div>

                    <div class="flex flex-col gap-4 border-t border-ink-100 bg-ink-50/70 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-9">
                        <p class="text-[13px] muted"><span class="font-semibold text-brand-600">*</span> Required fields</p>
                        <button type="submit" class="btn-primary group shrink-0 justify-center">
                            Submit request
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>
                    </div>
                </form>
            </div>

            {{-- ---------------- Sidebar ---------------- --}}
            <aside class="space-y-5 lg:sticky lg:top-32 lg:self-start">
                @if ($steps = $site->list('process_steps'))
                    @php
                        $stepIcons = ['document', 'users', 'target', 'check', 'star'];
                        $email = $site->get('contact_email');
                    @endphp

                    <div class="reveal relative isolate overflow-hidden rounded-[2rem] bg-brand-700 p-6 text-white sm:p-7">
                        <div class="pointer-events-none absolute inset-0 -z-10 text-ink-950 grid-overlay opacity-60" aria-hidden="true"></div>
                        <div class="pointer-events-none absolute -right-20 -top-20 -z-10 h-52 w-52 rounded-full bg-brand-600" aria-hidden="true"></div>
                        <div class="pointer-events-none absolute -right-8 -top-8 -z-10 h-28 w-28 rounded-full border border-white/15" aria-hidden="true"></div>

                        {{-- Header --}}
                        <div class="flex items-center gap-3.5">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white text-brand-700 shadow-[0_10px_24px_-12px_rgba(0,0,0,0.5)]">
                                <x-ui-icon name="compass" class="h-5 w-5" />
                            </span>
                            <div>
                                <h3 class="font-display text-xl font-bold leading-tight !text-white">What happens next</h3>
                                <p class="mt-0.5 text-[13px] text-brand-100">From request to results in {{ count($steps) }} stages</p>
                            </div>
                        </div>

                        {{-- Stages --}}
                        <ol class="mt-7 space-y-3">
                            @foreach ($steps as $i => $step)
                                <li class="group relative">
                                    @unless ($loop->last)
                                        <span class="absolute -bottom-3 left-[2.35rem] top-full h-3 w-px border-l border-dashed border-white/30" aria-hidden="true"></span>
                                    @endunless

                                    <div class="flex gap-4 rounded-2xl border border-white/10 bg-white/[0.07] p-4 transition duration-300 hover:border-white/25 hover:bg-white/[0.12]">
                                        <span class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/15 transition-colors duration-300 group-hover:bg-white group-hover:text-brand-700">
                                            <x-ui-icon :name="$stepIcons[$i] ?? 'check'" class="h-5 w-5" />
                                            <span class="absolute -right-1.5 -top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-white font-display text-[10px] font-bold text-brand-700 ring-2 ring-brand-700">
                                                {{ $i + 1 }}
                                            </span>
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-[10.5px] font-semibold uppercase tracking-[0.16em] text-brand-200">Stage {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                                            <p class="mt-0.5 font-display text-[15.5px] font-semibold !text-white">{{ $step['title'] }}</p>
                                            @if (! empty($step['description']))
                                                <p class="mt-1.5 text-[13px] leading-relaxed text-white/70">{{ $step['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>

                        {{-- Assurances --}}
                        <div class="mt-6 grid grid-cols-3 gap-2 border-t border-white/15 pt-6">
                            @foreach ([['shield', 'Confidential'], ['clock', '3-day response'], ['handshake', 'No obligation']] as [$icon, $label])
                                <div class="flex flex-col items-center gap-2 rounded-xl bg-white/[0.06] px-2 py-3 text-center">
                                    <x-ui-icon :name="$icon" class="h-4 w-4 text-brand-200" />
                                    <span class="text-[11.5px] font-medium leading-tight text-white/85">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if ($email)
                            <a href="mailto:{{ $email }}"
                               class="group/mail mt-4 flex items-center justify-between gap-3 rounded-2xl bg-white p-3.5 text-ink-900 transition hover:bg-brand-50">
                                <span class="flex min-w-0 items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-50 text-brand-600 transition group-hover/mail:bg-brand-600 group-hover/mail:text-white">
                                        <x-ui-icon name="mail" class="h-4 w-4" />
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-ink-400">Questions first?</span>
                                        <span class="block truncate text-[14px] font-semibold text-ink-950">{{ $email }}</span>
                                    </span>
                                </span>
                                <x-ui-icon name="arrow-up-right" class="h-4 w-4 shrink-0 text-ink-300 transition group-hover/mail:rotate-45 group-hover/mail:text-brand-600" />
                            </a>
                        @endif
                    </div>
                @endif

            </aside>
        </div>
    </section>

</x-layouts.app>
