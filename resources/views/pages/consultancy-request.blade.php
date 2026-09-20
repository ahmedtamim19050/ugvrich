<x-layouts.app title="Request Consultancy"
               description="Submit your requirement to UGV RICH and we will match it to the right faculty and professional expertise.">

    @php
        $selectedArea = old('service_category_id', optional($categories->firstWhere('slug', request('area')))->id);
    @endphp

    <x-page-hero
        eyebrow="Request consultancy"
        title='Tell us the problem. We will assign the <span class="text-accent">expertise</span>.'
        lead="Describe your requirement, attach any documents, and the RICH office will come back with an approach, a team and a timeline."
        :breadcrumbs="['Request Consultancy' => null]" />

    <section class="bg-white py-14 sm:py-16">
        <div class="container-rich">

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

            {{--
                A three-step form. Every step is in the DOM the whole time and only
                hidden with CSS, so nothing is lost on submit and the browser's own
                validation still works — `advance()` asks the current step's fields
                whether they are valid before moving on.

                If a submission comes back with errors, it opens on the step that
                holds the first one.
            --}}
            <form action="{{ route('consultancy.store') }}" method="POST" enctype="multipart/form-data"
                  class="reveal overflow-hidden rounded-[2rem] border border-ink-100 bg-white shadow-[0_30px_70px_-50px_rgba(7,20,38,0.45)]"
                  x-data="{
                      step: {{ $errors->hasAny(['requirement', 'service_category_id']) ? 2 : ($errors->has('document') ? 3 : 1) }},
                      last: 3,
                      chars: {{ mb_strlen((string) old('requirement')) }},
                      file: null,
                      area: '{{ $selectedArea }}',
                      fields: {
                          name: @js(old('name', '')),
                          phone: @js(old('phone', '')),
                          email: @js(old('email', '')),
                          organization: @js(old('organization', '')),
                      },
                      service: @js(old('area_of_interest', '')),
                      services: @js($categories->mapWithKeys(fn ($category) => [$category->id => $category->services->pluck('name')])),
                      areaNames: @js($categories->mapWithKeys(fn ($category) => [$category->id => $category->name])),
                      get areaName() { return this.areaNames[this.area] ?? 'Not sure yet' },
                      advance() {
                          const fields = this.$refs['step' + this.step].querySelectorAll('input, textarea, select');
                          for (const field of fields) {
                              if (! field.checkValidity()) { field.reportValidity(); return }
                          }
                          this.step = Math.min(this.step + 1, this.last);
                          this.$refs.top.scrollIntoView({ behavior: 'smooth', block: 'start' });
                      },
                      back() {
                          this.step = Math.max(this.step - 1, 1);
                          this.$refs.top.scrollIntoView({ behavior: 'smooth', block: 'start' });
                      },
                  }"
                  x-init="$watch('area', () => { service = '' })">

                @csrf

                {{-- Honeypot --}}
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website</label>
                    <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                {{-- Progress --}}
                <div x-ref="top" class="relative isolate overflow-hidden border-b border-ink-100 bg-ink-50/70 px-6 py-6 sm:px-10 sm:py-7">
                    <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-40 [mask-image:linear-gradient(to_left,black,transparent_70%)]" aria-hidden="true"></div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <h2 class="font-display text-2xl font-bold text-ink-950 sm:text-[28px]">Start an engagement</h2>
                        <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[12.5px] font-medium text-ink-600">
                            <x-ui-icon name="shield" class="h-4 w-4 text-brand-600" /> Confidential
                        </span>
                    </div>

                    <ol class="mt-7 grid gap-3 sm:grid-cols-3">
                        @foreach ([[1, 'About you', 'users'], [2, 'Your requirement', 'document'], [3, 'Documents & review', 'upload']] as [$n, $label, $icon])
                            <li class="flex items-center gap-3 rounded-2xl border bg-white p-3.5 transition duration-300"
                                :class="step === {{ $n }} ? 'border-brand-600 ring-4 ring-brand-100' : (step > {{ $n }} ? 'border-brand-200' : 'border-ink-200')">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl font-display text-[13px] font-bold transition-colors duration-300"
                                      :class="step >= {{ $n }} ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-500'">
                                    <template x-if="step > {{ $n }}"><x-ui-icon name="check" class="h-4 w-4" stroke="2.6" /></template>
                                    <span x-show="step <= {{ $n }}">{{ $n }}</span>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-[10.5px] font-semibold uppercase tracking-[0.14em] text-ink-400">Step {{ $n }}</span>
                                    <span class="block truncate font-display text-[14.5px] font-semibold text-ink-950">{{ $label }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="px-6 py-8 sm:px-10 sm:py-10">

                    {{-- Step 1 — About you --}}
                    <fieldset x-ref="step1" x-show="step === 1" x-transition.opacity>
                        <legend class="sr-only">About you</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">About you</p>
                        <p class="mt-1.5 text-[14px] muted">So we know who to reply to, and on whose behalf.</p>

                        <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <x-form.field name="name" label="Full name" icon="users" required autocomplete="name" x-model="fields.name" />
                            <x-form.field name="phone" label="Phone" type="tel" icon="phone" autocomplete="tel" x-model="fields.phone" />
                            <x-form.field name="email" label="Email" type="email" icon="mail" required autocomplete="email" x-model="fields.email" />
                            <x-form.field name="organization" label="Organization" icon="building" autocomplete="organization" x-model="fields.organization" />
                            <x-form.field name="designation" label="Designation" icon="briefcase" autocomplete="organization-title" />
                        </div>
                    </fieldset>

                    {{-- Step 2 — The requirement --}}
                    <fieldset x-ref="step2" x-show="step === 2" x-cloak x-transition.opacity>
                        <legend class="sr-only">Your requirement</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">Your requirement</p>
                        <p class="mt-1.5 text-[14px] muted">Pick the closest area, then describe the problem in your own words.</p>

                        <p class="mb-3 mt-7 text-[13px] font-semibold text-ink-700">Consultancy category</p>
                        <div class="grid gap-2.5 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($categories as $category)
                                <label class="group relative flex cursor-pointer items-center gap-3 rounded-2xl border p-3.5 transition has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-brand-600"
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
                            <label class="group relative flex cursor-pointer items-center gap-3 rounded-2xl border p-3.5 transition has-[:focus-visible]:outline-2 has-[:focus-visible]:outline-offset-2 has-[:focus-visible]:outline-brand-600"
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

                        {{-- Services inside the chosen category. Optional: people who
                             do not know which service they need can leave it alone. --}}
                        <div class="mt-7" x-show="area !== ''" x-cloak x-transition.opacity>
                            <label for="area_of_interest" class="mb-2 block text-[13px] font-semibold text-ink-700">
                                Which service? <span class="font-normal muted">(optional)</span>
                            </label>
                            <select id="area_of_interest" name="area_of_interest" x-model="service"
                                    class="w-full rounded-2xl border border-ink-200 bg-ink-50/60 px-4 py-3.5 text-[15px] text-ink-900 transition hover:border-ink-300 focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-100">
                                <option value="">Not sure — advise me</option>
                                <template x-for="name in (services[area] ?? [])" :key="name">
                                    <option :value="name" x-text="name"></option>
                                </template>
                            </select>
                            @error('area_of_interest')
                                <p class="mt-1.5 text-[13px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-7">
                            <div class="mb-2 flex items-end justify-between gap-3">
                                <label for="requirement" class="text-[13px] font-semibold text-ink-700">
                                    Brief description of requirement <span class="text-brand-600">*</span>
                                </label>
                                <span class="text-[12px] tabular-nums transition-colors" :class="chars < 20 ? 'text-ink-400' : 'text-brand-600'">
                                    <span x-text="chars">{{ mb_strlen((string) old('requirement')) }}</span> / 5000
                                </span>
                            </div>
                            <textarea id="requirement" name="requirement" rows="7" required minlength="20" maxlength="5000"
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
                    </fieldset>

                    {{-- Step 3 — Documents and review --}}
                    <fieldset x-ref="step3" x-show="step === 3" x-cloak x-transition.opacity>
                        <legend class="sr-only">Documents and review</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">Documents &amp; review</p>
                        <p class="mt-1.5 text-[14px] muted">Attach anything that helps — a brief, drawings, a tender document — then send it.</p>

                        <div class="mt-7 grid gap-6 lg:grid-cols-2">
                            <div>
                                <label for="document" class="mb-2 block text-[13px] font-semibold text-ink-700">Supporting document</label>
                                <label for="document"
                                       class="group flex h-[calc(100%-2rem)] cursor-pointer flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed px-6 py-9 text-center transition"
                                       :class="file ? 'border-brand-300 bg-brand-50/60' : 'border-ink-200 hover:border-brand-300 hover:bg-ink-50/60'">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl transition-colors"
                                          :class="file ? 'bg-brand-600 text-white' : 'bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white'">
                                        <x-ui-icon name="upload" class="h-5 w-5" x-show="! file" />
                                        <x-ui-icon name="document" class="h-5 w-5" x-show="file" x-cloak />
                                    </span>
                                    <span class="min-w-0">
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

                            {{-- What we are about to send --}}
                            <div class="rounded-2xl border border-ink-100 bg-ink-50/60 p-5 sm:p-6">
                                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-ink-400">Your request</p>
                                <dl class="mt-4 space-y-3 text-[14px]">
                                    @foreach ([['name', 'Name'], ['phone', 'Phone'], ['email', 'Email'], ['organization', 'Organization']] as [$field, $label])
                                        <div class="flex gap-3">
                                            <dt class="w-28 shrink-0 text-ink-500">{{ $label }}</dt>
                                            <dd class="min-w-0 flex-1 truncate font-medium text-ink-900" x-text="fields.{{ $field }} || '--'">--</dd>
                                        </div>
                                    @endforeach
                                    <div class="flex gap-3">
                                        <dt class="w-28 shrink-0 text-ink-500">Category</dt>
                                        <dd class="min-w-0 flex-1 font-medium text-ink-900" x-text="areaName">--</dd>
                                    </div>
                                    <div class="flex gap-3">
                                        <dt class="w-28 shrink-0 text-ink-500">Service</dt>
                                        <dd class="min-w-0 flex-1 font-medium text-ink-900" x-text="service || 'Not sure - advise me'">--</dd>
                                    </div>
                                    <div class="flex gap-3">
                                        <dt class="w-28 shrink-0 text-ink-500">Requirement</dt>
                                        <dd class="min-w-0 flex-1 font-medium text-ink-900"><span x-text="chars">0</span> characters</dd>
                                    </div>
                                    <div class="flex gap-3">
                                        <dt class="w-28 shrink-0 text-ink-500">Attachment</dt>
                                        <dd class="min-w-0 flex-1 truncate font-medium text-ink-900" x-text="file ?? 'None'">None</dd>
                                    </div>
                                </dl>

                                <button type="button" @click="step = 1"
                                        class="mt-5 inline-flex items-center gap-1.5 text-[13px] font-semibold text-brand-700">
                                    <x-ui-icon name="arrow-left" class="h-3.5 w-3.5" /> Edit your details
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </div>

                {{-- Navigation --}}
                <div class="flex flex-col gap-4 border-t border-ink-100 bg-ink-50/70 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-10">
                    <p class="text-[13px] muted">
                        <span class="font-semibold text-brand-600">*</span> Required fields ·
                        Step <span x-text="step">1</span> of 3
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <button type="button" x-show="step > 1" x-cloak @click="back()" class="btn-ghost">
                            <x-ui-icon name="arrow-left" class="h-4 w-4" /> Back
                        </button>

                        <button type="button" x-show="step < last" @click="advance()" class="btn-primary group justify-center">
                            Continue
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>

                        <button type="submit" x-show="step === last" x-cloak class="btn-primary group justify-center">
                            Submit request
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app>
