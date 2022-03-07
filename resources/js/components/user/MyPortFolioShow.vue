<template>
    <v-container>
        <v-row justify="center" class="justify-center mt-5">
            <v-col cols="12">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <template v-if="authUser.service && authUser.service.portfolio">
                <v-col cols="12" md="8">
                    <v-card light raised elevation="12" min-height="300" class="mx-auto">
                        <v-card-title class="primary white--text justify-center title"> My Portfolio</v-card-title>
                        <portfolio-carousel v-if="portfolio && portfolio.files.length > 0" :files="portfolio"></portfolio-carousel>
                        <template v-if="!editForm">
                            <v-card-text class="mt-5 py-3">
                                <div class="title font-weight-bold text-center">{{ portfolio && portfolio.title | capFirstLetter }}</div>
                                <div class="mt-3 body-1 px-3 pt-2"> {{ portfolio && portfolio.detail | capFirstLetter }}</div>
                            </v-card-text>
                            <v-card-actions class="justify-space-around mt-3 pb-5 px-2">
                                <v-btn text color="blue darken-1" @click="editPortfolio"><v-icon left>edit</v-icon><span v-if="$vuetify.breakpoint.mdAndUp">Edit</span></v-btn>
                                <v-btn text color="red darken-2" @click="confirmFileDel = true"><v-icon left>cancel</v-icon><span v-if="$vuetify.breakpoint.mdAndUp">Remove File</span><span v-else>Rmv File</span></v-btn>
                                <v-btn text color="red darken--2" @click="confirmDel = true"><v-icon left>delete_forever</v-icon><span v-if="$vuetify.breakpoint.mdAndUp">Delete Portfolio</span></v-btn>
                            </v-card-actions>
                            <template v-if="files.length < 5">
                                <v-card-actions class="justify-center" v-if="!previewUploads">
                                    <v-btn outlined color="primary lighten--2" class="mb-5" @click="openUpload">Add Images</v-btn>
                                    <input type="file" multiple ref="files" style="display:none" @change.prevent="pickImg" accept="image/*">
                                </v-card-actions>
                                <v-card-text v-else class="centralize mb-8">
                                    <v-card-actions v-if="filesPicked" class="info--text justify-center mb-3 mx-3 font-weight-bold">
                                        **{{ uploads.length }} files have been selected for upload.**
                                    </v-card-actions>
                                    <v-card-actions class="justify-center">
                                        <v-btn text large color="red darken-2" outlined @click="cancelUploads">Cancel</v-btn>
                                        <v-btn dark large color="primary" @click="uploadImgs" :loading="isBusy">Upload Files</v-btn>
                                    </v-card-actions>
                                </v-card-text>
                            </template>
                            <template v-else>
                                <v-alert type="info" class="my-5 text-center">
                                    You have reached the maximum allowed files limit for this portfolio.
                                </v-alert>
                            </template>
                        </template>
                        <template v-else>
                            <v-card-text class="mt-1 py-3">
                                <v-textarea label="Title" rows="1" v-model="edit.title" auto-grow required v-validate="'required|min:5|max:200'" :error-messages="errors.collect('title')" name="title"></v-textarea>
                                <v-textarea label="Details" rows="2" v-model="edit.detail" auto-grow required v-validate="'required|min:10|max:600'" :error-messages="errors.collect('detail')" name="detail"></v-textarea>
                                <v-text-field label="Price" v-model="edit.cost" v-validate="'numeric'" :error-messages="errors.collect('cost')" name="cost"></v-text-field>
                            </v-card-text>
                            <v-card-actions class="justify-center mb-8">
                                <v-btn text large color="red darken--2" @click="editForm = false">Cancel</v-btn>
                                <v-btn large color="primary darken--2" @click="updatePf" :disabled="isLoading"><v-icon large left>save</v-icon>Update</v-btn>
                            </v-card-actions>
                        </template>
                    </v-card>
                </v-col>
            </template>
        </v-row>
        <v-dialog v-model="confirmDel" max-width="450">
          <v-card min-height="150">
              <v-card-title class="subtitle-1 justify-center primary white--text">Delete this portfolio?</v-card-title>
              <v-card-actions class="pb-8 mt-8 justify-center">
                  <v-btn text color="red darken--2" @click="confirmDel = false" width="40%">Cancel</v-btn>
                  <v-btn dark color="primary" :loading="isLoading" @click="deletePf" width="40%">Yes, Delete</v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>
        <v-dialog v-model="confirmFileDel" max-width="450">
          <v-card min-height="150">
              <v-card-title class="subtitle-1 justify-center primary white--text">Remove the portfolio files?</v-card-title>
              <v-card-text class="pb-5 mt-5 justify-center">
                  <div class="mb-3 font-weight-bold">No Of Files: {{ files.length }}</div>
                  <div v-for="(file, index) in files" :key="file.id">
                      <div class="del_imags">
                          <div class="img"><v-img :src="file" alt="portfolio image"></v-img></div>
                          <div class="butn"><v-btn icon small color="red darken-2" @click="delPfFile(file, index)"><v-icon>delete_forever</v-icon></v-btn></div>
                      </div>
                  </div>
              </v-card-text>
              <v-card-actions class="justify-center pb-6">
                  <v-btn color="primary" width="50%" @click="confirmFileDel = false">Cancel</v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>
        <v-snackbar v-model="updateFailed" :timeout="6000" top color="red darken-1 white--text">
            Update failed. Please try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="pfUpdated" :timeout="6000" top color="green darken-1 white--text">
            Your portfolio has been updated successfully.
            <v-btn text color="white--text" @click="pfUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="pfCreatedSuccess" :timeout="6000" top color="green darken-1 white--text">
            Your portfolio has been created successfully.
            <v-btn text color="white--text" @click="pfCreatedSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="delFailed" :timeout="6000" top color="red darken-1 white--text">
            There was error while trying to delete the portfolio. Please try again.
            <v-btn text color="white--text" @click="delFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="filesUploaded" :timeout="6000" top color="green darken-1 white--text">
            The files were successfully uploaded.
            <v-btn text color="white--text" @click="filesUploaded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="maxExceeded" :timeout="6000" top color="red darken-1 white--text">
            You have exceeded your maximum allowable upload limit. Please reduce the number of files to be uploaded.
            <v-btn text color="white--text" @click="maxExceeded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="fileRemoved" :timeout="3000" top color="green darken-1 white--text">
            File removed successfully.
            <v-btn text color="white--text" @click="fileRemoved = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="fileRemovedErr" :timeout="6000" top color="red darken-1 white--text">
            There was an error while trying t remove the file. Pleas try again.
            <v-btn text color="white--text" @click="fileRemovedErr = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>


