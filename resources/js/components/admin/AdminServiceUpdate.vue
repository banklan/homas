<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin lighten--2" dark elevation="4" left :to="{name: 'AdminServicesList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <v-col cols="12" md="10">
                    <template v-if="edit">
                        <v-card light raised elevation="12" min-height="350" class="mx-auto">
                            <v-card-title class="justify-center admin white--text">Update Service</v-card-title>
                            <v-card-text class="mt-5">
                                <v-row justify="center">
                                    <v-col cols="12" md="10">
                                        <v-select label="Category" :items="categories" item-text="name" item-value="id" v-model="edit.category_id" required v-validate="'required'" :error-messages="errors.collect('category')" name="category" data-vv-name="state"></v-select>
                                        <v-text-field label="Title" v-model="edit.title" required v-validate="'required|min:10|max:200'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                                        <v-text-field label="Position" v-model="edit.position" required v-validate="'max:100'" :error-messages="errors.collect('position')" name="position"></v-text-field>
                                        <v-text-field label="Experience Years" v-model="edit.experience_years" required v-validate="'required|integer'" :error-messages="errors.collect('experience_years')" name="position" data-vv-as="experience years"></v-text-field>
                                        <v-textarea rows="2" auto-grow label="Highlight" v-model="edit.highlight" v-validate="'max:255'" :error-messages="errors.collect('highlight')" name="highlight"></v-textarea>
                                        <v-textarea rows="2" auto-grow label="Keystrength" v-model="edit.keystrength" v-validate="'max:150'" :error-messages="errors.collect('keystrength')" name="keystrength"></v-textarea>
                                        <v-textarea rows="3" auto-grow label="Description" v-model="edit.description" v-validate="'required|min:20|max:600'" :error-messages="errors.collect('description')" name="description"></v-textarea>
                                        <v-text-field label="Phone Number" v-model="edit.phone" required v-validate="'required|max:16'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                                        <v-textarea rows="1" auto-grow label="Address" v-model="edit.address" required v-validate="'required|min:5|max:255'" :error-messages="errors.collect('address')" name="address"></v-textarea>
                                        <v-text-field label="City" v-model="edit.city" required v-validate="'required|min:2|max:50'" :error-messages="errors.collect('city')" name="city"></v-text-field>
                                        <v-select label="Select State" :items="locations" item-text="state" item-value="id" v-model="edit.location_id" required v-validate="'required'" :error-messages="errors.collect('location')" name="location" data-vv-name="state"></v-select>
                                        <v-text-field label="Website" v-model="edit.website" required v-validate="'max:100'" :error-messages="errors.collect('website')" name="website"></v-text-field>
                                        <v-text-field label="Facebook Handle" v-model="edit.facebook" required v-validate="'max:100'" :error-messages="errors.collect('facebook')" name="facebook"></v-text-field>
                                        <v-text-field label="Instagram Handle" v-model="edit.instagram" required v-validate="'max:100'" :error-messages="errors.collect('instagram')" name="instagram"></v-text-field>
                                        <v-text-field label="Twitter Handle" v-model="edit.twitter" required v-validate="'max:100'" :error-messages="errors.collect('twitter')" name="twitter"></v-text-field>
                                    </v-col>
                                </v-row>
                            </v-card-text>
                            <v-card-actions class="justify-center pb-8">
                                <v-btn text large color="red darken-2" @click.prevent="$router.go(-1)" width="40%">Cancel</v-btn>
                                <v-btn large dark color="admin darken-2" @click="updateService" :loading="isUpdating" width="40%">Update</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                    <template v-else>
                        <v-alert type="info" class="mt-5">
                            The service you are trying to update is invalid or not existing.
                        </v-alert>
                    </template>
                </v-col>
            </template>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            service: null,
            edit:{
                category_id: null,
                title: '',
                position: '',
                experience_years: null,
                highlight: '',
                keystrength: '',
                description: '',
                phone: '',
                address: '',
                city: '',
                location_id: null,
                website: '',
                facebook: '',
                twitter: '',
                instagram: '',
            },
            isLoading: false,
            isUpdating: false,
            locations: [],
            categories: [],
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeader(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        }
    },
    methods: {
        updateService(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    // console.log(this.edit)
                    axios.post(this.api + `/auth-admin/admin_service_update/${this.id}`, {
                        service: this.edit
                    }, this.adminHeader).then((res) => {
                        this.isUpdating = false
                        this.$store.commit('adminUpdated')
                        console.log(res.data)
                        this.$router.push({name: 'AdminServiceDetail', params: {id: res.data.id, slug:res.data.slug}})
                    }).catch((e)=>{
                        console.log(e)
                        this.isUpdating = false
                    })
                }
            })
        },
        getService(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_service/${this.id}`, {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }).then((res) => {
                // console.log(res.data)
                this.isLoading = false
                this.edit = res.data
            })
        },
        getLocations(){
            axios.get(this.api + `/locations`).then((res) => {
                // console.log(res.data)
                this.locations = res.data
            })
        },
        getCategories(){
            axios.get(this.api + '/categories').then((res) => {
                this.categories = res.data
            })
        }
    },
    created() {
        this.getService()
        this.getLocations()
        this.getCategories()
    },
}
</script>

<style lang="css" scoped>
    .v-btn{
        text-decoration: none;
    }
</style>

