<template>
    <v-container>
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="6">
                <div class="section_header text-center">Popular Services</div>
            </v-col>
        </v-row>
        <v-row wrap justify="center" class="my-3">
            <template v-if="services.length > 0">
                <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                    <service-card-front :service="service" />
                </v-col>
            </template>
            <template v-else>
                <div class="px-2">
                    There are no services to display at the moment.
                </div>
            </template>
        </v-row>
        <v-row justify="center" class="mr-2" v-if="services.length > 0">
            <v-btn x-large outlined color="primary" to="/services" class="px-10">All Services</v-btn>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            services: []
        }
    },
    computed:{
         api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getFeatured(){
            axios.get(this.api + '/get_min_popular_services').then((res) => {
                // console.log("popular", res.data)
            }).catch(()=>{
            })
        },
        getServs(){
            axios.get(this.api + '/get_popular_services').then((res) => {
                this.services = res.data
            })
        }
    },
    created() {
        this.getServs()
    },
}
</script>

<style lang="scss" scoped>
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
