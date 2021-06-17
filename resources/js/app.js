/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

import VueGoodTablePlugin from 'vue-good-table';
import Router from 'vue-router';
import Buefy from 'buefy';

Vue.use(VueGoodTablePlugin);
Vue.use(Router);
Vue.use(Buefy);

Vue.prototype.$http = axios;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('solr-cloud', require('./components/SolrCloud.vue').default);
Vue.component('solr-cloud-table', require('./components/SolrCloudTable.vue').default);
Vue.component('solr-node-info', require('./components/SolrNodeInfo.vue').default);
Vue.component('solr-ops', require('./components/SolrOps.vue').default);
Vue.component('solr-ops-errors', require('./components/SolrOpsErrors.vue').default);
Vue.component('solr-running-map', require('./components/SolrRunningMap.vue').default);
Vue.component('solr-state', require('./components/SolrState.vue').default);
Vue.component('solr-shard-graph', require('./components/SolrShardGraph.vue').default);
Vue.component('solr-disk-usage-group-by', require('./components/SolrDiskUsageGroupBy.vue').default);
Vue.component('solr-nodes', require('./components/SolrNodes.vue').default);
Vue.component('solr-shard-cnt', require('./components/SolrShardCnt.vue').default);

const app = new Vue({
    el: '#app',
});
