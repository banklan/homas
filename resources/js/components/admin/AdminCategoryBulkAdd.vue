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
                    <v-card-title class="subtitle-1 admin white--text justify-center">Add Bulk Categories</v-card-title>
                    <v-card-text class="body-1 mt-5 px-3">
                        <v-text-field label="Category" v-model="newCat.name" placeholder="Enter category here.." required v-validate="'required|min:3|max:50'" :error-messages="errors.collect('name')" name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="justify-center pb-6">
                        <v-btn dark large :loading="isAdding" color="admin" @click="addCat">Add Category</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            <v-col cols="12" md="6">
                <v-card light raised elevation="8" min-height="100">
                    <v-card-title class="admin justify-center white--text">Categories<span v-if="categories.length > 0"><v-chip color="admin lighten-2 white--text ml-1">{{ categories.length }}</v-chip></span></v-card-title>
                    <v-card-text class="mt-4">
                        <template v-if="categories.length > 0">
                            <table class="table table-condensed table-striped table-hovered">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(cat, index) in categories" :key="cat.name">
                                        <td width="75%">{{ cat.name | capFirstLetter }}</td>
                                        <td><v-btn small icon dark color="blue darken-1" @click="openEditDial(cat, index)"><v-icon>edit</v-icon></v-btn>
                                            <v-btn small icon dark color="red darken-1" class="ml-2" @click="removeCat(index)"><v-icon>delete_forever</v-icon></v-btn>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <div class="mt-3 info--text pa-2">You have not added any category.</div>
                        </template>
                    </v-card-text>
                    <v-card-actions v-if="categories.length > 0" class="justify-center pb-6">
                        <v-btn text color="red darken-1" large @click="clearCatsDial = true">Clear</v-btn>
                        <v-btn admin white--text dark large :loading="isSubmitting" @click="submit">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="editDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Edit Category</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Category" v-model="catToEdit.name" required v-validate="'required|min:3|max:50'" :error-messages="errors.collect('updt.cat')" name="cat" data-vv-scope="updt"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="editDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isUpdating" @click="updateCategory" width="40%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="clearCatsDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 admin white--text justify-center">Clear Categories?</v-card-title>
                <v-card-text class="mt-5 body_text">
                    All categories will be cleared if you proceed.
                </v-card-text>
                <v-card-actions class="pb-8 mt-2 justify-center">
                    <v-btn text color="red darken--2" @click="clearCatsDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isClearing" @click="clearCats" width="40%">Clear</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="catExists" :timeout="4000" top dark color="red darken-1">
                Error! The category you are trying to add already exist.
            <v-btn text color="white--text" @click="catExists = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="catRemoved" :timeout="3000" top dark color="green darken-1">
                A category has been deleted from your list.
            <v-btn text color="white--text" @click="catRemoved = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="catsCleared" :timeout="4000" top dark color="green darken-1">
                Your category list has been cleared.
            <v-btn text color="white--text" @click="catsCleared = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createFail" :timeout="6000" top dark color="red darken-1">
                There was an error while trying to submit the bulk categories. Please ensure none of the categories already exist in the database and try again.
                <v-btn text color="white--text" @click="createFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isAdding: false,
            newCat: {
                name: ''
            },
            isSubmitting: false,
            categories: [],
            catExists: false,
            catRemoved: false,
            editDial: false,
            catToEditIndex: null,
            catToEdit:{
                name: ''
            },
            isUpdating: false,
            clearCatsDial: false,
            isClearing: false,
            catsCleared: false,
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
        addCat(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.categories.unshift(this.newCat)
                    let cats = JSON.parse(localStorage.getItem('newCats'))
                    if(!cats){
                        localStorage.setItem('newCats', JSON.stringify(this.categories))
                    }else{
                        if(cats.find(obj => obj.name === this.newCat.name)){
                            this.catExists = true
                        }else{
                            cats.unshift(this.newCat)
                            localStorage.setItem('newCats', JSON.stringify(cats))
                        }
                    }
                    this.categories = JSON.parse(localStorage.getItem('newCats'))
                    this.resetField()
                }
            })
        },
        resetField(){
            this.newCat.name = ''
            this.$validator.reset()
        },
        removeCat(index){
            this.categories.splice(index, 1)
            localStorage.setItem('newCats', JSON.stringify(this.categories))
            this.catRemoved = true
        },
        openEditDial(cat, ind){
            this.editDial = true
            this.catToEdit.name = cat.name
            this.catToEditIndex = ind
        },
        updateCategory(){
            this.$validator.validateAll('updt').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    this.categories.splice(this.catToEditIndex, 1)
                    this.categories.unshift(this.catToEdit)
                    this.editDial = false
                    this.catToEdit = {}
                    this.catToEditIndex = null
                    localStorage.setItem('newCats', JSON.stringify(this.categories))
                    this.isUpdating = false
                }
            })
        },
        clearCats(){
            this.isClearing = true
            localStorage.removeItem('newCats')
            this.categories = []
            this.isClearing = false
            this.clearCatsDial = false
            this.catsCleared = true
        },
        submit(){
            if(this.categories.length > 0){
                this.isSubmitting = true
                axios.post(this.api + '/auth-admin/admin_create_bulk_categories', {
                    categories: this.categories
                }, this.adminHeaders).then((res) => {
                    this.isSubmitting = false
                    this.$store.commit('bulkCategoriesCreated')
                    this.categories = []
                    localStorage.removeItem('newCats')
                    this.$router.push({name: 'AdminCategoriesList'})
                }).catch(()=>{
                    this.isSubmitting = false
                    this.createFail = true
                })
            }
        },
        loadAddedCategories(){
            let addedCats = JSON.parse(localStorage.getItem('newCats'))
            if(addedCats){
                this.categories = addedCats
            }
        }
    },
    created(){
        this.loadAddedCategories()
    }
}
</script>
