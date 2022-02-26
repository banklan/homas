<template>
    <div class="faq">
        <div class="banner"></div>
        <v-container>
            <v-row wrap justify="center" class="mt-n10">
                <v-col cols="12" md="10">
                    <v-card shaped min-height="600" class="mx-auto mt-n10 py-5" :class="$vuetify.breakpoint.smAndDown ? 'px-1 ml-n1 mr-n4' : 'px-6 ml-8 mr-3'">
                        <v-card-title class="headline justify-center primary--text my-5">Frequently Asked Questions</v-card-title>
                        <v-card-text>
                            <div class="faqs" v-for="(faq, i) in faqs" :key="i">
                                <div class="title mb-3">{{ faq.sn }}. {{ faq.question }}</div>
                                <div class="answers mb-5 pl-5"><strong>Answer: </strong> {{ faq.answer }} </div>
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    data(){
        return{
            faqs: [
                {sn: '1', question: 'Does Homas guarantee the genuiness of and bear responsibility for any loss or damage that may happen while engaging the use of any service provider listed on this site?', answer: 'No. While we seek to ensure that services listed on our websites are genuine and take proactive steps to verify the authenticity of such services, we wish to state categorically that we do not accept any liability for any loss that may arise from the engagement of any service providers. It remains the sole responsibilty of users to carry out due diligence to ensure they are dealing with non-fraudulent and genuine service providers.'},
                {sn: '2', question: 'Does Homas charge service providers who list their services on the website?', answer: 'No, we do not charge service providers for listing their services and publishing their portfolios on homas.com, it is absolutely free. However, we offer premium services in form of advertisements to specially promote and priotize services.'},
                {sn: '3', question: 'Do we allow publishing of tangible products or items on our site?', answer: 'No. This website is targeted at small and medium scale service providers, who provides intangible services(services that are not perceptible by touch in the real sense, like drycleaning, plumbing, electrical services, house-cleaning, security services etc). Contents that do not fall into these categories of intangible services will be deleted.'},
                {sn: '4', question: 'Does Homas give preference to some kind of services while displaying on our site?', answer: 'No. As long as the services published do not contravene our policies and terms, we treat all services equally. However, premium services understandably gets priotized and enjoy more visibility.'},
                {sn: '5', question: 'Does Homas confirm the genuiness of services posted on our site?', answer: 'While we carry out some checks to confirm the services do exist and the claims are genuine, unfortunately we still can not guarantee the genuiness of every services listed. Potential clients are therefore expected and encouraged to run their background checks before engaging the services of any SP.'},
                {sn: '6', question: 'A Service Provider request that i pay an upfont amount before commencing a job, what should i do?', answer: 'We strongly recommend that before any financial transactions take place, clients should meet with the SP, confirm their genuiness and ability to do the job. PAYMENTS SHOULD NOT BE MADE BEFORE MEETING ANY SERVICE PROVIDER LISTED ON OUR SITE.'},
                {sn: '7', question: 'In the event of a Service Provider scamming me, what should i do?', answer: 'Immediately you notice that a SP is acting fraudulently, kindly contact us via email or our mobile number listed on our Contact page. We might request you fill an incident form and we will blacklist such SP and remove their services/portfolios from our site. If they have been paid for a service and failed to meet up with their obligations, consider reporting them to law enforcement agents.'},
                {sn: '8', question: 'I tried posting my service on the site, it showed that it was successful but i cannot find it on the site.', answer: 'Every service published will be reviewed and then approved by our admins before it appears on the listing. This process should not exceed 24hours maximum. So, kindly excercise patience.'},
                {sn: '9', question: 'I got a call from someone purpotedly from Homas to pay an amount before my service is listed on the site.', answer: 'Homas will not call to request for payment before listing your services. It is absolutely free to list your services on homas.com, except for premium services. If you get such calls or messages, kindly report to us immediately.'},
                {sn: '10', question: 'Can i publish multiple services with same account?', answer: 'No. You can only publish a service with your account but you are allowed to publish up to 5 portfolios for a service.'}                ,
            ]
        }
    }
}
</script>

<style lang="scss" scoped>
    .faq{
        width: 100vw !important;

        .banner{
            margin-top: 30px;
            height: 15rem;
            width: 100%;
            background-image: linear-gradient(to bottom right, rgba(0, 59, 99, 0.89), rgba(255, 23, 69, 0.623)), url('/images/background/faq.jpg');
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        .v-card__text{
            color: rgba(0, 0, 0, .88) !important;

            .title{
                line-height: 1.75 !important;
                font-size: 1.2rem !important;
            }
            .answers{
                font-size: 18px !important;
                line-height: 1.70;
                font-family: 'Source Sans Pro', sans-serif;
            }
        }
    }

</style>
