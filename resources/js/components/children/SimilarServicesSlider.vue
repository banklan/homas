<template>
    <div>
        <vueper-slides :bullets="false" :touchable="true" slide-multiple :visible-slides="3" :gap="3" :slide-ratio="1 / 4" :dragging-distance="100">
            <vueper-slide v-for="(serv, i) in services" :key="i" image="`images/services/${serv.image}`" :link="`/service/${serv.id}/${serv.slug}`">
                <template #content>
                    <span class="label">
                        <div class="wrap">
                            <div class="lab">{{ serv.title | truncate(20) }}</div>
                        </div>
                    </span>
                </template>
            </vueper-slide>
        </vueper-slides>
    </div>
</template>

<script>
import { VueperSlides, VueperSlide } from 'vueperslides'
import 'vueperslides/dist/vueperslides.css'

export default {
    components: { VueperSlides, VueperSlide },
    props: ['services'],
    data() {
        return {
            // breakpoints:{
            //     1200:{
            //         slideRatio: 1/4
            //     },
            //     900:{
            //         slideRatio: 1/3
            //     },
            //     600:{
            //         slideRatio: 1/2
            //     },
            // }
        }
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

