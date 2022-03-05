<template>
    <v-container>
        <v-row justify="center" class="mt-7">
            <v-col cols="12" md="6">
                <div class="section_header text-center">New Services</div>
            </v-col>
        </v-row>
        <v-row wrap justify="center" class="my-3">
            <template v-if="services.length > 0">
                <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                    <service-card-front :service="service" />
                    <!-- <v-card light elevation="4" min-height="400" class="mt-5 mb-3" :to="{name: 'ServiceShow', params:{id: service.id, slug: service.slug}}">
                        <v-img v-if="service.image" :src="`/images/services/${service.image}`" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
                        <v-img v-else src="/images/shared/no-image.png" alt="Featured Images" aspect-ratio="1" height="250" transition="scale-transition"></v-img>
                        <v-card-title class="subtitle-1 justify-center mt-2">{{ service.title | capFirstLetter}}</v-card-title>
                        <v-card-text class="text-center body-1 grey--text mt-n3"><v-icon color="primary">place</v-icon> {{ service.city }} | <v-icon color="primary">layers</v-icon> {{ service.category && service.category.name }} </v-card-text>
                        <v-card-actions class="justify-space-around pb-6">
                            <v-chip v-if="service.is_approved" class="ma-2" color="green darken-3" text-color="white">
                                <v-avatar left><v-icon>mdi-checkbox-marked-circle</v-icon></v-avatar>
                                Verified
                            </v-chip>
                            <v-rating v-model="service.ratings" readonly dense color="primary"></v-rating>
                        </v-card-actions>
                    </v-card> -->
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
        getLatest(){
            axios.get(this.api + '/get_new_services').then((res) => {
                this.services = res.data
            }).catch(()=>{
            })
        },
    },
    created() {
        this.getLatest()
    },
}
</script>

<style lang="scss" scoped>
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
