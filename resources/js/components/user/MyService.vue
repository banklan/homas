<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-progress-circular v-if="isBusy" indeterminate color="accent" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <template v-if="authService">
                    <v-col cols="12" md="8">
                        <v-card light raised elevation="12" min-height="300" class="mx-auto">
                            <v-card-title class="primary white--text justify-center subtitle-1"> My Service</v-card-title>
                            <!-- <v-img :src="serviceImage" width="100%" height="380" transition="scale-transition"></v-img> -->
                            <v-img v-if="serviceImg" :src="serviceImg" width="100%" height="380" transition="scale-transition" alt="service image"></v-img>
                            <v-img v-else src="images/shared/no-image.png" width="100%" height="380" transition="scale-transition" alt="service image"></v-img>
                            <v-card-text class="body_text mt-5 px-8 pb-5">
                                <div class="title mb-2">{{ authService.title }}</div>
                                <div class="body_text">{{ authService.description }}</div>
                            </v-card-text>
                            <v-card-actions class="justify-space-around pb-8 px-2" v-if="$vuetify.breakpoint.smAndDown">
                                <v-btn icon color="primary"><v-icon left>visibility</v-icon> {{ authUser.service.views }}</v-btn>
                                <span class="primary--text"><v-rating v-model="authUser.service.ratings" readonly dense small color="primary"></v-rating></span>
                                <span>
                                    <v-menu offset-y top nudge-top="10" right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn @click="openMenu ? openMenu = !openMenu : ''" v-bind="attrs" v-on="on" icon small class="mt-n1">
                                                <v-icon color="blue darken-1">mdi-menu</v-icon>
                                            </v-btn>
                                        </template>
                                        <v-list>
                                            <v-list-item-group>
                                                <v-list-item>
                                                    <v-btn icon color="blue darken-1" to="/my-service/edit"><v-icon left>edit</v-icon></v-btn>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-btn icon color="primary darken-2" :to="{name: 'CreateServiceAddImage'}"><v-icon left>image</v-icon></v-btn>
                                                </v-list-item>
                                                <v-list-item>
                                                    <v-btn icon color="red darken-2" @click="confirmDel = true"><v-icon left>delete_forever</v-icon></v-btn>
                                                </v-list-item>
                                            </v-list-item-group>
                                        </v-list>
                                    </v-menu>
                                </span>
                            </v-card-actions>
                            <v-card-actions class="justify-space-around pb-8 mx-4" v-else>
                                <v-btn icon color="primary"><v-icon left>visibility</v-icon> {{ authUser.service.view_count }}</v-btn>
                                <span class="primary--text"><v-rating v-model="authUser.service.ratings" readonly dense small color="primary"></v-rating></span>
                                <v-btn icon color="primary" to="/my-service/edit"><v-icon left>edit</v-icon></v-btn>
                                <v-btn text color="primary darken-2" :to="{name: 'CreateServiceAddImage'}">Change Picture</v-btn>
                                <v-btn icon color="red darken-2" @click="confirmDel = true"><v-icon left>delete_forever</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-card v-if="authService && authService.highlight" light raised elevation="12" min-height="150" class="mt-8">
                            <v-card-title class="primary white--text justify-center subtitle-1">Achievements/Highlights</v-card-title>
                            <v-card-text class="mt-5 px-5 pb-5">
                                <div class="body_text">{{ authService.highlight }}</div>
                            </v-card-text>
                        </v-card>
                        <v-card v-if="authService && authService.keystrength" light raised elevation="12" min-height="150" class="mt-8">
                            <v-card-title class="primary white--text justify-center subtitle-1">Keystrength</v-card-title>
                            <v-card-text class="mt-5 px-5 pb-5">
                                <div class="body_text">{{ authService.keystrength }}</div>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mt-8">
                            <template v-if="authService.review">
                                <v-card-title class="primary white--text justify-center subtitle-1">Service Review ({{ authService.review.length }})</v-card-title>
                                <v-card-text class="body_text px-5 pb-5">
                                    <template v-if="authService.review.length > 0">
                                        <v-list three-line>
                                            <template v-if="!showAllReview">
                                                <div v-for="(review, i) in authService.review.slice(0, 3)" :key="review.id">
                                                    <p class="body_text primary--text">{{ review.author && review.author.fullname }} on {{ review.published }}</p>
                                                    <v-rating dense small color="primary" readonly v-model="review.ratings" class="mt-n3" half-increments></v-rating>
                                                    <p class="body_text font-weight-bold mt-2">{{ review.title | capFirstLetter }}</p>
                                                    <p class="body_text black--text lighten-5 mb-3">{{ review.detail | capFirstLetter }}</p>
                                                    <v-divider v-if="i < 2"></v-divider>
                                                </div>
                                                <v-card-actions class="justify-center">
                                                    <v-btn text color="primary" @click="showAllReview = true">All Reviews</v-btn>
                                                </v-card-actions>
                                            </template>
                                            <template v-else>
                                                <div v-for="(review, i) in authService.review" :key="review.id">
                                                    <p class="body_text primary--text">{{ review.author && review.author.fullname }} on {{ review.published }}</p>
                                                    <v-rating small dense color="secondary" readonly v-model="review.ratings" class="mt-n3" half-increments></v-rating>
                                                    <p class="body_text font-weight-bold mt-2">{{ review.title | capFirstLetter }}</p>
                                                    <p class="body_text black--text lighten-5 mb-3">{{ review.detail | capFirstLetter }}</p>
                                                    <v-divider v-if="i < authService.review.length - 1"></v-divider>
                                                </div>
                                                <v-divider></v-divider>
                                                <v-card-actions class="justify-center">
                                                    <v-btn text color="primary" @click="showAllReview = false">Show Less</v-btn>
                                                </v-card-actions>
                                            </template>
                                        </v-list>
                                    </template>
                                    <template v-else>
                                        <div class="mt-5">
                                            Your service has not been reviewed by any client.
                                        </div>
                                    </template>
                                </v-card-text>
                            </template>
                            <template v-else>
                                <v-card-title class="primary white--text justify-center subtitle-1">Service Review</v-card-title>
                                <v-card-text class="body_text px-5 pb-6">
                                    <div class="mt-5">
                                        Your service has not been reviewed by any client.
                                    </div>
                                </v-card-text>
                            </template>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card light raised elevation="12" min-height="400" class="">
                            <v-card-title class="primary white--text justify-center subtitle-1"> About & Contact</v-card-title>
                            <v-card-text class="justify-center mt-5">
                                <table class="table table-condensed table-striped table-hover" v-if="authService">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th style="border-top: none">Published: </th>
                                            <td style="border-top: none">{{ authService.published }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mover: </th>
                                            <td>{{ authUser.fullname | capFirstLetter }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category: </th>
                                            <td>{{ authService.category && authService.category.name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Position: </th>
                                            <td>{{ authService.position }}</td>
                                        </tr>
                                        <tr>
                                            <th>Years Of Experience: </th>
                                            <td>{{ authService.experience_years }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone Number: </th>
                                            <td>{{ authService.phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email: </th>
                                            <td>{{ authService.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Address: </th>
                                            <td>{{ authService.address }}</td>
                                        </tr>
                                        <tr>
                                            <th>City: </th>
                                            <td>{{ authService.city }}</td>
                                        </tr>
                                        <tr>
                                            <th>State: </th>
                                            <td>{{ authService.location && authService.location.state }}</td>
                                        </tr>
                                        <tr v-if="authService.website">
                                            <th>Website: </th>
                                            <td><a :href="`http://www.${authUser.service.website}`" target="_blank" link>www.{{ authUser.service.website }}</a></td>
                                        </tr>
                                        <tr v-if="authService.facebook">
                                            <th>Facebook: </th>
                                            <td><a :href="`https://www.facebook.com/${authUser.service.facebook}`" target="_blank" link>{{ authUser.service.facebook }}</a></td>
                                        </tr>
                                        <tr v-if="authService.instagram">
                                            <th width="30%">Instagram: </th>
                                            <td><a :href="`https://www.instagram.com/${authUser.service.instagram}`" target="_blank" link>@{{ authUser.service.instagram }}</a></td>
                                        </tr>
                                        <tr v-if="authService.twitter">
                                            <th>Twitter: </th>
                                            <td><a :href="`https://www.twitter.com/${authUser.service.twitter}`" target="_blank" link>@{{ authUser.service.twitter }}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mt-8 mb-8">
                            <v-card-title class="primary white--text justify-center subtitle-1"> Portfolio</v-card-title>
                            <v-card-text class="justify-center mt-5">
                                <template v-if="authService.portfolio && authService.portfolio.length > 0">
                                    <table class="table table-condensed table-hover table-striped">
                                        <tr v-for="item in authService.portfolio" :key="item.id">
                                            <th width="60%" style="border-top: none"><router-link :to="{name: 'MyPortFolioShow', params:{id: item.id, slug: item.slug }}">{{ item.title | capFirstLetter | truncate(60) }}</router-link></th>
                                            <td style="border-top: none"> {{ item.published }} </td>
                                        </tr>
                                    </table>
                                </template>
                                <template v-else>
                                    You have not set up any portfolio for your service.
                                    <div class="text-center mt-5 mb-4">
                                        <v-btn dark large class="primary" width="80%" elevation="6" :to="{name: 'CreatePortFolio'}">Add Portfolio</v-btn>
                                    </div>
                                </template>
                            </v-card-text>
                            <v-card-actions v-if="authService.portfolio && authService.portfolio.length > 0" class="justify-center pb-8">
                                <v-btn text color="primary" :to="{name: 'MyPortFolio'}">Manage Portfolio</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
                <template v-else>
                    <v-col cols="12" md="8">
                        <v-card light raised elevation="12" min-height="300" class="mx-auto">
                            <v-card-title class="primary white--text justify-center title"> My Service</v-card-title>
                            <v-card-text class="body_text mt-7">
                                <v-alert type="warning">
                                    You have not set up a service for your account.
                                </v-alert>
                            </v-card-text>
                            <v-card-actions class="justify-center">
                                <v-btn dark class="primary" large to="/create-service">Add My Service</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
            </template>
        </v-row>
        <v-dialog v-model="confirmDel" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center primary white--text">Delete this service?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-2">
                    If you proceed to delete, your service, pictures, all the portfolio and reviews linked will also be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDel = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteService" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="serviceUpdated" :timeout="6000" top color="green darken-1 white--text">
          Your service was updated successfully.
          <v-btn text color="white--text" @click="serviceUpdated = false">Close</v-btn>
      </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            showAllReview: false,
            confirmDel: false,
            isLoading: false,
            serviceImg: '',
            openMenu: true
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authService(){
            return this.$store.getters.authService
        },
        serviceUpdated(){
            return this.$store.getters.serviceUpdated
        },
        isBusy(){
            return this.$store.getters.isBusy
        },
        serviceImage(){
            return this.$store.getters.serviceImage
        },
        headers(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        deleteService(){
            this.isLoading = true
            axios.delete(this.api + `/auth/service/${this.authUser.service.id}/delete`,this.headers).then((res) => {
                this.isLoading = false
                this.confirmDel = false
                this.$store.commit('serviceDeleted')
                this.$store.dispatch('fetchAuthUser')
                this.$router.push('/dashboard')
            })
        },
        getService(){
            this.isLoading = true
            this.$store.dispatch('fetchService').then(() =>{
                this.isLoading = false
            })
        },
        getServiceImgFromS3(){
            axios.get(this.api + '/auth/get_my_service_image_from_s3', this.headers).then((res) => {
                this.serviceImg = res.data
            })
        }
    },
    mounted(){
        this.$store.dispatch('fetchService')
        this.getServiceImgFromS3()
    },
}
</script>

<style scoped>
    a{
        text-decoration: none !important;
    }
    .v-card{
        overflow: hidden !important;
    }
</style>
