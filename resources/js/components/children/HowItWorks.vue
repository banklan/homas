<template>
    <div class="primary how_to">
        <div class="section_header white--text mt-2 mb-3 text-center">How it works</div>
        <div class="how_details">
            <v-list class="primary white--text">
                <v-list-item dark v-for="item in hows" :key="item.i" class="white--text">
                    <v-list-item-content class="px-5">
                        <v-list-item-subtitle class="text-h5 font-weight-thin">{{ item.i }}.  {{ item.b }}</v-list-item-subtitle>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            hows: [
                {i: 1, b: 'Register with your email address.'},
                {i: 2, b: 'Verify your email, login and create Your services, indicating your service address and location.'},
                {i: 3, b: 'Add your portfolio, upload relevant pictures of your jobs.'},
                {i: 4, b: 'Be descriptive in the details of your services, cost, highlight your strengths and emphasize your selling points.'},
                {i: 5, b: 'Ensure your contact details and social media handles are correctly typed in your profile.'},
                {i: 6, b: 'Share and re-share your services with our share-button on you social media.'},
            ]
        }
    },
}
</script>

<style lang="scss" scoped>
 .how_to{
     min-height: 30rem;
     width: 100vw;
     color: #fff;
     padding: 20px;
     margin: 25px 0;
     color: #fff !important;

     .text-h5{
         font-weight: 300 !important;
     }
 }
</style>
