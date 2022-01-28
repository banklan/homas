<template>
    <v-list-item class="mb-n2 mt-n2" @click="expandReview">
        <v-list-item-content class="mt-2 pa-3">
            <p class="body_text admin--text">{{ review.user && review.user.fullname }} on {{ review.published }}</p>
            <v-rating dense small color="admin" readonly v-model="review.ratings" class="mt-2" half-increments></v-rating>
            <p class="body_text font-weight-bold mt-3">{{ review.title | capFirstLetter }}</p>
            <p v-if="miniRev" class="body_text black--text lighten-5 mb-3 mt-2 rev_detail" >{{ review.detail | capFirstLetter | truncate(150) }}</p>
            <p v-else class="body_text black--text lighten-5 mb-3 mt-2 rev_detail">{{ review.detail | capFirstLetter }}</p>
            <div class="mt-4 px-3 mb-2 justify-space-around text-center">
                <v-btn small dark class="white--text" @click="toggleIsApproved" :loading="isUpdating" :class="review.is_approved ? 'primary' : 'admin'">{{ review.is_approved ? 'Disapprove' : 'Approve' }}</v-btn>
                <v-btn small text dark color="red darken-2" @click="reviewDelDialog = true"><v-icon>delete_forever</v-icon></v-btn>
            </div>
            <v-divider></v-divider>
        </v-list-item-content>
        <v-dialog v-model="reviewDelDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this review?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1">
                    If you proceed to delete, this review will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="reviewDelDialog = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isLoading" @click="deleteReview" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-list-item>
</template>

<script>
export default {
    props: ['review', 'index'],
    data() {
        return {
            miniRev: true,
            isUpdating: false,
            reviewDelDialog: false,
            isLoading: false,
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        headers(){
            let authHeader = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return authHeader;
        },
        reviewStatusChanged(){
            return this.$store.getters.reviewStatusChanged
        },
    },
    methods: {
        expandReview(){
            console.log(this.review)
            this.miniRev = false
        },
        toggleIsApproved(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/toggle_review_approal/${this.review.id}`, {}, this.headers)
            .then((res) => {
                this.isUpdating = false
                this.review.is_approved = res.data.is_approved
                this.$store.commit('reviewStatusChanged')
                this.statusChanged = true
            })
        },
        deleteReview(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/delete_review/${this.review.id}`, {}, this.headers)
            .then((res) => {
                this.isLoading = false
                this.reviewDelDialog = false
                console.log(res.data)
                this.$store.commit('reviewDeleted', this.index)
            })
        }
    }
}
</script>

<style lang="css" scoped>
    .rev_detail{
        line-height: 1.6 !important;
    }

</style>
