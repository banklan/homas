<template>
    <v-container>
        <v-row class="mb-n3 mt-1" justify="space-around">
            <v-col cols="3" md="3">
                <v-btn dark small rounded color="admin" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9" md="6">
                <admin-search model="Portfolio" searchFor="portfolios"/>
            </v-col>
        </v-row>
        <v-row class="mt-5 mr-5">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="portfolios.length > 0">
                        <v-card-title class="subtitle-1 admin white--text justify-center"> Result for {{ $route.query.q }} <v-chip color="primary lighten-2" class="ml-1" v-if="portfolios.length > 0">{{ portfolios.length }}</v-chip></v-card-title>
                        <v-card-text>
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Service Id</th>
                                        <th>Price(&#8358;) </th>
                                        <th>Status</th>
                                        <th>Published</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="(portfolio, i) in portfolios" :key="portfolio.id">
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.id }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.title | truncate(50) }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.service_id }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.cost / 100 | price  }} </td>
                                        <td>{{ portfolio.is_approved ? 'Approved' : 'Not Aprvd' }} </td>
                                        <td>{{ portfolio.created_at | moment('DD/MM/YYYY') }}</td>
                                        <td><v-btn class="ml-3" small icon color="red darken-2" @click="delConfirm(portfolio, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search for {{ $route.query.q }} returned no portfolios.
                            </v-alert>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this portfolio?</v-card-title>
                <v-card-text class="justify-center mt-5 body_text">
                    If you proceed to delete, the portfolio will be deleted irrecoverably.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="delPortfolio" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            portfolios: [],
            confirmDelDial: false,
            portfolioToDel: null,
            portfolioToDelIndex: null,
            isDeleting: false
        }
    },
     watch: {
        '$route.query.q':{
            handler(newVal){
                this.portfolios = newVal
                this.getResult()
            },
            immediate: true
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
        getResult(){
            this.isLoading = true
            axios.post(this.api + '/auth-admin/admin_search_for_portfolios', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.portfolios = res.data
            })
        },
        showPortfolio(portfolio){
            this.$router.push({name: 'AdminPortfolioDetail', params:{id: portfolio.id, slug: portfolio.slug}})
        },
        delConfirm(portfolio, i){
            this.confirmDelDial = true
            this.portfolioToDel = portfolio
            this.portfolioToDelIndex = i
        },
        delPortfolio(){
            this.isDeleting = true
            let portfolio = this.portfolioToDel
            let ind = this.portfolioToDelIndex
            axios.post(this.api + `/auth-admin/admin_delete_portfolio/${portfolio.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.portfolios.splice(ind, 1)
                this.total -=
                this.$store.commit('adminDeletePortfolio')
            })
        },
    },
    mounted() {
        this.getResult()
    },
}
</script>

<style lang="css" scoped>
    table tbody tr{
        cursor: pointer;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
    table tbody tr td{
        white-space: nowrap;
    }
    .v-btn{
        text-decoration: none;
    }
</style>
