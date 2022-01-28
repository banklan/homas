<template>
    <div>
        <template v-if="!edit">
            <v-list-item class="mb-n5 mt-n5" @click="expandReview">
                <v-list-item-content class="mt-1 pa-3">
                    <p class="body-1 primary--text">{{ review.user && review.user.fullname }} on {{ review.published }}</p>
                    <v-rating dense small color="primary" readonly v-model="review.ratings" class="mt-2" half-increments></v-rating>
                    <p class="body-1 font-weight-bold mt-2">{{ review.title | capFirstLetter }}</p>
                    <p v-if="miniRev" class="body-1 black--text lighten-5 mb-3">{{ review.detail | capFirstLetter | truncate(150) }}</p>
                    <p v-else class="body-1 black--text lighten-5 mb-3">{{ review.detail | capFirstLetter }}</p>
                    <div v-if="authUser && authUser.id == review.user_id" class="justify-start mx-3 mt-3">
                        <v-btn icon color="primary" @click="switchToEdit" class="pr-10"><v-icon>edit</v-icon></v-btn>
                        <v-btn icon color="red darken-2" @click="confirmDel = true"><v-icon>delete_forever</v-icon></v-btn>
                    </div>
                </v-list-item-content>
            </v-list-item>
        </template>
        <template v-else>
            <v-text-field label="Title" v-model="editReview.title" required v-validate="'required|min:3|max:200'" :error-messages="errors.collect('title')" name="title"></v-text-field>
            <v-textarea rows="1" auto-grow label="Details" v-model="editReview.detail" required v-validate="'required|min:3|max:500'" :error-messages="errors.collect('details')" name="details"></v-textarea>
            <div class="text-center mt-3 pb-5 mx-3">
                <v-btn text color="red darken-2" @click="edit = false" width="40%">Cancel</v-btn>
                <v-btn dark color="primary" @click="updateReview" :loading="isLoading" width="40%">Update</v-btn>
            </div>
        </template>
        <v-dialog v-model="confirmDel" max-width="420">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center primary white--text">Delete this review?</v-card-title>
                <v-card-actions class="justify-center mt-5">
                    <v-btn text color="red darken--2" @click="confirmDel = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isLoading" @click="deleteReview" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top color="green darken-1 white--text">
            Your review has been updated.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFail" :timeout="6000" top color="red darken-1 white--text">
            Your review update failed. Please try again later.
            <v-btn text color="white--text" @click="updateFail = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    props: ['review', 'index', 'reviews'],
    data() {
        return {
            miniRev: true,
            isUpdating: false,
            reviewDelDialog: false,
            isLoading: false,
            edit: false,
            editReview: {
                title: '',
                detail: ''
            },
            updateSuccess: false,
            updateFail: false,
            confirmDel: false
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        headers(){
            let authHeader = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return authHeader;
        },
    },
    methods: {
        updateReview(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + `/auth/update_review/${this.review.id}`, {
                        review: this.editReview
                    }, this.headers).then((res) => {
                        this.isLoading = false
                        this.reviews.splice(this.index, 1)
                        this.reviews.unshift(res.data)
                        this.edit = false
                        this.updateSuccess = true
                    }).catch(() =>{
                        this.isLoading = false
                        this.updateFail = true
                    })
                }
            })
        },
        deleteReview(){
            this.isLoading = true
            axios.post(this.api + `/auth/delete_review/${this.review.id}`, {}, this.headers).then((res)=>{
                this.isLoading = false
                this.confirmDel = false
                this.reviews.splice(this.index, 1)
                this.$store.commit('userDeletedReview')
            })
        },
        switchToEdit(){
            this.edit = true
            this.editReview.title = this.review.title
            this.editReview.detail = this.review.detail
        },
        expandReview(){
            this.miniRev = false
        },
    }
}
</script>
