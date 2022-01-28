<template>
    <div class="welcome_banner">
        <div class="banner_caption text-center">
            <h1 class=" white--text mb-3 animate__animated animate__bounceInDown animate__slower">Welcome to Homas...</h1>
            <h3 class="white--text mt-3 pb-4 animate__animated animate__bounceInUp animate__slower">Connecting you to your neighbourhood service providers.</h3>
        </div>
        <div class="loc d-flex">
            <v-select :items="locations" item-text="state" return-object dark outlined large label="Choose Your Location" v-model="location"></v-select>
            <v-btn large outlined text dark @click="goLocation">Go!</v-btn>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            location: null,
            locations: []
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getLocations(){
            axios.get(this.api + `/locations`).then((res) => {
                this.locations = res.data
            })
        },
        goLocation(){
            this.$router.push({name: 'ServicesByLocation', params: {id: this.location.id, slug: this.location.slug}})
        }
    },
    mounted() {
        this.getLocations()
    },
}
</script>
