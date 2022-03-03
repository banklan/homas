<template>
    <div class="testimonial">
        <div class="banner"></div>
        <v-container>
            <v-row justify="center" wrap>
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="loading" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <v-col cols="12">
                        <div class="title text-center mt-8 mb-3">Testimonials</div>
                    </v-col>
                    <template v-if="testimonials.length > 0">
                        <v-col cols="12" md="10" v-for="(item) in testimonials" :key="item.id">
                            <v-card light elevation="8" min-height="200" class="mx-auto">
                                <v-container>
                                    <v-row dense>
                                        <v-col cols="4">
                                            <v-card class="fill-height img-wrap pa-1" flat>
                                                <v-img v-if="item.user && item.user.picture" :src="`/images/profiles/users/${item.user.picture}`" :alt="item.user && item.user.fullname" max-height="250"></v-img>
                                                <v-img v-else src="/images/shared/user6.jpg" :alt="item.user && item.user.fullname"></v-img>
                                            </v-card>
                                        </v-col>
                                        <v-col cols="8">
                                            <v-card flat class="py-2">
                                                <v-card-title class="justify-center title">{{ item.title }}</v-card-title>
                                                <v-card-subtitle class="text-center text-body-1 mb-n8" v-if="item.user">By {{ item.user.fullname }}</v-card-subtitle>
                                                <v-card-subtitle class="text-center text-body-1 font-italic" v-if="item.user">{{ item.user.service && item.user.service.position }}, {{ item.user.service && item.user.service.title }}</v-card-subtitle>
                                                <v-card-text class="text-body-1">{{ item.detail }}</v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-col>
                    </template>
                    <template v-else>
                        <v-col cols="12" md="8" class="justify-center mx-auto">
                            <v-alert type="warning" border="left" class="mt-5">
                                There are currently no testimonials to show.
                            </v-alert>
                        </v-col>
                    </template>
                    <template v-if="testimonials.length > 0">
                        <div class="text-center my-8">
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getTestimonials(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getTestimonials(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getTestimonials(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getTestimonials(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </div>
                    </template>
                </template>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data(){
        return{
            testimonials: [],
            loading: false,
            pagination: {},
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
    },
    methods: {
        getTestimonials(pag){
            this.loading = true
            pag = pag || `${this.api}/get_pgntd_testimonials`
            axios.get(pag).then((res) =>{
                this.loading = false
                this.testimonials = res.data.data
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
        },
    mounted(){
        this.getTestimonials()
    }
}
</script>

<style lang="scss" scoped>
    .testimonial{
        width: 100vw !important;
    }
    .img-wrap{
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        .v-image{
            height: 90%;
            border-radius: 4px;
        }
    }
</style>
