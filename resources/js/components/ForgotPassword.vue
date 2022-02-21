<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="5" v-if="showResetForm">
                <v-card light raised elevation="6" min-height="60" class="mt-10 pb-2">
                    <v-card-title class="primary white--text justify-center">Enter Your Email Address</v-card-title>
                    <v-card-text class="mt-6">
                        <v-text-field placeholder="Your Email Address" v-model="email" required v-validate="'required|email'" :error-messages="errors.collect('email')" name="email"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center my-2 pb-5">
                        <v-btn width="60%" dark color="primary" large :loading="isLoading" @click="sendRecoveryMail">Submit</v-btn>
                    </v-card-actions>
                    <div v-if="requestFailed" class="requestFailed text-center mt-n2 mx-2">
                        <div class="error white--text">{{ error }}</div>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="12" md="8" v-else class="mt-8 mb-8">
                <v-alert type="success" border="left">
                    An email has been sent to {{ email }}. Kindly check your email and follow the instructions. Thank you.
                </v-alert>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            email: '',
            isLoading: false,
            showResetForm: true,
            requestFailed: false,
            error: ''
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        }
    },
    methods: {
        sendRecoveryMail(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    axios.post(this.api + '/send_recovery_password_email', {
                        email: this.email
                    }).then((res)=> {
                        // redirect to email sent msg
                        this.isLoading = false
                        this.showResetForm = false
                    }).catch((err) => {
                        this.isLoading = false
                        this.requestFailed = true
                        if(err.response.status === 501){
                            this.error = 'Error! We did not find that email address in our database.'
                        }else{
                            this.error = "Password recovery request failed. Please ensure your connection is good and try again"
                        }
                    })
                }
            })
        }
    }
}
</script>

<style lang="css" scoped>
   .requestFailed{
       margin-bottom: 10px;
   }
   .error{
       min-height: 3rem;
       padding: 15px;
   }

</style>
