<template>
    <div class="welcome_banner">
        <div class="banner_caption text-center">
            <h1 class="white--text mb-3 animate__animated animate__bounceInDown animate__slower">Welcome to Hozulinks...</h1>
            <h3 class="white--text mt-3 pb-4 animate__animated animate__bounceInUp animate__slower">Connecting you to your neighbourhood artisans and service providers.</h3>
        </div>
        <div class="loc d-flex mt-8" :style="$vuetify.breakpoint.smAndDown ? 'width:80%' : 'width: 60%'">
            <v-select :items="locations" item-text="state" return-object dark outlined large label="Choose Your Location" v-model="location" style="border-radius:0"></v-select>
            <v-btn x-large color="primary white--text" dark @click="goLocation" tile :style="$vuetify.breakpoint.smAndDown ? 'padding:0 25px': 'padding:0 30px'">Go!</v-btn>
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

<style lang="scss" scoped>
.v-btn{
    padding: 28px !important;
    transition: all .4s;
    &:hover{
        background: #fff !important;
        color: #FF5200 !important;
    }
}
</style>
