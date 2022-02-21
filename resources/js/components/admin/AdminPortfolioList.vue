<template>
    <v-container>
         <v-row>
            <v-col cols="12" md="5" offset-md="6">
               <admin-search model="Portfolio" searchFor="portfolios"/>
           </v-col>
        </v-row>
        <v-row justify="start" class="mr-3 mt-n6">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Portfolios <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="portfolios.length > 0">
                            <table class="table table-hover table-condensed table-striped">
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
                                <tbody>
                                    <tr v-for="(portfolio, i) in portfolios" :key="portfolio.id">
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.id }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.title | truncate(50) }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.service_id }} </td>
                                        <td @click="showPortfolio(portfolio)">{{ portfolio.cost / 100 | price  }} </td>
                                        <td>{{ portfolio.is_approved ? 'Approved' : 'Not Aprvd' }} </td>
                                        <td>{{ portfolio.created_at | moment('DD/MM/YYYY') }}</td>
                                        <td><v-btn small icon color="admin lighten-2" :to="{name: 'AdminPortfolioUpdate', params:{id: portfolio.id}}"><v-icon>edit</v-icon></v-btn><v-btn class="ml-3" small icon color="red darken-2" @click="delConfirm(portfolio, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" class="mt-5">
                                There are no portfolios to display at the moment.
                            </v-alert>
                        </template>
                    </v-card-text>
                    <v-card-actions class="my-5" v-if="portfolios.length > 0">
                        <span class="pl-4">
                            <v-btn color="primary" @click.prevent="getPortfolios(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getPortfolios(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                            <v-btn color="primary" @click.prevent="getPortfolios(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                            <v-btn color="primary" @click.prevent="getPortfolios(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
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
        <v-snackbar :value="adminDelPortfolio" :timeout="4000" top color="green darken-1 white--text">
            A portfolio has been deleted successfully.
            <v-btn text color="white--text" @click="adminDelPortfolio = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="adminUpdatedPortfolio" :timeout="4000" top color="green darken-1 white--text">
            A portfolio has been updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedPortfolio = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            portfolios: [],
            pagination: {},
            q: '',
            isToggling: false,
            confirmDelDial: false,
            portfolioToDel: null,
            portfolioToDelIndex: null,
            isDeleting: false,
            isLoading: false,
            total: 0
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
        adminDelPortfolio(){
            return this.$store.getters.adminDeletedPortfolios
        },
        adminNewPortfolio(){
            return this.$store.getters.adminNewPortfolio
        },
        adminUpdatedPortfolio(){
            return this.$store.getters.adminUpdatedPortfolio
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getPortfolios(pag){
            pag = pag || `${this.api}/auth-admin/get_pgntd_portfolios`
            this.isLoading = true
            axios.get(pag, this.adminHeaders).then((res) => {
                this.portfolios = res.data.data
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
        search(){
            if(this.q.trim() !== ''){
                this.$router.push({name: 'AdminSearchPortfolioRes', query:{q: this.q}})
            }
        }
    },
    created(){
        this.getPortfolios()
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
        text-decoration: none !important;
    }
    table tbody tr td, table tbody tr th, table thead tr th{
        white-space: nowrap !important;
    }
</style>
