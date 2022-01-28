<<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <!-- <v-btn rounded color="admin" dark elevation="4" left :to="{name: 'AdminReviewList'}"><v-icon left>arrow_left</v-icon> Back</v-btn> -->
                <v-btn dark small rounded color="admin" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <template v-if="review">
                    <v-col cols="12" md="7">
                        <v-card light raised elevation="12" min-height="350" class="mx-auto">
                            <v-card-title class="justify-center admin white--text">Review</v-card-title>
                            <v-card-text class="body_text lighten-2 mt-3 px-5 pb-5">
                                <div class="font-weight-black mb-2">Service:</div>
                                <div>{{ review.service && review.service.title }}</div>
                                <v-divider></v-divider>
                                <div>
                                    <p class="body-1"><span class="font-weight-bold">{{ review.user && review.user.fullname }}</span> on {{ review.published }}</p>
                                    <v-rating small dense color="primary" readonly v-model="review.ratings" half-increments></v-rating>
                                    <p class="body-1 font-weight-bold mt-2">{{ review.title | capFirstLetter }}</p>
                                    <p class="body_text mb-3 mt-n2">{{ review.detail | capFirstLetter }}</p>
                                </div>
                            </v-card-text>
                            <v-card-actions class="justify-center pb-8">
                                <v-btn small dark :color="review.is_approved ? 'primary' : 'admin'" @click="toggleApprovedDial = true" :loading="isUpdating">{{ review.is_approved ? 'Disapprove' : 'Approve' }}</v-btn>
                                <v-btn small text dark color="red darken-2" @click="confirmDelDial = true"><v-icon>delete_forever</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
            </template>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin justify-center white--text">Delete this review?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    If you proceed to delete, this review will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="delReview" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="toggleApprovedDial" max-width="450">
            <v-card min-height="150" v-if="review">
                <v-card-title class="subtitle-1 justify-center admin white--text">{{ review.is_approved ? 'Disapprove' : 'Approve' }} this review?</v-card-title>
                <v-card-actions class="pb-8 mt-5 justify-center">
                    <v-btn text color="red darken--2" @click="toggleApprovedDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isTogglingApproved" @click="toggleIsApproved" width="40%">{{ review.is_approved ? 'Disapprove' : 'Approve' }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="reviewStatusChanged" :timeout="4000" top color="green darken-1 white--text">
            Review approve status has been updated successfully.
            <v-btn text color="white--text" @click="reviewStatusChanged = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            isLoading: false,
            review: null,
            reviewDelDialog: false,
            confirmDelDial: false,
            toggleApprovedDial: false,
            isTogglingApproved: false,
            isUpdating: false,
            reviewStatusChanged: false,
            isDeleting: false
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let authHeader = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return authHeader;
        },
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getReview(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_review/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.review = res.data
            })
        },
        toggleIsApproved(){
            this.isTogglingApproved = true
            axios.post(this.api + `/auth-admin/toggle_review_is_approved/${this.$route.params.id}`, {}, this.adminHeaders).then((res) =>{
                this.isTogglingApproved = false
                this.toggleApprovedDial = false
                this.review.is_approved = res.data.is_approved
                this.reviewStatusChanged = true
            })
        },
        delReview(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_delete_review/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.$router.push('/admin/reviews')
            this.$store.commit('adminDeleteReview')
            })
        },
    },
    mounted(){
        this.getReview()
    }
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tbody tr td, table tbody tr th{
        white-space: nowrap !important;
    }
</style>
