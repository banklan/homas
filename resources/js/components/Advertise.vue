<template>
    <div class="contact">
        <div class="banner"></div>
        <v-container>
            <v-row justify="center" class="mt-n10">
                <v-col cols="12" md="10">
                    <v-card elevation="8" light min-height="400" class="mx-auto mt-n10 py-5" :class="$vuetify.breakpoint.smAndDown ? 'px-1 ml-n1 mr-n3' : 'px-6 ml-8 mr-3'" shaped>
                        <v-card-title class="headline justify-center mt-3 pb-5">Advertise Your Services</v-card-title>
                        <v-card-text class="mt-3 text-body-1 grey--text text--darken-3">
                            Kindly reach us through any of the means on our <router-link to="/contact-us">Contact page</router-link> for your advert placements.
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>


<style scoped lang="scss">
    .contact{
        width: 100vw !important;

        .banner{
            margin-top: 30px;
            height: 15rem;
            width: 100%;
            background-image: linear-gradient(to bottom right, rgba(0, 59, 99, 0.89), rgba(255, 23, 69, 0.623)), url('/images/background/advert.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .v-card{
            margin-top: -7rem;
            z-index: 1000;
            margin-bottom: 8rem;
        }
        a{
            text-decoration: none;
        }
    }
</style>

