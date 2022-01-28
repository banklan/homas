<template>
    <v-container>
        <v-row justify="center" class="mt-7">
            <template v-if="authUser">
                <template v-if="getServiceFail">
                    <v-alert type="error" border="left" class="mt-8">
                        There was an error while trying to get your service from the server. Please logout, then login and try again.
                    </v-alert>
                </template>
                <template v-else>
                    <v-col cols="12" md="5">
                        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
                        <v-card v-else light raised elevation="12" min-height="300" class="mx-auto">
                            <v-card-title class="primary white--text justify-center subtitle-1">{{ authUser.fullname | capFirstLetter }}'s Dashboard</v-card-title>
                            <v-card-text class="mt-5 ml-3">
                                <div class="title mb-2 font-weight-bold">My Service</div>
                                <template v-if="authService">
                                    <div class="subtitle-1">
                                        <router-link to="/my-service">{{ authService.title }}</router-link>
                                    </div>
                                    <div class="subtitle-2 mt-2">{{ authService.description | truncate(120)}}</div>
                                    <hr>
                                    <div class="title mb-2 font-weight-bold">My Portfolio</div>
                                    <template v-if="authService.portfolio.length > 0">
                                        <v-list rounded class="ml-n4">
                                            <v-list-item-group color="primary">
                                                <template v-for="pf in authService.portfolio">
                                                    <v-list-item :key="pf.id" :to="{name: 'MyPortFolioShow', params:{id: pf.id, slug:pf.slug}}">
                                                        <v-list-item-avatar>
                                                        <v-icon color="accent">people_alt</v-icon>
                                                        </v-list-item-avatar>
                                                        <v-list-item-content>
                                                        <v-list-item-title>{{ pf.title }}</v-list-item-title>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                </template>
                                            </v-list-item-group>
                                        </v-list>
                                    </template>
                                </template>
                                <template v-else>
                                    <div class="info--text subtitle-2">
                                        **You have not added a service for your account.**
                                        <div class="mt-5">
                                            <v-btn dark class="primary" to="/create-service">Add Your Service</v-btn>
                                        </div>
                                    </div>
                                </template>
                                <hr class="mt-3">
                                <div class="mt-2">
                                    <v-btn text dark color="primary" to="/my-profile">My Profile</v-btn>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="5">
                        <v-card light raised elevation="12" min-height="300" class="">
                            <v-card-title class="primary white--text justify-center subtitle-1">{{ authUser.first_name }} Service</v-card-title>
                            <v-card-text class="body-1">
                                <template v-if="authService">
                                    <table class="table table-condensed table-striped table-hover">
                                        <tr>
                                            <th>Service Type</th>
                                            <td>{{ authService.is_premium ? 'Premium' : 'Basic'}}</td>
                                        </tr>
                                        <tr>
                                            <th>No of Views</th>
                                            <td>{{ authService.view_count }}</td>
                                        </tr>
                                        <tr>
                                            <th width="50%">No of Reviews</th>
                                            <td>{{ authService.review ? authService.review.length : '0' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ratings (5)</th>
                                            <td>{{ authService.ratings }}</td>
                                        </tr>
                                    </table>
                                </template>
                                <template v-else>
                                    <v-alert type="info" border="left" class="mt-5">
                                        You haven't set up a service for your account.
                                    </v-alert>
                                </template>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mt-5">
                            <v-card-title class="primary white--text justify-center subtitle-1">Reviews for Service</v-card-title>
                            <v-card-text class="body-1">
                                <template v-if="authService">
                                    <template v-if="authService.review.length > 0">
                                        <v-list class="ml-n3">
                                            <v-list-item-group color="primary">
                                                <div v-for="rev in authService.review.slice(0, 3)"  :key="rev.id">
                                                    <v-list-item class="my-n2">
                                                        <v-list-item-content>
                                                            <v-list-item-title>{{ rev.title }} <v-rating small dense color="primary" readonly v-model="rev.ratings" half-increments></v-rating></v-list-item-title>
                                                            <v-list-item-subtitle class="mt-1">{{ rev.user && rev.user.fullname }}</v-list-item-subtitle>
                                                        </v-list-item-content>
                                                    </v-list-item>
                                                    <v-divider></v-divider>
                                                </div>
                                            </v-list-item-group>
                                        </v-list>
                                    </template>
                                    <template v-else>
                                        <v-alert type="info" class="mt-5">
                                            There are no reviews for your service yet.
                                        </v-alert>
                                    </template>
                                </template>
                                <template v-else>
                                    <v-alert type="info" class="mt-5">
                                        You have not added a service to your account.
                                    </v-alert>
                                </template>
                            </v-card-text>
                            <v-card-actions class="justify-center mt-n6 pb-6" v-if="authService && authService.review.length > 0">
                                <v-btn text large color="primary" :to="{name: 'MyServiceReviews'}">All Reviews for my service</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
            </template>
        </v-row>
        <v-snackbar v-model="userDeletedService" :timeout="4000" top color="green darken-1 white--text">
            Your service has been deleted.
            <v-btn text color="white--text" @click="userDeletedService = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            service: null,
            getServiceFail: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authService
        },
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        userDeletedService(){
            return this.$store.getters.userDeletedService
        }
    },
    methods: {
        getService(){
            this.isLoading = true
            axios.get(this.api + '/my_service', {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            })
            .then((res) => {
                this.isLoading = false
                this.service = res.data
                // console.log(res.data)
            }).catch((err) => {
                if(err.response.status === 401){
                    this.$store.commit('logOut')
                    this.$router.push('/')
                }else{
                    this.isLoading = false
                    this.getServiceFail = true
                }
            })
        }
    },
    mounted() {
        this.$store.dispatch('fetchService')
        axios.get(this.api + '/auth/auth_service', this.authHeaders).then((res) =>{
            this.service = res.data
            console.log("service is =>" + res.data)
        })
    },
    created(){
        // console.log(this.authService)
    }
}
</script>

<style scoped>
    a{
        text-decoration: none !important;
    }

    /* .v-card{
        overflow:auto !important;
    } */

    .v-card{
        overflow: hidden !important;
        word-wrap: break-word !important;
        hyphens: auto;
    }
    table a{
        text-transform: lowercase;
    }
</style>
