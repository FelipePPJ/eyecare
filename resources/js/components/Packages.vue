<template>
    <div class="p-6 bg-white shadow rounded-lg">

        <!-- header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">{{ t('packages.title') }}</h2>

            <!-- Novo exame -->
            <button class="bg-indigo-600 text-white px-4 py-2 cursor-pointer rounded-md hover:bg-indigo-700" @click="openModal()">
                {{ t('packages.new') }}
            </button>
        </div>

        <!-- Wrapper responsivo -->
        <div class="overflow-x-auto">
            <!-- Relação de pacotes cadastrados -->
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">{{ t('form.name') }}</th>
                        <th class="px-4 py-2 text-left">{{ t('packages.exams_count') }}</th>
                        <th class="px-4 py-2 text-left">{{ t('form.observations') }}</th>
                        <th class="px-4 py-2 text-right">{{ t('form.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop de pacotes -->
                    <tr v-for="pkg in packages" :key="pkg.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ pkg.name }}</td>
                        <td class="px-4 py-2">{{ pkg.exams?.length || 0 }}</td>
                        <td class="px-4 py-2" :title="pkg.observations">{{ pkg.observations | truncate(30) }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <button @click="openModal(pkg)" class="text-blue-600 cursor-pointer hover:underline"><i class="fa-solid fa-pen-to-square"></i> {{ t('form.edit') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-[rgb(0,0,0)]/50 flex items-center justify-center z-50">
            <div class="bg-white m-4 p-6 rounded-lg w-full max-w-3xl overflow-y-auto max-h-[90vh]">

                <!-- Cabeçalho -->
                <h3 class="text-lg font-semibold mb-4">
                    {{ form.id ? t('packages.edit') : t('packages.new') }}
                </h3>

                <div v-if="messageError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    {{ messageError }}
                </div>

                <form @submit.prevent="savePackage">

                    <!-- Nome do pacote -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ t('form.name') }} <span class="text-red-600">*</span></label>
                        <input v-model="form.name" type="text" :placeholder="t('form.name') + '..'" class="border rounded-md w-full px-3 py-2" required />
                        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
                    </div>

                    <!-- Observações -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">{{ t('form.observations') }}</label>
                        <textarea v-model="form.observations" rows="3" :placeholder="t('form.observations') + '..'" class="border rounded-md w-full px-3 py-2"></textarea>
                        <p v-if="errors.observations" class="text-red-600 text-sm mt-1">{{ errors.observations[0] }}</p>
                    </div>

                    <!-- Lista de exames associados -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <h4 class="font-medium">{{ t('packages.exams') }} <span class="text-red-600">*</span></h4>
                            <button type="button" @click="addExamRow" class="text-sm bg-green-600 cursor-pointer text-white px-3 py-1 rounded-md hover:bg-green-700">
                                + {{ t('packages.add_exam') }}
                            </button>
                        </div>

                        <div class="overflow-x-auto border rounded-md">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-2 py-1 text-left">{{ t('form.name') }}</th>
                                        <th class="px-2 py-1 text-left w-28">{{ t('exams.group') }}</th>
                                        <th class="px-2 py-1 text-left">{{ t('exams.laterality') }}</th>
                                        <th class="px-2 py-1 text-left">{{ t('exams.comment') }}</th>
                                        <th class="px-2 py-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in form.exams" :key="index" class="border-t">
                                        <td class="px-2 py-3">
                                            <select
                                                v-model="row.exam_id"
                                                class="border rounded-md w-full px-2 py-1"
                                                @change="handleExamChange(row, index)"
                                            >
                                                <option value="">{{ t('form.select') }}</option>
                                                <option
                                                    v-for="exam in availableExams(row.exam_id)"
                                                    :value="exam.id"
                                                    :key="exam.id"
                                                >
                                                    {{ exam.name }}
                                                </option>
                                            </select>
                                        </td>

                                        <td class="px-2 py-3">
                                            <select v-model="row.group_id" class="border rounded-md w-full px-2 py-1">
                                                <option value="">{{ t('form.select') }}</option>
                                                <option v-for="g in groups" :value="g.id" :key="g.id">{{ g.name }}</option>
                                            </select>
                                        </td>

                                        <td class="px-2 py-3">
                                            <select v-model="row.laterality" class="border rounded-md w-full px-2 py-1">
                                                <option value="">{{ t('form.select') }}</option>
                                                <option v-for="opt in ['OD','OE','AO']" :value="opt" :key="opt">{{ t(opt) }}</option>
                                            </select>
                                        </td>

                                        <td class="px-2 py-3">
                                            <input v-model="row.comment" type="text" class="border rounded-md w-full px-2 py-1" :placeholder="t('exams.comment') + '..'">
                                        </td>

                                        <td class="px-2 py-3 text-center">
                                            <button type="button" @click="removeExamRow(index)" class="text-red-600 cursor-pointer hover:underline px-2">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <!-- {{ t('form.remove') }} -->
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Erros gerais de validação -->
                        <div v-if="Object.keys(errors).length" class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 mt-4">
                            <h4 class="font-semibold mb-2">{{ t('errors.validation') }}</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                <li v-for="(msgs, field) in errors" :key="field">
                                    <span v-for="msg in msgs" :key="msg">{{ msg }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="showModal=false" class="px-3 py-2 border rounded-md">
                            {{ t('form.cancel') }}
                        </button>
                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            {{ t('form.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div> <!-- .modal -->

    </div>
</template>

<script>
    export default {
        data() {
            return {
                packages: [],
                exams: [],
                groups: [],
                showModal: false,
                errors: {},
                messageError: '',
                form: {
                    id: null,
                    name: '',
                    observations: '',
                    // Relacionamento packages_vs_exams
                    exams: [
                        { exam_id: '', group_id: '', laterality: '', comment: '' }
                    ]
                }
            };
        },
        // Inicialização do componente
        async mounted() {
            await this.loadPackages();
            await this.loadExams();
            await this.loadGroups();
        },
        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            // Carga de pacotes
            async loadPackages() {
                const res = await fetch('/api/v1/packages');
                this.packages = await res.json();
            },

            // Carga de exames
            async loadExams() {
                const res = await fetch('/api/v1/exams');
                this.exams = await res.json();
            },

            // Carga de grupos
            async loadGroups() {
                const res = await fetch('/api/v1/groups');
                this.groups = await res.json();
            },

            // Abertura de modal para registro/edição de pacote de exames
            openModal(pkg = null) {
                this.errors = {};
                this.messageError = '';

                if (pkg) {
                    // edição
                    this.form = {
                        id: pkg.id,
                        name: pkg.name,
                        observations: pkg.observations || '',
                        exams: pkg.exams?.map(e => ({
                            exam_id: e.id,
                            group_id: e.pivot?.group_id || '',
                            laterality: e.pivot?.laterality || '',
                            comment: e.pivot?.comment || ''
                        })) || []
                    };
                } else {
                    // novo
                    this.form = {
                        id: null,
                        name: '',
                        observations: '',
                        exams: [{ exam_id: '', group_id: '', laterality: '', comment: '' }]
                    };
                }

                this.showModal = true;
            },

            // Adiciona nova linha no modal para inserção de um novo exame
            addExamRow() {
                this.form.exams.push({ exam_id: '', group_id: '', laterality: '', comment: '' });
            },

            // Remove linha de exame da associação do pacote
            removeExamRow(index) {
                this.form.exams.splice(index, 1);
            },

            // Quando um exame é carregado, alterado, carrega os dados pré-cadastrados para os selects
            handleExamChange(row, index) {
                const selectedExam = this.exams.find(e => e.id === row.exam_id);
                if (!selectedExam) return;

                // Preenche automaticamente campos relacionados
                this.form.exams[index].group_id = selectedExam.group?.id || '';
                this.form.exams[index].laterality = selectedExam.laterality || '';
                this.form.exams[index].comment = selectedExam.comment || '';
            },

            // Para evitar exames duplicados retorna lista de exames que ainda não foram selecionados
            availableExams(currentExamId = null) {
                const selectedIds = this.form.exams
                    .map(e => e.exam_id)
                    .filter(id => id && id !== currentExamId);

                return this.exams.filter(e => !selectedIds.includes(e.id));
            },

            // Submit de form/modal, cadastro/edição de pacotes de exames
            async savePackage() {
                this.errors = {};
                this.messageError = '';

                const method = this.form.id ? 'PUT' : 'POST';
                const url = this.form.id
                    ? `/api/v1/packages/${this.form.id}`
                    : '/api/v1/packages';

                try {
                    const res = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form),
                    });

                    if (!res.ok) {
                        if (res.status === 422) {
                            const data = await res.json();
                            this.errors = this.$flattenErrors(data.errors || {});
                            this.messageError = data.message || this.t('errors.validation');
                            return;
                        }

                        throw new Error(this.t('errors.save'));
                    }

                    this.showModal = false;
                    await this.loadPackages();

                } catch (e) {
                    this.messageError = this.t('errors.other');
                }
            }
        }
    };
</script>
