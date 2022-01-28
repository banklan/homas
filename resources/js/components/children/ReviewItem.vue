<template>
    <v-list-item class="mb-n5 mt-n5" @click="expandReview">
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
        <v-dialog v-model="reviewDelDialog" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center pt-8 mb-4 font-weight-bold">Are you sure you want to delete this review?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, this review will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="reviewDelDialog = false">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteReview">Yes, Delete</v-btn>
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
            isLoading: false
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
