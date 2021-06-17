<template>
    <hot-table ref="nodetable"
               v-if="data"
               :data="data"
               :colHeaders="colHeaders"
               columnSorting="true"
               licenseKey="non-commercial-and-evaluation">

    </hot-table>
</template>
<script>
    import {HotTable, HotColumn} from '@handsontable/vue';

    export default {
        data:function() {
            return {
                data: null,
                colHeaders: ['shard_name', 'range', 'shard_state', 'core', 'core_node', 'base_url', 'node_name', 'state', 'ip', 'leader', 'dish_usage'],
            }
        },
        mounted: function() {
            console.log('mounted');
            this.getSolrNode();
        },
        methods: {
            getSolrNode: function() {
                this.$http.get('/api/sc/getShards')
                    .then(res => {
                        this.data = res.data;
                        this.$nextTick(() => {
                            if(this.$refs.nodetable) {
                                this.$refs.nodetable.hotInstance.loadData(this.data);
                            }
                        });
                    }).catch(error => {
                        console.log(error);
                })
            },
        },
        components: {
            HotTable,
            HotColumn
        },
    }
</script>