<template>
    <div class="welcome_banner">
        <div class="banner_caption text-center">
            <h1 class=" white--text mb-3 animate__animated animate__bounceInDown animate__slower">Welcome to Homas...</h1>
            <h3 class="white--text mt-3 pb-4 animate__animated animate__bounceInUp animate__slower">Connecting you to your neighbourhood service providers.</h3>
        </div>
        <!-- <div class="loc d-flex">
            <v-select :items="locations" item-text="state" return-object dark outlined large label="Choose Your Location" v-model="location"></v-select>
            <v-btn large outlined text dark @click="goLocation">Go!</v-btn>
        </div> -->
        <div class="input-group loc" :style="$vuetify.breakpoint.smAndDown ? 'width:60%' : 'width:40%'">
            <!-- <v-select :items="locations" item-text="state" return-object dark outlined large label="Choose Your Location" v-model="location"></v-select> -->
            <select class="custom-select bg-transparent" :style="$vuetify.breakpoint.smAndDown ? 'height:40px' : 'height:55px'" id="inputGroupSelect04" style="color: #fff">
                <option selected class="select">Choose Location</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select>
            <div class="input-group-append bg-transparent">
            <!-- <v-btn large outlined text dark @click="goLocation">Go!</v-btn> -->
                <button class="btn btn-outline-primary" type="button">Button</button>
            </div>
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
    #inputGroupSelect04{
        border:1px solid #fff !important;
        // .select:after{
        //     color: red !important;
        // }
    }
    .custom-select {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='white' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") !important;
    }
    .input-group button{
        border:1px solid #FF5200 !important;
        background: #FF5200 !important;
        color: #fff !important;
        transition: all .3s;

        &:hover{
            background: transparent !important;
            border: 1px solid #fff !important;
            color: #fff !important;
        }
    }
    .custom-select-indicator{
        coor: #fff !important;
    }
</style>
