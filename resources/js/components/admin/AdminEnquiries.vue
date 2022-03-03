<template>
    <v-container class="">
        <v-row>
            <v-col cols="11" md="6" offset-md="5">
                <v-text-field placeholder="Search for Enquiries..." v-model="q" outlined dense append-icon="search" @keyup="searchForEnqs"></v-text-field>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-col cols="12" md="11">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="200" class="scroll" :class="$vuetify.breakpoint.smAndDown ? 'mr-1': ''">
                    <v-card-title class="admin white--text justify-center subtitle-1">Enquiries</v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="!searchedEnqs">
                            <template v-if="enquiries.length > 0">
                                <div class="my-3 px-2 subtitle-1" v-if="enquiries.length > 0">Enquiries ({{ total }}) &nbsp; &nbsp; Unread({{ unreadCount }})</div>
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Email</th>
                                            <th>Sender</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(enquiry, i) in enquiries" :key="enquiry.id">
                                            <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.sent }} </td>
                                            <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.email }} </td>
                                            <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.fullname }} </td>
                                            <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.subject | truncate(50) }} </td>
                                            <td><v-btn small icon color="red darken-2" @click="delConfirm(enquiry, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert class="mt-5" type="info" border="left">
                                    There are no enquiries to show at the moment.
                                </v-alert>
                            </template>
                        </template>
                        <template v-else>
                            <div class="my-3 px-2 subtitle-1" v-if="searchResult.length > 0">Search returned <strong>{{ total }} </strong>enquiries.</div>
                            <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                            <template v-else>
                                <template v-if="searchResult.length > 0">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Email</th>
                                                <th>Sender</th>
                                                <th>Subject</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(enquiry, i) in searchResult" :key="enquiry.id">
                                                <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.sent }} </td>
                                                <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.email }} </td>
                                                <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.fullname }} </td>
                                                <td @click="showEnquiry(enquiry)" :class="!enquiry.is_read ? 'font-weight-bold' : ''">{{ enquiry.subject | truncate(50) }} </td>
                                                <td><v-btn small icon color="red darken-2" @click="delConfirm(enquiry, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                                <template v-else>
                                    <v-alert class="mt-5" type="info" border="left">
                                        Your search for <strong>{{ q }}</strong> returned to enquiries.
                                    </v-alert>
                                </template>
                            </template>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="!searchedEnqs">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getEnquiries(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                        </span>
                        <span class="pl-8">
                            Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                        </span>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Are you sure you want to delete this enquiry?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, the enquiry will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken-2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" width="40%" @click="delEnquiry">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminDelEnquiry" :timeout="4000" top color="green darken-1 white--text">
            An enquiry has been deleted successfully.
            <v-btn text color="white--text" @click="adminDelEnquiry = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            enquiries: [],
            pagination: {},
            q: '',
            confirmDelDial: false,
            enquiryToDel: null,
            enquiryToDelIndex: null,
            isDeleting: false,
            isLoading: false,
            total: 0,
            unreadCount: 0,
            searchedEnqs: false,
            searchResult: []
        }
    },
    computed: {
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
        adminDelEnquiry(){
            return this.$store.getters.adminDeleteEnquiry
        },
    },
    methods: {
        getEnquiries(pag){
            pag = pag || `${this.api}/auth-admin/all_enquiries`
            this.isLoading = true
            axios.get(pag, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.enquiries = res.data.data
                this.total = res.data.total
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
        showEnquiry(enquiry){
            this.$router.push({name: 'AdminEnquiryShow', params:{id: enquiry.id}})
        },
        delConfirm(enq, i){
            this.confirmDelDial = true
            this.enquiryToDel = enq
            this.enquiryToDelIndex = i
        },
        delEnquiry(){
            this.isDeleting = true
            let enq = this.enquiryToDel
            let ind = this.enquiryToDelIndex
            axios.post(this.api + `/auth-admin/admin_del_enquiry/${enq.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.enquiries.splice(ind, 1)
                this.$store.commit('adminDeleteEnquiry')
            })
        },
        getUnreadCount(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_unread_enquiry_count`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.unreadCount = res.data
            })
        },
        searchForEnqs(){
            if(this.q.trim().length > 0){
                this.searchedEnqs = true
                this.isLoading = true
                axios.post(this.api +`/auth-admin/admin_search_for_enqs`, {
                    q: this.q.trim()
                }, this.adminHeaders).then((res) => {
                    this.isLoading = false
                    this.searchResult = res.data
                    this.total = res.data.length
                })
            }else{
                this.searchedEnqs = false
                this.getEnquiries()
            }
        },
        // clearNewEnquiryNotif(){
        //     axios.post(this.api + '/auth-admin/clear_new_enquiries_notification', {}, this.adminHeaders)
        //     .then((res) =>{
        //         this.$store.commit('updateAdminNotifs', 'Enquiry')
        //     })
        // },
        resetNewEnqNotf(){
            let enq = window.localStorage.getItem('enquiryNotif')
            if(enq){
                this.$store.commit('resetEnqNotif')
            }
        }
    },
    created(){
        this.getEnquiries()
        this.getUnreadCount()
        this.resetNewEnqNotf()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
    .v-btn, a{
        text-transform: none !important;
    }
    table tbody tr td{
        white-space: nowrap !important;
    }
</style>
