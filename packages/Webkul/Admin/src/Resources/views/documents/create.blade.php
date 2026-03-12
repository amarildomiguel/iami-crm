<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.documents.create.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.documents.store')"
        enctype="multipart/form-data"
    >
        <div class="flex flex-col gap-4">
            {{-- Header --}}
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="documents.create" />
                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.documents.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <a href="{{ route('admin.documents.index') }}" class="transparent-button">
                        @lang('admin::app.documents.create.back-btn')
                    </a>
                    <button type="submit" class="primary-button">
                        @lang('admin::app.documents.create.save-btn')
                    </button>
                </div>
            </div>

            {{-- Form Body --}}
            <div class="flex gap-5">
                <div class="flex flex-1 flex-col gap-2">
                    <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.documents.create.general')
                        </div>

                        {{-- Título --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.documents.create.title-label')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="title"
                                :value="old('title')"
                                rules="required"
                                :label="trans('admin::app.documents.create.title-label')"
                            />
                            <x-admin::form.control-group.error control-name="title" />
                        </x-admin::form.control-group>

                        {{-- Tipo de Documento --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.documents.create.type')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="document_type"
                                :value="old('document_type')"
                                rules="required"
                                :label="trans('admin::app.documents.create.type')"
                            >
                                <option value="">@lang('admin::app.documents.create.select-type')</option>
                                @foreach ($documentTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('document_type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                            <x-admin::form.control-group.error control-name="document_type" />
                        </x-admin::form.control-group>

                        {{-- Processo --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.process')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="lead_id"
                                :value="old('lead_id')"
                                :label="trans('admin::app.documents.create.process')"
                            >
                                <option value="">@lang('admin::app.documents.create.select-process')</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('lead_id') == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->title }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Cliente --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.client')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="person_id"
                                :value="old('person_id')"
                                :label="trans('admin::app.documents.create.client')"
                            >
                                <option value="">@lang('admin::app.documents.create.select-client')</option>
                                @foreach ($persons as $person)
                                    <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                        {{ $person->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Advogado Responsável --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.documents.create.lawyer')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="user_id"
                                :value="old('user_id')"
                                rules="required"
                                :label="trans('admin::app.documents.create.lawyer')"
                            >
                                <option value="">@lang('admin::app.documents.create.select-lawyer')</option>
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
                                @lang('admin::app.documents.create.status')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="select"
                                name="status"
                                :value="old('status', 'rascunho')"
                                :label="trans('admin::app.documents.create.status')"
                            >
                                <option value="rascunho">Rascunho</option>
                                <option value="revisao">Em Revisão</option>
                                <option value="finalizado">Finalizado</option>
                                <option value="protocolado">Protocolado</option>
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        {{-- Data Limite --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.due-date')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="date"
                                name="due_date"
                                :value="old('due_date')"
                                :label="trans('admin::app.documents.create.due-date')"
                            />
                        </x-admin::form.control-group>

                        {{-- Data de Protocolo --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.filing-date')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="date"
                                name="filing_date"
                                :value="old('filing_date')"
                                :label="trans('admin::app.documents.create.filing-date')"
                            />
                        </x-admin::form.control-group>

                        {{-- Referência no Tribunal --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.court-reference')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="court_reference"
                                :value="old('court_reference')"
                                :label="trans('admin::app.documents.create.court-reference')"
                            />
                        </x-admin::form.control-group>

                        {{-- Descrição --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.description')
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="textarea"
                                name="description"
                                :value="old('description')"
                                :label="trans('admin::app.documents.create.description')"
                                rows="3"
                            />
                        </x-admin::form.control-group>

                        {{-- Ficheiro --}}
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.documents.create.file')
                            </x-admin::form.control-group.label>
                            <input
                                type="file"
                                name="file"
                                class="w-full rounded-md border border-gray-300 p-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
