<template>
    <div class="p-6 bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">{{ t('requests.title') }}</h2>

            <div class="flex gap-2">
                <!-- Exportar para PDF -->
                <button
                    @click="exportPdf"
                    :disabled="!hasContent"
                    class="bg-red-600 text-white px-4 py-2 rounded-md cursor-pointer hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <i class="fa-solid fa-file-pdf"></i>
                    {{ t('form.export') }}
                </button>
                <!-- Incluir pacote -->
                <button
                    @click="showPackagesModal = true"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md cursor-pointer hover:bg-indigo-700"
                    >
                    {{ t('requests.new_packages') }}
                </button>
                <!-- Incluir exames avulsos -->
                <button
                    @click="showExamsModal = true"
                    class="bg-emerald-600 text-white px-4 py-2 rounded-md cursor-pointer hover:bg-emerald-700"
                    >
                    {{ t('requests.new_exams') }}
                </button>
            </div>
        </div>

        <!-- BOX DE EXAMES AVULSOS -->
        <div v-if="standaloneExams.length" class="border-2 border-dashed border-cyan-600 rounded-lg mb-6 p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 w-3/5">{{ t('requests.exams') }}</h3>

                <!-- Select de grupo geral -->
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">{{ t('requests.general_group') }}:</label>
                    <select v-model="globalGroupAvulsos" @change="applyGroupToAllAvulsos" class="border rounded px-2 py-1 text-sm">
                        <option value="">{{ t('form.select') }}</option>
                        <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
                    </select>
                </div>

                <button
                    @click="standaloneExams = []"
                    class="text-sm text-red-600 hover:underline"
                    >
                    <i class="fa-solid fa-trash-can"></i> {{ t('requests.clean_standalone') }}
                </button>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left w-80">{{ t('form.name') }}</th>
                        <th class="px-3 py-2 text-left w-10">{{ t('exams.laterality') }}</th>
                        <th class="px-3 py-2 text-left w-10">{{ t('exams.group') }}</th>
                        <th class="px-3 py-2 text-left">{{ t('exams.comment') }}</th>
                        <th class="px-3 py-2 text-right">{{ t('form.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(exam, index) in standaloneExams" :key="'avulso-'+index" class="border-t">
                        <td class="px-2 py-3">{{ exam.name }}</td>
                        <td class="px-2 py-3">
                            <select v-model="exam.laterality" class="border rounded px-2 py-1">
                                <option value="">{{ t('form.select') }}</option>
                                <option v-for="opt in ['OD','OE','AO']" :key="opt" :value="opt">{{ t(opt) }}</option>
                            </select>
                        </td>
                        <td class="px-2 py-3">
                            <select v-model="exam.group_id" class="border rounded px-2 py-1">
                                <option value="">{{ t('form.select') }}</option>
                                <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
                            </select>
                        </td>
                        <td class="px-2 py-3">
                            <input type="text" v-model="exam.comment" class="border rounded px-2 py-1 w-full" />
                        </td>
                        <td class="px-2 py-3 text-right">
                            <!-- Remover elemento -->
                            <button @click="removeStandaloneExam(index)" class="text-red-600 hover:underline cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Observações do box -->
            <div class="mt-4">
                <label class="text-gray-500 text-right">{{ t('form.observations') }}:</label>
                <textarea
                    v-model="standaloneObservations"
                    class="border rounded-md w-full px-3 py-2"
                    placeholder="Observações.."
                ></textarea>
            </div>
        </div>

        <!-- BOX DE PACOTES -->
        <div v-for="(pkg, index) in selectedPackages" :key="pkg.id" class="border-2 border-cyan-600 border-dashed rounded-lg mb-6 p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-800 w-3/5">{{ pkg.name }}</h3>

                <!-- Select de grupo geral -->
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">{{ t('requests.general_group') }}:</label>
                    <select v-model="pkg.globalGroup" @change="applyGroupToAllInPackage(index)" class="border rounded px-2 py-1 text-sm">
                        <option value="">{{ t('form.select') }}</option>
                        <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
                    </select>
                </div>

                <button
                    @click="removePackage(index)"
                    class="text-sm text-red-600 hover:underline"
                    >
                    <i class="fa-solid fa-trash-can"></i> {{ t('requests.clean_package') }}
                </button>
            </div>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left w-80">{{ t('form.name') }}</th>
                        <th class="px-3 py-2 text-left w-10">{{ t('packages.laterality') }}</th>
                        <th class="px-3 py-2 text-left w-10">{{ t('packages.group') }}</th>
                        <th class="px-3 py-2 text-left">{{ t('packages.comment') }}</th>
                        <th class="px-3 py-2 text-right">{{ t('form.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(exam, eindex) in pkg.exams"
                        :key="'pkg-'+pkg.id+'-'+eindex"
                        class="border-t"
                        >
                        <td class="px-2 py-3">{{ exam.name }}</td>
                        <td class="px-2 py-3">
                            <select v-model="exam.pivot.laterality" class="border rounded px-2 py-1">
                                <option value="">{{ t('form.select') }}</option>
                                <option v-for="opt in ['OD','OE','AO']" :key="opt" :value="opt">{{ t(opt) }}</option>
                            </select>
                        </td>
                        <td class="px-2 py-3">
                            <select v-model="exam.pivot.group_id" class="border rounded px-2 py-1">
                                <option value="">{{ t('form.select') }}</option>
                                <option v-for="g in groups" :key="g.id" :value="g.id">{{ g.name }}</option>
                            </select>
                        </td>
                        <td class="px-2 py-3">
                            <input
                                type="text"
                                v-model="exam.pivot.comment"
                                class="border rounded px-2 py-1 w-full"
                                />
                        </td>
                        <td class="px-2 py-3 text-right">
                            <!-- Remover elemento -->
                            <button @click="removeExamFromPackage(index, eindex)" class="text-red-600 hover:underline cursor-pointer">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- Observações do box -->
            <div class="mt-4">
                <label class="text-gray-500 text-right">{{ t('form.observations') }}:</label>
                <textarea
                    v-model="pkg.observations"
                    class="border rounded-md w-full px-3 py-2"
                    placeholder="Observações.."
                ></textarea>
            </div>
        </div>

        <!-- Modais -->
        <packages-modal
            :show="showPackagesModal"
            @close="showPackagesModal = false"
            @selected="addPackages"
            ></packages-modal>
        <exams-modal
            :show="showExamsModal"
            @close="showExamsModal = false"
            @selected="addStandaloneExams"
            ></exams-modal>
    </div>
</template>

<script>
    // Importa modais para carga de seleção de exames e pacotes
    import PackagesModal from './PackagesModal.vue'
    import ExamsModal from './ExamsModal.vue'

    export default {
        components: {
            PackagesModal,
            ExamsModal
        },

        data() {
            return {
                showPackagesModal: false,
                showExamsModal: false,
                selectedPackages: [],
                standaloneExams: [],
                groups: [],
                globalGroupAvulsos: '',
                standaloneObservations: '',
            }
        },

        // Inicialização
        async mounted() {
            await this.loadGroups()
        },

        computed: {
            // Habilita botão exportar apenas se existir conteúdo já carregado na solicitação
            hasContent() {
                return this.standaloneExams.length || this.selectedPackages.length
            }
        },

        methods: {
            // Carga de traduções injetadas no front
            t(key) {
                // Helper para auxiliar o acesso às traduções via subarray (ex.: 'menu.exams')
                return key.split('.').reduce((o, i) => o[i] ?? null, window.i18n) ?? key;
            },

            // Realiza carga de grupos via API
            async loadGroups() {
                const res = await fetch('/api/v1/groups')
                this.groups = await res.json()
            },

            // Adiciona um pacode de exames
            addPackages(selected) {
                selected.forEach(pkg => {
                    if (!this.selectedPackages.some(p => p.id === pkg.id)) {
                        this.selectedPackages.push({
                            ...pkg,
                            globalGroup: '',
                            exams: pkg.exams.map(e => ({
                                ...e,
                                pivot: {
                                    ...e.pivot,
                                    laterality: e.pivot?.laterality || '',
                                    group_id: e.pivot?.group_id || '',
                                    comment: e.pivot?.comment || ''
                                }
                            }))
                        });
                    }
                });
            },

            // Remove um pacote inteiro
            removePackage(index) {
                this.selectedPackages.splice(index, 1)
            },

            // Remove apenas 1 exame de dentro de um pacote
            removeExamFromPackage(pkgIndex, examIndex) {
                this.selectedPackages[pkgIndex].exams.splice(examIndex, 1)
            },

            // Adiciona exames avulsos
            addStandaloneExams(selected) {
                selected.forEach(exam => {
                    if (!this.standaloneExams.some(e => e.id === exam.id)) {
                        // Carrega padrão da tabela de exames
                        this.standaloneExams.push({
                            ...exam,
                            group_id: exam.group_id || '',
                            laterality: exam.laterality || '',
                            comment: exam.comment || ''
                        })
                    }
                })
            },

            // Remove exames avulsos
            removeStandaloneExam(index) {
                this.standaloneExams.splice(index, 1)
            },

            // Aplica um grupo padrão para que fazem parte do box exames avulsos
            applyGroupToAllAvulsos() {
                if (!this.globalGroupAvulsos) return
                this.standaloneExams = this.standaloneExams.map(exam => ({
                    ...exam,
                    group_id: this.globalGroupAvulsos
                }))
            },

            // Aplica um grupo padrão para o box do pacote em questão
            applyGroupToAllInPackage(pkgIndex) {
                const pkg = this.selectedPackages[pkgIndex]
                if (!pkg.globalGroup) return
                pkg.exams = pkg.exams.map(exam => ({
                    ...exam,
                    pivot: {
                        ...exam.pivot,
                        group_id: pkg.globalGroup,
                        laterality: exam.pivot.laterality || ''
                    }
                }))
            },

            // Gerar/Exportar dados de solicitação de exame. Envia via API para o back processar e gerar PDF
            async exportPdf() {
                // Monta o payload completo para enviar ao backend
                const payload = {
                    boxes: [
                        // Exames avulsos
                        {
                            type: 'avulsos',
                            observations: this.standaloneObservations,
                            exams: this.standaloneExams.map(e => ({
                                ...e,
                                is_individual: e.group_id == 1, // Marca grupo 'individual'
                                group_id: e.group_id || '',
                                laterality: e.laterality || '',
                                comment: e.comment || ''
                            }))
                        },
                        // Pacotes de exames
                        ...this.selectedPackages.map(pkg => ({
                            type: 'package',
                            name: pkg.name,
                            observations: pkg.observations ?? '',
                            exams: pkg.exams.map(e => ({
                                ...e,
                                is_individual: (e.pivot?.group_id || e.group_id) == 1, // Marca grupo 'individual'
                                group_id: e.pivot?.group_id || e.group_id,
                                laterality: e.pivot?.laterality || e.laterality,
                                comment: e.pivot?.comment || e.comment
                            }))
                        }))
                    ]
                }

                // Se não há nada para exportar, não faz nada
                if (!payload.boxes.some(box => box.exams.length)) {
                    alert(this.t('errors.pdf_export_nothing'))
                    return
                }

                try {
                    const res = await fetch('/api/v1/pdf/export', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    })

                    if (!res.ok) throw new Error(this.t('errors.pdf_generate'))

                    // Abre o PDF em nova aba
                    const blob = await res.blob()
                    const url = window.URL.createObjectURL(blob)
                    window.open(url, '_blank')

                } catch (err) {
                    console.error(err)
                    alert(this.t('errors.pdf_erro'))
                }
            }
        }
    }
</script>
