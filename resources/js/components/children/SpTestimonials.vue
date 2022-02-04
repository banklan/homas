<template>
    <div class="testimonial">
        <v-container>
           <v-row wrap justify="center" class="mt-5">
                <v-col cols="12">
                    <div class="text-center section_header mt-3 pa-2">What our professionals are saying</div>
                </v-col>
            </v-row>
            <v-row wrap justify="center">
                <v-col cols="12" md="10">
                    <v-carousel height="300" cycle class="mx-auto align-center" :show-arrows="true" show-arrows-on-hover transition="slide-x-transition">
                        <v-carousel-item v-for="(item, i) in items" :key="i">
                            <v-card class="fill-height px-2 py-1" elevation="8">
                                <v-container>
                                    <v-row dense>
                                        <v-col cols="4">
                                            <v-card max-height="280">
                                                <v-img v-if="item.user && item.user.picture" height="230" :src="`/images/profiles/users/${item.user.picture}`" alt="item.user.fullname"></v-img>
                                                <v-img v-else src="/images/shared/user6.jpg" height="230" alt="item.user.fullname"></v-img>
                                            </v-card>
                                        </v-col>
                                        <v-col cols="8">
                                            <v-card class="mx-auto pl-3" elevation="0">
                                                <v-card-title class="justify-center test_title px-2 mb-n1">{{ item.title }}</v-card-title>
                                                <v-card-subtitle class="text-center text-body-1 mb-n8" v-if="item.user">By {{ item.user.fullname }}</v-card-subtitle>
                                                <v-card-subtitle class="text-center sub_title" v-if="item.user">{{ item.user.service && item.user.service.position }}, {{ item.user.service && item.user.service.title }}</v-card-subtitle>
                                                <v-card-text class="detail mt-n3">&#65282;{{ item.detail | truncate($vuetify.breakpoint.smAndDown ? 100 : 240) }}&#65282;</v-card-text>
                                            </v-card>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card>
                        </v-carousel-item>
                    </v-carousel>
                </v-col>
           </v-row>
           <v-row justify="center" class="mt-5 pb-8">
               <v-btn dark text x-large to="/testimonials" color="primary">See All Testimonials</v-btn>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data(){
        return{
            items: [],
            isFetching: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
    },
    methods:{
        getTestimonials(){
            this.isFetching = true
            axios.get(this.api + '/get_featured_testimonials/').then((res) => {
                this.isFetching = false
                this.items = res.data
            })
        }
    },
    created(){
        this.getTestimonials()
    }
}
</script>

<style lang="scss" scoped>
    .testimonial{
        .test_title{
            font-size: 16px important;
            font-weight: 400 !important;
            line-height: 1.80;
            color: #2b2b2bfc;
        }
        .detail{
            font-size: 16px !important;
            font-weight: 400 !important;
            line-height: 1.80;
            font-style: italic;
            color: #323232fc;
            padding: 10px;
        }
        .sub_title{
            font-size: 14px !important;
            font-weight: 400 !important;
            line-height: 1.80;
            font-style: italic;
            color: #323232fc;
        }

        .headline{
            font-size: 30px !important;
            margin-top: -25px;
            margin-bottom: 30px;
        }
        .user{
            flex: 0 1 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            margin-left: .7rem;
        }
        .svg_icon{
            fill: #003B63 !important;
            height: 3rem;
        }
    }
</style>
