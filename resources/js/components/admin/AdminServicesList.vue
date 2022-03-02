<template>
    <v-container>
        <v-row>
            <v-col cols="12" md="5"  offset-md="6">
               <admin-search model="Service" searchFor="services"/>
           </v-col>
        </v-row>
        <v-row class="mt-n4">
            <v-col cols="12" md="11">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3" :class="$vuetify.breakpoint.smAndDown ? 'mr-1': ''">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Services <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text>
                        <template v-if="services.length > 0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Title</th>
                                        <th>Position</th>
                                        <th>Ratings</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="service_list">
                                    <tr v-for="(service, i) in services" :key="service.id">
                                        <td @click="showService(service)">{{ service.id }}</td>
                                        <td @click="showService(service)">{{ service.user && service.user.fullname }}</td>
                                        <td @click="showService(service)" class="text-truncate">{{ service.title }}</td>
                                        <td @click="showService(service)">{{ service.position }}</td>
                                        <td @click="showService(service)">{{ service.ratings }}</td>
                                        <td>{{ service.is_approved ? 'Approved' : 'Not-Approved' }}</td>
                                        <td><v-btn small icon color="admin" :to="{name: 'AdminServiceUpdate', params:{id: service.id, slug: service.slug}}"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small icon color="red darken-2" @click="confirmDel(service, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no services in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5 justify-space-between pb-8" v-if="services.length > 0">
                        <div>
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getServices(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </div>
                        <div class="download mr-4">
                            <v-btn dark color="admin" :href="`http://localhost:8000/api/services/export`"><v-icon left>mdi-download</v-icon>Download</v-btn>
                        </div>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Delete this service?</v-card-title>
                <v-card-text class="mt-5 body_text subtitle-1">
                    If you proceed to delete, the service along with it's portfolios and reviews will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn large text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn large dark color="admin" :loading="isUpdating" @click="deleteService" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            services: [],
            total: null,
            pagination: {},
            isLoading: false,
            confirmDelDial: false,
            serviceTodel: null,
            serviceTodelIndex: null,
            serviceDeleteFailed: false,
            isUpdating: false,
            statusToggleDial: false,
            serviceStatusToUpdate: null,
            serviceStatusUpdated: false,
            serviceStatusUpdateFail: false
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
            return this.$store.getters.adminUpdatedService
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
        getServices(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_paginated_services`
            axios.get(pag, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.services = res.data.data
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
        showService(service){
            this.$router.push({name: 'AdminServiceDetail', params:{id: service.id, slug: service.slug}})
        },
        confirmDel(service, i){
            this.serviceTodel = service
            this.serviceTodelIndex = i
            this.confirmDelDial = true
        },
        deleteService(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_del_service/${this.serviceTodel.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isUpdating = false
                this.confirmDelDial = false
                this.services.splice(this.serviceTodelIndex, 1)
                this.$store.commit('adminDeletedService')
            }).catch(() => {
                this.isUpdating = false
                this.confirmDelDial = false
                this.serviceDeleteFailed = true
            })
        },
        openStatusToggleDial(service){
            this.serviceStatusToUpdate = service
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
        resetServiceNotf(){
            let notif = window.localStorage.getItem('servNotif')
            if(notif){
                this.$store.commit('resetServiceNotif')
            }
        }
    },
    mounted(){
        this.getServices()
        this.resetServiceNotf()
    }
}
</script>

<style lang="css" scoped>
    table .service_list tr td{
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
