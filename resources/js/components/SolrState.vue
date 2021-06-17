<template>
    <div class="container-fluid">
        <h4>StateJson</h4>
        <vue-json-pretty :data="data"></vue-json-pretty>
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
                data: '',
            }
        },
        mounted: function() {
            this.getState();
        },
        methods: {
            getState: function () {
                this.$http.get('/api/sc/getState')
                    .then( (res) => {
                        this.data = res.data;
                    }).catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>