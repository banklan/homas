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
                <v-row justify="center" class="mt-5 mr-3">
                    <v-col cols="12" md="10">
                        <div class="title justify-center">Service Review List</div>
                        <v-card light raised elevation="12" min-height="150" class="mx-auto mt-5">
                            <v-card-title class="justify-center title primary white--text">Service</v-card-title>
                            <v-card-text class="body-1 mt-3 black--text lighten-2">{{ service.title | capFirstLetter}}</v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="250" class="mx-auto mt-5">
                            <v-card-title class="justify-center title primary white--text">Reviews ({{ service.review.length }})</v-card-title>
                            <v-card-text class="body-2 mt-5 mx-3 black--text lighten-2">
                                <v-list two-line nav>
                                    <v-list-item-group dense color="primary" class="ml-n2">
                                        <template v-if="service.review.length > 0">
                                            <div v-for="(review, i) in service.review" :key="review.id">
                                                <review-item-gen :review="review" :index="i"></review-item-gen>
                                                <v-divider v-if="i < service.review.length"></v-divider>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="ml-2 mt-3">This service has no reviews yet.</div>
                                        </template>
                                    </v-list-item-group>
                                </v-list>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </template>
        </template>
        <v-snackbar :value="reviewDeleted" :timeout="5000" top color="green darken-1 white--text">
            Review has been deleted.
            <v-btn text color="white--text" @click="reviewDeleted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            service: null,
            id: this.$route.params.service
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        deletedReviewIndex(){
            let ind = this.$store.getters.deletedReviewIndex
            let delRev = this.$store.getters.reviewDeleted
            if(delRev == true){
                this.service.review.splice(ind, 1)
            }
            return ind
        },
        reviewDeleted(){
            return this.$store.getters.reviewDeleted
        }
    },
    methods: {
        getService(){
            this.isLoading = true
            axios.get(this.api + `/get_service/${this.id}`).then((res) => {
                this.isLoading = false
                this.service = res.data
            })
        },
    },
    created() {
        this.getService()
    },
}
</script>
