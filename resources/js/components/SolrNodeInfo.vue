<template>
    <vue-good-table
            :columns="columns"
            :sort-options="sortOption"
            :rows="data"
            @on-cell-click="onCellClick"
            ref="leader">
    </vue-good-table>
</template>
<script>
    export default {
        data: function() {
            return {
                columns: [
                    {label: 'IP',field: 'ip' },
                    {label: 'total shard', field: 'total', type: 'number'},
                    {label: 'leader', field: 'leader_count', type: 'number'},
                    {label: 'Solr Version', field: 'solr_version'},
                    {label: 'OS', field: 'os'},
                    {label: 'Java Version', field: 'java_version'},
                    {label: 'Heap Size', field: 'jvm_heap'},
                    {label: 'total disk(GB)', field: 'total_disk', type: 'number'},
                    {label: 'used(GB)', field: 'used', type: 'number'},
                    {label: 'free(GB)', field: 'free', type: 'number'},
                    {label: 'used(%)', field: 'used_per', type: 'number'}
                ],
                sortOption: {
                    enabled: true
                },
                data: [],
            }
        },
        mounted: function() {
            this.getSolrNode();
        },
        methods: {
            getSolrNode: function() {
                this.$http.get('/api/sc/getNodeInfo')
                    .then(res => {
                        this.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
            onCellClick: function(param) {
                window.open("http://" + param.row.ip+":8983/solr", "_blank");
            }
        }
    }
</script>