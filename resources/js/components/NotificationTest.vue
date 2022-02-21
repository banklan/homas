<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card elevation="12" light min-height="300">
                    <v-card-title class="primary white--text subtitle-1 justify-center">Notification Test</v-card-title>
                    <v-card-text class="mt-5 mx-5">
                        <v-text-field label="Name" v-model="name" required></v-text-field>
                    </v-card-text>
                    <v-card-text v-if="msgs.length > 0">
                        <div v-for="msg in msgs" :key="msg.id">
                           <h3>{{ msg }}</h3>
                        </div>
                    </v-card-text>
                    <v-card-actions class="justify-center">
                        <v-btn large dark color="primary" :loading="isLoading" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="submitFailed" :timeout="3000" top color="red darken-1 white--text">
            There was an error while trying to submit. Please try again.
            <v-btn text color="white--text" @click="submitFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="submitted" :timeout="3000" top color="green darken-1 white--text">
            Your message <strong>{{ resp && resp.notif.name }}</strong> was submitted!!
            <v-btn text color="white--text" @click="submitted = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            name: '',
            submitSuccessful: false,
            submitFailed: false,
            msgs: [],
            submitted: false,
            resp: null
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        notif(){
            return this.$store.getters.adminEnquiryNotif
        }
    },
    methods:{
        submit(){
            this.isLoading = true
            axios.post(this.api + '/send-notification', {
                name: this.name
            }).then((res) => {
                this.isLoading = false
                this.name = ''
                this.submitSuccessful = true
            }).catch(() => {
                this.isLoading =  false
                this.submitFailed = true
            })
        },
        listenForEvent(){
            Echo.channel('notif')
                .listen('TestNameSaved', (msg) => {
                    // console.log("received data from pusher", msg)
                    if(msg){
                        this.submitted = true
                        this.resp = msg
                    }

                })
        },
        listen(){
            Echo.channel('notif')
                .listen('TestNameSaved', (e) => {
                    console.log(e.notif.name)
                })
        }
    },
}
</script>
