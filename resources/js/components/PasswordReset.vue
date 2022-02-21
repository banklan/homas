<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="5">
                <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <template v-if="resetPasswordForm">
                        <v-card elevation="6" light raised min-height="250" class="my-10">
                            <v-card-title class="justify-center primary white--text">Reset Password</v-card-title>
                            <v-card-text class="text-center mt-5">
                                <v-text-field label="New Password" type="password" v-model="pswd.password" ref="new_pswd" required v-validate="'required|min:5|max:20'" :error-messages="errors.collect('password')" name="password"></v-text-field>
                                <v-text-field label="Confirm Password" type="password" v-model="pswd.password_confirmation" required v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('password_confirmation')" name="password_confirmation" data-vv-as="password confirmation"></v-text-field>
                            </v-card-text>
                            <v-card-actions class="pb-8 justify-center">
                                <v-btn dark class="justify-center primary" large width="60%" @click="resetPswd" :loading="isSaving">Reset Password</v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                    <template v-if="resetRequestFailed">
                        <v-alert type="error" border="left" class="my-8">{{ resetError }}</v-alert>
                    </template>
                    <template v-if="resetSuccess">
                        <v-alert type="success" border="left" class="my-8">Password has been successfully reset. You can now <router-link to="/login">login</router-link></v-alert>
                    </template>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        data(){
            return{
                token: this.$route.query.token,
                isLoading: false,
                resetRequestFailed: false,
                resetError: '',
                resetPasswordForm: false,
                pswd: {
                    password: '',
                    password_confirmation: ''
                },
                resetSuccess: false,
                isSaving: false
            }
        },
        computed:{
            api(){
                return this.$store.getters.api
            },
        },
        methods: {
            getToken(){
                this.isLoading = true
                axios.post(this.api + '/confirm_pswd_reset_req', {
                    token: this.token
                }).then((res) =>{
                    this.isLoading = false
                    this.resetPasswordForm = true
                }).catch((err) => {
                    this.isLoading = false
                    if(err.response.status === 422){
                        this.resetRequestFailed = true
                        this.resetError = 'Reset request failed. Invalid token!'
                    }else if(err.response.status === 423){
                        this.resetRequestFailed = true
                        this.resetError = 'Reset request failed. Kindly initiate a fresh reset request.'
                    }
                })
            },
            resetPswd(){
                this.$validator.validateAll().then((isValid) => {
                    if (isValid) {
                        this.isSaving = true
                        axios.post(this.api + '/reset_user_password', {
                            pswd: this.pswd,
                            token: this.token
                        }).then((res) => {
                            this.isSaving = false
                            this.resetPasswordForm = false
                            this.resetSuccess = true
                        }).catch((err) => {
                            this.isSaving = false
                        })
                    }
                })
            }
        },
        created(){
            this.getToken()
        }
    }
</script>
