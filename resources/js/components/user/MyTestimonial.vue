<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-progress-circular v-if="loading" indeterminate color="primary" :width="4" :size="70" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <template v-if="testimonial">
                        <v-card elevation="4" light min-height="250" class="mx-auto">
                            <v-card-title class="primary white--text subtitle-1 justify-center">My Testimonial</v-card-title>
                            <v-card-text class="pa-4">
                                <table class="table table-condensed table-hover">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th style="border-top: none">Title</th>
                                            <td style="border-top: none">{{ testimonial.title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Detail</th>
                                            <td>{{ testimonial.detail }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>{{ testimonial.is_confirmed ? 'Confirmed' : 'Awaiting'  }}</td>
                                        </tr>
                                        <tr>
                                            <th>Published</th>
                                            <td>{{ testimonial.created_at | moment('DD/MM/YYYY') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                            <v-card-actions v-if="testimonial" class="justify-center pb-8 mt-n5">
                                <v-btn icon color="blue" :to="{name: 'EditTestimonial', params:{id: testimonial.id}}"><v-icon>edit</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                    <template v-else>
                        <v-alert type="info" border="left" class="mt-8 mb-8">
                            You have not written a testimonial for homas.com. Would you like to <router-link to="/new-testimonial">write one now?</router-link>
                        </v-alert>
                    </template>
                </template>
            </v-col>
        </v-row>
        <v-snackbar :value="testimonialUpdateSuccess" :timeout="5000" top color="green darken-1 white--text">
            Your testimonial has been updated.
            <v-btn text color="white--text" @click="testimonialUpdateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="testimonialCreated" :timeout="5000" top color="green darken-1 white--text">
            Testimonial has been created successfully.
            <v-btn text color="white--text" @click="testimonialCreated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            testimonial: null,
            loading: false,
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
        authUser(){
            return this.$store.getters.authUser
        },
        testimonialUpdateSuccess(){
            return this.$store.getters.testimonialUpdateSuccess
        },
        testimonialCreated(){
            return this.$store.getters.testimonialCreated
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAuthFlashMsg')
        next()
    },
    methods: {
        getAuthTestimonial(){
            this.loading = true
            axios.get(this.api + '/auth/get_auth_testimonial', this.authHeaders).then((res) => {
                this.loading = false
                this.testimonial = res.data
            })
        }
    },
    mounted(){
        this.getAuthTestimonial()
    }
}
</script>

<style scoped>
    a{
        color: rgb(228, 228, 228) !important;
    }
</style>
