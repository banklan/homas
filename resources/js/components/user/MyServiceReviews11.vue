<template>
    <v-container>
        <!-- <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular> -->
        <v-row class="mt-4" justify="center" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <template v-if="authService && authService.review.length > 0">
                 <h1>{{ authService.review }}</h1>
                 <h1>this is thew review</h1>
                    <!-- <v-list class="ml-n3">
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
                    </v-list> -->
                </template>
                <!-- <v-list-item class="mb-n5 mt-n5" @click="expandReview">
                    <v-list-item-content class="mt-1 pa-3">
                        <p class="body-1 primary--text">{{ review.author && review.author.fullname }} on {{ review.published }}</p>
                        <v-rating dense small color="primary" readonly v-model="review.ratings" class="mt-2" half-increments></v-rating>
                        <p class="body-1 font-weight-bold mt-2">{{ review.title | capFirstLetter }}</p>
                        <p v-if="miniRev" class="body_text black--text lighten-5 mb-3">{{ review.detail | capFirstLetter | truncate(150) }}</p>
                        <p v-else class="body_text black--text lighten-5 mb-3">{{ review.detail | capFirstLetter }}</p>
                        <div class="mt-2 px-3 mb-2 justify-start">
                            <v-btn small dark class="primary white--text" @click="toggleIsApproved" :loading="isUpdating">{{ review.is_approved ? 'Disapprove' : 'Approve' }}</v-btn>
                            <v-btn small text dark color="red darken-2" @click="reviewDelDialog = true"><v-icon>delete_forever</v-icon></v-btn>
                        </div>
                        <v-divider></v-divider>
                    </v-list-item-content>
                </v-list-item> -->
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            reviews: [],
            isLoading: false
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authService
        },
        api(){
            return this.$store.getters.api
        },
        headers(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        expandReview(){},
        getMyReviews(){
            this.isLoading = true
            axios.get(this.api + '/auth/get_my_service_reviews', this.headers).then((res) => {
                this.isLoading = false
                this.reviews = res.data
                console.log(res.data)
            })
        }
    },
    mounted(){
        this.getMyReviews()
    }
}
</script>
