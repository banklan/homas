<template>
    <v-container>
        <v-row justify="center" class="mt-3">
            <v-col cols="12" md="6">
                <div class="section_header text-center">Featured Services</div>
            </v-col>
        </v-row>
        <v-row wrap justify="space-around" class="my-3">
            <template v-if="services.length > 0">
                <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                    <service-card-front :service="service" />
                </v-col>
            </template>
            <template v-else>
                <div class="px-2">
                    There are no services to show at the moment.
                </div>
            </template>
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
        goToSearch(){
            this.$refs.search.click()
        },
        getFeatured(){
            axios.get(this.api + '/get_min_featured_services').then((res) => {
                this.services = res.data
            }).catch((err)=>{
                // console.log("featured error", err)
            })
        }
    },
    created() {
        this.getFeatured()
    },
}
</script>

<style lang="scss" scoped>
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
    
