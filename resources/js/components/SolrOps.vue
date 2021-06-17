<template>
    <div class="container-fluid">
        <div class="columns">
            <div class="column is-2" v-for="(item, key, index) in data">
                <div class="card">
                    <div class="card-content">
                        <div class="content">
                            <h4>{{key}}</h4>
                            <hr>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th abbr title="Request">요청</th>
                                        <th abbr title="Error">에러</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{item.requests}}</td>
                                        <td>{{item.errors}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                data: null,
            }
        },
        mounted: function() {
            this.getCollectionOps();
        },
        methods: {
            getCollectionOps: function () {
                this.$http.get('/api/sc/getCollectionOps')
                    .then( (res) => {
                        this.data = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>