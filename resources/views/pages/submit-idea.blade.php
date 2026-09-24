<x-layouts.app :title="__('site.actions.submit_idea')"
               :description="__('site.ideas.meta_description')">

    <x-page-hero
        :eyebrow="__('site.ideas.hero_eyebrow')"
        :title="__('site.ideas.hero_title')"
        :lead="__('site.ideas.hero_lead')"
        :breadcrumbs="[__('site.nav.startup') => route('startup'), __('site.actions.submit_idea') => null]" />

    <section class="bg-white py-14 sm:py-16">
        <div class="container-rich">

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
                    <label for="website">{{ __('site.forms.website') }}</label>
                    <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                {{-- Progress --}}
                <div x-ref="top" class="relative isolate overflow-hidden border-b border-ink-100 bg-ink-50/70 px-6 py-6 sm:px-10 sm:py-7">
                    <div class="pointer-events-none absolute inset-0 -z-10 text-brand-700 grid-overlay opacity-40 [mask-image:linear-gradient(to_left,black,transparent_70%)]" aria-hidden="true"></div>

                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <h2 class="font-display text-2xl font-bold text-ink-950 sm:text-[28px]">{{ __('site.ideas.form_title') }}</h2>
                        <span class="inline-flex items-center gap-2 rounded-full border border-ink-200 bg-white px-3.5 py-1.5 text-[12.5px] font-medium text-ink-600">
                            <x-ui-icon name="shield" class="h-4 w-4 text-brand-600" /> {{ __('site.ideas.confidential') }}
                        </span>
                    </div>

                    <ol class="mt-7 grid gap-3 sm:grid-cols-2">
                        @foreach ([[1, __('site.ideas.step_you')], [2, __('site.ideas.step_idea')]] as [$n, $label])
                            <li class="flex items-center gap-3 rounded-2xl border bg-white p-3.5 transition duration-300"
                                :class="step === {{ $n }} ? 'border-brand-600 ring-4 ring-brand-100' : (step > {{ $n }} ? 'border-brand-200' : 'border-ink-200')">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl font-display text-[13px] font-bold transition-colors duration-300"
                                      :class="step >= {{ $n }} ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-500'">
                                    <template x-if="step > {{ $n }}"><x-ui-icon name="check" class="h-4 w-4" stroke="2.6" /></template>
                                    <span x-show="step <= {{ $n }}">{{ $n }}</span>
                                </span>
                                <span class="min-w-0">
                                    <span class="block text-[10.5px] font-semibold uppercase tracking-[0.14em] text-ink-400">{{ __('site.consultancy.step', ['number' => $n]) }}</span>
                                    <span class="block truncate font-display text-[14.5px] font-semibold text-ink-950">{{ $label }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="px-6 py-8 sm:px-10 sm:py-10">

                    {{-- Step 1 — About you --}}
                    <fieldset x-ref="step1" x-show="step === 1" x-transition.opacity>
                        <legend class="sr-only">{{ __('site.ideas.step_you') }}</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">{{ __('site.ideas.step_you') }}</p>
                        <p class="mt-1.5 text-[14px] muted">{{ __('site.ideas.you_note') }}</p>

                        <div class="mt-7 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <x-form.field name="name" :label="__('site.forms.full_name')" icon="users" required autocomplete="name" />
                            <x-form.field name="phone" :label="__('site.forms.phone')" type="tel" icon="phone" autocomplete="tel" />
                            <x-form.field name="email" :label="__('site.forms.email')" type="email" icon="mail" required autocomplete="email" />

                            <div>
                                <label for="role" class="mb-2 block text-[13px] font-semibold text-ink-700">
                                    {{ __('site.ideas.role_label') }} <span class="text-brand-600">*</span>
                                </label>
                                <select id="role" name="role" required
                                        class="w-full rounded-2xl border border-ink-200 bg-ink-50/60 px-4 py-3.5 text-[15px] text-ink-900 transition hover:border-ink-300 focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-100">
                                    @foreach (\App\Support\Vocabulary::all('idea_roles') as $value => $label)
                                        <option value="{{ $value }}" @selected(old('role', 'student') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="department" class="mb-2 block text-[13px] font-semibold text-ink-700">{{ __('site.ideas.department') }}</label>
                                <select id="department" name="department"
                                        class="w-full rounded-2xl border border-ink-200 bg-ink-50/60 px-4 py-3.5 text-[15px] text-ink-900 transition hover:border-ink-300 focus:border-brand-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-brand-100">
                                    <option value="">{{ __('site.ideas.select_department') }}</option>
                                    @foreach (\App\Support\Vocabulary::all('departments') as $code => $label)
                                        <option value="{{ $code }}" @selected(old('department') === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-form.field name="programme" :label="__('site.ideas.programme')" icon="academic" />
                        </div>
                    </fieldset>

                    {{-- Step 2 — The idea --}}
                    <fieldset x-ref="step2" x-show="step === 2" x-cloak x-transition.opacity>
                        <legend class="sr-only">{{ __('site.ideas.step_idea') }}</legend>
                        <p class="font-display text-[19px] font-bold text-ink-950">{{ __('site.ideas.step_idea') }}</p>
                        <p class="mt-1.5 text-[14px] muted">{{ __('site.ideas.idea_note') }}</p>

                        <div class="mt-7 space-y-6">
                            <x-form.field name="title" :label="__('site.ideas.title_label')" icon="lightbulb" required
                                          :placeholder="__('site.ideas.title_placeholder')" />

                            @foreach ([
                                ['problem', true],
                                ['solution', true],
                                ['beneficiaries', false],
                                ['resources_needed', false],
                            ] as [$field, $required])
                                @php
                                    $label = __('site.ideas.'.$field.'_label');
                                    $placeholder = __('site.ideas.'.$field.'_placeholder');
                                @endphp
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
                                <x-form.field name="team_size" :label="__('site.ideas.team_size')" icon="users" :placeholder="__('site.ideas.team_size_placeholder')" />

                                <div>
                                    <label for="document" class="mb-2 block text-[13px] font-semibold text-ink-700">{{ __('site.ideas.file_label') }}</label>
                                    <label for="document"
                                           class="group flex cursor-pointer items-center gap-4 rounded-2xl border-2 border-dashed px-5 py-4 transition"
                                           :class="file ? 'border-brand-300 bg-brand-50/60' : 'border-ink-200 hover:border-brand-300 hover:bg-ink-50/60'">
                                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl transition-colors"
                                              :class="file ? 'bg-brand-600 text-white' : 'bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white'">
                                            <x-ui-icon name="upload" class="h-5 w-5" x-show="! file" />
                                            <x-ui-icon name="document" class="h-5 w-5" x-show="file" x-cloak />
                                        </span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-[14px] font-medium text-ink-950" x-text="file ?? @js(__('site.ideas.file_hint'))">{{ __('site.ideas.file_hint') }}</span>
                                            <span class="mt-0.5 block text-[12px] muted">{{ __('site.ideas.file_types') }}</span>
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
                        <span class="font-semibold text-brand-600">*</span> {{ __('site.consultancy.required_note') }} ·
                        {!! __('site.consultancy.step_of', ['current' => '<span x-text="step">1</span>', 'total' => 2]) !!}
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <button type="button" x-show="step > 1" x-cloak @click="back()" class="btn-ghost">
                            <x-ui-icon name="arrow-left" class="h-4 w-4" /> {{ __('site.consultancy.back') }}
                        </button>

                        <button type="button" x-show="step < last" @click="advance()" class="btn-primary group justify-center">
                            {{ __('site.consultancy.continue') }}
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>

                        <button type="submit" x-show="step === last" x-cloak class="btn-primary group justify-center">
                            {{ __('site.ideas.submit') }}
                            <x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-layouts.app>
