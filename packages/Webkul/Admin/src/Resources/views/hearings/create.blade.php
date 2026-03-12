<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.hearings.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.hearings.store')">
        <div class="flex flex-col gap-4">
            {{-- Header --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="hearings.create" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.hearings.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <a
                        href="{{ route('admin.hearings.index') }}"
                        class="transparent-button"
                    >
                        @lang('admin::app.hearings.create.back-btn')
                    </a>

                    <button
                        type="submit"
                        class="primary-button"
                    >
                        @lang('admin::app.hearings.create.save-btn')
                    </button>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="flex gap-5">
                <div class="flex flex-1 flex-col gap-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.hearings.create.general')
                        </div>

                        {{-- Processo --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.hearings.create.process')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_id"
                                :value="old('lead_id')"
                                rules="required"
                                :label="trans('admin::app.hearings.create.process')"
                            >
                                <option value="">@lang('admin::app.hearings.create.select-process')</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->title }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="lead_id" />
                        </x-admin::form.control-group>

                        {{-- Tipo de Audiência --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.hearings.create.hearing-type')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="hearing_type"
                                :value="old('hearing_type')"
                                rules="required"
                                :label="trans('admin::app.hearings.create.hearing-type')"
                            >
                                <option value="">@lang('admin::app.hearings.create.select-type')</option>
                                <option value="Julgamento">@lang('admin::app.hearings.create.types.trial')</option>
                                <option value="Instrução">@lang('admin::app.hearings.create.types.instruction')</option>
                                <option value="Conciliação">@lang('admin::app.hearings.create.types.conciliation')</option>
                                <option value="Audiência Preliminar">@lang('admin::app.hearings.create.types.preliminary')</option>
                                <option value="Leitura de Sentença">@lang('admin::app.hearings.create.types.sentence-reading')</option>
                                <option value="Outro">@lang('admin::app.hearings.create.types.other')</option>
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="hearing_type" />
                        </x-admin::form.control-group>

                        {{-- Data e Hora --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.hearings.create.scheduled-at')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="datetime-local"
                                name="scheduled_at"
                                :value="old('scheduled_at')"
                                rules="required"
                                :label="trans('admin::app.hearings.create.scheduled-at')"
                            />

                            <x-admin::form.control-group.error control-name="scheduled_at" />
                        </x-admin::form.control-group>

                        {{-- Tribunal --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.hearings.create.court')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="court"
                                :value="old('court')"
                                rules="required"
                                :label="trans('admin::app.hearings.create.court')"
                                :placeholder="trans('admin::app.hearings.create.court-placeholder')"
                            />

                            <x-admin::form.control-group.error control-name="court" />
                        </x-admin::form.control-group>

                        {{-- Sala --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.hearings.create.court-room')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="court_room"
                                :value="old('court_room')"
                                :label="trans('admin::app.hearings.create.court-room')"
                            />
                        </x-admin::form.control-group>

                        {{-- Juiz --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.hearings.create.judge')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="judge_name"
                                :value="old('judge_name')"
                                :label="trans('admin::app.hearings.create.judge')"
                            />
                        </x-admin::form.control-group>

                        {{-- Advogado Responsável --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.hearings.create.lawyer')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="user_id"
                                :value="old('user_id')"
                                rules="required"
                                :label="trans('admin::app.hearings.create.lawyer')"
                            >
                                <option value="">@lang('admin::app.hearings.create.select-lawyer')</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error control-name="user_id" />
                        </x-admin::form.control-group>

                        {{-- Estado --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.hearings.create.status')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                :value="old('status', 'agendada')"
                                :label="trans('admin::app.hearings.create.status')"
                            >
                                <option value="agendada">Agendada</option>
                                <option value="realizada">Realizada</option>
                                <option value="adiada">Adiada</option>
                                <option value="cancelada">Cancelada</option>
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Observações --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.hearings.create.notes')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="textarea"
                                name="notes"
                                :value="old('notes')"
                                :label="trans('admin::app.hearings.create.notes')"
                                rows="4"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
