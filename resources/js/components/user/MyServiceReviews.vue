<template>
    <v-container>
        <v-row class="mt-4" justify="center" :class="$vuetify.breakpoint.smAndDown ? 'ml-n10':''">
            <v-col cols="12" md="10">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <v-card light raised elevation="12" min-height="150" class="mt-5">
                        <v-card-title class="primary white--text justify-center subtitle-1">Reviews for My Service <span class="ml-2"><v-chip color="primary darken-1">{{ total }}</v-chip></span></v-card-title>
                        <v-card-text class="body-1 pa-3 mx-3">
                            <template v-if="reviews.length > 0">
                                <v-list class="ml-n3">
                                    <v-list-item-group color="primary">
                                        <div v-for="rev in reviews" :key="rev.id">
                                            <my-service-review :rev="rev" />
                                            <v-divider></v-divider>
                                        </div>
                                    </v-list-item-group>
                                </v-list>
                            </template>
                            <template v-else>
                                There are no reviews for your service yet.
                            </template>
                        </v-card-text>
                        <v-card-actions v-if="total > 0" class="pb-6">
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getReviews(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getReviews(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getReviews(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getReviews(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </v-card-actions>
                    </v-card>
                </template>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            reviews: [],
            isLoading: false,
            miniRev: true,
            total: 0
        }
    },
    computed: {
        authUser(){
            return this.$store.getters.authUser
        },
        authService(){
            return this.$store.getters.authService
        },
        api(){
            return this.$store.getters.api
        },
        headers(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        getMyReviews(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth/get_my_service_reviews`
            axios.get(pag, this.headers).then((res) => {
                this.isLoading = false
                this.reviews = res.data.data
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
        changeMini(){
            console.log(this.reviews)
        }
    },
    mounted() {
        this.getMyReviews()
    },
}
</script>
