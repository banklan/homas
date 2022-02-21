<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-card elevation="4" light min-height="200" clsass="mx-auto">
                    <v-card-title class="primary white--text subtitle-1 justify-center">Write New Testimonial</v-card-title>
                    <v-card-text class="pa-4">
                        <v-text-field label="Title" v-model="testimonial.title" required v-validate="'required|min:4|max:50'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                        <v-textarea label="Detail" rows="2" auto-grow v-model="testimonial.detail" required v-validate="'required|min:10|max:400'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8 mt-n2">
                        <v-btn large dark color="primary" :loading="isSaving" @click="submit">Submit Testimonial</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { store } from '../../store'
export default {
    data(){
        return{
            testimonial: {
                title: '',
                detail: ''
            },
            isSaving: false,
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
    beforeRouteEnter (to, from, next) {
        let headers = {
            headers: {
                "Authorization": `Bearer ${store.getters.authUser.token}`
            }
        }
        axios.get(store.getters.api + '/auth/check_auth_testimonial', headers)
        .then((res) => {
            if(res.data == 0){
                next()
            }else{
                next('/my-testimonial')
            }
        })
    },
    methods: {
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isSaving = true
                    axios.post(this.api + '/auth/create_testimonial', {
                        testimonial: this.testimonial
                    }, this.authHeaders).then((res) => {
                        this.isSaving = false
                        this.$store.commit('TestimonialCreated')
                        this.$router.push({name: 'MyTestimonial'})
                    })
                }
            })
        }
    },
}
</script>
