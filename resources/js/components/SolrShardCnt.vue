<template>
    <vue-good-table
            :columns="columns"
            :sort-options="sortOption"
            :rows="data"
            ref="leader">
    </vue-good-table>
</template>
<script>
    export default {
        data: function() {
            return {
                columns: [
                    {label: 'shard_name',field: 'shard_name' },
                    {label: 'cnt', field: 'cnt', type: 'number'},
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
                this.$http.get('/api/sc/getShardCnt')
                    .then(res => {
                        this.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            }
        }
    }
</script>