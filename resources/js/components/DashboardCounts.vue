<template>
    <div class="p-6 bg-white shadow rounded-lg">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">{{ t('dashboard.resume') }}</h2>

        <div v-if="loading" class="text-gray-500">{{ t('loading') }}</div>
        <div v-else class="grid grid-cols-2 gap-4">
            <!-- Contagem de exames cadastrados -->
            <div class="bg-indigo-50 text-indigo-700 rounded-md p-4 text-center">
                <p class="text-3xl font-bold">{{ counts.exams }}</p>
                <p class="text-sm font-medium">{{ t('menu.exams') }}</p>
            </div>
            <!-- Contagem de pacotes cadastrados -->
            <div class="bg-green-50 text-green-700 rounded-md p-4 text-center">
                <p class="text-3xl font-bold">{{ counts.packages }}</p>
                <p class="text-sm font-medium">{{ t('menu.packages') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                counts: {
                    exams: 0,
                    packages: 0
                },
                loading: true,
            };
        },
        // Carga inicial do componente
        mounted() {
            this.fetchCounts();
        },
        // Métodos e funções disponíveis
        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            // Consulta API para obtenção de métricas
            async fetchCounts() {
                try {
                    // Consome API de contadores
                    const response = await fetch('/api/v1/dashboard-counts');
                    const data = await response.json();
                    this.counts = data;

                } catch (error) {
                    // Tratativa de erros
                    console.warn('Falha ao carregar dados do dashboard.', error);
                } finally {
                    // Remove loading independente de retorno
                    this.loading = false;
                }
            }
        }
    };
</script>
