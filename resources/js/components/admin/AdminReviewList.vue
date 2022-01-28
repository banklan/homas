<template>
    <v-container>
        <v-row class="">
            <v-col cols="12" md="5" offset-md="6">
               <admin-search model="Review" searchFor="reviews"/>
           </v-col>
        </v-row>
        <v-row justify="start" class="mr-3 mt-n6">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Reviews <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="reviews.length > 0">
                            <table class="table table-hover table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Author</th>
                                        <th>Service</th>
                                        <th>Review </th>
                                        <th>Status</th>
                                        <th>Published</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(rev, i) in reviews" :key="rev.id">
                                        <td @click="showReview(rev)">{{ rev.id }} </td>
                                        <td @click="showReview(rev)">{{ rev.user && rev.user.fullname }} </td>
                                        <td @click="showReview(rev)">{{ rev.service && rev.service.title | truncate(30) }} </td>
                                        <td @click="showReview(rev)">{{ rev.title | truncate(40)  }} </td>
                                        <td>{{ rev.is_approved ? 'Approved' : 'Not Aprvd' }} </td>
                                        <td>{{ rev.created_at | moment('DD/MM/YYYY') }}</td>
                                        <td><v-btn small icon color="red darken-2" @click="delConfirm(rev, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                There are no reviews to display at the moment.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="reviews.length > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getReviews(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getReviews(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getReviews(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getReviews(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this review?</v-card-title>
                <v-card-text class="justify-center mt-5 body_text">
                    If you proceed to delete, the review will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="delReview" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminDeleteReview" :timeout="4000" top color="green darken-1 white--text">
            A review has been deleted successfully.
            <v-btn text color="white--text" @click="adminDeleteReview = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            reviews: [],
            reviewToDel: null,
            reviewToDelIndex: null,
            isDeleting: false,
            confirmDelDial: false
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        adminReviewStatus(){
            return this.$store.getters.adminReviewStatus
        },
        deletedReviewIndex(){
            let ind = this.$store.getters.deletedReviewIndex
            let delRev = this.$store.getters.reviewDeleted
            if(delRev == true){
                this.service.review.splice(ind, 1)
            }
            return ind
        },
        adminDeleteReview(){
            return this.$store.getters.adminDeleteReview
        },
        reviewStatusChanged(){
            return this.$store.getters.reviewStatusChanged
        }
    },
    methods: {
        getReviews(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_reviews`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.reviews = res.data.data
                this.total = res.data.total
                this.isLoading = false
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        delConfirm(rev, i){
            this.confirmDelDial = true
            this.reviewToDel = rev
            this.reviewToDelIndex = i
        },
        delReview(){
            this.isDeleting = true
            let review = this.reviewToDel
            let ind = this.reviewToDelIndex
            axios.post(this.api + `/auth-admin/admin_delete_review/${review.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.reviews.splice(ind, 1)
                this.total -=
                this.$store.commit('adminDeleteReview')
            })
        },
        showReview(rev){
            this.$router.push({name: 'AdminReviewDetail', params:{id: rev.id}})
        }
    },
    created() {
        this.getReviews()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
    .v-btn, a{
        text-decoration: none !important;
    }
    table tbody tr td, table tbody tr th, table thead tr th{
        white-space: nowrap !important;
    }
</style>

