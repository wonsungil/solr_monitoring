<template>
    <div class="container-fluid">
        <h4>Running Jobs</h4>
        <div class="content">
            <table class="table">
                <thead>
                <tr>
                    <th abbr title="async-id">async-id</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="data.length > 0" v-for="(item, key, index) in data" :key="key">
                    <td><button class="button is-primar" @click="getRequestStatus(item)">{{item}}</button></td>
                </tr>
                <tr v-else>
                    <td>현재 진행 중인 작업 없음</td>
                </tr>
                </tbody>
            </table>
        </div>
        <br>
        <h4>RequestStatus</h4>
        <div>
            <vue-json-pretty :data="requestStatus"></vue-json-pretty>
        </div>
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
                requestStatus:'Async-Id를 클릭하세요.',
            }
        },
        mounted: function() {
            this.getRunningMap();
        },
        methods: {
            getRunningMap: function () {
                this.$http.get('/api/sc/getRunningMap')
                    .then( (res) => {
                        console.log(res.data);
                        this.data = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            },
            getRequestStatus: function(asyncId) {
                this.$http.get('/api/sc/getRequestStatus/'+asyncId)
                    .then((res) => {
                        this.requestStatus = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>