<script>
export default {
    data(){
        return{
            editForm: false,
            edit: {
                title: '',
                detail: '',
                cost: null
            },
            isLoading: false,
            updateFailed: false,
            pfUpdated: false,
            confirmDel: false,
            delFailed: false,
            uploadPortfolioFiles: false,
            files: [],
            uploads: [],
            uploadBtns: false,
            filesUploaded: false,
            maxExceeded: false,
            previewUploads: false,
            filesPicked: false,
            isBusy: false,
            confirmFileDel: false,
            fileRemoved: false,
            fileRemovedErr: false,
            // pfImgs: []
        }
    },
    computed:{
        authUser(){
            return this.$store.getters.authUser
        },
        portfolio(){
            let ports = this.$store.getters.authUser.service.portfolio
            let port = ports.find(item => item.id == this.$route.params.id)
            return port
        },
        api(){
            return this.$store.getters.api
        },
        pfCreatedSuccess(){
            return this.$store.getters.portfolioCreated
        },
        fullHeader(){
            let Header = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`,
                    "Content-Type": "multipart/form-data"
                }
            }
            return Header;
        },
        header(){
            let Header = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return Header;
        }
    },
    methods: {
        editPortfolio(){
            this.editForm = true
            this.edit.title = this.portfolio.title
            this.edit.detail = this.portfolio.detail
            this.edit.cost = this.portfolio.cost / 100
        },
        updatePf(){
            this.$validator.validateAll().then((isValid) => {
                if (isValid) {
                    this.isLoading = true
                    // this.edit.cost = this.edit.cost * 100
                    axios.post(this.api + `/auth/update_portfolio/${this.portfolio.id}`, {
                        portfolio: this.edit
                    }, this.header).then((res) => {
                        this.isLoading = false
                        this.pfUpdated = true
                        this.$store.dispatch('fetchService')
                        this.editForm = false
                    }).catch((err) => {
                        this.isLoading = false
                        this.updateFailed = true
                    })
                }
            })
        },
        deletePf(){
            this.isLoading = true
            axios.post(this.api + `/auth/del_portfolio/${this.portfolio.id}`, this.header)
            .then((res) => {
                this.isLoading = false
                this.$store.commit('portfolioDeleted')
                this.confirmDel = false
                this.$router.push('/my-portfolio')
            }).catch((err) => {
                this.isLoading = false
                this.delFailed = true
            })
        },
        // deletePf(){
        //     this.isLoading = true
        //     axios.delete(this.api + `/portfolio/${this.portfolio.id}/delete`, this.header)
        //     .then((res) => {
        //         this.isLoading = false
        //         this.$store.commit('portfolioDeleted')
        //         this.confirmDel = false
        //         this.$router.push('/my-portfolio')
        //     }).catch((err) => {
        //         this.isLoading = false
        //         this.delFailed = true
        //     })
        // },
        getPortfolioFiles(){
            this.isLoading = true
            axios.get(this.api + `/auth/get_my_portfolio_files/${this.portfolio.id}`, this.header)
            .then((res) => {
                this.isLoading = false
                this.files = res.data
            }).catch(() =>{
                this.isLoading = false
            })
        },
        openUpload(){
            this.$refs.files.click()
        },
        pickImg(e){
            const files = e.target.files
            if(!files){
                return false
            }
            this.previewUploads = true
            let filesCount = this.files.length
            let uploadAble = parseInt(5 - filesCount)
            if(files.length <= uploadAble){
                for(let i=0; i<files.length; i++){
                    this.uploads.push(files[i])
                }
                this.filesPicked = true
            }else{
                 this.maxExceeded = true
                 this.previewUploads = false
            }
        },
        cancelUploads(){
            this.previewUploads = false
            this.uploads = []
        },
        uploadImgs(){
            this.isBusy = true
            let form = new FormData();
            let self = this
            for(let i=0; i<this.uploads.length; i++){
                let file = self.uploads[i]
                form.append('files[' + i + ']', file)
            }
            axios.post(this.api + `/auth/upload_pf_images/${this.portfolio.id}`, form, this.fullHeader)
            .then((res)=>{
                this.isBusy = false
                this.uploadBtns = false
                this.filesUploaded = true
                this.previewUploads = false
                this.getPfImages()
                this.resetUpload()
            }).catch((err) => {
                this.isLoading = false
            })
        },
        resetUpload(){
            this.uploads = []
            this.$refs.files.value = ''
        },
        delPfFile(file, index){
            axios.post(this.api + `/auth/del_pf_file/${file.id}`, {}, this.header).then((rs) => {
                this.files.splice(index, 1)
                this.fileRemoved = true
            }).catch(() => {
                this.fileRemovedErr = true
            })
        },
        getPfImages(){
            axios.get(this.api + `/auth/get_my_pf_images_from_s3/${this.$route.params.id}`, this.header)
            .then((res) => {
                this.files = res.data
            })
        }
    },
    created(){
        // this.getPortfolioFiles()
        this.getPfImages()
    }
}
</script>

<style lang="scss" scoped>
    .v-btn{
        text-decoration: none;
    }
    .centralize{
        display: flex;
        flex-direction: column;
        margin-left: 20px;
        padding: 10px 15px;
        margin-bottom: 40px;
    }
    .del_imags{
        padding: 0 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;

        .img .v-image{
            height: 60px;
            width: 60px;
            border-radius: 3px;
        }
        .butn{
            margin-right: 30px;
        }
    }
</style>

