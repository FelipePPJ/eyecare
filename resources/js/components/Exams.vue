<template>
    <div class="p-6 bg-white shadow rounded-lg">

        <!-- header -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">{{ t('exams.title') }}</h2>

            <!-- Novo exame -->
            <button class="bg-indigo-600 text-white px-4 py-2 cursor-pointer rounded-md hover:bg-indigo-700" @click="openModal()">
                {{ t('exams.new') }}
            </button>
        </div>

        <!-- Wrapper responsivo -->
        <div class="overflow-x-auto">
            <!-- Relação de exames cadastrados -->
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left whitespace-nowrap">{{ t('form.name') }}</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">{{ t('exams.group') }}</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">{{ t('exams.laterality') }}</th>
                        <th class="px-4 py-2 text-left whitespace-nowrap">{{ t('exams.comment') }}</th>
                        <th class="px-4 py-2 text-right whitespace-nowrap">{{ t('form.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop de exames -->
                    <tr v-for="exam in exams" :key="exam.id" class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ exam.name }}</td>
                        <td class="px-4 py-2">{{ exam.group?.name }}</td>
                        <td class="px-4 py-2">{{ exam.laterality ? t(exam.laterality) : '-' }}</td>
                        <td class="px-4 py-2"><span :title="exam.comment">{{ exam.comment | truncate(20) }}</span></td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <button @click="openModal(exam)" class="text-blue-600 hover:underline"><i class="fa-solid fa-pen-to-square"></i> {{ t('form.edit') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-[rgb(0,0,0)]/50 flex items-center justify-center z-50">
            <div class="bg-white m-4 p-6 rounded-lg w-full max-w-md">

                <!-- Caso exista ID = edição | se não, novo registro de exame -->
                <h3 class="text-lg font-semibold mb-4">
                    {{ form.id ? t('exams.edit') : t('exams.new') }}
                </h3>

                <!-- Mensagem geral de erro -->
                <div v-if="messageError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                    {{ messageError }}
                </div>

                <form @submit.prevent="saveExam">

                    <!-- Campos nome -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ t('form.name') }} <span class="text-red-600">*</span></label>
                        <input v-model="form.name" type="text" :placeholder="t('form.name') + '..'" class="border rounded-md w-full px-3 py-2" required />
                        <p v-if="errors.name" class="text-red-600 text-sm mt-1">{{ errors.name[0] }}</p>
                    </div>

                    <!-- Campo grupo -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ t('exams.group') }} <span class="text-red-600">*</span></label>
                        <select v-model="form.group_id" class="border rounded-md w-full px-3 py-2">
                            <option value="">{{ t('form.select') }}</option>
                            <option v-for="g in groups" :value="g.id" :key="g.id">{{ g.name }}</option>
                        </select>
                        <p v-if="errors.group_id" class="text-red-600 text-sm mt-1">{{ errors.group_id[0] }}</p>
                    </div>

                    <!-- Campo lateralidade -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ t('exams.laterality') }}</label>
                        <select v-model="form.laterality" class="border rounded-md w-full px-3 py-2">
                            <option value="">{{ t('form.select') }}</option>
                            <option v-for="opt in ['OD','OE','AO']" :value="opt" :key="opt">{{ t(opt) }}</option>
                        </select>
                        <p v-if="errors.laterality" class="text-red-600 text-sm mt-1">{{ errors.laterality[0] }}</p>
                    </div>

                    <!-- Campo comentário -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">{{ t('exams.comment') }}</label>
                        <textarea v-model="form.comment" rows="3" :placeholder="t('exams.comment') + '..'" class="border rounded-md w-full px-3 py-2"></textarea>
                        <p v-if="errors.comment" class="text-red-600 text-sm mt-1">{{ errors.comment[0] }}</p>
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
                exams: [], // Lista de exames
                groups: [], // Lista de grupos
                showModal: false,
                errors: {}, // Guarda erros de validação
                messageError: '', // Mensagem geral opcional para notificar
                // Preenchimento de modal para criação/edição de um exame
                form: {
                    id: null,
                    name: '',
                    group_id: '',
                    laterality: '',
                    comment: ''
                },
            };
        },
        // Carga inicial do componente
        async mounted() {
            await this.loadExams();
            await this.loadGroups();
        },
        // Relação de métodos e funções disponíveis
        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            // Chamada API para carga de exames
            async loadExams() {
                const res = await fetch('/api/v1/exams');
                this.exams = await res.json();
            },

            // Chamada API para carga de grupos
            async loadGroups() {
                const res = await fetch('/api/v1/groups');
                this.groups = await res.json();
            },

            // Recebe o click para abertura do modal de insert/edit exames
            openModal(exam = null) {
                this.errors = {}; // limpa erros antigos
                this.messageError = '';
                // Identifica estrutura e comportamento do form
                this.form = exam
                    ? { ...exam, group_id: exam.group?.id, laterality: exam.laterality || '' }
                    : { id: null, name: '', group_id: '', laterality: '', comment: '' };
                this.showModal = true; // Exibe modal
            },

            // Processa e salva submissão de formulário
            async saveExam() {
                this.errors = {};
                this.messageError = '';

                const method = this.form.id ? 'PUT' : 'POST';
                const url = this.form.id
                    ? `/api/v1/exams/${this.form.id}`
                    : '/api/v1/exams';

                try {
                    const res = await fetch(url, {
                        method,
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(this.form),
                    });

                    if (!res.ok) {
                        // Erro de validação
                        if (res.status === 422) {
                            const data = await res.json();
                            this.errors = this.$flattenErrors(data.errors || {});
                            this.messageError = data.message || this.t('errors.validation');
                            return;
                        }

                        // Outro erro qualquer
                        throw new Error(this.t('errors.save'));
                    }

                    // Sucesso
                    this.showModal = false;
                    await this.loadExams();

                } catch (e) {
                    this.messageError = this.t('errors.other');
                }
            }
        }
    };
</script>
