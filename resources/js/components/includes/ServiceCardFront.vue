<template>
    <div>
        <v-card light elevation="4" min-height="400" class="mt-5 mb-3" :to="{name: 'ServiceShow', params:{id: service.id, slug: service.slug}}">
            <v-img v-if="serviceImg" :src="serviceImg" alt="Featured Images" aspect-ratio="1" height="270" transition="scale-transition"></v-img>
            <v-img v-else src="/images/shared/no-image.png" alt="Featured Images" aspect-ratio="1" height="270" transition="scale-transition"></v-img>
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
    </div>
</template>

<script>
export default {
    props: ['service'],
    data() {
        return {
            serviceImg: ''
        }
    },
    computed:{
         api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getServiceImg(){
            axios.get(this.api + `/get_service_img_from_s3/${this.service.id}`).then((res) => {
                this.serviceImg = res.data
            })
        }
    },
    created(){
        this.getServiceImg()
    }
}
</script>

<style lang="css" scoped>
    .v-btn, a{
        text-decoration: none !important;
    }
</style>
