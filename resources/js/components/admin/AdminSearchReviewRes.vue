<template>
    <v-container>
        <v-row class="mb-n3 mt-1 mr-8" justify="space-around">
            <v-col cols="3" md="3">
                <v-btn dark small rounded color="admin" elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon>Back</v-btn>
            </v-col>
            <v-col cols="9" md="6">
                <admin-search model="Review" searchFor="reviews"/>
            </v-col>
        </v-row>
        <v-row class="mt-5 mr-5">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="100" class="scroll">
                    <template v-if="reviews.length > 0">
                        <v-card-title class="subtitle-1 admin white--text justify-center"> Result for {{ $route.query.q }} <v-chip color="primary lighten-2" class="ml-1" v-if="reviews.length > 0">{{ reviews.length }}</v-chip></v-card-title>
                        <v-card-text class="mt-5">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Author</th>
                                        <th>Service</th>
                                        <th>Review </th>
                                        <th>Status</th>
                                        <th>Published</th>
                                    </tr>
                                </thead>
                                <tbody class="table_list">
                                    <tr v-for="rev in reviews" :key="rev.id">
                                        <td @click="showReview(rev)">{{ rev.id }} </td>
                                        <td @click="showReview(rev)">{{ rev.user && rev.user.fullname }} </td>
                                        <td @click="showReview(rev)">{{ rev.service && rev.service.title | truncate(30) }} </td>
                                        <td @click="showReview(rev)">{{ rev.title | truncate(40)  }} </td>
                                        <td>{{ rev.is_approved ? 'Approved' : 'Not Aprvd' }} </td>
                                        <td>{{ rev.created_at | moment('DD/MM/YYYY') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-card-text>
                    </template>
                    <template v-else>
                        <v-card-text>
                            <v-alert type="info" border="left" class="mt-8">
                                Your search for {{ $route.query.q }} returned no review.
                            </v-alert>
                        </v-card-text>
                    </template>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            reviews: [],
        }
    },
    watch: {
        '$route.query.q':{
            handler(newVal){
                this.reviews = newVal
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
            axios.post(this.api + '/auth-admin/admin_search_for_reviews', {
                q: this.$route.query.q
            }, this.adminHeaders).then((res)=>{
                this.isLoading = false
                this.reviews = res.data
            }).catch(()=>{
                this.isLoading = false
            })
        },
        showReview(review){
            this.$router.push({name: 'AdminReviewDetail', params:{id: review.id}})
        },
    },
    created() {
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
