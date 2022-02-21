<template>
    <v-container>
        <v-row justify="center" class="mt-5">
            <v-col cols="12">
                <slider animation="fade">
                    <slider-item v-for="(i, index) in list" :key="index" :style="i" @click="hello">
                        <p style="line-height: 280px; font-size: 5rem; text-align: center;">Page{{ index + 1 }}</p>
                    </slider-item>
                </slider>
                <!-- <div class="slider_box mt-10 pb-10">
                    <div class="slide-img">
                        <img src="/images/portfolios/&1zv4dyl.jpg">
                        <div class="overlay">
                            <v-btn large dark class="view-btn primary">View Now</v-btn>
                        </div>
                    </div>
                    <div class="detail-box">
                        <div class="type">
                            <h3>Title here</h3>
                        </div>
                    </div>
                </div> -->
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
// import Slider from '../slider.vue'
import { Slider, SliderItem } from 'vue-easy-slider'
export default {
    data() {
        return {
            list: [
                { backgroundColor: '#3f51b5', width: '100%', height: '100%' },
                { backgroundColor: '#eee', width: '100%', height: '100%' },
                { backgroundColor: '#f44336', width: '100%', height: '100%' },
            ],
        }
    },
    components:{
        Slider,
        SliderItem,
    },
    methods: {
        hello($event) {
            console.log(`hello index: ${$event}`)
        },
    }
}
</script>

<style lang="scss" scoped>
    .slider_box{
        width: 300px;
        box-shadow: 2px 2px 30px rgba(0,0,0,0.2);
        border-radius: 10px;
        overflow: hidden;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        .slider-img{
            height: 450px;
            position: relative;
            &:hover .overlay{
                visibility: visible;
                animation: fade 0.5s;
            }
            img{
                width: 100%;
                height: 100%;
                object-fit: cover;
                box-sizing: border-box;
            }
            .overlay{
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                height: 100%;
                width: 100%;
                background-color: rgba(236, 138, 92, .6);
                display: flex;
                justify-content: center;
                align-items: center;
                visibility: hidden;
            }
        }
        .detail-box{
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: yellow;
            padding: 10px 20px;
            box-sizing: border-box;
        }
        // padding: 10px;
        // background: #ddd;
        // height: 300px;
        // width: 100%;
        // margin: 40px auto;
        // // text-align: center;
        // display: flex;
        // justify-content: center;
        // text-align: center;
    }
    @keyframes fade{
        0%{
            opacity: 0;
        }
        100%{
            opacity: 1;
        }
    }
</style>
