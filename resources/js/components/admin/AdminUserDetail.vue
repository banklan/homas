<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin lighten--2" dark elevation="4" left :to="{name: 'AdminUsersList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5" :class="$vuetify.breakpoint.smAndDown ? 'ml-n3 mr-n5': ''">
            <v-col cols="12" md="5">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="6" min-height="400" class="">
                    <template v-if="user">
                        <v-img v-if="user.picture" :src="`/images/profiles/users/${user.picture}`" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                        <v-img v-else src="/images/shared/user6.jpg" aspect-ratio="1" height="300" transition="scale-transition"></v-img>
                        <v-card-text>
                            <div class="subtitle-1 my-3 text-center">User Details</div>
                            <v-simple-table light>
                                <template v-slot:default>
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="35%">User ID:</th>
                                            <td>{{ user.id }}</td>
                                        </tr>
                                        <tr>
                                            <th>First Name:</th>
                                            <td>{{ user.first_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Name:</th>
                                            <td>{{ user.last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ user.email }}</td>
                                        </tr>
                                        <tr>
                                            <th>Phone No:</th>
                                            <td>{{ user.phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>{{ user.status == 0 ? 'Disabled' : 'Enabled' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Joined:</th>
                                            <td>{{ user.created_at | moment('DD/MM/YY') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Updated:</th>
                                            <td>{{ user.updated_at | moment('DD/MM/YY, H:ma') }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-card-text>
                        <v-card-actions class="justify-space-around pb-8">
                            <v-btn dark :color="user.status ? 'primary' : 'green darken-3'" @click="statusDialog = true">{{ user.status == 1 ? 'Disable' : 'Enable' }}</v-btn>
                            <v-btn dark small icon color="admin lighten-3" :to="{name: 'AdminUserUpdate', params: {id: user.id}}"><v-icon>edit</v-icon></v-btn>
                            <v-btn dark small icon color="red darken-2" @click="confirmDelDialog = true"><v-icon>delete</v-icon></v-btn>
                        </v-card-actions>
                    </template>
                    <template v-else>
                        <v-alert type="info" border="left" class="mt-5">
                            The user you are trying to view is invalid.
                        </v-alert>
                    </template>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card light raised outlined elevation="4" min-height="150">
                    <v-card-title class="admin white--text justify-center subtitle-1">Reset Password</v-card-title>
                    <v-card-text class="mt-4">
                        <v-btn text dark color="admin" @click="resetPassword = !resetPassword">Reset Password</v-btn>
                        <template v-if="resetPassword">
                            <v-text-field label="New Password" type="password" v-model="password.password" required ref="new_pswd" v-validate="'required'" :error-messages="errors.collect('new')" name="new" data-vv-as="new password"></v-text-field>
                            <v-text-field label="Confirm Password" type="password" v-model="password.password_confirmation" v-validate="'required|confirmed:new_pswd'" :error-messages="errors.collect('confirm')" name="confirm" data-vv-as="confirm password"></v-text-field>
                        </template>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8" v-if="resetPassword">
                        <v-btn text dark color="red darken-2" @click="resetPassword = false" width="40%">Cancel</v-btn>
                        <v-btn dark class="admin darken-2" :loading="isUpdating" @click="updatePswd" width="40%">Update</v-btn>
                    </v-card-actions>
                </v-card>
                <v-card light raised outlined elevation="4" min-height="150" class="mt-5">
                    <v-card-title class="admin white--text justify-center subtitle-1">{{ user && user.fullname }}'s Service</v-card-title>
                    <v-card-text class="">
                        <table class="table table-condensed table-hover">
                            <thead></thead>
                            <tbody>
                                <tr v-if="user && user.service">
                                    <th width="100%"><v-btn class="text-capitalize" text color="admin" :to="{name: 'AdminServiceDetail', params:{id: user.service.id, slug: user.service.slug}}">{{ user.service.title }}</v-btn></th>
                                </tr>
                                <tr v-else>
                                    <td>No service for this user yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDialog" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Delete this user?</v-card-title>
                <v-card-text class="mt-4 body_text">
                    If you proceed to delete, the user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDialog = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isLoading" @click="deleteUser" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="statusDialog" max-width="450">
            <v-card min-height="150" v-if="user">
                <v-card-title class="subtitle-1 admin white--text justify-center">{{ user.status ? 'Disable' : 'Enable'}} this user?</v-card-title>
                <v-card-actions class="mt-5 justify-center">
                    <v-btn text color="red darken--2" @click="statusDialog = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isUpdating" @click="updateStatus" width="40%">Yes, {{ user.status ? 'Disable' : 'Enable'}}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="userStatusUpdated" :timeout="4000" top color="green darken-1 white--text">
            User was {{ user && user.status == 0 ? 'disabled' : 'enabled' }} successfully.
            <v-btn text color="white--text" @click="userStatusUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetSuccessfull" :timeout="4000" top color="green darken-1 white--text">
            Password was reset successfully.
            <v-btn text color="white--text" @click="passwordResetSuccessfull = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="passwordResetFailed" :timeout="6000" top color="red darken-1 white--text">
            Password reset failed. Please ensure that the new password is between 5 & 20 characters.
            <v-btn text color="white--text" @click="passwordResetFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedUser" :timeout="6000" top color="green darken-1 white--text">
            User detail was updated!
            <v-btn text color="white--text" @click="adminUpdatedUser = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data() {
        return {
            user: null,
            isLoading: false,
            id: this.$route.params.id,
            confirmDelDialog: false,
            statusDialog: false,
            userStatusUpdated: false,
            resetPassword: false,
            password: {
                password: '',
                password_confirmation: ''
            },
            isUpdating: false,
            passwordResetSuccessfull: false,
            passwordResetFailed: false,
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminUpdatedUser(){
            return this.$store.getters.adminUpdatedUser
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
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getUser(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_user/${this.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.user = res.data
            })
        },
        deleteUser(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/admin_del_user/${this.id}`, {}, this.adminHeaders).then((res) => {
                this.confirmDelDialog = false
                this.$store.commit('adminDeletedUser')
                this.$router.push({name: 'AdminUsersList'})
            })
        },
        updateStatus(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/update_user_status/${this.user.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isUpdating = false
                this.statusDialog = false
                this.userStatusUpdated = true
                this.user.status = res.data
            })
        },
        updatePswd(){
            this.$validator.validateAll('pswd').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_user_password/${this.id}`, {
                        password: this.password
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.passwordResetSuccessfull = true
                        this.resetPassword = false
                        this.password = {}
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                    }).catch(()=>{
                        this.isUpdating = false
                        this.passwordResetFailed = true
                    })
                }
            })
        },
    },
    created() {
        this.getUser()
    },
}
</script>

<style lang="css" scoped>
    a, .v-btn{
        text-decoration: none !important;
    }

    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table.sub_table tbody tr{
        cursor: pointer;
    }
    .v-card .v-card__text tbody tr td{
        white-space: nowrap !important;
    }
</style>
