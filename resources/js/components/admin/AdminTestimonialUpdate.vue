<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin" dark elevation="4" left :to="{name: 'AdminTestimonialList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-col cols="12" md="9">
                <v-card :loading="isLoading" elevation="12" light min-height="250" class="mx-auto scroll">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Update Testimonial</v-card-title>
                    <template v-if="testimonial">
                        <v-card-text class="ml-3 pr-3 pa-2">
                            <v-text-field label="Title" v-model="testimonial.title" required v-validate="'required|min:4|max:50'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                            <v-textarea label="Detail" rows="2" auto-grow v-model="testimonial.detail" required v-validate="'required|min:10|max:400'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-8 mt-n2">
                            <v-btn large dark color="admin" :loading="isSaving" @click="submit">Update Testimonial</v-btn>
                        </v-card-actions>
                    </template>
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
            testimonial: null,
            isSaving: false,
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
        getTestimonial(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_testimonial/${this.$route.params.id}`, this.headers).then((res) => {
                this.isLoading = false
                this.testimonial = res.data
            })
        },
        submit(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isSaving = true
                    axios.post(this.api + `/auth-admin/update_testimonial/${this.$route.params.id}`, {
                        testimonial: this.testimonial
                    }, this.headers).then((res) => {
                        // this.$store.commit('adminUpdatedTestimonial')
                        this.$store.commit('adminUpdatedTestimonial')
                        this.$router.push({name: 'AdminTestimonialShow', params: {id:res.data.id}})
                    })
                }
            })
            // console.log(this.testimonial)
        }
    },
    created(){
        this.getTestimonial()
    }
}
</script>

