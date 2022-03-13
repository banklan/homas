<template>
    <v-container fluid>
        <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
        <v-row v-else justify="center" class="mt-5">
            <v-col cols="12" md="4">
                <v-card dark elevation="6" raised outlined width="100%" min-height="200" class="mx-auto scroll" color="#e9309f">
                    <v-card-title class="subtitle-1 justify-center">General Statistics</v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr>
                                    <th>No of Admins</th>
                                    <td>{{ admins }}</td>
                                </tr>
                                <tr>
                                    <th>Logged-in Admins</th>
                                    <td>{{ loggedInAdmin }}</td>
                                </tr>
                                <tr>
                                    <th>No of Users</th>
                                    <td>{{ users }}</td>
                                </tr>
                                <tr>
                                    <th>Disabled Users</th>
                                    <td>{{ disabledUsers }}</td>
                                </tr>
                                <tr>
                                    <th>Logged-in Users</th>
                                    <td>{{ loggedInUsers }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card dark elevation="6" raised outlined width="100%" min-height="200" class="mx-auto scroll" color="#304fe9">
                    <v-card-title class="subtitle-1 justify-center">Services</v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr>
                                    <th>No of Services</th>
                                    <td>{{ services }}</td>
                                </tr>
                                <tr>
                                    <th>No of Premium Services</th>
                                    <td>{{ premServices }}</td>
                                </tr>
                                <tr>
                                    <th>Approved Services</th>
                                    <td>{{ apprvdServices }}</td>
                                </tr>
                                <tr>
                                    <th>Not Approved Services</th>
                                    <td>{{ notApprvdServices }}</td>
                                </tr>
                                <tr>
                                    <th>Featured Services</th>
                                    <td>{{ featServices }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="4">
                <v-card dark elevation="6" raised outlined width="100%" min-height="200" class="mx-auto scroll" color="#ff8100">
                    <v-card-title class="subtitle-1 justify-center">Portfolio & Reviews</v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr>
                                    <th>No of Portfolios</th>
                                    <td>{{ portfolios }}</td>
                                </tr>
                                <tr>
                                    <th>Approved Portfolios</th>
                                    <td>{{ apprvdPfs }}</td>
                                </tr>
                                <tr>
                                    <th>No of Reviews</th>
                                    <td>{{ reviews }}</td>
                                </tr>
                                <tr>
                                    <th>Not Approved Reviews</th>
                                    <td>{{ apprvdReviews }}</td>
                                </tr>
                                <tr>
                                    <th>No of Testimonials</th>
                                    <th>{{ testimonials }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="6">
                <v-card dark elevation="6" raised outlined width="100%" min-height="200" class="mx-auto scroll" color="#720e93">
                    <v-card-title class="subtitle-1 justify-center">Popular Services</v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped" v-if="popularServs.length > 0">
                            <thead></thead>
                            <tbody class="white--text">
                                <tr v-for="serv in popularServs" :key="serv.id">
                                    <td class="text-truncate">{{ serv.title }}</td>
                                    <td><v-rating dense readonly small v-model="serv.ratings"></v-rating></td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="mt-3 white--text">There are no services to be displayed at the moment.</div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card dark elevation="6" raised outlined width="100%" min-height="200" class="mx-auto scroll" color="#065408">
                    <v-card-title class="subtitle-1 justify-center">Latest Services</v-card-title>
                    <v-card-text>
                        <table class="table table-hover table-striped white--text" v-if="latestServs.length > 0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date Created</th>
                                </tr>
                            </thead>
                            <tbody class="white--text">
                                <tr v-for="serv in latestServs" :key="serv.id">
                                    <td class="text-truncate">{{ serv.title }}</td>
                                    <td>{{ serv.created_at | moment('DD/MM/YYYY') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="mt-3 white--text">There are no services to be displayed at the moment.</div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-row justify="center" class="mt-5">
            <v-col cols="12" md="6">
                <v-card light elevation="6" raised outlined width="100%" min-height="100" class="mx-auto scroll">
                    <v-card-title class="subtitle-1 justify-center">Services Created Last Weeks</v-card-title>
                    <v-card-text v-if="chartServicesCount.length > 0">
                        <service-line-charts v-if="servChartLoaded" :chart-data="chartServicesCount" :chart-labels="chartServicesLabels"></service-line-charts>
                        <template v-if="ServiceChartError">{{ ServiceChartError }}</template>
                    </v-card-text>
                    <v-card-text v-else>
                        There are no services data to be displayed at the moment.
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card light elevation="6" raised outlined width="100%" min-height="100" class="mx-auto scroll">
                    <v-card-title class="subtitle-1 justify-center admin--text">Users Created Last Weeks</v-card-title>
                    <v-card-text v-if="chartUsersCount.length > 0">
                        <service-line-charts v-if="usersChartLoaded" :chart-data="chartUsersCount" :chart-labels="chartUsersLabels" />
                        <template v-if="UsersChartError">{{ UsersChartError }}</template>
                    </v-card-text>
                    <v-card-text v-else>
                        There are no users data to be displayed at the moment.
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ServiceLineCharts from '../charts/ServiceLineChart'

export default {
    components: {
        'service-line-charts': ServiceLineCharts,
    },
    props: ['toggleMini', 'smAndDown'],
    data() {
        return {
            isLoading: false,
            admins: null,
            loggedInAdmin: null,
            users: null,
            disabledUsers: null,
            loggedInUsers: null,
            services: null,
            premServices: null,
            apprvdServices: null,
            notApprvdServices: null,
            featServices: null,
            reviews: null,
            portfolios:  null,
            apprvdPfs: null,
            reviews: null,
            apprvdReviews: null,
            servChartLoaded: false,
            chartServicesCount: [],
            chartServicesLabels: [],
            ServiceChartError: null,
            usersChartLoaded: false,
            chartUsersCount: [],
            chartUsersLabels: [],
            UsersChartError: null,
            popularServs: [],
            latestServs: [],
            testimonials: null
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
        getAdminStats(){
            axios.get(this.api + '/auth-admin/all_superusers', this.adminHeaders).then((res) => {
                this.admins = res.data.length
                let auth = res.data.filter(item => item.is_loggedin == true)
                this.loggedInAdmin = auth.length
            })
        },
        getUsersStats(){
            axios.get(this.api + '/auth-admin/get_all_users', this.adminHeaders).then((res) => {
                this.users = res.data.length
                let auth = res.data.filter(item =>item.is_loggedin)
                this.loggedInUsers = auth.length
                let disabled = res.data.filter(item => item.status == false)
                this.disabledUsers = disabled.length
            })
        },
        getServicesStats(){
            axios.get(this.api + '/auth-admin/get_all_services', this.adminHeaders).then((res) => {
                this.services = res.data.length
                let prem = res.data.filter(item =>item.is_premium)
                this.premServices = prem.length
                let apprvd = res.data.filter(item =>item.is_approved)
                this.apprvdServices = apprvd.length
                let notApprvd = res.data.filter(item =>item.is_approved == false)
                this.notApprvdServices = notApprvd.length
                let feat = res.data.filter(item =>item.is_featured)
                this.featServices = feat.length
            })
        },
        getPortfolioStats(){
            axios.get(this.api + '/auth-admin/get_all_portfolios', this.adminHeaders).then((res) => {
                this.portfolios = res.data.length
                let apprvd = res.data.filter(item =>item.is_approved)
                this.apprvdPfs = apprvd.length
            })
        },
        getReviewsStats(){
            axios.get(this.api + '/auth-admin/get_all_reviews', this.adminHeaders).then((res) => {
                this.reviews = res.data.length
                let apprvd = res.data.filter(item =>item.is_approved)
                this.apprvdReviews = apprvd.length
            })
        },
        getServicesChartData(){
            this.servChartLoaded = false
            axios.get(this.api + '/auth-admin/get_last_weeks_services', this.adminHeaders).then((res) => {
                this.chartServicesCount = res.data.map(item => item.services_count)
                this.chartServicesLabels = res.data.map(item => item.week_starting)
                this.servChartLoaded = true
                console.log('services chart ', res.data)
            }).catch((e) =>{
                this.ServiceChartError = e
            })
        },
        getUsersChartData(){
            this.usersChartLoaded = false
            axios.get(this.api + '/auth-admin/get_last_weeks_users', this.adminHeaders).then((res) => {
                this.chartUsersCount = res.data.map(item => item.users_count)
                this.chartUsersLabels = res.data.map(item => item.week_starting)
                this.usersChartLoaded = true
                console.log('users chart ', res.data)
            }).catch((e) =>{
                this.UsersChartError = e
            })
        },
        getPopularServices(){
            axios.get(this.api + '/auth-admin/get_popular_services', this.adminHeaders).then((res) => {
                this.popularServs = res.data
            })
        },
        getLatestServices(){
            axios.get(this.api + '/auth-admin/get_latest_services', this.adminHeaders).then((res) => {
                this.latestServs = res.data
            })
        },
        getTestimonials(){
            axios.get(this.api + '/auth-admin/get_testimonial_count', this.adminHeaders).then((res) => {
                this.testimonials = res.data
            })
        }
    },
    mounted() {
        this.getAdminStats()
        this.getUsersStats()
        this.getServicesStats()
        this.getPortfolioStats()
        this.getReviewsStats()
        this.getPopularServices()
        this.getLatestServices()
        this.getTestimonials()
        this.getServicesChartData()
        this.getUsersChartData()
    },
}
</script>
