<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.deadlines.create.title')
    </x-slot>

    <x-admin::form :action="route('admin.deadlines.store')">
        <div class="flex flex-col gap-4">
            {{-- Header --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="deadlines.create" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.deadlines.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <a href="{{ route('admin.deadlines.index') }}" class="transparent-button">
                        @lang('admin::app.deadlines.create.back-btn')
                    </a>
                    <button type="submit" class="primary-button">
                        @lang('admin::app.deadlines.create.save-btn')
                    </button>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="flex gap-5">
                <div class="flex flex-1 flex-col gap-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.deadlines.create.general')
                        </div>

                        {{-- Processo --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.deadlines.create.process')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_id"
                                :value="old('lead_id')"
                                rules="required"
                                :label="trans('admin::app.deadlines.create.process')"
                            >
                                <option value="">@lang('admin::app.deadlines.create.select-process')</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->title }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="lead_id" />
                        </x-admin::form.control-group>

                        {{-- Título --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.deadlines.create.title-label')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="title"
                                :value="old('title')"
                                rules="required"
                                :label="trans('admin::app.deadlines.create.title-label')"
                            />
                            <x-admin::form.control-group.error control-name="title" />
                        </x-admin::form.control-group>

                        {{-- Data de Início --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.deadlines.create.start-date')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="date"
                                name="start_date"
                                :value="old('start_date', date('Y-m-d'))"
                                rules="required"
                                :label="trans('admin::app.deadlines.create.start-date')"
                            />
                            <x-admin::form.control-group.error control-name="start_date" />
                        </x-admin::form.control-group>

                        {{-- Dias Úteis --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.deadlines.create.business-days')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="number"
                                name="business_days"
                                :value="old('business_days')"
                                :label="trans('admin::app.deadlines.create.business-days')"
                                min="1"
                                placeholder="ex: 15"
                            />
                            <div class="mt-1 text-xs text-gray-500">
                                @lang('admin::app.deadlines.create.business-days-hint')
                            </div>
                        </x-admin::form.control-group>

                        {{-- Data Limite --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.deadlines.create.due-date')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="date"
                                name="due_date"
                                :value="old('due_date')"
                                rules="required"
                                :label="trans('admin::app.deadlines.create.due-date')"
                            />
                            <x-admin::form.control-group.error control-name="due_date" />
                        </x-admin::form.control-group>

                        {{-- Prioridade --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.deadlines.create.priority')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="priority"
                                :value="old('priority', 'normal')"
                                :label="trans('admin::app.deadlines.create.priority')"
                            >
                                <option value="baixa">Baixa</option>
                                <option value="normal" selected>Normal</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Estado --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.deadlines.create.status')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                :value="old('status', 'pendente')"
                                :label="trans('admin::app.deadlines.create.status')"
                            >
                                <option value="pendente">Pendente</option>
                                <option value="em_curso">Em Curso</option>
                                <option value="concluido">Concluído</option>
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Prazo Judicial --}}
                        <x-admin::form.control-group>
                            <div class="flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    name="court_deadline"
                                    value="1"
                                    {{ old('court_deadline') ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300"
                                />
                                <label class="text-sm dark:text-white">
                                    @lang('admin::app.deadlines.create.court-deadline')
                                </label>
                            </div>
                        </x-admin::form.control-group>

                        {{-- Responsável --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.deadlines.create.lawyer')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="user_id"
                                :value="old('user_id')"
                                rules="required"
                                :label="trans('admin::app.deadlines.create.lawyer')"
                            >
                                <option value="">@lang('admin::app.deadlines.create.select-lawyer')</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="user_id" />
                        </x-admin::form.control-group>

                        {{-- Descrição --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.deadlines.create.description')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                :label="trans('admin::app.deadlines.create.description')"
                                rows="3"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
