<template>
    <v-container class="">
         <v-row class="">
            <v-col cols="10" md="5" offset-md="6">
               <v-text-field placeholder="Search By email..." v-model="q" outlined dense append-icon="search" @keyup="searchForUsers"></v-text-field>
           </v-col>
        </v-row>
        <v-row justify="start" class="mr-3 mt-n6">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3" :class="$vuetify.breakpoint.smAndDown ? 'mr-1': ''">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Password Reset Request Log <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-1">
                        <template v-if="!searchedReqs">
                            <template v-if="passwordRequests.length > 0">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>User Email</th>
                                            <th>Request IP</th>
                                            <th>Request Date</th>
                                            <th>Reset Rate</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(req, i) in passwordRequests" :key="req.id">
                                            <td>{{ req.user_id }} </td>
                                            <td>{{ req.email }} </td>
                                            <td>{{ req.request_ip }} </td>
                                            <td>{{ req.request_date_time  }} </td>
                                            <td>{{ req.reset_date_time  }} </td>
                                            <td><v-btn class="" small icon color="red darken-2" @click="delConfirm(req, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert type="info" class="mt-5">
                                    There are no password reset requests to display at the moment.
                                </v-alert>
                            </template>
                        </template>
                        <template v-else>
                            <template v-if="searchRez.length > 0">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>User Email</th>
                                            <th>Request IP</th>
                                            <th>Request Date</th>
                                            <th>Reset Rate</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(req, i) in searchRez" :key="req.id">
                                            <td>{{ req.user_id }} </td>
                                            <td>{{ req.email }} </td>
                                            <td>{{ req.request_ip }} </td>
                                            <td>{{ req.request_date_time  }} </td>
                                            <td>{{ req.reset_date_time  }} </td>
                                            <td><v-btn class="" small icon color="red darken-2" @click="delConfirm(req, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert type="info" border="left" class="mt-5">
                                    Your search  for {{ q }} returned no reset requests.
                                </v-alert>
                            </template>
                        </template>
                    </v-card-text>
                    <template v-if="!searchedReqs">
                        <v-card-actions class="my-5" v-if="passwordRequests.length > 0">
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getPasswordRequests(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getPasswordRequests(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getPasswordRequests(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getPasswordRequests(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450" transition="scale-transition">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center primary white--text">Delete this Password Reset Request?</v-card-title>
                <v-card-text class="justify-center mt-5 body_text">
                    If you proceed to delete, the request will be deleted permanently.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isDeleting" @click="delReq" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="reqDeleted" :timeout="4000" top color="green darken-1 white--text">
            Password reset request have been deleted successfully.
            <v-btn text color="white--text" @click="reqDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="reqDelFailed" :timeout="4000" top color="red darken-1 white--text">
            There was a problem deleting the password reset request. Please try again later.
            <v-btn text color="white--text" @click="reqDelFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            passwordRequests: [],
            total: null,
            confirmDelDial: false,
            reqToDelIndex: null,
            reqToDel: null,
            isDeleting: false,
            reqDeleted: false,
            reqDelFailed: false,
            q: '',
            searchedReqs: false,
            searchRez: []
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
        getPasswordRequests(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_pswd_reqs`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.passwordRequests = res.data.data
                this.total = res.data.total
                this.isLoading = false
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        searchForUsers(){
            if(this.q.trim() !== ''){
                this.searchedReqs = true
                axios.post(this.api +`/auth-admin/admin_search_for_pswd_resets`, {
                    q: this.q.trim()
                }, this.adminHeaders).then((res) => {
                    this.searchRez = res.data
                    this.total = res.data.length
                })
            }else{
                this.searchedReqs = false
                this.getPasswordRequests()
            }
        },
        delConfirm(req, i){
            this.confirmDelDial = true
            this.reqToDel = req
            this.reqToDelIndex = i
        },
        delReq(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/del_password_req/${this.reqToDel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.reqDeleted = true
            }).catch(() => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.reqDelFailed = true
            })
        }
    },
    mounted(){
        this.getPasswordRequests()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td, table tbody tr th, table thead tr th{
        white-space: nowrap !important;
    }
</style>
