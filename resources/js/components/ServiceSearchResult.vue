<template>
    <v-container>
        <v-row justify="start" class="justify-center mt-5">
            <v-col cols="3">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="9" md="6" offset-md="2">
                <service-search-bar></service-search-bar>
            </v-col>
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="services.length > 0">
                <v-row justify="center">
                    <v-col cols="12">
                        <div v-if="services" class="subtitle-1 text-center">Search for <em>"{{ q }}"</em> returned {{ services.length | pluralize('service') }}</div>
                    </v-col>
                </v-row>
                <v-row justify="start" class="mt-3">
                    <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                        <single-service-card :service="service"></single-service-card>
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
