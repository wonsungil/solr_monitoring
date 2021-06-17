<template>
    <vue-good-table
        :columns="columns"
        :sort-options="sortOption"
        :rows="data"
        :row-style-class="rowStyle">

    </vue-good-table>
</template>
<script>
    export default {
        data: function() {
            return {
                columns: [
                    {label: '샤드명',       field: 'shard_name', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '샤드상태',     field: 'shard_state', filterOptions: { enabled: true, filterValue: ''}},
                    {label: 'core',         field: 'core', filterOptions: { enabled: true, filterValue: ''}},
                    {label: 'core_node',    field: 'core_node', filterOptions: { enabled: true, filterValue: ''}},
                    {label: 'node_name',    field: 'node_name', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '노드상태',     field: 'state', filterOptions: { enabled: true, filterValue: ''}},
                    {label: 'IP',           field: 'ip', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '리더여부',     field: 'leader', filterOptions: { enabled: true, filterValue: ''}, type:'boolean'},
                    {label: '범위', field: 'range'},
                    {label: 'disk_usage', field: 'disk_usage'},
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
                this.$http.get('/api/sc/getShards')
                    .then(res => {
                        this.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
            rowStyle: function(row) {
                if(row.shard_state === 'active') {
                    return '';
                } else if(row.shard_state === 'inactive') {
                    return 'bg-secondary';
                }
            }
        }

    }
</script>