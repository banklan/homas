<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left to="/my-service"><v-icon left>arrow_left</v-icon> Back To My Service</v-btn>
            </v-col>
            <template v-if="authUser.service">
                <v-col cols="12" md="8" v-if="authUser.service && authUser.service.portfolio.length > 0">
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-title class="primary white--text justify-center title"> My Portfolio</v-card-title>
                        <v-card-text class="body-1 mt-2 py-3 pb-5">
                            <v-alert type="info" border="left">
                                You currently have {{ portfolio.length }} portfolio set up for your service
                            </v-alert>
                            <v-list three-line nav class="pb-10">
                                <template v-for="(item, i) in portfolio">
                                    <my-portfolio-list :portfolio="item" :key="i" index="i"/>
                                </template>
                            </v-list>
                        </v-card-text>
                        <template v-if="authService">
                            <v-card-actions class="justify-center pb-8" v-if="authService.portfolio.length < 3 || authService.is_premium">
                                <v-btn dark color="primary" to="/create-portfolio">Add More Portfolio</v-btn>
                            </v-card-actions>
                        </template>
                    </v-card>
                </v-col>
                <v-col cols="12" md="8" v-else>
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-title class="primary white--text justify-center title"> My Portfolio</v-card-title>
                        <v-card-text class="mt-2 py-3 pb-5">
                            <v-alert type="warning" border="left">
                                You currently have no portfolio(s) set up for your service.
                            </v-alert>
                        </v-card-text>
                        <v-card-actions class="justify-center mt-n3 pb-6">
                            <v-btn large color="primary" :to="{name: 'CreatePortFolio'}">Create Portfolio</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </template>
            <template v-else>
                <v-col cols="12" md="8">
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-title class="primary white--text justify-center title"> My Portfolio</v-card-title>
                        <v-card-text class="mt-2 py-3 pb-5">
                            <v-alert type="warning" border="left">
                                You currently have neither a service nor portfolio set up for your account.
                            </v-alert>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6">
                            <v-btn large color="primary" :to="{name: 'CreateService'}">Add My Service</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </template>
        </v-row>
        <v-snackbar :value="portfolioDeleted" :timeout="6000" top color="green darken-1 white--text">
            One portfolio has been deleted.
            <v-btn text color="white--text" @click="portfolioDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data(){
        return{

        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        portfolio(){
            return this.$store.getters.authUser.service.portfolio
        },
        api(){
            return this.$store.getters.api
        },
        portfolioDeleted(){
            return this.$store.getters.portfolioDeleted
        },
        authService(){
            return this.$store.getters.authService
        },
    },
    created(){
        this.$store.dispatch('fetchService')
    }
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
    .v-list-item__subtitle{
        line-height: 1.6 !important;
    }
</style>
