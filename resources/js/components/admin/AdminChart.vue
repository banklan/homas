<template>
    <v-container fluid>
        <v-row justify="space-between">
            <v-col cols="12" md="6">
                <v-card elevation="8" light outlined max-height="400">
                    <line-chart v-if="loaded" :chart-data="services" :chart-labels="labels" />
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card elevation="8" light outlined max-height="400">
                    <bar-chart v-if="usersLoaded" :chart-data="users" :chart-labels="usersLabels" />
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card elevation="8" light outlined max-height="400">
                    <doughnut-chart v-if="usersLoaded" :chart-data="users" :chart-labels="usersLabels" />
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import LineChart from '../charts/ChartSample'
import BarChart from '../charts/BarChart'
import DoughnutChart from '../charts/DoughnutChart'
export default {
    components: { LineChart, BarChart, DoughnutChart },
    data() {
        return {
            loaded: false,
            chartdata: null,
            services: [],
            labels: [],
            users: [],
            usersLabels: [],
            usersLoaded: false
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
        getServicesChart(){
            this.loaded = false
            axios.get(this.api + '/auth-admin/get_last_weeks_services', this.adminHeaders).then((res) => {
                this.services = res.data.map(item => item.services_count)
                this.labels = res.data.map(item => item.week_starting)
                this.loaded = true
            }).catch((e) =>{
                // console.log(e)
            })
        },
        getUsersChart(){
            this.usersLoaded = false
            axios.get(this.api + '/auth-admin/get_last_weeks_users', this.adminHeaders).then((res) => {
                this.users = res.data.map(item => item.users_count)
                this.usersLabels = res.data.map(item => item.week_starting)
                this.usersLoaded = true
            }).catch((e) =>{
                // console.log(e)
            })
        }
    },
    mounted(){
        this.getServicesChart()
        this.getUsersChart()
    }
}
</script>
