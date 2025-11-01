import './bootstrap';

import Vue from 'vue/dist/vue.esm.js';

import { truncate } from './utils/filter' // Filtro para strings
import { flattenErrors } from './utils/errors' // Flatten de erros

import Requests from './components/Requests.vue'; // Construtor de tela principal para componente de solicitações de exames
import DashboardCounts from './components/DashboardCounts.vue'; // Componente reativo para exibir métricas do controle de exames
import Exams from './components/Exams.vue'; // Componente reativo para CRUD padrão de exames
import Packages from './components/Packages.vue'; // Componente reativo para CRUD padrão de pacotes de exames

/**
 * Registro global para filtros
 */
Vue.filter('truncate', truncate);

/**
 * Métodos globais
 */
Vue.prototype.$flattenErrors = flattenErrors;

/**
 * Componentes
 */
Vue.component('dashboard-counts', DashboardCounts);
Vue.component('exams', Exams);
Vue.component('packages', Packages);
Vue.component('requests', Requests);

new Vue({
    el: '#app',
});
