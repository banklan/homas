<template>
    <div class="how_to">
        <div class="section_header white--text mt-6 mb-3 text-center">How it works</div>
        <div class="how_details">
            <v-list class="white--text ml-5">
                <v-list-item dark v-for="item in hows" :key="item.i" class="white--text">
                    <div class="list_text">{{ item.i }}.  {{ item.b }}</div>
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
                {i: 6, b: 'Share and re-share your services with our share-buttons on you social media.'},
            ]
        }
    },
}
</script>

<style lang="scss" scoped>
 .how_to{
    background-image: url('/images/background/howto.png');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    min-height: 35rem;
    width: 100vw;
    color: #fff;
    padding: 20px;
    margin-top: 25px;
    color: #fff !important;
    .v-list{
        background: inherit !important;
    }

    .list_text{
        font-size: 1.30rem !important;
        font-weight: 400 !important;
        color: #fff !important;
        line-height: 1.8;
        letter-spacing: .0125em!important;
        margin-bottom: 25px;
    }
 }
 @media screen and (max-width: 782px) {
     .how_to{
         padding: 20px 10px;
     }
 }
</style>
