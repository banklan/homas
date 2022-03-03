<template>
    <div class="about">
        <div class="banner"></div>
        <v-container>
            <v-row wrap justify="center" class="mt-n10">
                <v-col cols="12" md="10">
                    <v-card shaped light class="mx-auto mt-n10 py-5" :class="$vuetify.breakpoint.smAndDown ? 'px-1' : 'px-6 ml-8 mr-3'">
                        <v-card-title class="headline justify-center primary--text pb-2">Our Story</v-card-title>
                        <v-card-text>
                            <div class="subtitle-1 mb-3 primary--text">Our Mission</div>
                            <div class="mission mb-6">To help provide the online platform that gives start-ups and mid-size service providers the needed visibility and exposures to thrive their services.</div>
                            <p>Welcome to <strong>{{ app_name }}</strong>, an online resource dedicated to empowering small scale artisans, professionals, start-ups and medium-sized service providers to achieve success.</p>
                            <p>We are a brand borne out of a pressing desire to bridge the gap between the tools and resources the big organizations use as an advantage over the small-scale start-ups and medium-sized companies. Global online presence. </p>
                            <p>In an era where financial, economic and social factors (among others) work against start-ups, providing a platform where they can showcase their services and publish their portfolios for free will go a long way in addressing some of the gaps and providing a leverage.
                            </p>
                            <p>At <strong>{{ app_name }}</strong>, we help you re-discover your services and provide a platform to launch them to the consciousness of your local market and give you global attention.</p>
                            <p>Our website, an online directory boasts of thousands of unique visitors every month who search for artisans, SME's and service providers within their locations and beyond, based on categories and locations.
                                Service providers can take advantage of this, to customize their service pages and express themselves, just the way it suits their businesses and services. Our feedback tools like reviews and ratings can also help them
                                gain key knowledge vital to help grow their businesses and improve their services.</p>
                            <p>Whats more? We help you harness the powerful magic of social media to share your services to the people and market that matters. Our aim is to develop a close bond with you
                                that will enable us gain insight into and share in your dreams. Being a part of your success story is our motivation and fulfillment indeed.
                            </p>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-8">
                            <v-btn dark x-large elevation="6" color="primary" shaped :to="{name: 'Register'}">Join Us Today</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data() {
        return {
            app_name: window.Laravel.app_name
        }
    },
}
</script>

<style lang="scss" scoped>
    .about{
        width: 100vw !important;

        .banner{
            margin-top: 30px;
            height: 15rem;
            width: 100%;
            background-image: linear-gradient(to bottom right, rgba(0, 59, 99, 0.89), rgba(255, 23, 69, 0.623)), url('/images/background/about.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .v-card__text{
            font-family: 'Source Sans Pro', sans-serif !important;
            line-height: 1.80;
            color: rgba(0,0,0,.88) !important;

            .mission{
                font-size: 1.1rem !important;
                line-height: 1.7 !important;
                font-weight: 600 !important;
            }
            p{
                font-size: 17px !important;
                line-height: 1.7;
                font-family: 'Source Sans Pro', sans-serif;
            }
        }
    }
    @media screen and(max-width: 780px){
        .v-btn{
            width: 80% !important;
        }
    }
</style>
