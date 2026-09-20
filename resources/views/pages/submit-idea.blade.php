<x-layouts.app title="Submit Your Innovation"
               description="Submit an innovation idea to the UGV RICH Innovation Wing. Open to students, faculty, staff and alumni.">

    <x-page-hero
        eyebrow="Submit your innovation"
        title='Every enterprise starts as an <span class="text-accent">idea</span>.'
        lead="Tell us the problem you have spotted and how you would solve it. The Innovation Wing reviews every submission."
        :breadcrumbs="['Startup & Incubation' => route('startup'), 'Submit Your Innovation' => null]" />

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
                Two steps: who you are, then the idea itself. Both stay in the DOM
                and are only hidden, so one submit sends everything and the
                browser's own validation still applies.
            --}}
            <form action="{{ route('ideas.store') }}" method="POST" enctype="multipart/form-data"
                  class="reveal overflow-hidden rounded-[2rem] border border-ink-100 bg-white shadow-[0_30px_70px_-50px_rgba(7,20,38,0.45)]"
                  x-data="{
                      step: {{ $errors->hasAny(['title', 'problem', 'solution', 'document']) ? 2 : 1 }},
                      last: 2,
                      file: null,
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
                  }">
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
                        <h2 class="font-display text-2xl font-bold text-ink-950 sm:text-[28px]">Submit your innovation</h2>
                        <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[12.5px] font-medium text-ink-600">
                            <x-ui-icon name="shield" class="h-4 w-4 text-brand-600" /> Reviewed in confidence
                        </span>
                    </div>

                    <ol class="mt-7 grid gap-3 sm:grid-cols-2">
                        @foreach ([[1, 'About you'], [2, 'Your idea']] as [$n, $label])
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
                        <p class="mt-1.5 text-[14px] muted">So we know who to talk to, and which department to involve.</p>

                        <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <x-form.field name="name" label="Full name" icon="users" required autocomplete="name" />
                            <x-form.field name="phone" label="Phone" type="tel" icon="phone" autocomplete="tel" />
                            <x-form.field name="email" label="Email" type="email" icon="mail" required autocomplete="email" />

                            <div>
                                <label for="role" class="mb-2 block text-[13px] font-semibold text-ink-700">
                                    You are <span class="text-brand-600">*</span>
                                </label>
                                <select id="role" name="role" required
                                        class="w-full rounded-2xl border border-ink-200 bg-ink-50/60 px-4 py-3.5 text-[15px] text-ink-900 transition hover:border-ink-300 focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-100">
                                    @foreach (config('rich.idea_roles') as $value => $label)
                                        <option value="{{ $value }}" @selected(old('role', 'student') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="department" class="mb-2 block text-[13px] font-semibold text-ink-700">Department</label>
                                <select id="department" name="department"
                                        class="w-full rounded-2xl border border-ink-200 bg-ink-50/60 px-4 py-3.5 text-[15px] text-ink-900 transition hover:border-ink-300 focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-100">
                                    <option value="">Select your department</option>
                                    @foreach (config('rich.departments') as $code => $label)
                                        <option value="{{ $code }}" @selected(old('department') === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-form.field name="programme" label="Programme / designation" icon="academic" />
                        </div>
                    </fieldset>

                    {{-- Step 2 — The idea --}}
                    <fieldset x-ref="step2" x-show="step === 2" x-cloak x-transition.opacity>
                        <legend class="sr-only">Your idea</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">Your idea</p>
                        <p class="mt-1.5 text-[14px] muted">It does not need to be finished. Tell us the problem and how you would solve it.</p>

                        <div class="mt-7 space-y-6">
                            <x-form.field name="title" label="Idea title" icon="lightbulb" required
                                          placeholder="e.g. Low-cost water testing kit for coastal households" />

                            @foreach ([
                                ['problem', 'What problem does it solve?', 'Who has this problem today, and what do they do about it now?', true],
                                ['solution', 'What is your solution?', 'What would you build, and what makes it different from what exists?', true],
                                ['beneficiaries', 'Who benefits?', 'Students, farmers, a industry, a community — whoever the users would be.', false],
                                ['resources_needed', 'What support do you need?', 'Lab access, materials, a mentor, funding — whatever would help you start.', false],
                            ] as [$field, $label, $placeholder, $required])
                                <div>
                                    <label for="{{ $field }}" class="mb-2 block text-[13px] font-semibold text-ink-700">
                                        {{ $label }} @if ($required)<span class="text-brand-600">*</span>@endif
                                    </label>
                                    <textarea id="{{ $field }}" name="{{ $field }}" rows="{{ $required ? 5 : 3 }}"
                                              @if ($required) required minlength="20" @endif
                                              maxlength="{{ $required ? 5000 : 2000 }}"
                                              placeholder="{{ $placeholder }}"
                                              @class([
                                                  'w-full rounded-2xl border bg-ink-50/60 px-4 py-3.5 text-[15px] leading-relaxed text-ink-900 placeholder:text-ink-400 transition focus:bg-white focus:outline-none focus:ring-4',
                                                  'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has($field),
                                                  'border-ink-200 hover:border-ink-300 focus:border-brand-400 focus:ring-brand-100' => ! $errors->has($field),
                                              ])>{{ old($field) }}</textarea>
                                    @error($field)
                                        <p class="mt-1 text-[13px] text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach

                            <div class="grid gap-6 lg:grid-cols-2">
                                <x-form.field name="team_size" label="Team size" icon="users" placeholder="e.g. Just me, or 4 students" />

                                <div>
                                    <label for="document" class="mb-2 block text-[13px] font-semibold text-ink-700">Supporting file (optional)</label>
                                    <label for="document"
                                           class="group flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-dashed px-5 py-4 transition"
                                           :class="file ? 'border-brand-300 bg-brand-50/60' : 'border-ink-200 hover:border-brand-300 hover:bg-ink-50/60'">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition-colors"
                                              :class="file ? 'bg-brand-600 text-white' : 'bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white'">
                                            <x-ui-icon name="upload" class="h-5 w-5" x-show="! file" />
                                            <x-ui-icon name="document" class="h-5 w-5" x-show="file" x-cloak />
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-[14px] font-medium text-ink-950" x-text="file ?? 'Sketch, slides or a short write-up'">Sketch, slides or a short write-up</span>
                                            <span class="mt-0.5 block text-[12px] muted">PDF, Word, PowerPoint, ZIP or image · up to 10 MB</span>
                                        </span>
                                    </label>
                                    <input id="document" type="file" name="document" class="sr-only"
                                           @change="file = $event.target.files[0]?.name ?? null"
                                           accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.png,.jpg,.jpeg">
                                    @error('document')
                                        <p class="mt-1.5 text-[13px] text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                {{-- Navigation --}}
                <div class="flex flex-col gap-4 border-t border-ink-100 bg-ink-50/70 px-6 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-10">
                    <p class="text-[13px] muted">
                        <span class="font-semibold text-brand-600">*</span> Required fields ·
                        Step <span x-text="step">1</span> of 2
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
                            Submit idea
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app>
