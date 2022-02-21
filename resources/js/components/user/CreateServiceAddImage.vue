<template>
    <v-container>
        <v-row justify="center">
            <v-col cols="12" md="8">
                <v-card light raised elevation="12" min-height="300" class="mx-auto">
                    <v-card-text class="mt-5 pb-5 mx-5">
                        <div class="title black--text lighten-3 mt-5 pb-5">A picture is worth more than a million words! Add a picture of your service/logo/brand for identity.</div>
                        <template v-if="!previewImg">
                            <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Upload Image</v-btn>
                            <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                        </template>
                        <template v-else>
                            <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                            <v-img :src="previewImgUrl" height="200" contain alt="service photo" aspect-ratio="1"></v-img>
                            <v-card-actions class="justify-center mt-4 ml-n3">
                              <v-btn dark color="primary" @click="uploadImg" :loading="isLoading"><v-icon left>cloud</v-icon>Upload Image</v-btn>
                              <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                            </v-card-actions>
                        </template>
                    </v-card-text>
                    <v-card-actions class="pb-5 mx-5" v-if="!previewImg">
                        <v-btn text color="primary" large dark class="bactToService" to="/my-service">No, i don't want to upload image</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="uploadFailed" :timeout="6000" top color="red darken-1 white--text">
            Image upload failed. Please try again and ensure you are uploading a valid image format.
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            isLoading: false,
            previewImg: false,
            previewImgUrl: '',
            image: null,
            uploadFailed: false
        }
    },
    computed: {
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        fullheader(){
            let conf = {
                headers: {
                    Authorization: `Bearer ${this.authUser.token}`,
                },
            }
            return conf
        }
    },
    methods: {
        openUpload(){
            this.$refs.image.click()
        },
        pickImg(e){
            const file = e.target.files[0]
            this.image = file
            this.previewImg = true
            this.previewImgUrl = URL.createObjectURL(file)
        },
        uploadImg(){
            if(this.image !== null){
                this.isLoading = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth/service/add_image`, form, {
                    headers: {
                        "Authorization": `Bearer ${this.authUser.token}`
                    }
                }).then((res) => {
                    this.$router.push('/my-service')
                }).catch(() => {
                    this.uploadFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.previewImg = null
            this.previewImgUrl = ''
        }
    }
}
</script>

<style lang="scss" scoped>
    .bactToService{
        text-decoration: none !important;
    }
</style>
