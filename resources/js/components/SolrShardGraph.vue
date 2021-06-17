<template>
    <div class="container-fluid" style="height:500vh;">
        <div class="content" style="height:500vh;">
            <tree :data="treeData" layoutType="horizontal" linkLayout="orthogonal" marginy="0" marginx="400" minzoom="0.96" maxzoom="100" style="height:500vh; width:80%;">
                <template #node="{data, node: {depth}, radius, isRetracted}">
                    <template v-if="data.children && data.children.length">
                        <circle r="3" >
                            <title>{{data.text}}</title>
                        </circle>
                    </template>
                    <template v-else>
                        <circle r="3" >
                            <title style="font-weight:bold;">{{data.text}}</title>
                        </circle>
                    </template>
                </template>
            </tree>
        </div>
    </div>
</template>
<style>
    .treeclass .nodetree text {
        font: 15px sans-serif;
        cursor: pointer;
    }
</style>
<script>
    import {tree} from 'vued3tree'
    
    export default {
        components: {
            tree,
        },
        data: function() {
            return {
                treeData: null,
            }
        },
        mounted: function() {
            this.getShardGraph();
        },
        methods: {
            getShardGraph: function () {
                this.$http.get('/api/sc/getShardGraph')
                    .then( (res) => {
                        this.treeData = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            },
        }
    }
</script>