<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template v-if="service">
                <v-row justify="center" class="mt-3">
                    <div class="title text-center mb-2">{{ service.title | capFirstLetter }}</div>
                </v-row>
                <v-row justify="space-around" class="mt-3">
                    <v-col cols="12" md="7">
                        <v-card light raised elevation="12" min-height="350" class="mx-auto pb-5">
                            <v-img v-if="service.image" :src="`/images/services/${service.image}`" width="100%" height="350" transition="scale-transition"></v-img>
                            <v-img v-else src="/images/no-image.png" width="100%" height="350" transition="scale-transition"></v-img>
                            <v-card-text class="mt-3 text-center">
                                <v-row class="text-center">
                                    <v-col cols="12">
                                        <div class="d-flex text-center justify-space-around mb-5 px-5">
                                            <v-chip v-if="service.is_approved" color="green darken-3" text-color="white">
                                                <v-avatar left><v-icon>mdi-checkbox-marked-circle</v-icon></v-avatar>
                                                Verified
                                            </v-chip>
                                            <span class="subtitle-2 primary--text"><v-rating v-model="service.ratings" readonly dense small color="primary"></v-rating></span>
                                            <span class="subtitle-2"><v-icon color="blue darken-1">visibility</v-icon>&nbsp;{{ service.views }}</span>
                                            <span>
                                                <v-menu offset-y bottom nudge-bottom="10" left>
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-btn @click="openShare ? openShare = !openShare : ''" v-bind="attrs" v-on="on" icon small class="mt-n1">
                                                            <v-icon color="grey">share</v-icon>Share
                                                        </v-btn>
                                                    </template>
                                                    <v-list>
                                                        <v-list-item-group>
                                                            <v-list-item>
                                                                <ShareNetwork network="facebook" url="https://www.google.com" title="service.title" descr="social.details" hashtags="cccrrrzz"><v-icon color="#1877f2" class="px-2">mdi-facebook</v-icon></ShareNetwork>
                                                            </v-list-item>
                                                            <v-list-item>
                                                                <ShareNetwork network="twitter" url="https://www.google.com" title="service.title" descr="social.details" hashtags="cccrrrzz"><v-icon color="#1da1f2" class="px-2">mdi-twitter</v-icon></ShareNetwork>
                                                            </v-list-item>
                                                            <v-list-item>
                                                                <ShareNetwork network="telegram" url="https://www.google.com" title="service.title" descr="social.details" hashtags="cccrrrzz"><v-icon color="blue" class="px-2">mdi-telegram</v-icon></ShareNetwork>
                                                            </v-list-item>
                                                        </v-list-item-group>
                                                    </v-list>
                                                </v-menu>
                                            </span>
                                        </div>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-text class="pb-8 px-5 ml-5">
                                <div class="font-weight-black mb-3 subtitle-1">Description:</div>
                                <span class="body_text text--darken-2">{{ service.description }}</span>
                                <div v-if="service.highlight" class="font-weight-black mt-5 mb-2 subtitle-1">Highlights:</div>
                                <span v-if="service.highlight" class="subtitle-1 body_text text--darken-2">{{ service.highlight }}</span>
                                <div v-if="service.keystrength" class="font-weight-black mt-5 mb-2 subtitle-1">Key strengths:</div>
                                <span v-if="service.keystrength" class="subtitle-1 mb-5 body_text text--darken-2">{{ service.keystrength }}</span>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mx-auto mt-8">
                            <v-card-title class="subtitle-1 primary white--text justify-center">Portfolio</v-card-title>
                            <v-card-text class="body-1 pb-8">
                                <v-list two-line nav v-if="service">
                                    <v-list-item-group dense light color="white" class="ml-n2">
                                        <template v-if="service.portfolio.length > 0">
                                            <div v-for="(pf, index) in service.portfolio" :key="pf.id">
                                                <v-list-item class="mb-n5" :to="{name: 'PortfolioShowSingle', params: {id: pf.id, slug: pf.slug}}">
                                                    <v-list-item-content class="mt-1">
                                                        <v-list-item-subtitle class="subtitle-2 black--text lighten-3">{{ pf.title | truncate(50) }}</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                </v-list-item>
                                                <v-divider v-if="index < service.portfolio.length - 1"></v-divider>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="ml-2 mt-3 body_text">This service has no portfolio yet.</div>
                                        </template>
                                    </v-list-item-group>
                                </v-list>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mx-auto mt-8">
                            <v-card-title class="subtitle-1 primary white--text justify-center">Reviews ({{ service.review && service.review.length }})</v-card-title>
                            <v-card-text class="mt-3 px-5">
                                <template v-if="authUser && authUser.id !== service.user_id">
                                    <div class="text-center title mt-4 mb-3">New Review</div>
                                    <v-text-field label="Title" v-model="newReview.title" required v-validate="'required|min:3|max:200'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                                    <v-textarea rows="1" auto-grow placeholder="Details" v-model="newReview.detail" required v-validate="'required|min:3|max:500'" :error-messages="errors.collect('details')" name="details"></v-textarea>
                                    <span class="mt-3">Rate: <v-rating v-model="newReview.rating" required half-increments dense></v-rating></span>
                                    <v-card-actions class="mt-3 pb-5 justify-center">
                                        <v-btn dark large class="primary" :loading="isSubmitting" @click="submitReview" width="50%">Submit</v-btn>
                                    </v-card-actions>
                                    <v-divider></v-divider>
                                </template>
                                <v-list two-line nav v-if="service.review">
                                    <v-list-item-group dense color="primary" class="ml-2">
                                        <template v-if="service.review && service.review.length > 0">
                                            <div v-for="(rev, index) in service.review.slice(0, 4)" :key="rev.id">
                                                <review-item-gen :review="rev" :index="index" :reviews="service.review"></review-item-gen>
                                                <v-divider></v-divider>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="ml-2 mt-3 body-1 black--text">This service has no reviews yet.</div>
                                        </template>
                                    </v-list-item-group>
                                </v-list>
                            </v-card-text>
                            <v-card-actions v-if="service.review && service.review.length > 0" class="justify-center mt-n3 pb-6">
                                <v-btn text color="primary" :to="{name: 'ServiceReviewList', params: {service: service.id}}">All Reviews</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="4">
                        <v-card light raised elevation="12" min-height="400" class="mx-auto scroll">
                            <v-card-title class="subtitle-1 primary white--text justify-center">Service Details</v-card-title>
                            <v-card-text class="px-3 py-3">
                                <table class="table table-condensed table-hover">
                                    <thead></thead>
                                    <tbody>
                                        <tr class='no_border'>
                                            <th width="30%">Author: </th>
                                            <td>{{ service.user && service.user.fullname }} </td>
                                        </tr>
                                        <tr>
                                            <th>Position: </th>
                                            <td>{{ service.position }} </td>
                                        </tr>
                                        <tr>
                                            <th>Published: </th>
                                            <td>{{ service.published }} </td>
                                        </tr>
                                        <tr>
                                            <th>Category: </th>
                                            <td>{{ service.category && service.category.name }} </td>
                                        </tr>
                                        <tr>
                                            <th>Experience Year(s): </th>
                                            <td>{{ service.experience_years }} </td>
                                        </tr>
                                        <tr>
                                            <th>Email: </th>
                                            <td>{{ service.email }} </td>
                                        </tr>
                                        <tr>
                                            <th>Phone No: </th>
                                            <td>{{ service.phone }} </td>
                                        </tr>
                                        <tr>
                                            <th>Service Address: </th>
                                            <td>{{ service.address }} </td>
                                        </tr>
                                        <tr>
                                            <th>Service Location: </th>
                                            <td>{{ service.location && service.location.state }} </td>
                                        </tr>
                                        <tr v-if="service.facebook">
                                            <th>Facebook: </th>
                                            <td>{{ service.facebook }} </td>
                                        </tr>
                                        <tr v-if="service.instagram">
                                            <th>Instagram: </th>
                                            <td>{{ service.instagram }} </td>
                                        </tr>
                                        <tr>
                                            <th v-if="service.twitter">Twitter: </th>
                                            <td>{{ service.twitter }} </td>
                                        </tr>
                                        <tr class="no_border">
                                            <th v-if="service.website">Website: </th>
                                            <td>{{ service.website }} </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row justify="center" class="mt-10 pb-5" v-if="similarServices.length > 0">
                    <v-col cols="12">
                        <similar-services :services="similarServices" />
                    </v-col>
                </v-row>
                <v-row justify="center" class="mt-5 pb-5" v-if="locTopServices.length > 0">
                    <v-col cols="12">
                        <loc-top-services :services="locTopServices" />
                    </v-col>
                </v-row>
            </template>
            <template v-else>
                <v-row justify="center" class="mt-5">
                    <v-col cols="12" md="8">
                        <v-alert type="warning" border="left">There are no services listed for {{ service && service.location.state }} state yet.</v-alert>
                    </v-col>
                </v-row>
            </template>
        </template>
        <v-snackbar :value="reviewRatingError" :timeout="6000" top color="red darken-1 white--text">
            Kindly rate the service by clicking on the review rating button to choose a rating.
            <v-btn text color="white--text" @click="reviewRatingError = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createReviewFail" :timeout="6000" top color="red darken-1 white--text">
            Your review has not been sent. Please try again and ensure that you fill all fields and rate the service.
            <v-btn text color="white--text" @click="createReviewFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createReviewSuccess" :timeout="4000" top color="green darken-1 white--text">
            Your review has been submitted. Thank you.
            <v-btn text color="white--text" @click="createReviewSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="userReviewDeleted" :timeout="4000" top color="green darken-1 white--text">
            Review has been deleted.
            <v-btn text color="white--text" @click="userReviewDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            slug: this.$route.params.slug,
            isLoading: false,
            service: null,
            miniRev: false,
            author: null,
            image: null,
            newReview: {
                title: '',
                detail: '',
                rating: 0
            },
            reviewRatingError: false,
            isSubmitting: false,
            createReviewFail: false,
            createReviewSuccess: false,
            similarServices: [],
            servicesInCity: [],
            openShare: true,
            socialMedia: [
                {color: '#1877f2', icon: 'mdi-facebook', text: 'Facebook'},
                {color: '#1da1f2', icon: 'mdi-twitter', text: 'Twitter'},
                {color: '#c32aa3', icon: 'mdi-instagram', text: 'Instagram'},
            ],
            locTopServices: []
        }
    },
    watch:{
        '$route.params.id': {
            handler(newVal){
                this.id = newVal
                this.getService()
                this.getSimilarServices()
                this.incrementCount()
            },
            immediate: true
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        userLoggedIn(){
            return this.$store.getters.userLoggedIn
        },
        headers(){
            let conf = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return conf
        },
        userReviewDeleted(){
            return this.$store.getters.userDeletedReview
        },
    },
    methods: {
        getService(){
            this.isLoading = true
            axios.get(this.api + `/get_service/${this.id}`).then((res) => {
                this.isLoading = false
                this.service = res.data
                this.service.views = res.data.views ++
                this.getLocTopServices(res.data.category_id, res.data.location_id)
            })
        },
        incrementCount(){
            axios.post(this.api + `/increment_view_count/${this.id}`).then((res)=>{
                this.service.views = res.data
            })
        },
        submitReview(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    if(this.newReview.rating > 0){
                        this.isSubmitting = true
                        axios.post(this.api + `/auth/new_service_review/${this.id}`, {
                            title: this.newReview.title,
                            detail: this.newReview.detail,
                            rating: this.newReview.rating,
                        }, this.headers).then((res) => {
                            this.isSubmitting = false
                            this.service.review.unshift(res.data)
                            this.newReview.title = ""
                            this.newReview.detail = ""
                            this.newReview.rating = 0
                            this.$validator.pause()
                            this.$validator.fields.items.forEach(field => field.reset())
                            this.$validator.errors.clear()
                            this.createReviewSuccess = true
                        }).catch(()=>{
                            this.isSubmitting = false
                            this.createReviewFail = true
                        })
                    }else{
                        this.reviewRatingError = true
                    }
                }
            })
        },
        getSimilarServices(){
            this.isLoading = true
            axios.get(this.api + `/get_similar_services/${this.id}`).then((res) => {
                this.isLoading = false
                this.similarServices = res.data
            })
        },
        getLocTopServices(cat, loc){
            axios.get(this.api + `/get_top_services_for_location/${cat}/${loc}/${this.id}`).then((res) => {
                this.locTopServices = res.data
            })
        }
    },
    mounted(){
        this.getService()
        this.getSimilarServices()
        this.incrementCount()
    }
}
</script>

<style lang="scss" scoped>
    a{
        text-decoration: none !important;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table .no_border th, table .no_border td{
        border: none !important;
    }
    table tbody tr td, table tbody tr th{
        white-space: nowrap !important;
    }
</style>
