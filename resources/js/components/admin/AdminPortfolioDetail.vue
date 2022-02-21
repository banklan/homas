<template>
    <v-container fluid>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin" dark elevation="4" left :to="{name: 'AdminPortfolioList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <template v-if="portfolio">
                    <v-col cols="12" md="10">
                        <div class="title mt-4 mx-2 text-truncate"><span class="primary--text font-weight-bold mr-1">Title:</span>{{ portfolio.title }}</div>
                        <v-card light raised elevation="4" min-height="100" class="mx-auto mt-3">
                            <v-card-text class="pt-8 px-7 body_text">
                                <table class="table table-hover table-striped">
                                    <thead></thead>
                                    <tbody>
                                        <tr>
                                            <th width="20%">Service:</th>
                                            <td>{{ portfolio.service && portfolio.service.title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cost(&#8358;):</th>
                                            <td>{{ portfolio.cost / 100 | price }}</td>
                                        </tr>
                                        <tr>
                                            <th>Approved:</th>
                                            <td>{{ portfolio.is_approved ? 'Approved' : 'Not Approved' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </v-card-text>
                            <v-card-text class="mt-n2 px-7 body_text">
                                <div class="primary--text font-weight-bold mr-1">Details:</div>
                                <div>{{ portfolio.detail }}</div>
                            </v-card-text>
                            <v-card-actions class="px-5 justify-center pb-5">
                                <v-btn icon color="admin lighten-3" class="mx-5" :to="{name: 'AdminPortfolioUpdate', params:{id: portfolio.id}}">
                                    <v-icon>edit</v-icon>
                                </v-btn>
                                <v-btn dark :color="portfolio.is_approved ? 'primary' : 'green darken-2'" class="mx-5" @click="toggleIsApprovedDial = true"> {{ portfolio.is_approved ? 'Dis-approve' : 'Approve' }} </v-btn>
                                <v-btn icon color="red darken-2" class="mx-5" @click="delPfConfirmDial = true">
                                    <v-icon>delete_forever</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-card min-height="400" min-width="80%" class="mt-5">
                            <v-carousel height="400">
                                <v-carousel-item v-for="(item, i) in files" :key="i" :src="`/images/portfolios/${item.file}`" reverse-transition="fade-transition" transition="fade-transition"></v-carousel-item>
                            </v-carousel>
                            <v-card-actions class="justify-center px-5" v-if="files">
                                <v-btn icon color="red darken-2 mx-5" @click="confirmDelFile">
                                    <v-icon>delete_forever</v-icon>
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
            </template>
        </v-row>
        <v-dialog v-model="toggleIsApprovedDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">{{ portfolio && portfolio.is_approved ? 'Dis-approve' : 'Approve' }} this portfolio?</v-card-title>
                <v-card-actions class="mt-5 pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="toggleIsApprovedDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isBusy" @click="toggleIsApproved" width="40%">Yes, {{ portfolio && portfolio.is_approved ? 'Dis-approve' : 'Approve' }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="delPfConfirmDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this portfolio?</v-card-title>
                <v-card-actions class="mt-5 pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="delPfConfirmDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isBusy" @click="deletePf" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelFileDial" max-width="450">
            <v-card min-height="150" class="mx-auto">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete Portfolio File(s)</v-card-title>
                <v-card-text class="mt-3" v-if="delDialFiles.length > 0">
                    <v-alert type="warning">
                        Files deleted are not recoverable
                    </v-alert>
                </v-card-text>
                <v-card-text class="img_wrap mt-n3" v-if="delDialFiles.length > 0">
                    <v-list>
                        <v-list-item v-for="(file, i) in delDialFiles" :key="i">
                            <v-list-item-content>
                                <v-img :alt="file.file" :src="`/images/portfolios/${file.file}`" width="80" height="80" ></v-img>
                            </v-list-item-content>
                            <v-list-item-icon>
                                <v-btn icon @click="removeFile(file, i)"><v-icon color="red darken-2">cancel</v-icon></v-btn>
                            </v-list-item-icon>
                        </v-list-item>
                    </v-list>
                </v-card-text>
                <v-card-text v-else>
                    <v-alert type="info" class="mt-5">
                        There are no files.
                    </v-alert>
                </v-card-text>
                <v-card-actions class="justify-space-around pb-8">
                    <v-btn text color="red darken-2" width="40%" @click="confirmDelFileDial = false">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUpdatedPortfolio" :timeout="4000" top color="green darken-1 white--text">
            Portfolio has been updated successfully.
            <v-btn text color="white--text" @click="adminUpdatedPortfolio = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data(){
        return{
            isLoading: false,
            portfolio: null,
            files: [],
            toggleIsApprovedDial: false,
            isBusy: false,
            delPfConfirmDial: false,
            confirmDelFileDial: false,
            delDialFiles: []
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
            let authHeader = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return authHeader;
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
        getPortfolio(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_portfolio/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.portfolio = res.data
            })
        },
        getFiles(){
            axios.get(this.api + `/auth-admin/admin_get_portfolio_files/${this.$route.params.id}`, this.adminHeaders)
            .then((res) => {
                this.files = res.data
            })
        },
        toggleIsApproved(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/toggle_portfolio_approved_status/${this.$route.params.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isBusy = false
                this.toggleIsApprovedDial = false
                this.portfolio.is_approved  = res.data.is_approved
            })
        },
        deletePf(){
            this.isBusy = true
            axios.post(this.api + `/auth-admin/admin_delete_portfolio/${this.$route.params.id}`, {}, this.adminHeaders).then((res) => {
                this.isBusy = false
                this.$store.commit('adminDeletePortfolio')
                this.$router.push({name: 'AdminPortfolioList'})
            })
        },
        confirmDelFile(){
            this.confirmDelFileDial = true
            this.delDialFiles = this.files
        },
        removeFile(file){
            axios.post(this.api + `/auth-admin/admin_del_portfolio_file/${file.id}`, {}, this.adminHeaders).then((res) =>{
            })
            this.delDialFiles.splice(file, 1)
        }
    },
    mounted() {
        this.getPortfolio()
        this.getFiles()
    },
}
</script>

<style lang="css" scoped>
    .v-card__text{
        line-height: 1.8 !important;
    }
    .v-btn{
        text-decoration: none !important;
    }
    .del_file{
        display: flex;
        /* justify-content: space-around; */
        align-items: center;
        padding: 10px;
    }
    .del_file .v-image{
        border: 1px #3e3e3e 6px;
        height: 60px !important;
        width: 60px !important;
    }
    .img_wrap{
        width: 70% !important;
    }
</style>
