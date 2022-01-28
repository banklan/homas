<template>
    <v-container>
        <v-row justify="center" class="mt-7 mb-5">
            <v-col cols="12" md="8">
                <div class="section_header text-center">
                    Take your services to the next level in 2 easy steps!
                </div>
            </v-col>
        </v-row>
        <v-row justify="center" class="mb-8">
            <v-col cols="12" md="5">
                <v-card tile raised elevation="12" outlined ripple min-height="400" light>
                    <v-card-title class="justify-center mt-5">
                        <svg class="svg_icon">
                            <use xlink:href="/svg_icons/sprite2.svg#icon-tools-2" />
                        </svg>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <p class="card_headers pa-3">Join Our professionals</p>
                        <p class="title pt-2 px-2">Register and create your service. <br> Be descriptive with the details of your services.</p>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8">
                        <v-btn dark color="primary white--text" rounded x-large to="/register" class="px-5">Register Now</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="5">
                <v-card hover tile raised outlined ripple min-height="400" light>
                    <v-card-title class="justify-center mt-5">
                        <svg class="svg_icon">
                            <use xlink:href="/svg_icons/sprite.svg#icon-like" />
                        </svg>
                    </v-card-title>
                    <v-card-text class="text-center">
                        <p class="card_headers pa-3">Get Verified</p>
                        <p class="title pt-2">Add your portfolio. Include valid contacts, socia media handles & business location & get verified.</p>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-8 mt-n1">
                        <v-btn color="primary white--text" rounded x-large @click="goToSearch" class="px-5">Join Our Professionals</v-btn>
                        <a ref="search" href="#pick_loc" style="display:none"></a>
                    </v-card-actions>
                </v-card>
            </v-col>

        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
        }
    },
    methods: {
        goToSearch(){
            this.$refs.search.click()
        }
    },
    created() {

    },
}
</script>

<style lang="scss" scoped>
    .svg_icon{
        height: 6rem;
        fill: #FF5200;
    }
    .v-btn{
        text-decoration: none !important;
    }
</style>
