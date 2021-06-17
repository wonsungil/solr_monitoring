<template>
    <div class="columns">
        <div class="column is-3">
            <h4>디스크 사용량 별 REPLICA 수</h4>
            <vue-good-table
                    :columns="disk.columns"
                    :sort-options="disk.sortOption"
                    :rows="disk.data"
                    ref="leader">
            </vue-good-table>
        </div>
        <div class="column is-5">
            <h4>SPLIT 후 예상 REPLICA 수</h4>
            <vue-good-table
                    :columns="split.columns"
                    :sort-options="split.sortOption"
                    :rows="split.data"
                    ref="leader">
            </vue-good-table>
        </div>
        <div class="column is-4">
            <h4>SPLIT 후 노드별 평균 Replica/Leader</h4>
            <vue-good-table
                    :columns="avg.columns"
                    :sort-options="avg.sortOption"
                    :rows="avg.data"
                    ref="leader">
                <template slot="table-column" slot-scope="props">
                    <span v-if="props.column.label == ''">
                        <input v-model="avg.nodeCount" @change="getAvgShard"/>
                    </span>
                    <span v-else>
                        {{props.column.label}}
                    </span>
                </template>
                
                
            </vue-good-table>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                disk: {
                    columns: [
                        {label: '',field: 'label' },
                        {label: 'leader count', field: 'leader_cnt', type: 'number'},
                        {label: 'replica count', field: 'replica_cnt', type: 'number'},
                    ],
                    sortOption: {
                        enabled: false
                    },
                    data: [],
                },
                split: {
                    columns: [
                        {label: '',field: 'label' },
                        {label: '스플릿 전', field: 'beforeSplit', type: 'number'},
                        {label: '스플릿 후', field: 'afterSplit', type: 'number'},
                        {label: '스플릿 후 Replica', field: 'afterSplitTotalReplica', type: 'number'},
                        {label: '스플릿 후 Leader', field: 'afterSplitTotalLeader', type: 'number'},
                    ],
                    sortOption: {
                        enabled: false
                    },
                    data: [],
                },
                avg: {
                    columns: [
                        {label: '',field: 'label' },
                        {label: '총 Replica 수', field: 'totalReplica', type: 'number'},
                        {label: '평균 Replica 수 / 노드', field: 'avgReplicaPerNode', type: 'number'},
                        {label: '평균 리더 수 / 노드', field: 'avgLeaderPerNode', type: 'number'},
                    ],
                    sortOption: {
                        enabled: false
                    },
                    data: [],
                    nodeCount : 67
                }
                
            }
        },
        mounted: function() {
            this.getShardCountUsageDisk();
            this.getReplicaSplitShard();
            this.getAvgShard();
        },
        methods: {
            getShardCountUsageDisk: function() {
                this.$http.get('/api/sc/getShardCountUsageDisk')
                    .then(res => {
                        this.disk.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getReplicaSplitShard: function() {
                this.$http.get('/api/sc/getReplicaSplitShard')
                    .then(res => {
                        this.split.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            },
            getAvgShard: function() {
                console.log('change!');
                this.$http.get('/api/sc/getAvgShard/'+this.avg.nodeCount)
                    .then(res => {
                        this.avg.data = res.data;
                    }).catch(error => {
                    console.log(error);
                })
            }
        }
    }
</script>