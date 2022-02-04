<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin" dark elevation="4" left :to="{name: 'AdminTestimonialList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-card :loading="isLoading" elevation="12" light min-height="250" class="mx-auto scroll">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Testimonial Detail</v-card-title>
                    <v-card-text class="ml-3 pr-3 pa-2">
                        <table class="table table-hover" v-if="testimonial">
                            <thead></thead>
                            <tbody>
                                <tr>
                                    <th width="25%" style="border-top: none">Title</th>
                                    <td style="border-top: none">{{ testimonial.title }}</td>
                                </tr>
                                <tr>
                                    <th>Detail</th>
                                    <td>{{ testimonial.detail }}</td>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <td>{{ testimonial.user && testimonial.user.fullname }}</td>
                                </tr>
                                <tr v-if="testimonial.user">
                                    <th>Service</th>
                                    <td>{{ testimonial.user.service && testimonial.user.service.title }}</td>
                                </tr>
                                <tr>
                                    <th>Testimonial Posted</th>
                                    <td class="wrap">{{ testimonial.created_at | moment('DD/MM/YYYY') }}</td>
                                </tr>
                                <tr>
                                    <th>Confirmed Status</th>
                                    <td class="wrap">{{ testimonial.is_confirmed ? 'Confirmed' : 'Not Confirmed' }}<v-btn small dark class="ml-2" :color="testimonial.is_confirmed ? '#ff5b0d' : '#095089'" @click="changeConfirmStatus" :loading="isChangingConfirm">Change</v-btn></td>
                                </tr>
                                <tr>
                                    <th>Feature Status</th>
                                    <td class="wrap">{{ testimonial.is_featured ? 'Featured' : 'Not Featured' }} <v-btn small dark class="ml-2" :color="testimonial.is_featured ? '#007605' : '#a7004d'" @click="changeFeatureStatus" :loading="isChangingFeature">Change</v-btn></td>
                                </tr>
                                <tr>
                                    <th>Last Update</th>
                                    <td class="wrap">{{ testimonial.updated_at | moment('DD/MM/YYYY - h:mma') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <v-alert v-else class="mt-5" type="info" border="left">
                            You are trying to view an invalid testimonial.
                        </v-alert>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="testimonial">
                        <v-btn dark icon color="blue darken-1" class="mx-2" :to="{name: 'AdminTestimonialUpdate', params: {id:testimonial.id}}"><v-icon>edit</v-icon></v-btn>
                        <v-btn dark icon color="red darken-2" class="mx-2" @click="confirmDelDial = true"><v-icon>delete_forever</v-icon></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Delete this testimonial?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, the testimonial will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken-2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" width="40%" @click="delTestimonial">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="testimonialStatusChanged" :timeout="3000" top color="green darken-1 white--text">
            This testimonial status has been changed.
            <v-btn text color="white--text" @click="testimonialStatusChanged = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedTestimonial" :timeout="4000" top color="green darken-1 white--text">
            The testimonial has been updated.
            <v-btn text color="white--text" @click="adminUpdatedTestimonial = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            testimonial: null,
            isChangingConfirm: false,
            isChangingFeature: false,
            testimonialStatusChanged: false,
            confirmDelDial: false,
            isDeleting: false,
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
        adminUpdatedTestimonial(){
            return this.$store.getters.adminUpdatedTestimonial
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getTestimonial(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_testimonial/${this.$route.params.id}`, this.headers).then((res) => {
                this.isLoading = false
                this.testimonial = res.data
            })
        },
        changeConfirmStatus(){
            this.isChangingConfirm = true
            axios.post(this.api + `/auth-admin/change_testimonial_confirm_status/${this.$route.params.id}`, {}, this.headers).then((res) => {
                this.isChangingConfirm = false
                this.testimonial.is_confirmed = res.data
                this.testimonialStatusChanged = true
            })
        },
        changeFeatureStatus(){
            this.isChangingFeature = true
            axios.post(this.api + `/auth-admin/change_testimonial_feature_status/${this.$route.params.id}`, {}, this.headers).then((res) => {
                this.isChangingFeature = false
                this.testimonial.is_featured = res.data
                this.testimonialStatusChanged = true
            })
        },
        delTestimonial(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/admin_del_testimonial/${this.$route.params.id}`, {}, this.headers).then((res) => {
                this.isDeleting = true
                this.$store.commit('adminDeleteTestimonial')
                this.$router.push({name: 'AdminTestimonialList'})
            })
        }
    },
    created() {
        this.getTestimonial()
    },
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tbody tr td.wrap, table tbody tr th{
        white-space: nowrap !important;
    }
</style>



