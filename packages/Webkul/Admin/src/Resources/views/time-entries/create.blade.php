<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.time-entries.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.time-entries.store')">
        <div class="flex flex-col gap-4">
            {{-- Header --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="time-entries.create" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.time-entries.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <a href="{{ route('admin.time-entries.index') }}" class="transparent-button">
                        @lang('admin::app.time-entries.create.back-btn')
                    </a>
                    <button type="submit" class="primary-button">
                        @lang('admin::app.time-entries.create.save-btn')
                    </button>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="flex gap-5">
                <div class="flex flex-1 flex-col gap-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.time-entries.create.general')
                        </div>

                        {{-- Processo --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.process')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_id"
                                :value="old('lead_id')"
                                rules="required"
                                :label="trans('admin::app.time-entries.create.process')"
                            >
                                <option value="">@lang('admin::app.time-entries.create.select-process')</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->title }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="lead_id" />
                        </x-admin::form.control-group>

                        {{-- Advogado --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.lawyer')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="user_id"
                                :value="old('user_id')"
                                rules="required"
                                :label="trans('admin::app.time-entries.create.lawyer')"
                            >
                                <option value="">@lang('admin::app.time-entries.create.select-lawyer')</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="user_id" />
                        </x-admin::form.control-group>

                        {{-- Data --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.date')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="date"
                                name="entry_date"
                                :value="old('entry_date', date('Y-m-d'))"
                                rules="required"
                                :label="trans('admin::app.time-entries.create.date')"
                            />
                            <x-admin::form.control-group.error control-name="entry_date" />
                        </x-admin::form.control-group>

                        {{-- Horas --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.hours')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="number"
                                name="hours"
                                :value="old('hours')"
                                rules="required|min_value:0.25|max_value:24"
                                :label="trans('admin::app.time-entries.create.hours')"
                                step="0.25"
                                min="0.25"
                                max="24"
                            />
                            <x-admin::form.control-group.error control-name="hours" />
                        </x-admin::form.control-group>

                        {{-- Tipo de Actividade --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.activity-type')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="activity_type"
                                :value="old('activity_type')"
                                rules="required"
                                :label="trans('admin::app.time-entries.create.activity-type')"
                            >
                                <option value="">@lang('admin::app.time-entries.create.select-type')</option>
                                @foreach ($activityTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('activity_type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="activity_type" />
                        </x-admin::form.control-group>

                        {{-- Taxa Horária (Kz) --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.time-entries.create.hourly-rate')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="number"
                                name="hourly_rate"
                                :value="old('hourly_rate')"
                                :label="trans('admin::app.time-entries.create.hourly-rate')"
                                step="0.01"
                                min="0"
                            />
                        </x-admin::form.control-group>

                        {{-- Facturável --}}
                        <x-admin::form.control-group>
                            <div class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="billable"
                                    value="1"
                                    {{ old('billable', '1') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300"
                                />
                                <label class="text-sm dark:text-white">
                                    @lang('admin::app.time-entries.create.billable')
                                </label>
                            </div>
                        </x-admin::form.control-group>

                        {{-- Descrição --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.time-entries.create.description')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                rules="required"
                                :label="trans('admin::app.time-entries.create.description')"
                                rows="4"
                            />
                            <x-admin::form.control-group.error control-name="description" />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
