<template>
    <v-container>
        <v-row justify="center" class="mt-6">
            <v-col cols="12" md="5">
                <v-card light raised elevation="12" min-height="320" width="90%" class="mx-auto">
                    <v-card-title class="primary white--text justify-center title font-weight-bold">Admin Login</v-card-title>
                    <v-card-text class="mt-4">
                        <v-text-field label="Email" type="text" v-model="cred.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                        <v-text-field label="Password" type="password" v-model="cred.password" required v-validate="'required|min:3'" :error-messages="errors.collect('password')" name="password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center px-5">
                        <v-btn color="primary" large block @click.prevent="login" :loading="isLoading">Login</v-btn>
                    </v-card-actions>
                    <v-card-actions class="justify-center mt-2 pb-6 mx-2">
                        Not an Admin? <router-link to="/login">&nbsp; Login as a user </router-link>
                    </v-card-actions>
                    <div v-if="authError" class="pb-5">
                        <div class="error white--text pa-4 mx-3">
                            {{ errorMsg }}
                        </div>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            cred: {
                email: '',
                password: ''
            },
            isLoading: false,
            authError: false,
            errorMsg: ''
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        redirectOnLogin(){
            return this.$store.getters.redirectOnLogin
        },
        authAdmin(){
            return this.$store.getters.authUser
        },
    },
    methods: {
        login(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + '/auth-admin/login', this.cred)
                        .then((res) => {
                            this.isLoading = false
                            this.$store.commit('adminLoginSuccess', res.data)

                            // this.getModelsStats()
                            this.$router.push({name: 'AdminDashboard'})
                        }).catch((err) => {
                            this.isLoading = false
                            this.authError = true
                            if(err.response.status === 441){
                                this.errorMsg = 'Error! Invalid login credentials.'
                            }else if(err.response.status === 501){
                                this.errorMsg = 'You cannot login into your account now. Kindly contact the admin.'
                            }else{
                                this.errorMsg = 'Error! Invalid login credentials.'
                            }
                        })
                }
            })
        },
        getModelsStats(){
            this.$store.dispatch('getAdminStats')
        }
    },
    mounted() {
        // this.$store.dispatch('getAdminStats')
    },
}
</script>

<style lang="scss" scoped>
    a{
        text-decoration: none;
    }
</style>
