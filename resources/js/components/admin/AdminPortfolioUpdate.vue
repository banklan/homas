<template>
    <v-container fluid>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn dark small rounded color="admin" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <template v-if="portfolio">
                    <v-col cols="12" md="10">
                        <v-card light raised elevation="4" min-height="100" class="mx-auto mt-3">
                            <v-card-title class="admin justify-center white--text">Update Portfolio</v-card-title>
                            <v-card-text class="pt-8 px-7 ml-5 body_text">
                                <v-text-field label="Title" v-model="portfolio.title" required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title"></v-text-field>
                                <v-text-field label="Price" v-model="portfolio.cost" required v-validate="'decimal'" :error-messages="errors.collect('cost')" name="cost"></v-text-field>
                                <v-textarea label="Detail" auto-grow rows="2" v-model="portfolio.detail" required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                            </v-card-text>
                            <v-card-actions class="justify-center pb-8">
                                <v-btn large text color="red darken-2" width="40%" @click.prevent="$router.go(-1)">Cancel</v-btn>
                                <v-btn large dark color="admin" width="40%" :loading="isUpdating" @click="updatePf">Update</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </template>
            </template>
        </v-row>
    </v-container>
</template>

<script>
    export default {
    data() {
        return {
            portfolio: null,
            isLoading: false,
            isUpdating: false,
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
    },
    methods:{
        getPortfolio(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/admin_get_portfolio/${this.$route.params.id}`, this.adminHeaders).then((res) => {
                this.isLoading = false
                this.portfolio = res.data
                this.portfolio.cost = res.data.cost / 100
            })
        },
        updatePf(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/admin_update_portfolio/${this.$route.params.id}`, {
                        portfolio: this.portfolio,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.$router.push({name: 'AdminPortfolioDetail', params:{id: res.data.id, slug: res.data.slug}})
                        this.$store.commit('adminUpdatedPortfolio')
                    })
                }
            })
        }
    },
    mounted(){
        this.getPortfolio()
    }
}
</script>
