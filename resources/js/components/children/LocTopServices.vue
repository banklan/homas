<template>
    <div class="mt-n7">
        <div class="text-center subtitle-1 mt-n5 mb-1 pa-3 primary white--text">Top Services </div>
        <div class="slider_wrap">
            <div class="wrap">
                <div v-for="service in services" :key="service.id">
                    <div class="card" @click="goToService(service)">
                        <img :src="`/images/services/${service.image}`" contain>
                        <div class="title text-center">
                            <div class="text-truncate subject">{{ service.title }}</div>
                            <div class="loc primary--text"><v-icon color="primary mr-2">place</v-icon> {{service.location.state }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['services'],
    methods: {
        goToService(service){
            this.$router.push({name: 'ServiceShow', params:{id: service.id, slug: service.slug}})
        }
    }
}
</script>

<style lang="scss" scoped>
    .slider_wrap{
        width: 100%;
        height: 270px;
        box-sizing: border-box;
        border: 1px solid #fefefe;
        border-radius: 4px;
        background: #fff;
        .wrap{
            width: 100%;
            height: 270px;
            display: flex;
            justify-content: space-between;
            // align-items: stretch;
            overflow-x: scroll;
            overflow-y: hidden;
            -ms-overflow-style: none;
            scrollbar-width: none !important;
            box-sizing: border-box;
            .card{
                margin: 0 10px;
                max-height: 270px !important;
                box-shadow: 0 3px 5px -1px rgba(219,219,219,.2) !important;
                border-radius: 4px;
                background-color: #fff;
                color: rgba(0,0,0,.87);
                border: 1px solid #f1f1f1;
                border-width: thin;
                display: block;
                max-width: 100%;
                outline: none;
                text-decoration: none;
                transition-property: box-shadow,opacity;
                overflow-wrap: break-word;
                position: relative;
                white-space: normal;
                img{
                    height: 270px;
                    width: 100% !important;
                }
                .title{
                    height: 90px;
                    width: 100%;
                    position: absolute;
                    bottom: 0;
                    color: #fff;
                    background: rgba(0,0,0,.62);
                    text-align: center;
                    padding: 5px;
                    // padding: 5px 5px 5px 5px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    box-sizing: border-box;
                    .subject{
                        font-size: .9rem !important;
                        margin-top: -10px;
                        padding-bottom: 10px;
                    }
                    .loc{
                        margin-top: -10px;
                        font-size: .8rem;
                        box-sizing: border-box;
                    }
                }
                &:hover{
                    cursor: pointer;
                }
            }
        }
    }
    @media screen and (max-width: 959px){
        .slider_wrap{
            height: 200px !important;
            .wrap{
                height: 200px !important;
                .card{
                    max-height: 200px !important;
                    img{
                        height: 200px !important;
                    }
                    .title{
                        .subject{
                            font-size: .75rem !important;
                            padding: 5px 10px;
                        }
                        .loc{
                            font-size: .7rem !important;
                            padding: 5px !important
                        }
                    }
                }
            }
        }
    }
</style>
