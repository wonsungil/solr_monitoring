<template>
    <div class="container-fluid">
        <h4>Error List</h4>
        <b-tabs v-model="activeTab">
            <b-tab-item  v-for="(item, key, index) in data" :key="key" :label="key + '(' + item.length + ')'">
                <div class="content">
                    <table class="table">
                        <thead>
                        <tr>
                            <th abbr title="collection">collection</th>
                            <th abbr title="shard">shard</th>
                            <th abbr title="async">async</th>
                            <th abbr title="operation">operation</th>
                            <th abbr title="msg">msg</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="item.length > 0" v-for="(rowItem, rowKey, rowIndex) in item.slice().reverse()" :key="rowKey">
                            <td>{{rowItem.request.collection}}</td>
                            <td>{{rowItem.request.shard}}</td>
                            <td>{{rowItem.request.async}}</td>
                            <td>{{rowItem.request.operation}}</td>
                            <td>
                                <vue-json-pretty :data="rowItem.response"></vue-json-pretty>
                            </td>
                        </tr>
                        <tr v-else>
                            <td>
                                <span>No error</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </b-tab-item>
        </b-tabs>
    </div>
</template>
<script>
    import BTabItem from "buefy/src/components/tabs/TabItem";
    import VueJsonPretty from 'vue-json-pretty';
    
    export default {
        components: {
            BTabItem,
            VueJsonPretty
        },
        data: function() {
            return {
                data: null,
                activeTab:null,
            }
        },
        mounted: function() {
            this.getCollectionOps();
        },
        methods: {
            getCollectionOps: function () {
                this.$http.get('/api/sc/getCollectionOpsErrors')
                    .then( (res) => {
                        this.data = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            },
        }
    }
</script>