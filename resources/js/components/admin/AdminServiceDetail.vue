<<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="12" md="3">
                <v-btn rounded color="admin" dark elevation="4" left :to="{name: 'AdminServicesList'}"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row justify="start" class="mt-5">
            <v-progress-circular v-if="isLoading" indeterminate color="accent" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
            <template v-else>
                <template v-if="service">
                    <v-col cols="12" md="7">
                        <div class="title mt-4 mb-2 text-truncate">{{ service.title }}</div>
                        <v-card light raised elevation="12" min-height="350" class="mx-auto">
                            <v-img v-if="serviceImg" :src="serviceImg" width="100%" height="350" transition="scale-transition"></v-img>
                            <v-img v-else src="/images/shared/no-image.png" width="100%" height="350" transition="scale-transition"></v-img>
                            <v-card-text class="body-2 body_text lighten-2 mt-3 px-6 pb-5">
                                <div class="font-weight-black mb-3">Description:</div>
                                <span>{{ service.description }}</span>
                                <div v-if="service.highlight" class="font-weight-black mt-5 mb-2">Highlights:</div>
                                <span>{{ service.highlight }}</span>
                                <div v-if="service.keystrength" class="font-weight-black mt-5 mb-2">Highlights:</div>
                                <span>{{ service.keystrength }}</span>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mx-auto mt-5">
                            <v-card-title class="subtitle-1 admin white--text justify-center">Portfolio</v-card-title>
                            <v-card-text class="body_text mt-3">
                                <v-list two-line nav v-if="service">
                                    <v-list-item-group dense class="ml-n1">
                                        <template v-if="service.portfolio.length > 0">
                                            <div v-for="(pf, index) in service.portfolio" :key="pf.id">
                                                <v-list-item class="mb-n5 mt-n5" :to="{name: 'AdminPortfolioShow', params: {id: pf.id, slug: pf.slug}}">
                                                    <v-list-item-content class="mt-1">
                                                        <v-list-item-subtitle class="subtitle-1 body_text text-truncate">{{ pf.title }}</v-list-item-subtitle>
                                                    </v-list-item-content>
                                                </v-list-item>
                                                <v-divider v-if="index < service.portfolio.length - 1"></v-divider>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <v-alert type="info" class="mt-5">
                                                <div>This user has not created any portfolio for their service yet.</div>
                                            </v-alert>
                                        </template>
                                    </v-list-item-group>
                                </v-list>
                            </v-card-text>
                        </v-card>
                        <v-card light raised elevation="12" min-height="150" class="mx-auto mt-5">
                            <v-card-title class="subtitle-1 admin white--text justify-center">Reviews</v-card-title>
                            <v-card-text class="body_text mt-5">
                                <v-list two-line nav v-if="service">
                                    <v-list-item-group dense color="primary" class="ml-2">
                                        <template v-if="service.review.length > 0">
                                            <div v-for="rev in service.review.slice(0, 4)" :key="rev.id">
                                                <admin-review-item :review="rev"></admin-review-item>
                                                <v-divider></v-divider>
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="ml-2 mt-3">This service has no reviews yet.</div>
                                        </template>
                                    </v-list-item-group>
                                </v-list>
                            </v-card-text>
                            <v-card-actions v-if="service.review.length > 0" class="justify-center mt-n3 pb-6">
                                <v-btn text color="admin" :to="{name: 'AdminServiceReviewList', params: {service: service.id}}">All Reviews</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                    <v-col cols="12" md="5" class="mt-7">
                        <v-card light raised elevation="12" min-height="400" class="mx-auto mt-7 scroll">
                            <v-card-title class="subtitle-1 admin white--text justify-center">Service Details</v-card-title>
                            <v-card-text class="px-3 py-3">
                                <table class="table table-condensed table-striped">
                                    <tr>
                                        <th width="25%" style="border-top: none">Author: </th>
                                        <td style="border-top: none">{{ service && service.user.fullname }} </td>
                                    </tr>
                                    <tr>
                                        <th>Position: </th>
                                        <td>{{ service.position }} </td>
                                    </tr>
                                    <tr>
                                        <th>Published: </th>
                                        <td>{{ service.published }} </td>
                                    </tr>
                                    <tr>
                                        <th>Category: </th>
                                        <td>{{ service.category.name }} </td>
                                    </tr>
                                    <tr>
                                        <th>Experience(years): </th>
                                        <td>{{ service.experience_years }} </td>
                                    </tr>
                                    <tr>
                                        <th>Email: </th>
                                        <td>{{ service.email }} </td>
                                    </tr>
                                    <tr>
                                        <th>Phone No: </th>
                                        <td>{{ service.phone }} </td>
                                    </tr>
                                    <tr>
                                        <th>Service Address: </th>
                                        <td>{{ service.address }} </td>
                                    </tr>
                                    <tr>
                                        <th>Service City: </th>
                                        <td class="text-capitalize">{{ service.city }} </td>
                                    </tr>
                                    <tr>
                                        <th>Service Location: </th>
                                        <td>{{ service.location.state }} </td>
                                    </tr>
                                    <tr>
                                        <th>Approve Status: </th>
                                        <td>{{ service.is_approved ? 'Approved' : 'Not Approved' }} &nbsp;<v-btn small text color="primary" @click="toggleApprovedDial = true">Update</v-btn></td>
                                    </tr>
                                    <tr>
                                        <th>Plan: </th>
                                        <td>{{ service.is_premium ? 'Premium' : 'Regular' }} &nbsp;<v-btn small text color="primary" @click="toggleIsPremiumDial = true">Update</v-btn></td>
                                    </tr>
                                    <tr>
                                        <th>Feature Status: </th>
                                        <td>{{ service.is_featured ? 'Featured' : 'Not Featured' }} &nbsp;<v-btn small text color="primary" @click="toggleIsFeaturedDial = true">Update</v-btn></td>
                                    </tr>
                                    <tr v-if="service.facebook">
                                        <th>Facebook: </th>
                                        <td ><v-btn text class="text-lowercase primary--text ml-n8" :href="`https://facebook.com/${service.facebook}`">https://www.facebook.com/{{ service.facebook }}</v-btn></td>
                                    </tr>
                                    <tr v-if="service.instagram">
                                        <th>Instagram: </th>
                                        <td>{{ service.instagram }} </td>
                                    </tr>
                                    <tr v-if="service.twitter">
                                        <th>Twitter: </th>
                                        <td>{{ service.twitter }} </td>
                                    </tr>
                                    <tr v-if="service.website">
                                        <th>Website: </th>
                                        <td>www.{{ service.website }} </td>
                                    </tr>
                                    <tr>
                                        <th>View Count: </th>
                                        <td>{{ service.views }} </td>
                                    </tr>
                                    <tr>
                                        <th>Ratings: </th>
                                        <td>{{ service.ratings }} </td>
                                    </tr>
                                </table>
                            </v-card-text>
                            <v-card-actions class="justify-center pb-5 px-3">
                                <v-btn icon color="admin lighten-3" :to="{name: 'AdminServiceUpdate', params: {id: service.id}}"><v-icon>edit</v-icon></v-btn>
                                <v-btn icon color="red darken-2" @click="confirmDelDial = true"><v-icon>delete_forever</v-icon></v-btn>
                                <v-btn icon color="admin" :href="`http://localhost:8000/api/service/${service.id}/export`"><v-icon left>mdi-download</v-icon></v-btn>
                            </v-card-actions>
                        </v-card>
                        <v-card light raised elevation="12" min-height="100" class="mx-auto mt-7">
                            <v-card-title class="subtitle-1 admin white--text justify-center">Service Image</v-card-title>
                            <v-card-text class="mt-5">
                                <template v-if="!previewImg">
                                    <v-btn outlined color="admin" class="mb-5" @click="openUpload">Change Image</v-btn>
                                    <input type="file" ref="image" style="display:none" @change.prevent="pickImg" accept="image/*">
                                </template>
                                <template v-else>
                                    <v-card-subtitle class="text-center subtitle-1">Preview Uploaded Image</v-card-subtitle>
                                    <v-img :src="previewImgUrl" height="180" contain alt="service photo" aspect-ratio="1"></v-img>
                                    <v-card-actions class="justify-center mt-4 ml-n3">
                                        <v-btn dark color="primary" @click="uploadImg" :loading="isUpdating">Upload Image</v-btn>
                                        <v-btn text small @click="removeImg"><v-icon color="red darken--2">delete</v-icon></v-btn>
                                    </v-card-actions>
                                </template>
                            </v-card-text>
                            <v-card-text class="mt-n8" v-if="service.image">
                                <v-btn text color="red darken-2" @click="deleteImgDial = true"><v-icon left>delete_forever</v-icon>Delete Image</v-btn>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </template>
                <template v-else>
                    <v-alert type="warning" border="left">The service is not avalilable.</v-alert>
                </template>
            </template>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin justify-center white--text">Delete this service?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    If you proceed to delete, this service will be deleted irrecoverably along with the portfolio, reviews etc.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isLoading" @click="deleteService" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteImgDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this service image?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    If you proceed to delete, you will not be able to recover it again.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="deleteImgDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="deleteImg" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="toggleApprovedDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Change Approval Status?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    Change the approval status of this service
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="toggleApprovedDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isTogglingApproved" @click="toggleApproved" width="40%">Yes, Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="toggleIsPremiumDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Change Service Plan?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    Change the plan of this service?
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="toggleIsPremiumDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isTogglingPremium" @click="togglePremiumStatus" width="40%">Change Plan</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="toggleIsFeaturedDial" max-width="450">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Feature Service?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    Feature this service? Featured services will appear at the front page of the site and other catchy call-to-actions.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="toggleIsFeaturedDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isTogglingFeatured" @click="toggleIsFeatured" width="40%">Yes, Feature</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar :value="adminUpdated" :timeout="4000" top color="green darken-1 white--text">
            Service was updated successfully.
            <v-btn text color="white--text" @click="adminUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top color="red darken-1 white--text">
            Image update failed. Please ensure you are uploading a valid image.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="imageUpdated" :timeout="4000" top color="green darken-1 white--text">
            Image updated successfully.
            <v-btn text color="white--text" @click="imageUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="serviceImgDeleted" :timeout="4000" top color="green darken-1 white--text">
            Service Image deleted successfully.
            <v-btn text color="white--text" @click="serviceImgDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="serviceImgDelFail" :timeout="5000" top color="red darken-1 white--text">
            Service Image delete failed. Please try again.
            <v-btn text color="white--text" @click="serviceImgDelFail = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="reviewStatusChanged" :timeout="4000" top dark color="green darken-2">
            The review is_approved status was changed.
            <v-btn text color="white--text" @click="reviewStatusChanged = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            id: this.$route.params.id,
            slug: this.$route.params.slug,
            isLoading: false,
            service: null,
            miniRev: false,
            author: null,
            isTogglingApproved: false,
            isTogglingPremium: false,
            isTogglingFeatured: false,
            confirmDelDial: false,
            previewImg: false,
            previewImgUrl: '',
            image: null,
            updateFailed: false,
            imageUpdated: false,
            isUpdating: false,
            deleteImgDial: false,
            serviceImgDeleted: false,
            toggleApprovedDial: false,
            toggleIsPremiumDial: false,
            toggleIsFeaturedDial: false,
            serviceImgDelFail: false,
            isDeleting: false,
            serviceImg: null
        }
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        headers(){
            let authHeader = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return authHeader;
        },
        adminUpdated(){
            return this.$store.getters.adminUpdated
        },
        reviewStatusChanged(){
            return this.$store.getters.reviewStatusChanged
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    methods: {
        getService(){
            this.isLoading = true
            axios.get(this.api + `/auth-admin/get_service/${this.id}`, this.headers).then((res) => {
                this.isLoading = false
                this.service = res.data
                this.getServiceImage()
            })
        },
        toggleApproved(){
            this.isTogglingApproved = true
            axios.post(this.api + `/auth-admin/toggle_is_approved/${this.id}`, {}, this.headers).then((res) =>{
                this.isTogglingApproved = false
                this.toggleApprovedDial = false
                this.service.is_approved = res.data
            })
        },
        togglePremiumStatus(){
            this.isTogglingPremium = true
            axios.post(this.api + `/auth-admin/toggle_is_premium/${this.id}`, {}, this.headers).then((res) =>{
                this.isTogglingPremium = false
                this.toggleIsPremiumDial = false
                this.service.is_premium = res.data
            })
        },
        toggleIsFeatured(){
            this.isTogglingFeatured = true
            axios.post(this.api + `/auth-admin/toggle_is_featured/${this.id}`, {}, this.headers).then((res) =>{
                this.toggleIsFeaturedDial = false
                this.isTogglingFeatured = false
                this.service.is_featured = res.data
            })
        },
        deleteService(){
            this.isLoading = true
            axios.post(this.api + `/auth-admin/admin_delete_service/${this.id}`, {}, this.headers)
                 .then((res) => {
                     this.$router.push('/admin/services')
                 })
        },
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
                this.isUpdating = true
                let form = new FormData();
                form.append('image', this.image)

                axios.post(this.api + `/auth-admin/update_service_image/${this.id}`, form, this.headers).then((res) => {
                    this.isUpdating = false
                    this.service.image = res.data.image
                    this.imageUpdated = true
                    this.removeImg()
                }).catch(() => {
                    this.isUpdating = false
                    this.updateFailed = true
                })
            }
        },
        removeImg(){
            this.image = null
            this.previewImg = null
            this.previewImgUrl = ''
        },
        deleteImg(){
            this.isDeleting = true
            axios.post(this.api + `/auth-admin/delete_service_image/${this.id}`, {}, this.headers)
            .then((res) => {
                this.isDeleting = false
                this.service.image = null
                this.deleteImgDial = false
                this.serviceImgDeleted = true
            }).catch(() => {
                this.isDeleting = false
                this.serviceImgDelFail = true
            })
        },
        getServiceImage(){
            axios.get(this.api + `/auth-admin/admin_get_service_image/${this.$route.params.id}`, this.headers)
            .then((res) => {
                this.serviceImg = res.data
            })
        }
    },
    created(){
        this.getService()
    }
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none !important;
    }
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tbody tr td, table tbody tr th{
        white-space: nowrap !important;
    }
</style>
