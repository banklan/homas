<template>
    <div class="locations mt-6">
        <v-container>
            <v-row wrap justify="center" align="center">
                <v-col cols="12" md="10">
                    <v-card light outlined class="mx-auto mt-5" min-height="250">
                        <v-card-title class="justify-center section_header mt-3">Popular Locations</v-card-title>
                        <v-card-text class="mt-5 mx-3">
                            <v-row justify="center">
                                <v-col cols="6" md="3" v-for="(loc, i) in locations" :key="i">
                                    <v-btn light outlined color="primary darken-2" :to="{name: 'ServicesByLocation', params: {id: loc.id, slug: loc.slug}}">{{ loc.state }}</v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data(){
        return{
            locations: []
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getPopularLocations(){
            axios.get(this.api + '/popular_locations').then((res)=>{
                this.locations = res.data
            })
        },
    },
    created() {
        this.getPopularLocations()
    },
}
</script>

<style lang="scss" scoped>
    .locations{
        background: rgba(255, 255, 255, 1);
        .v-btn{
            text-decoration: none !important;
        }
    }
</style>
