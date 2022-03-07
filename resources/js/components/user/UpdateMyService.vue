<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left to="/my-service"><v-icon left>arrow_left</v-icon> Back To My Service</v-btn>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="8">
                <v-card light raised elevation="12" min-height="200" class="mx-auto">
                    <v-card-title class="primary white--text justify-center subtitle-1">Update Service </v-card-title>
                    <v-card-text class="body-1 pa-3 mt-5 mr-6 pl-6">
                        <v-text-field label="Name Of Service Company" v-model="service.title" required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title" data-vv-as="title/name of service"></v-text-field>
                        <v-text-field label="Your Position In Company" v-model="service.position" v-validate="'max:100'" :error-messages="errors.collect('position')" name="position"></v-text-field>
                        <v-textarea label="Description Of Service" rows="3" auto-grow v-model="service.description" required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('description')" name="description"></v-textarea>
                        <v-text-field label="Experience Years" v-model="service.experience_years" required v-validate="'required|numeric'" :error-messages="errors.collect('experience_years')" name="experience_years" data-vv-as="years of experience"></v-text-field>
                        <v-textarea label="Highlights(major achievements, awards & recognition)" rows="2" auto-grow v-model="service.highlight" v-validate="'min:5|max:255'" :error-messages="errors.collect('highlights')" name="highlights"></v-textarea>
                        <v-textarea label="Keystrength(advantage over your key competitors)" rows="2" auto-grow v-model="service.keystrength" v-validate="'min:5|max:150'" :error-messages="errors.collect('keystrength')" name="keystrength"></v-textarea>
                        <v-textarea label="Address" rows="2" auto-grow v-model="service.address" v-validate="'required|min:5|max:255'" :error-messages="errors.collect('address')" name="address"></v-textarea>
                        <v-text-field label="City" v-model="service.city" required v-validate="'required|max:30'" :error-messages="errors.collect('city')" name="city"></v-text-field>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-select :items="locations" item-text="state" item-value="id" label="Select State" v-model="service.location_id" persistent-hint required v-validate="'required'" :error-messages="errors.collect('location_id')" name="location_id" data-vv-as="state"></v-select>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Phone Number" v-model="service.phone" required v-validate="'required|numeric'" :error-messages="errors.collect('phone_number')" name="phone_number" data-vv-as="phone number"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Website" v-model="service.website"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Facebook Handle" v-model="service.facebook" prefix="https://www.facebook.com/"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Instagram handle" v-model="service.instagram"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Twitter handle" v-model="service.twitter"></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-10 mt-4">
                        <v-btn text color="red darken-2" large width="40%" :to="{name: 'MyService'}">Cancel</v-btn>
                        <v-btn large dark color="primary" width="40%" :loading="isSubmitting" @click="update">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            isSubmitting: false,
            locations: [],
            categories: [],
            service: {
                title: '',
                position: '',
                description: '',
                experience_years: '',
                highlight: '',
                keystrength: '',
                phone: '',
                address: '',
                city: '',
                location_id: null,
                website: '',
                facebook: '',
                instagram: '',
                twitter: '',
            },
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        api(){
            return this.$store.getters.api
        },
        authUserService(){
            return this.$store.getters.authUserService
        },
        header(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        }
    },
    methods: {
        getCategories(){
            axios.get(this.api + '/categories').then((res) => {
                this.categories = res.data
            })
        },
        getStates(){
            axios.get(this.api + '/locations').then((res) => {
                this.locations = res.data
            })
        },
        update(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isSubmitting = true
                    axios.post(this.api + '/auth/update_my_service', {
                        service: this.service
                    },this.header).then((res) => {
                        this.isSubmitting = false
                        this.$store.commit('ServiceWasUpdated')
                        this.$router.push({name: 'MyService'})
                    }).catch(() => {
                        this.isSubmitting = false
                        this.updateFail = true
                    })
                }
            })
        },
        getService(){
            this.service = this.authUserService
        }
    },
    created() {
        this.getCategories()
        this.getStates()
        this.getService()
    },
   
}
</script>