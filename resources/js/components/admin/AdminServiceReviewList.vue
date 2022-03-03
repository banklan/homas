<template>
    <v-container>
        <v-row class="">
            <v-col cols="12" md="5" offset-md="6">
               <admin-search model="Review" searchFor="reviews"/>
           </v-col>
        </v-row>
        <v-row justify="start" class="mr-3 mt-n6">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
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
                                        <td @click="showReview(rev)">{{ rev.title | truncate(50) }} </td>
                                        <td @click="showReview(rev)">{{ rev.service_id }} </td>
                                        <td @click="showReview(rev)">{{ rev.cost / 100 | price  }} </td>
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
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            reviews: [],
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
        reviewDeleted(){
            return this.$store.getters.reviewDeleted
        },
        reviewStatusChanged(){
            return this.$store.getters.reviewStatusChanged
        }
    },
    methods: {
        getReviews(pag){
            pag = pag || `${this.api}/auth-admin/get_pgntd_reviews`
            this.isLoading = true
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
        delConfirm(){},
        showReview(rev){}
    },
    created() {
        this.getReviews()
    },
}
</script>
