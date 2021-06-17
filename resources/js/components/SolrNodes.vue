<template>
    <vue-good-table
        :columns="columns"
        :sort-options="sortOption"
        :rows="data">
        <template slot="table-row" slot-scope="props">
            <span v-if="props.column.field == 'shards'">
                    <span v-for="item in props.row.shards">
                        <span v-if="item.leader"><b>{{item.core.replace('tibuzz_', '')}} ({{item.disk_usage}})</b></span>
                        <span v-else>{{item.core.replace('tibuzz_', '')}} ({{item.disk_usage}})</span>
                         <br />
                    </span>
            </span>
            <span v-else-if="props.column.field == 'cores'">
                    <span v-for="item in props.row.shards">
                        {{item.core.replace('tibuzz_', '')}} ({{item.core_node}})<br />
                    </span>
            </span>
        </template>
    </vue-good-table>
</template>
<script>
    export default {
        data: function() {
            return {
                columns: [
                    {label: 'IP',           field: 'ip', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '샤드수(리더)',       field: 'shard_cnt', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '디스크사용률', field: 'disk_usage_per', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '샤드 사용률',  field: 'shards', filterOptions: { enabled: true, filterValue: ''}},
                    {label: '코어명',       field: 'cores', filterOptions: { enabled: true, filterValue: ''}},
                ],
                sortOption: {
                    enabled: true
                },
                data: [],
            }
        },
        mounted: function() {
            this.getSolrNodes();
        },
        methods: {
            getSolrNodes: function() {
                this.$http.get('/api/sc/getNodes')
                    .then(res => {
                        this.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
        }

    }
</script>