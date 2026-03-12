@props([
    'provinceValue'      => '',
    'municipalityValue'  => '',
    'communeValue'       => '',
    'provinceName'       => 'province',
    'municipalityName'   => 'municipality',
    'communeName'        => 'commune',
    'required'           => false,
])

<div
    x-data="angolaAddress({
        province: '{{ $provinceValue }}',
        municipality: '{{ $municipalityValue }}',
        commune: '{{ $communeValue }}'
    })"
    class="flex flex-col gap-3"
>
    <!-- Província -->
    <div class="flex flex-col gap-1">
        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">
            @lang('admin::app.common.angola-address.province')
            @if($required) <span class="text-red-600">*</span> @endif
        </label>
        <select
            name="{{ $provinceName }}"
            x-model="province"
            @change="onProvinceChange()"
            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
            {{ $required ? 'required' : '' }}
        >
            <option value="">— @lang('admin::app.common.angola-address.select-province') —</option>
            <template x-for="prov in provinces" :key="prov.code">
                <option :value="prov.name" :selected="province === prov.name" x-text="prov.name"></option>
            </template>
        </select>
    </div>

    <!-- Município -->
    <div class="flex flex-col gap-1" x-show="province">
        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">
            @lang('admin::app.common.angola-address.municipality')
        </label>
        <select
            name="{{ $municipalityName }}"
            x-model="municipality"
            @change="onMunicipalityChange()"
            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
        >
            <option value="">— @lang('admin::app.common.angola-address.select-municipality') —</option>
            <template x-for="mun in municipalities" :key="mun">
                <option :value="mun" :selected="municipality === mun" x-text="mun"></option>
            </template>
        </select>
    </div>

    <!-- Comuna -->
    <div class="flex flex-col gap-1" x-show="municipality">
        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">
            @lang('admin::app.common.angola-address.commune')
        </label>
        <input
            type="text"
            name="{{ $communeName }}"
            x-model="commune"
            placeholder="@lang('admin::app.common.angola-address.commune-placeholder')"
            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
        />
    </div>
</div>

@once
<script>
function angolaAddress(initial) {
    const data = {
        'Bengo': ['Ambriz', 'Bula Atumba', 'Dande', 'Dembos', 'Nambuangongo', 'Pango Aluquém'],
        'Benguela': ['Balombo', 'Benguela', 'Baía Farta', 'Bocoio', 'Caimbambo', 'Catumbela', 'Chongorói', 'Cubal', 'Ganda', 'Lobito'],
        'Bié': ['Andulo', 'Camacupa', 'Catabola', 'Chitato', 'Cuemba', 'Cunhinga', 'Kuito', 'Malanje', 'Nharea'],
        'Cabinda': ['Belize', 'Buco-Zau', 'Cabinda', 'Cacongo'],
        'Cubango': ['Cuchi', 'Cuito Cuanavale', 'Dirico', 'Longa', 'Mavinga', 'Menongue', 'Nancova', 'Rivungo'],
        'Cuando': ['Calai', 'Cuangar', 'Cuchi', 'Dirico', 'Longa', 'Mavinga', 'Menongue', 'Nancova'],
        'Cuanza‑Norte': ['Ambaca', 'Bolongongo', 'Cangandala', 'Cazengo', 'Golungo Alto', 'Gonguembo', 'Lucala', 'Malanje', 'Quiculungo', 'Samba Cajú'],
        'Cuanza‑Sul': ['Amboim', 'Cassongue', 'Cela', 'Conda', 'Ebo', 'Libolo', 'Mussende', 'Porto Amboim', 'Quibala', 'Quilenda', 'Seles', 'Sumbe'],
        'Cunene': ['Cahama', 'Cuanhama', 'Curoca', 'Namacunde', 'Ombadja'],
        'Huambo': ['Bailundo', 'Caála', 'Catchiungo', 'Chicala-Cholohanga', 'Chimoio', 'Ecunha', 'Huambo', 'Longonjo', 'Mungo', 'Ukuma', 'Chicala Cholohanga'],
        'Huíla': ['Cacula', 'Caconda', 'Caluquembe', 'Chiange', 'Chibia', 'Chicomba', 'Chipindo', 'Cuvango', 'Gambos', 'Humpata', 'Jamba', 'Lubango', 'Matala', 'Quilengues', 'Quipungo'],
        'Icolo e Bengo': ['Cazenga', 'Icolo e Bengo', 'Quiçama', 'Viana'],
        'Luanda': ['Belas', 'Cacuaco', 'Cazenga', 'Ícolo e Bengo', 'Kilamba Kiaxi', 'Luanda', 'Quiçama', 'Talatona', 'Viana'],
        'Lunda‑Norte': ['Alto Chicapa', 'Cambulo', 'Capenda-Camulemba', 'Caungula', 'Chitato', 'Cuango', 'Cuílo', 'Lóvua', 'Lucapa', 'Xá-Muteba'],
        'Lunda‑Sul': ['Cacolo', 'Dala', 'Muconda', 'Saurimo'],
        'Malanje': ['Cacuso', 'Calandula', 'Cambundi-Catembo', 'Cangandala', 'Caombo', 'Cuaba Nzoji', 'Cunda-Dia-Baze', 'Luquembo', 'Malanje', 'Marimba', 'Massango', 'Mucari', 'Quela', 'Quirima'],
        'Moxico': ['Alto Zambeze', 'Bundas', 'Camanongue', 'Léua', 'Luau', 'Luchazes', 'Lumeje', 'Luena'],
        'Moxico Leste': ['Luau', 'Luchazes'],
        'Namibe': ['Bibala', 'Camucuio', 'Moçâmedes', 'Tômbua', 'Virei'],
        'Uíge': ['Ambuíla', 'Bembe', 'Buengas', 'Bungo', 'Damba', 'Kibaxe', 'Macocola', 'Maquela do Zombo', 'Milunga', 'Mucaba', 'Negage', 'Puri', 'Quimbele', 'Quitexe', 'Sanza Pombo', 'Songo', 'Uíge'],
        'Zaire': ['Cuimba', 'M\'banza Congo', 'Noqui', 'Nzeto', 'Soyo', 'Tomboco'],
    };

    const provinceList = Object.keys(data).map(name => ({ name, code: name }));

    return {
        province: initial.province || '',
        municipality: initial.municipality || '',
        commune: initial.commune || '',
        provinces: provinceList,
        municipalities: [],

        init() {
            if (this.province) {
                this.municipalities = data[this.province] || [];
            }
        },

        onProvinceChange() {
            this.municipalities = data[this.province] || [];
            this.municipality = '';
            this.commune = '';
        },

        onMunicipalityChange() {
            this.commune = '';
        },
    };
}
</script>
@endonce
