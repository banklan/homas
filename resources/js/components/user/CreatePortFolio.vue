<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <template v-if="authUser.service">
                <v-col cols="12" md="8">
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-title class="primary white--text justify-center title"> Create New Portfolio</v-card-title>
                        <v-card-text class="mt-1 py-3">
                            <v-textarea label="Title" rows="1" v-model="portfolio.title" :counter="200" auto-grow required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title"></v-textarea>
                            <v-textarea label="Details" rows="2" v-model="portfolio.detail" auto-grow required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                            <v-text-field label="Price" v-model="portfolio.cost" v-validate="'decimal'" :error-messages="errors.collect('cost')" name="cost"></v-text-field>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-8">
                            <v-btn large color="primary darken--2" @click="create" :disabled="isLoading" width="50%">Submit</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </template>
        </v-row>
        <v-snackbar :value="createSuccess" :timeout="4000" top color="green darken-1 white--text">
            Your portfolio was created successfully.
            <v-btn text color="white--text" @click="createSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="createFail" :timeout="6000" top color="red darken-1 white--text">
            Your portfolio was not created. Please try again later.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
import { store } from '../../store'
export default {
    data() {
        return {
            isLoading: false,
            portfolio: {
                title: '',
                detail: '',
                cost: null
            },
            createSuccess: false,
            createFail: false
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authService
        },
        api(){
            return this.$store.getters.api
        },
        header(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        }
    },
    methods: {
        create(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    this.portfolio.cost = this.portfolio.cost
                    axios.post(this.api + `/auth/portfolio/${this.authUser.service.id}`, {
                        portfolio: this.portfolio
                    }, this.header).then((res) => {
                        this.isLoading = false
                        this.createSuccess = true
                        this.$store.dispatch('fetchService')
                        this.$store.commit('portfolioCreated')
                        this.$router.push({name: 'MyPortFolioShow', params: {id: res.data.id, slug: res.data.slug}})
                        // redirect
                    }).catch((err) => {
                        this.isLoading = false
                        this.createFail = true
                    })
                }
            })
        }
    },
    beforeRouteEnter (to, from, next) {
        let pfLen = store.getters.authUser.service.portfolio.length
        let prem = store.getters.authUser.service.is_premium
        if(!prem && pfLen > 2){
            next('/my-portfolio')
        }else{
            next()
        }
    },
}
</script>
