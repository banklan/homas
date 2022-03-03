<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="3">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <v-col cols="9" v-if="portfolio && portfolio.title">
                <div class="title flex-start">{{ portfolio && portfolio.title }}</div>
            </v-col>
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="70" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="portfolio">
                <v-row justify="start" class="mt-4">
                    <v-col cols="12" md="8">
                        <v-card light raised elevation="12" min-height="200" class="mx-auto">
                            <v-card-title class="primary white--text justify-center subtitle-1"> Portfolio</v-card-title>
                            <gen-portfolio-carousel :files="portfolio.file"></gen-portfolio-carousel>
                            <v-card-text class="mt-3">
                                <div class="px-3 pt-2 pb-4 body_text"> {{ portfolio.detail | capFirstLetter }}</div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card light raised elevation="12" min-height="120" class="mx-auto">
                            <v-card-title class="primary white--text justify-center subtitle-1"> Service</v-card-title>
                            <v-card-text class="mt-5">
                                <router-link :to="{name: 'ServiceShow', params:{id: portfolio.service_id, slug: portfolio.service.slug}}">{{ portfolio.service.title }}</router-link>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mt-5">
                            <v-card-title class="primary white--text justify-center subtitle-1"> Other Portfolios for this service</v-card-title>
                            <v-card-text class="mt-3 py-3">
                                <template v-if="others.length > 0">
                                    <div v-for="(pf, index) in others" :key="pf.id" class="px-2">
                                        <router-link :to="{name: 'PortfolioShowSingle', params: {id: pf.id, slug: pf.slug}}">{{ pf.title | truncate(75) }}</router-link>
                                        <v-divider v-if="index < others.length - 1"></v-divider>
                                    </div>
                                </template>
                                <template v-else>
                                    <div class="py-2 px-1 body-1">
                                        There are no other portfolios to show.
                                    </div>
                                </template>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </template>
            <template v-else>
                <v-row justify="center" class="mt-5">
                    <v-col cols="12" md="8">
                        <v-alert type="warning" border="left">
                            The portfolio you are trying to view is invalid.
                        </v-alert>
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
            id: this.$route.params.id,
            portfolio: null,
            isLoading: false,
            others: [],
            files: []
        }
    },
    watch:{
        '$route.params':{
            handler(newVal){
                let id = newVal
                this.getPortfolio(id)
                this.getOtherPortfolios(id)
            },
            immediate: true,
            deep: true
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods:{
        getPortfolio(){
            this.isLoading = true
            axios.get(this.api + `/get_portfolio/${this.$route.params.id}`).then((res) => {
                this.isLoading = false
                this.portfolio = res.data
            })
        },
        getPortfolioFiles(portfolio){
            axios.get(this.api + `/get_portfolio_files/${portfolio}`)
            .then((res) => {
                this.files = res.data
            }).catch((err) =>{
                // console.log(err)
            })
        },
        getOtherPortfolios(){
            this.isLoading = true
            axios.get(this.api + `/get_other_portfolios/${this.$route.params.id}`).then((res)=>{
                this.isLoading = false
                this.others = res.data
            }).catch(()=>{
                this.isLoading = false
            })
        }
    },
    created() {
        this.getPortfolio()
        this.getOtherPortfolios()
    },
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
</style>

