<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12">
                <VueSlickCarousel v-bind="settings">
                    <div v-for="serv in services" :key="serv.id">
                    <v-card height="250" light raised elevation="12" max-width="200">
                        <v-img height="200" :src="`images/services/${serv.image}`"></v-img>
                        <v-card-actions class="justify-center">
                            <div class="primary--text">{{ serv.title }}</div>
                        </v-card-actions>
                    </v-card>
                    </div>
                </VueSlickCarousel>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import 'vue-slick-carousel/dist/vue-slick-carousel-theme.css'
export default {
    components: { VueSlickCarousel },
    data() {
        return {
            services: [],
            settings: {
                // arrows: true,
                // dots: true,
                centerMode: true,
                centerPadding: "20px",
                // dotsClass: "slick-dots custom-dot-class",
                // edgeFriction: 0.35,
                focusOnSelect: true,
                infinite: true,
                speed: 500,
                slidesToShow: 3,
                // slidesToScroll: 1,
                // touchThreshold: 5,
                // asNavFor: true,
                // draggable: true,
                // rows: 1,
                // swipe: true,
                // swipeToSlide: true,
                // initialSlide: 2
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
    },
    mounted() {
        this.getServices()
        // this.mySwiper.slideTo(3, 1000, false)
    },
}
</script>

<style lang="scss" scoped>
    .wrapper{
        height: 250px;
        width: 100vw;
        background: #cccccc;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
        padding: 0;

        .slider{
            height: 90%;
            width: 100%;
            margin: auto 15px;
            background-color: yellowgreen;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: auto;

            img{
                height: 200px;
                width: 300px;
            }
        }
    }
</style>
