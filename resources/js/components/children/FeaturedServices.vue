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
                    <v-card light elevation="4" min-height="400" class="mt-5 mb-3" :to="{name: 'ServiceShow', params:{id: service.id, slug: service.slug}}">
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
                    </v-card>
                </v-col>
            </template>
            <template v-else>
                There are no services to show at the moment.
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
    .svg_icon{
        height: 6.25rem;
        fill: #FF5200;
    }
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
