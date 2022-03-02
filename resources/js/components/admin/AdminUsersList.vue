<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="5">
               <admin-search model="User" searchFor="users"/>
           </v-col>
            <v-col cols="4" md="3" offset-md="4">
                <v-btn dark color="admin" :to="{name: 'AdminCreateUser'}"><v-icon left>add</v-icon>New User</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4" :class="$vuetify.breakpoint.smAndDown ? 'mr-1': ''">
            <v-col cols="12" md="11">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll mr-2">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Users</v-card-title>
                    <v-card-text>
                        <template v-if="users.length > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fullname</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>(En/Dis)able</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="expert_list">
                                    <tr v-for="(user, i) in users" :key="user.id">
                                        <td @click="showUser(user)">{{ user.id }}</td>
                                        <td @click="showUser(user)">{{ user.fullname }}</td>
                                        <td @click="showUser(user)">{{ user.email }}</td>
                                        <td>{{ user.status ? 'Active' : 'Disabled' }}</td>
                                        <td><v-btn small dark :color="user.status ? 'primary' : 'green darken-3' " @click="openStatusToggleDial(user)">{{ user.status ? 'Disable' : 'Enable' }}</v-btn></td>
                                        <td><v-btn small icon color="admin" :to="{name: 'AdminUserUpdate', params:{id: user.id}}"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(user, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no users in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5 pb-8 justify-space-between" v-if="users.length > 0">
                        <div>
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getUsers(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getUsers(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getUsers(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getUsers(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </div>
                        <div class="download mr-4">
                            <v-btn dark color="admin" :href="`http://localhost:8000/api/users/export`"><v-icon left>mdi-download</v-icon>Download</v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Delete this user?</v-card-title>
                <v-card-text class="mt-5 subtitle-1">
                    If you proceed to delete, the user will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn large text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn large dark color="admin" :loading="isUpdating" @click="deleteUser" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="statusToggleDial" max-width="480">
            <v-card min-height="150" v-if="userStatusToUpdate">
                <v-card-title class="subtitle-1 admin white--text justify-center">{{ userStatusToUpdate.status ? 'Disable' : ' Enable'}} this user?</v-card-title>
                <v-card-actions class="mt-5 justify-center">
                    <v-btn text color="red darken--2" @click="statusToggleDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isUpdating" @click="updateStatus" width="40%">Yes, {{ userStatusToUpdate.status ? 'Disable' : 'Enable'}}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="userStatusUpdated" :timeout="4000" top dark color="green darken-2">
            User status has been updated successfully.
            <v-btn text color="white--text" @click="userStatusUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="userStatusUpdateFail" :timeout="6000" top dark color="red darken-2">
            There was an error while trying to update the user. Please try again.
            <v-btn text color="white--text" @click="userStatusUpdateFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="userDeleted" :timeout="4000" top dark color="green darken-2">
            User has been deleted successfully.
            <v-btn text color="white--text" @click="userDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="userDeleteFailed" :timeout="4000" top dark color="red darken-2">
            An error occur while trying to delete the user. Please try again.
            <v-btn text color="white--text" @click="userDeleteFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedUser" :timeout="4000" top dark color="green darken-2">
            User was updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedUser = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="newUserCreated" :timeout="4000" top dark color="green darken-2">
            A new user has been created successfully.
            <v-btn text color="white--text" @click="newUserCreated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            users: [],
            pagination: {},
            isLoading: false,
            confirmDelDial: false,
            userTodel: null,
            userTodelIndex: null,
            userDeleteFailed: false,
            isUpdating: false,
            statusToggleDial: false,
            userStatusToUpdate: null,
            userStatusUpdated: false,
            userStatusUpdateFail: false
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
        newUserCreated(){
            return this.$store.getters.newUserCreated
        },
        adminUpdatedUser(){
            return this.$store.getters.adminUpdatedExpert
        },
        userDeleted(){
            return this.$store.getters.adminDeletedUser
        },
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getUsers(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_users`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.users = res.data.data
                this.total = res.data.total
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            }).catch(() => {
                this.isLoading = false
            })
        },
        showUser(user){
            this.$router.push({name: 'AdminUserDetail', params:{id: user.id}})
        },
        confirmDel(user, i){
            this.userTodel = user
            this.userTodelIndex = i
            this.confirmDelDial = true
        },
        deleteUser(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_del_user/${this.userTodel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.confirmDelDial = false
                this.users.splice(this.userTodelIndex, 1)
                this.$store.commit('adminDeletedUser')
            }).catch(() => {
                this.isUpdating = false
                this.confirmDelDial = false
                this.userDeleteFailed = true
            })
        },
        openStatusToggleDial(user){
            this.userStatusToUpdate = user
            this.statusToggleDial = true
        },
        updateStatus(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/update_user_status/${this.userStatusToUpdate.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.statusToggleDial = false
                let user = this.users.find((item) => item.id == this.userStatusToUpdate.id)
                user.status = res.data
                this.userStatusUpdated = true
            }).catch(() => {
                this.isUpdating = false
                this.statusToggleDial = false
                this.userStatusUpdateFail = true
            })
        },
        resetNewUserNotf(){
            let notif = window.localStorage.getItem('userNotif')
            if(notif){
                this.$store.commit('resetUserNotif')
            }
        }
    },
    created(){
        this.getUsers()
        this.resetNewUserNotf()
    }
}
</script>

<style lang="css" scoped>
    table .expert_list tr td{
        cursor: pointer;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td{
        white-space: nowrap !important;
    }
    .v-btn{
        text-decoration: none;
    }
</style>
