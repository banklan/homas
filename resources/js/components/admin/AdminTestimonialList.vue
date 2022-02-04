<template>
    <v-container class="">
        <v-row>
            <v-col cols="11" md="6" offset-md="5">
                <v-text-field placeholder="Search for Testimonials..." v-model="q" outlined dense append-icon="search" @keyup="searchForTests"></v-text-field>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-col cols="12" md="11">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised outlined elevation="4" min-height="200" class="scroll">
                    <v-card-title class="justify-center subtitle-1 admin white--text">Testimonials <v-chip class="admin lighten-1 ml-1" dark>{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="!searchedTests">
                            <template v-if="testimonials.length > 0">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Author</th>
                                            <th>Title</th>
                                            <th>Feature status</th>
                                            <th>Confirmed Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(test, i) in testimonials" :key="test.id">
                                            <td @click="showTestimonial(test)">{{ test.created_at | moment('DD/MM/YYYY') }} </td>
                                            <td @click="showTestimonial(test)">{{ test.user && test.user.fullname }} </td>
                                            <td @click="showTestimonial(test)" class="text-truncate">{{ test.title }} </td>
                                            <td @click="showTestimonial(test)">{{ test.is_featured }} </td>
                                            <td @click="showTestimonial(test)">{{ test.is_confirmed }} </td>
                                            <td><v-btn small icon color="blue darken-1" :to="{name: 'AdminTestimonialUpdate', params:{id: test.id}}"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class="ml-1" @click="delConfirm(test, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert class="mt-5" type="info" border="left">
                                    There are no testimonials to show at the moment.
                                </v-alert>
                            </template>
                        </template>
                        <template v-else>
                            <div class="my-3 px-2 subtitle-1" v-if="searchResult.length > 0">Search returned <strong>{{ total | pluralize('testimonial') }}</strong>.</div>
                            <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                            <template v-else>
                                <template v-if="searchResult.length > 0">
                                    <table class="table table-hover table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Author</th>
                                                <th>Title</th>
                                                <th>Feature status</th>
                                                <th>Confirmed Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(test, i) in searchResult" :key="test.id">
                                                <td @click="showTestimonial(test)">{{ test.created_at | moment('DD/MM/YYYY') }} </td>
                                                <td @click="showTestimonial(test)">{{ test.user && test.user.fullname }} </td>
                                                <td @click="showTestimonial(test)" class="text-truncate">{{ test.title }} </td>
                                                <td @click="showTestimonial(test)">{{ test.is_featured }} </td>
                                                <td @click="showTestimonial(test)">{{ test.is_confirmed }} </td>
                                                <td><v-btn small icon color="blue darken-1" :to="{name: 'AdminTestimonialUpdate', params: {id: test.id}}"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class="ml-1" @click="delConfirm(test, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                                <template v-else>
                                    <v-alert class="mt-5" type="info" border="left">
                                        Your search for <strong>{{ q }}</strong> returned to testimonials.
                                    </v-alert>
                                </template>
                            </template>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="!searchedTests">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getTestimonials(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTestimonials(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTestimonials(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getTestimonials(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
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
                <v-card-title class="subtitle-1 admin white--text justify-center">Are you sure you want to delete this testimonial?</v-card-title>
                <v-card-text class="justify-center mt-5 subtitle-1 black--text lighten-2">
                    If you proceed to delete, the testimonial will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken-2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" width="40%" @click="delTestimonial">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminDelTestimonial" :timeout="4000" top color="green darken-1 white--text">
            A testimonial has been deleted.
            <v-btn text color="white--text" @click="adminDelTestimonial = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            testimonials: [],
            pagination: {},
            q: '',
            confirmDelDial: false,
            testimonialToDel: null,
            testimonialToDelIndex: null,
            isDeleting: false,
            isLoading: false,
            total: 0,
            searchedTests: false,
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
        adminDelTestimonial(){
            return this.$store.getters.adminDelTestimonial
        },
    },
    methods: {
        getTestimonials(pag){
            pag = pag || `${this.api}/auth-admin/all_testimonials`
            this.isLoading = true
            axios.get(pag, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.testimonials = res.data.data
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
        showTestimonial(testimonial){
            this.$router.push({name: 'AdminTestimonialShow', params:{id: testimonial.id}})
        },
        delConfirm(test, i){
            this.confirmDelDial = true
            this.testimonialToDel = test
            this.testimonialToDelIndex = i
        },
        delTestimonial(){
            this.isDeleting = true
            let test = this.testimonialToDel
            let ind = this.testimonialToDelIndex
            axios.post(this.api + `/auth-admin/admin_del_testimonial/${test.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.testimonials.splice(ind, 1)
                this.$store.commit('adminDeleteTestimonial')
            })
        },
        searchForTests(){
            if(this.q.trim().length > 0){
                this.searchedTests = true
                this.isLoading = true
                axios.post(this.api +`/auth-admin/admin_search_for_tests`, {
                    q: this.q.trim()
                }, this.adminHeaders).then((res) => {
                    this.isLoading = false
                    this.searchResult = res.data
                    this.total = res.data.length
                })
            }else{
                this.searchedTests = false
                this.getTestimonials()
            }
        },
    },
    created(){
        this.getTestimonials()
    }
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
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
