<template>
    <v-container>
        <v-row justify="start" class="justify-center mt-5">
            <v-col cols="3">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="12" md="6" offset-md="2">
                <service-search-bar></service-search-bar>
            </v-col>
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="services.length > 0">
                <v-row justify="center">
                    <v-col cols="12" md="6">
                        <div class="subtitle-1 primary white--text text-center pa-2">Search for <em>"{{ q }}"</em> returned {{ services.length | pluralize('service') }}</div>
                    </v-col>
                </v-row>
                <v-row justify="start" class="mt-3">
                    <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                        <single-service-card :service="service"></single-service-card>
                    </v-col>
                </v-row>
            </template>
            <template v-else>
                <v-row justify="center" class="mt-5">
                    <v-col cols="12" md="6">
                        <v-alert type="error" border="left" class="pa-5">
                            Your search for <em>"{{ q }}"</em> returned no services.
                        </v-alert>
                        <div class="text-center py-5">
                            <v-btn large ripple outlined color="primary" :to="{name: 'AllServices'}">All Services</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </template>
        </template>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            q: '',
            query: this.$route.query.q,
            services: [],
            isLoading: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    watch: {
        '$route.query.q': {
            handler(newVal){
                this.q = newVal
                this.isLoading = true
                axios.post(this.api + '/search_for_services', {
                    q: this.q
                }).then((res) => {
                    this.isLoading = false
                    this.services = res.data
                })
            },
            immediate: true
        }
    },
    methods: {
        search(){
            this.isLoading = true
            axios.post(this.api +  `/search_for_services`, {
                q: this.query
            }).then((res)=>{
                this.isLoading = false
                this.services = res.data
            })
        }
    },
    created(){
        this.search()
    }
}
</script>

<style lang="scss" scoped>
    .v-btn{
        transition: all 0.4s;

        &:hover{
            background: #FF5200 !important;
            color: #fff !important;
            border: 1px solid #FF5200 !important;
        }
    }
</style>
