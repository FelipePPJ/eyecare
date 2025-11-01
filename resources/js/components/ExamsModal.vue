<template>
    <div v-if="show" class="fixed inset-0 bg-[rgb(0,0,0)]/50 flex items-center justify-center z-50">
        <div class="bg-white m-4 p-6 rounded-lg w-full max-w-4xl">

            <!-- Cabeçalho -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Selecionar Exames</h3>
            </div>

            <!-- Carregando -->
            <div v-if="loading" class="text-center text-gray-500 py-6">
                {{ t('loading') }}
            </div>

            <!-- Corpo -->
            <div v-else class="overflow-y-auto max-h-[60vh] border rounded mb-4">
                <!-- Wrapper responsivo -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left w-10"></th>
                                <th class="px-3 py-2 text-left">Nome</th>
                                <th class="px-3 py-2 text-left">Grupo</th>
                                <th class="px-3 py-2 text-left">Lateralidade</th>
                                <th class="px-3 py-2 text-left">Comentário</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="exam in exams"
                                :key="exam.id"
                                class="border-t hover:bg-gray-50"
                                >
                                <td class="px-3 py-2 text-center">
                                    <input
                                        type="checkbox"
                                        class="w-4 h-4"
                                        v-model="selected"
                                        :value="exam"
                                    />
                                </td>
                                <td class="px-3 py-2 flex-nowrap">{{ exam.name }}</td>
                                <td class="px-3 py-2">{{ exam.group?.name || '-' }}</td>
                                <td class="px-3 py-2">{{ exam.laterality ? t(exam.laterality) : '-' }}</td>
                                <td class="px-3 py-2">{{ exam.comment || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!exams.length" class="text-center text-gray-500 py-4">
                        Nenhum exame cadastrado.
                    </div>
                </div>
            </div>
            <!-- Ações -->
            <div class="flex justify-end space-x-2">
                <button
                    @click="$emit('close')"
                    class="px-3 py-2 border rounded-md"
                    >
                Cancelar
                </button>
                <button
                    @click="confirmSelection"
                    :disabled="!selected.length"
                    class="px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                    >
                Incluir Selecionados
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: { type: Boolean, default: false }
        },
        data() {
            return {
                loading: true,
                exams: [],
                selected: []
            };
        },
        watch: {
            show(value) {
                if (value) {
                    this.loadExams();
                    this.selected = [];
                }
            }
        },
        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            async loadExams() {
                this.loading = true;
                try {
                    const res = await fetch('/api/v1/exams');
                    this.exams = await res.json();
                } catch (e) {
                    console.error('Erro ao carregar exames', e);
                } finally {
                    this.loading = false;
                }
            },
            confirmSelection() {
                // Emite para o pai os exames selecionados
                this.$emit('selected', this.selected);
                this.selected = [];
                this.$emit('close');
            }
        }
    };
</script>
