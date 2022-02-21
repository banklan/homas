<template>
    <v-container>
        <v-row justify="start">
            <v-col cols="10" md="5">
               <v-text-field placeholder="Search for Locations..." v-model="q" outlined dense append-icon="search" @keyup="searchForLocs"></v-text-field>
           </v-col>
           <v-col cols="11" md="5" offset-md="1">
               <div :class="$vuetify.breakpoint.smAndDown ? 'mt-n6 mb-4 float-right mr-10' : ''">
                    <v-btn v-if="showBtn" dark color="admin" @click="addNewLocDial = true"><v-icon left>add</v-icon>New</v-btn>
                    <!-- <v-btn dark color="admin" :href="`http://localhost:8000/api/clients/export`"><v-icon left>mdi-download</v-icon>Download</v-btn> -->
                    <span v-if="showFilePicker">
                        <v-btn dark color="admin" class="" @click="openUpload"><v-icon left>mdi-upload</v-icon>Upload CSV</v-btn>
                        <input type="file" ref="file" style="display:none" @change.prevent="pickFile" accept=".csv">
                    </span>
                    <span v-else>
                        <span class="pl-4">{{ file.name }}</span>
                        <span>
                            <v-btn elevation="8" dark color="admin" @click="uploadFile" :loading="isUploading"><v-icon left>mdi-upload</v-icon>Upload File</v-btn>
                            <v-btn icon dark color="red darken-2" @click="cancelUpld"><v-icon left>cancel</v-icon></v-btn>
                        </span>
                        <v-alert type="info" class="mt-2">Please ensure your file is in .csv format</v-alert>
                    </span>
                </div>
           </v-col>
        </v-row>
        <v-row justify="start" class="mr-3 mt-n6">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Locations <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="!searchedLocs">
                            <template v-if="locations.length > 0">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>State</th>
                                            <th>Popular</th>
                                            <th>Created </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(loc, i) in locations" :key="loc.id">
                                            <td>{{ loc.id }} </td>
                                            <td width="25%">{{ loc.state }} </td>
                                            <td>{{ loc.is_popular ? 'Popular' : 'Not Pop' }} <span class="ml-2"><admin-location-popular :loc="loc" :index="i" /></span></td>
                                            <td>{{ loc.created_at | moment('DD/MM/YYYY') }}</td>
                                            <td><v-btn small icon color="admin" @click.prevent="openEditDial(loc)"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class='ml-3' @click="delConfirm(loc, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                        </template>
                        <template v-else>
                            <template v-if="searchResult.length > 0">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>State</th>
                                            <th>Popular</th>
                                            <th>Created </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(loc, i) in searchResult" :key="loc.id">
                                            <td>{{ loc.id }} </td>
                                            <td width="25%">{{ loc.state }} </td>
                                            <td>{{ loc.is_popular ? 'Popular' : 'Not Pop' }} <span class="ml-2"><admin-location-popular :loc="loc" :index="i" /></span></td>
                                            <td>{{ loc.created_at | moment('DD/MM/YYYY') }}</td>
                                            <td><v-btn small icon color="admin" @click.prevent="openEditDial(loc)"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class='ml-2' @click="delConfirm(loc, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert type="info" border="left" class="mt-5">
                                    Your search  for {{ q }} returned no location.
                                </v-alert>
                            </template>
                        </template>
                    </v-card-text>
                    <template v-if="!searchedLocs">
                        <div class="my-5" v-if="locations.length > 0" width="100%">
                            <div class="btm_row">
                                <div class="pgnt">
                                    <span class="pl-4">
                                        <v-btn color="primary" @click.prevent="getLocations(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getLocations(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getLocations(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getLocations(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                                    </span>
                                    <span class="pl-6">
                                        Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                    </span>
                                </div>
                                <div class="download_btn">
                                    <v-btn dark color="admin" :href="`http://localhost:8000/api/locations/export`"><v-icon left>mdi-download</v-icon>Download</v-btn>
                                </div>
                            </div>
                        </div>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450" transition="scale-transition">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this location?</v-card-title>
                <v-card-text class="justify-center mt-5 body_text">
                    If you proceed to delete, the location will be deleted permanently.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="delLoc" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="updateDial" max-width="450">
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <v-card min-height="150">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Update Location</v-card-title>
                    <v-card-text class="justify-center mt-5 body_text">
                        <v-text-field label="State" v-model="locToEdit.state" required v-validate="'required|min:3|max:80'" :error-messages="errors.collect('state')" name="state"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="pb-8 justify-center">
                        <v-btn text color="red darken--2" @click="updateDial = false" width="40%">Cancel</v-btn>
                        <v-btn dark color="admin" :loading="isUpdating" @click="updateLoc" width="40%">Update</v-btn>
                    </v-card-actions>
                </v-card>
            </transition>
        </v-dialog>
        <v-dialog v-model="addNewLocDial" max-width="450">
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <v-card min-height="150">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Add New Location</v-card-title>
                    <v-card-text class="justify-center mt-5 body_text">
                        <v-text-field label="State" v-model="newLoc.state" required v-validate="'required|min:3|max:100'" :error-messages="errors.collect('state')" name="state"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="pb-8 justify-center">
                        <v-btn text color="red darken--2" @click="addNewLocDial = false" width="40%">Cancel</v-btn>
                        <v-btn dark color="admin" :loading="isUpdating" @click="addNewLoc" width="40%">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </transition>
        </v-dialog>
        <v-snackbar v-model="LocUpdated" :timeout="4000" top color="green darken-1 white--text">
            The location was updated successfully.
            <v-btn text color="white--text" @click="LocUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="newLocAdded" :timeout="4000" top color="green darken-1 white--text">
            New location has been added successfully.
            <v-btn text color="white--text" @click="newLocAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="addLocFailed" :timeout="6000" top color="red darken-1 white--text">
            Create new location failed. Please ensure you are adding a location that doesn't exist and try again.
            <v-btn text color="white--text" @click="addLocFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="locDeleted" :timeout="4000" top color="green darken-1 white--text">
            A location was deleted successfully.
            <v-btn text color="white--text" @click="locDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="locationStatusUpdated" :timeout="3000" top color="green darken-1 white--text">
            A location status was updated successfully.
            <v-btn text color="white--text" @click="locationStatusUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="4000" top color="red darken-1 white--text">
            Location update failed. Please try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="uploadSuccessful" :timeout="4000" top color="green darken-1 white--text">
            Your file was uploaded successfully.
            <v-btn text color="white--text" @click="uploadSuccessful = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="uploadFailed" :timeout="8000" top color="red darken-1 white--text">
            Your file upload failed. Please ensure that the file you are trying to upload is in csv format and it doesn't contain invalid or locations already existing in the database.
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            locations: [],
            updateDial: false,
            locToEdit: {
                id: null,
                state: ''
            },
            isUpdating: false,
            LocUpdated: false,
            addNewLocDial: false,
            newLoc: {
                state: ''
            },
            newLocAdded: false,
            addLocFailed: false,
            q: '',
            searchedLocs: false,
            searchResult: [],
            locToDel: null,
            locToDelIndex: null,
            confirmDelDial: false,
            isDeleting: false,
            locDeleted: false,
            isToggling: false,
            updateFailed: false,
            file: '',
            showFilePicker: true,
            isUploading: false,
            uploadSuccessful: false,
            uploadFailed: false,
            showBtn: true
        }
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetUpdatedFlashMsg')
        next()
    },
    computed: {
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        locationStatusUpdated(){
            return this.$store.getters.locationStatusUpdated
        }
    },
    methods: {
        getLocations(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_locations`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.locations = res.data.data
                this.total = res.data.total
                this.isLoading = false
                this.pagination = {
                    current_page: res.data.current_page,
                    last_page: res.data.last_page,
                    first_link: res.data.first_page_url,
                    last_link: res.data.last_page_url,
                    prev_link: res.data.prev_page_url,
                    next_link: res.data.next_page_url,
                }
            })
        },
        openEditDial(loc){
            this.updateDial = true
            this.locToEdit.id = loc.id
            this.locToEdit.state = loc.state
         },
        updateLoc(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_location/${this.locToEdit.id}`, {
                        loc: this.locToEdit
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.updateDial = false
                        this.LocUpdated = true
                        let updated = this.locations.find(item => item.id === res.data.id)
                        updated.state = res.data.state
                    }).catch(() =>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        addNewLoc(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/add_new_loc`, {
                        loc: this.newLoc
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.addNewLocDial = false
                        this.locations.unshift(res.data)
                        this.newLocAdded = true
                        this.total++
                        this.newLoc.state = ''
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                    }).catch(()=>{
                        this.isUpdating = false
                        this.addNewLocDial = false
                        this.addLocFailed = true
                    })
                }
            })
        },
        delConfirm(loc, i){
            this.confirmDelDial = true
            this.locToDel = loc
            this.locToDelIndex = i
        },
        delLoc(){
            this.isDeleting = true
            let loc = this.locToDel
            let ind = this.locToDelIndex
            axios.post(this.api + `/auth-admin/admin_delete_loc/${loc.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.locations.splice(ind, 1)
                this.total -=
                this.locDeleted = true
            })
        },
        searchForLocs(){
            if(this.q.length > 0){
                this.searchedLocs = true
                axios.post(this.api +`/auth-admin/admin_search_for_locs`, {
                    q: this.q.trim()
                }, this.adminHeaders).then((res) => {
                    this.searchResult = res.data
                    this.total = res.data.length
                })
            }else{
                this.searchedLocs = false
                this.getLocations()
            }
        },
        openUpload(){
            this.showBtn = false
            this.$refs.file.click()
        },
        pickFile(e){
            const file = e.target.files[0]
            this.file = file
            this.showFilePicker = false
        },
        uploadFile(){
            if(this.file !== null){
                this.isUploading = true
                let form = new FormData();
                form.append('file', this.file)

                axios.post(this.api + `/auth-admin/locations/import/`, form, this.adminHeaders).then((res) => {
                    this.isUploading = false
                    this.showFilePicker = true
                    this.showBtn = true
                    this.uploadSuccessful = true
                    this.getLocations()
                }).catch(() => {
                    this.isUploading = false
                    this.uploadFailed = true
                })
            }
        },
        downloadClient(){
            axios.get(this.api + '/clients/export').then((res) => {
            })
        },
        cancelUpld(){
            this.showFilePicker = true
            this.showBtn = true
        }
    },
    created() {
        this.getLocations()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll{
        overflow-x: scroll !important;
    }
    table tr{
        cursor: pointer;
    }
    .v-btn, a{
        text-decoration: none !important;
    }
    table tbody tr td, table tbody tr th, table thead tr th{
        white-space: nowrap !important;
    }
    .btm_row{
        display: flex !important;
        justify-content: space-between !important;
        align-items: center;
        padding: 10px 20px;
    }
    @media screen and (max-width: 782px){
        .btm_row{
            flex-direction: column;

        }
        .pgnt{
            margin-bottom: 17px;
            padding: 5px 8px;
        }
    }
</style>

