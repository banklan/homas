<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="12">
                <v-btn rounded color="admin lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4" justify="center" :class="$vuetify.breakpoint.smAndDown ? 'ml-n4 mr-n5': ''">
            <v-col cols="12" md="8">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Create New SuperUser (Admin)</v-card-title>
                    <v-card-text class="body-1 mt-5 px-8">
                        <v-text-field label="First Name" v-model="user.first_name" required placeholder="First Name" v-validate="'required|min:2|max:50'" :error-messages="errors.collect('first_name')" name="first_name" data-vv-as="first name"></v-text-field>
                        <v-text-field label="Last Name" v-model="user.last_name" required placeholder="Surname" v-validate="'required|min:2|max:50'" :error-messages="errors.collect('last_name')" name="last_name" data-vv-as="last name"></v-text-field>
                        <v-row>
                            <v-col cols="12" md="6">
                                <v-text-field label="Email" type="text" v-model="user.email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field label="Phone No" type="text" v-model="user.phone" required v-validate="'max:16|numeric'" :error-messages="errors.collect('phone')" name="phone"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-select label="Role" :items="roles" item-value="value" item-text="role" v-model="user.role" required v-validate="'required'" :error-messages="errors.collect('role')" name="role"></v-select>
                        <div class="sub_title my-4 font-weight-black">Create Password</div>
                        <v-text-field type="password" label="Password" v-model="user.password" required v-validate="'required|min:5|max:30'" ref="password" :error-messages="errors.collect('password')" name="password"></v-text-field>
                        <v-text-field type="password" label="Confirm Password" v-model="user.password_confirmation" required v-validate="'required|confirmed:password'" :error-messages="errors.collect('confirm')" name="confirm" data-vv-as="confirm password"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn large text color="red darken-2" width="40%" @click.prevent="$router.go(-1)">Cancel</v-btn>
                        <v-btn large dark color="admin" width="40%" @click="createUser" :loading="isLoading">Create User</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="createFail" :timeout="8000" top color="red darken-1 white--text">
            New super-user failed to create. Please try again and ensure all fields are validly filled.
            <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            user:{
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                role: '',
                password: '',
                password_confirmation: '',
            },
            roles: [
                {role: 'Admin', value: 'admin'},
                {role: 'Super User', value: 'su'},
            ],
            isLoading: false,
            createFail: false
        }
    },
    computed:{
         authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods: {
        createUser(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isLoading = true
                    axios.post(this.api + `/auth-admin/super_user_create`, {
                        user: this.user
                    }, this.adminHeaders).then((res)=>{
                        this.isLoading = false
                        this.$store.commit('newAdminCreated')
                        this.$router.push({name: 'AdminSuperUsersList'})
                    }).catch(() => {
                        this.isLoading = false
                        this.createFail = true
                    })
                }
            })
        }
    },
}
</script>

<style lang="css" scoped>

</style>
