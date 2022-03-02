<template>
    <v-container>
        <v-row>
            <v-col cols="10" md="5">
               <v-text-field placeholder="Search for Categories..." v-model="q" outlined dense append-icon="search" clearable @keyup="searchForCats"></v-text-field>
           </v-col>
           <v-col cols="11" md="5" offset-md="1">
                <div :class="$vuetify.breakpoint.smAndDown ? 'mt-n6 mb-4 mr-5' : ''">
                    <v-btn v-if="showBtn" dark color="admin" @click="addNewCatDial = true"><v-icon left>add</v-icon>New</v-btn>
                    <v-btn v-if="showBtn" dark color="secondary darken-2" :to="{name: 'AdminCategoryBulkAdd'}"><v-icon left>add</v-icon>Bulk</v-btn>
                    <span v-if="showFilePicker" :class="$vuetify.breakpoint.smAndDown ? 'mt-2 pt-2' : ''">
                        <v-btn dark color="admin" class="" @click="openUpload"><v-icon left>mdi-upload</v-icon>Upload CSV</v-btn>
                        <input type="file" ref="file" style="display:none" @change.prevent="pickFile" accept=".csv">
                    </span>
                    <span v-else>
                        <span class="px-4">{{ file.name }}</span>
                        <span>
                            <v-btn elevation="8" dark color="admin" @click="uploadFile" :loading="isUploading"><v-icon left>mdi-upload</v-icon>Upload File</v-btn>
                            <v-btn icon dark color="red darken-2" class="ml-2" @click="cancelUpld"><v-icon left>cancel</v-icon></v-btn>
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
                    <v-card-title class="subtitle-1 admin white--text justify-center">Categories <v-chip class="ml-1" dark color="admin lighten-2">{{ total }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="!searchedCats">
                            <template v-if="categories.length > 0">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Created </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cat, i) in categories" :key="cat.id">
                                            <td>{{ cat.id }} </td>
                                            <td width="50%">{{ cat.name }} </td>
                                            <td>{{ cat.created_at | moment('DD/MM/YYYY') }}</td>
                                            <td><v-btn small icon color="admin" @click.prevent="openEditDial(cat)"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class='ml-2' @click="delConfirm(cat, i)"><v-icon>delete_forever</v-icon></v-btn></td>
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
                                            <th>Name</th>
                                            <th>Created </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(cat, i) in searchResult" :key="cat.id">
                                            <td>{{ cat.id }} </td>
                                            <td width="50%">{{ cat.name }} </td>
                                            <td>{{ cat.created_at | moment('DD/MM/YYYY') }}</td>
                                            <td><v-btn small icon color="admin" @click.prevent="openEditDial(cat)"><v-icon>edit</v-icon></v-btn><v-btn small icon color="red darken-2" class='ml-2' @click="delConfirm(cat, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </template>
                            <template v-else>
                                <v-alert type="info" border="left" class="mt-5">
                                    Your search  for {{ q }} returned no categories.
                                </v-alert>
                            </template>
                        </template>
                    </v-card-text>
                    <template v-if="!searchedCats">
                        <v-card-actions class="my-5" v-if="categories.length > 0" width="100%">
                            <div class="btm_row">
                                <div class="pgnt">
                                    <span class="pl-4">
                                        <v-btn color="primary" @click.prevent="getCategories(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getCategories(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getCategories(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                        <v-btn color="primary" @click.prevent="getCategories(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                                    </span>
                                    <span class="pl-6">
                                        Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                                    </span>
                                </div>
                                <div class="download_btn">
                                    <v-btn dark color="admin" :href="`http://localhost:8000/api/categories/export`"><v-icon left>mdi-download</v-icon>Download</v-btn>
                                </div>
                            </div>
                        </v-card-actions>
                    </template>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="confirmDelDial" max-width="450" transition="scale-transition">
            <v-card min-height="150">
                <v-card-title class="subtitle-1 justify-center admin white--text">Delete this category?</v-card-title>
                <v-card-text class="justify-center mt-5 body_text">
                    If you proceed to delete, the category will be deleted permanently.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="40%">Cancel</v-btn>
                    <v-btn dark color="admin" :loading="isDeleting" @click="delCat" width="40%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="updateDial" max-width="450">
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <v-card min-height="150">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Update category</v-card-title>
                    <v-card-text class="justify-center mt-5 body_text">
                        <v-text-field label="Name" v-model="catToEdit.name" required v-validate="'required|min:3|max:120'" :error-messages="errors.collect('name')" name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="pb-8 justify-center">
                        <v-btn text color="red darken--2" @click="updateDial = false" width="40%">Cancel</v-btn>
                        <v-btn dark color="admin" :loading="isUpdating" @click="updateCat" width="40%">Save Update</v-btn>
                    </v-card-actions>
                </v-card>
            </transition>
        </v-dialog>
        <v-dialog v-model="addNewCatDial" max-width="450">
            <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn" leave-active-class="animate__animated animate__fadeOut">
                <v-card min-height="150">
                    <v-card-title class="subtitle-1 justify-center admin white--text">Add New Category</v-card-title>
                    <v-card-text class="justify-center mt-5 body_text">
                        <v-text-field label="Name" v-model="newCat.name" required v-validate="'required|min:3|max:100'" :error-messages="errors.collect('name')" name="name"></v-text-field>
                    </v-card-text>
                    <v-card-actions class="pb-8 justify-center">
                        <v-btn text color="red darken--2" @click="addNewCatDial = false" width="40%">Cancel</v-btn>
                        <v-btn dark color="admin" :loading="isUpdating" @click="addNewCat" width="40%">Submit</v-btn>
                    </v-card-actions>
                </v-card>
            </transition>
        </v-dialog>
        <v-snackbar v-model="CatUpdated" :timeout="4000" top color="green darken-1 white--text">
            The category was updated successfully.
            <v-btn text color="white--text" @click="CatUpdated = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="newCatAdded" :timeout="4000" top color="green darken-1 white--text">
            New category has been added successfully.
            <v-btn text color="white--text" @click="newCatAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="addCatFailed" :timeout="6000" top color="red darken-1 white--text">
            Create new category failed. Please ensure you are adding a category that isn't existing and try again.
            <v-btn text color="white--text" @click="addCatFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="catDeleted" :timeout="4000" top color="green darken-1 white--text">
            A category was deleted successfully.
            <v-btn text color="white--text" @click="catDeleted = false">Close</v-btn>
        </v-snackbar>
         <v-snackbar v-model="uploadSuccessful" :timeout="4000" top color="green darken-1 white--text">
            Your file was uploaded successfully.
            <v-btn text color="white--text" @click="uploadSuccessful = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="uploadFailed" :timeout="8000" top color="red darken-1 white--text">
            Your file upload failed. Please ensure that the file you are trying to upload is in csv format and it does not contain invalid or categories already existing in the database.
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar :value="bulkCategoriesCreated" :timeout="4000" top color="green darken-1 white--text">
            New categories have been added successfully.
            <v-btn text color="white--text" @click="bulkCategoriesCreated = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            categories: [],
            updateDial: false,
            catToEdit: {
                name: ''
            },
            isUpdating: false,
            CatUpdated: false,
            addNewCatDial: false,
            newCat: {
                name: ''
            },
            newCatAdded: false,
            addCatFailed: false,
            q: '',
            searchedCats: false,
            searchResult: [],
            catToDel: null,
            catToDelIndex: null,
            confirmDelDial: false,
            isDeleting: false,
            catDeleted: false,
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
        bulkCategoriesCreated(){
            return this.$store.getters.bulkCategoriesCreated
        }
    },
    methods: {
        getCategories(pag){
            this.isLoading = true
            pag = pag || `${this.api}/auth-admin/get_pgntd_categories`
            axios.get(pag, this.adminHeaders).then((res) => {
                this.categories = res.data.data
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
        openEditDial(cat){
            this.updateDial = true
            this.catToEdit = cat
        },
        updateCat(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/update_category/${this.catToEdit.id}`, {
                        cat: this.catToEdit
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.updateDial = false
                        this.CatUpdated = true
                    })
                }
            })
        },
        addNewCat(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/add_new_cat`, {
                        cat: this.newCat
                    }, this.adminHeaders).then((res) => {
                        this.isUpdating = false
                        this.addNewCatDial = false
                        this.categories.unshift(res.data)
                        this.newCatAdded = true
                        this.total++
                        this.newCat.name = ''
                        this.$validator.pause()
                        this.$validator.fields.items.forEach(field => field.reset())
                        this.$validator.errors.clear()
                    }).catch(()=>{
                        this.isUpdating = false
                        this.addNewCatDial = false
                        this.addCatFailed = true
                    })
                }
            })
        },
        delConfirm(cat, i){
            this.confirmDelDial = true
            this.catToDel = cat
            this.catToDelIndex = i
        },
        delCat(){
            this.isDeleting = true
            let cat = this.catToDel
            let ind = this.catToDelIndex
            axios.post(this.api + `/auth-admin/admin_delete_cat/${cat.id}`, {}, this.adminHeaders)
            .then((res) => {
                this.isDeleting = false
                this.confirmDelDial = false
                this.categories.splice(ind, 1)
                this.total -=
                this.catDeleted = true
            })
        },
        showReview(rev){
            this.$router.push({name: 'AdminReviewDetail', params:{id: rev.id}})
        },
        searchForCats(){
            if(this.q.length > 0){
                this.searchedCats = true
                axios.post(this.api +`/auth-admin/admin_search_for_cats`, {
                    q: this.q.trim()
                }, this.adminHeaders).then((res) => {
                    this.searchResult = res.data
                    this.total = res.data.length
                })
            }else{
                this.searchedCats = false
                this.getCategories()
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

                axios.post(this.api + `/auth-admin/categories/import/`, form, this.adminHeaders).then((res) => {
                    this.isUploading = false
                    this.showFilePicker = true
                    this.showBtn = true
                    this.uploadSuccessful = true
                    this.getCategories()
                }).catch(() => {
                    this.isUploading = false
                    this.uploadFailed = true
                })
            }
        },
        cancelUpld(){
            this.showFilePicker = true
            this.showBtn = true
        }
    },
    created() {
        this.getCategories()
    },
}
</script>

<style lang="css" scoped>
    .v-card.scroll .v-card__text{
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
</style>

