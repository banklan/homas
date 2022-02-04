<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-progress-circular v-if="loading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <v-card elevation="4" light min-height="200" clsass="mx-auto">
                        <v-card-title class="primary white--text subtitle-1 justify-center">Update My Testimonial</v-card-title>
                        <v-card-text class="pa-4">
                            <template v-if="testimonial">
                                <v-text-field label="Title" v-model="testimonial.title" required v-validate="'required|min:4|max:50'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                                <v-textarea label="Detail" rows="2" auto-grow v-model="testimonial.detail" required v-validate="'required|min:10|max:400'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                            </template>
                            <template v-else>
                                <v-alert type="info" border="left" class="mt-5">
                                    You have not written a testimonial for homas.com. Would you like to <router-link to="/new-testimonial">write one now?</router-link>
                                </v-alert>
                            </template>
                        </v-card-text>
                        <v-card-actions v-if="testimonial" class="justify-center pb-8 mt-n2">
                            <v-btn large dark color="primary" :loading="isUpdating" @click="update">Update Testimonial</v-btn>
                        </v-card-actions>
                    </v-card>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            testimonial: {},
            isUpdating: false,
            loading: false
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
    },
    methods: {
        getAuthTestimonial(){
            this.loading = true
            axios.get(this.api + '/auth/get_auth_testimonial', this.authHeaders).then((res) => {
                this.loading = false
                if(res.data){
                    this.testimonial = res.data
                }else{
                    this.testimonial = null
                }
            })
        },
        update(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth/update_testimonial', {
                        testimonial: this.testimonial
                    }, this.authHeaders).then((res) => {
                        this.isUpdating = false
                        this.$store.commit('TestimonialUpdated')
                        this.$router.push({name: 'MyTestimonial'})
                        console.log(res.data)
                    })
                }
            })
        }
    },
    mounted(){
        this.getAuthTestimonial()
    }
}
</script>

<style scoped>

</style>
