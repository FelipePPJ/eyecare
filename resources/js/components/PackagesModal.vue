<template>
    <div v-if="show" class="fixed inset-0 bg-[rgb(0,0,0)]/50 flex items-center justify-center z-50">
        <div class="bg-white m-4 p-6 rounded-lg w-full max-w-4xl">

            <!-- Cabeçalho -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Selecionar Pacotes</h3>
            </div>

            <!-- Carregando -->
            <div v-if="loading" class="text-center text-gray-500 py-6">
                {{ t('loading') }}
            </div>

            <!-- Lista de pacotes -->
            <div v-else class="overflow-y-auto max-h-[60vh] border rounded mb-4">
                <!-- Wrapper responsivo -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 text-left w-10"></th>
                                <th class="px-3 py-2 text-left">Nome</th>
                                <th class="px-3 py-2 text-left">Observações</th>
                                <th class="px-3 py-2 text-left">Qtd. Exames</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="pkg in packages"
                                :key="pkg.id"
                                class="border-t hover:bg-gray-50"
                                >
                                <td class="px-3 py-2 text-center">
                                    <input
                                        class="w-4 h-4"
                                        type="checkbox"
                                        v-model="selected"
                                        :value="pkg.id"
                                        />
                                </td>
                                <td class="px-3 py-2 flex-nowrap">{{ pkg.name }}</td>
                                <td class="px-3 py-2">{{ pkg.observations | truncate(30) }}</td>
                                <td class="px-3 py-2">{{ pkg.exams?.length || 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="!packages.length" class="text-center text-gray-500 py-4">
                        Nenhum pacote cadastrado.
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
            show: Boolean
        },
        data() {
            return {
                loading: false,
                packages: [],
                selected: []
            };
        },
        async mounted() {
            await this.loadPackages();
        },
        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            async loadPackages() {
                this.loading = true;
                try {
                    const res = await fetch('/api/v1/packages');
                    this.packages = await res.json();
                } catch (e) {
                    console.error('Erro ao carregar pacotes', e);
                } finally {
                    this.loading = false;
                }
            },

            confirmSelection() {
                const selected = this.packages.filter(p => this.selected.includes(p.id));
                this.$emit('selected', selected);
                this.selected = [];
                this.$emit('close');
            }
        }
    };
</script>
