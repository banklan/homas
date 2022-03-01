<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="12">
                <v-btn rounded color="admin lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4" justify="start">
            <v-col cols="12" md="6">
                <v-card light raised elevation="8" min-height="200">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Add Bulk Locations</v-card-title>
                    <v-card-text class="body-1 mt-5 px-3">
                        <v-text-field label="State" v-model="newState.name" placeholder="Enter state here.." required v-validate="'required|min:3|max:50'" :error-messages="errors.collect('name')" name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn dark large :loading="isAdding" color="admin" @click="addState">Add Location</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card light raised elevation="8" min-height="100">
                    <v-card-title class="admin justify-center white--text">Locations<span v-if="locations.length > 0"><v-chip color="admin lighten-2 white--text ml-1">{{ locations.length }}</v-chip></span></v-card-title>
                    <v-card-text class="mt-4">
                        <template v-if="locations.length > 0">
                            <table class="table table-condensed table-striped table-hovered">
                                <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(loc, index) in locations" :key="loc.state">
                                        <td width="50%">{{ loc.name | capFirstLetter }}</td>
                                        <td><v-btn small icon dark color="blue darken-1" @click="openEditDial(loc, index)"><v-icon>edit</v-icon></v-btn>
                                            <v-btn small icon dark color="red darken-1" class="ml-2" @click="removeState(index)"><v-icon>delete_forever</v-icon></v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="mt-3 info--text pa-2">You have not added any states.</div>
                        </template>
                    </v-card-text>
                    <v-card-actions v-if="locations.length > 0" class="justify-center pb-6">
                        <v-btn text color="red darken-1" large @click="clearLocsDial = true">Clear</v-btn>
                        <v-btn admin white--text dark large :loading="isSubmitting" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="editDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Edit Location</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="State" v-model="locToEdit.name" required v-validate="'required|min:3|max:50'" :error-messages="errors.collect('updt.state')" name="state" data-vv-scope="updt"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="editDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isUpdating" @click="updateLocation" width="40%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="clearLocsDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Clear Locations</v-card-title>
                <v-card-text class="mt-5 body_text">
                    All locations will be cleared if you proceed.
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="clearLocsDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isClearing" @click="clearLocs" width="40%">Clear</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="stateExists" :timeout="4000" top dark color="red darken-1">
            <v-alert type="error" border="left">
                Error! The state you are trying to add already exist.
            </v-alert>
            <v-btn text color="white--text" @click="stateExists = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="stateRemoved" :timeout="3000" top dark color="green darken-1">
                A state has been deleted from your list.
            <v-btn text color="white--text" @click="stateRemoved = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="locsCleared" :timeout="4000" top dark color="green darken-1">
                Your location list has been cleared.
            <v-btn text color="white--text" @click="locsCleared = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createFail" :timeout="6000" top dark color="red darken-1">
                There was an error while trying to add the bulk locations. Please ensure none of the locations exist in the database and try again.
                <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isAdding: false,
            newState: {
                name: ''
            },
            isSubmitting: false,
            locations: [],
            stateExists: false,
            stateRemoved: false,
            editDial: false,
            locToEditIndex: null,
            locToEdit:{
                name: ''
            },
            isUpdating: false,
            clearLocsDial: false,
            isClearing: false,
            locsCleared: false,
            createFail: false
        }
    },
    computed:{
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
    },
    methods: {
        addState(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.locations.unshift(this.newState)
                    let locs = JSON.parse(localStorage.getItem('newLocs'))
                    if(!locs){
                        localStorage.setItem('newLocs', JSON.stringify(this.locations))
                    }else{
                        if(locs.find(obj => obj.name === this.newState.name)){
                            this.stateExists = true
                        }else{
                            locs.unshift(this.newState)
                            localStorage.setItem('newLocs', JSON.stringify(locs))
                        }
                    }
                    this.locations = JSON.parse(localStorage.getItem('newLocs'))
                    this.resetField()
                }
            })
        },
        resetField(){
            this.newState.name = ''
            this.$validator.reset()
        },
        removeState(index){
            this.locations.splice(index, 1)
            localStorage.setItem('newLocs', JSON.stringify(this.locations))
            this.stateRemoved = true
        },
        openEditDial(loc, ind){
            this.editDial = true
            this.locToEdit.name = loc.name
            this.locToEditIndex = ind
        },
        updateLocation(){
            this.$validator.validateAll('updt').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    this.locations.splice(this.locToEditIndex, 1)
                    this.locations.unshift(this.locToEdit)
                    this.editDial = false
                    this.locToEdit = {}
                    this.locToEditIndex = null
                    localStorage.setItem('newLocs', JSON.stringify(this.locations))
                    this.isUpdating = false
                }
            })
        },
        clearLocs(){
            this.isClearing = true
            localStorage.removeItem('newLocs')
            this.locations = []
            this.isClearing = false
            this.clearLocsDial = false
            this.locsCleared = true
        },
        submit(){
            if(this.locations.length > 0){
                this.isSubmitting = true
                axios.post(this.api + '/auth-admin/admin_create_bulk_locations', {
                    locations: this.locations
                }, this.adminHeaders).then((res) => {
                    this.isSubmitting = false
                    this.$store.commit('bulkLocationsCreated')
                    this.locations = []
                    localStorage.removeItem('newLocs')
                    this.$router.push({name: 'AdminLocationList'})
                }).catch(()=>{
                    this.isSubmitting = false
                    this.createFail = true
                })
            }
        },
        loadAddedLocations(){
            let addedLocs = JSON.parse(localStorage.getItem('newLocs'))
            if(addedLocs){
                this.locations = addedLocs
            }
        }
    },
    created(){
        this.loadAddedLocations()
    }
}
</script>
