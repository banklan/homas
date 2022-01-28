<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12">
                <vueper-slides :bullets="false" :touchable="true" slide-multiple :visible-slides="3" :gap="3" :slide-ratio="1 / 4" :dragging-distance="100">
                    <vueper-slide v-for="(slide, i) in services" :key="i" :image="`images/services/${slide.image}`" :link="`/service/${slide.id}/${slide.slug}`">
                        <template #content>
                            <span class="label">
                                <div class="wrap">
                                    <div class="lab">{{ slide.title | truncate(20) }}</div>
                                </div>
                            </span>
                        </template>
                    </vueper-slide>
                </vueper-slides>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import { VueperSlides, VueperSlide } from 'vueperslides'
    import 'vueperslides/dist/vueperslides.css'
export default {
    components: { VueperSlides, VueperSlide },
    data() {
        return {
            services: [],
            breakpoints:{
                1200:{
                    slideRatio: 1/4
                },
                900:{
                    slideRatio: 1/3
                },
                600:{
                    slideRatio: 1/2
                },
            }
        }
    },
     computed: {
        api(){
            return this.$store.getters.api
        },
     },
    methods: {
        getServices(){
            axios.get(this.api + '/get_all_services').then((res) => {
                this.services = res.data
            })
        },
        goToLink(slide){
            this.$router.push({name: 'ServiceShow', params: {id: slide.id, slug: slide.slug}})
        }
    },
    mounted() {
        this.getServices()
        // this.mySwiper.slideTo(3, 1000, false)
    },
}
</script>

<style lang="scss" scoped>
    a{
        text-decoration: none !important;
        // white--text grey darken-4 title text-center
        position: relative;

        .label{
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0,0,0,.5);
            padding: 10px;
            bottom: 0;
            text-align: center;
            overflow: hidden;
            width: 100%;
            .wrap{
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                .lab{
                    font-size: 16px;
                    color: #fff;
                    margin: 5px;
                    padding-left: 10px;
                }
                @media screen and (max-width: 720px){
                    .lab{
                        font-size: 14px !important;
                        padding-left: 0;
                        text-align: center;
                        margin: 0 auto !important;
                    }
                }
            }
        }
    }

</style>
