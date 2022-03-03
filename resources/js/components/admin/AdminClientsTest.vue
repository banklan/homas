<template>
    <v-container>
        <v-row class="mt-2 mb-5" justify="space-around">
            <span v-if="showFilePicker">
                <v-btn dark color="admin" class="mb-5" @click="openUpload"><v-icon left>mdi-upload</v-icon>Upload CSV</v-btn>
                <input type="file" ref="file" style="display:none" @change.prevent="pickFile" accept=".csv">
            </span>
            <span v-else>
                <span class="pr-4">{{ file.name }}</span>
                <v-btn elevation="8" dark color="admin" @click="uploadFile" :loading="isUploading"><v-icon left>mdi-upload</v-icon>Upload File</v-btn>
                <v-alert type="info" class="mt-2">Please ensure your file is in .csv format</v-alert>
            </span>
            <v-btn elevation="8" dark color="primary" :href="`http://localhost:8000/api/clients/export`"><v-icon left>mdi-download</v-icon>Download</v-btn>
        </v-row>
        <v-row justify="start" class="mr-5">
            <v-col cols="12">
                <v-progress-circular indeterminate color="primary" :width="4" :size="40" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="6" min-height="200" class="scroll mr-3">
                    <v-card-title class="subtitle-1 admin white--text justify-center">Clients <v-chip v-if="clients.length > 0" class="ml-1" dark color="admin lighten-2">{{ clients.length }}</v-chip></v-card-title>
                    <v-card-text class="mt-5">
                        <template v-if="clients.length > 0">
                            <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Phone </th>
                                            <th>Address</th>
                                            <th>Date Joined</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(client, i) in clients" :key="client.id">
                                            <td>{{ client.id }} </td>
                                            <td width="25%">{{ client.name }} </td>
                                            <td >{{ client.age }} </td>
                                            <td>{{ client.phone }} </td>
                                            <td>{{ client.address | truncate(20) }} </td>
                                            <td>{{ client.created_at | moment('DD/MM/YYYY') }}</td>
                                            <td><v-btn small icon color="red darken-2" class='ml-2' @click="delConfirm(client, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                        </tr>
                                    </tbody>
                                </table>
                        </template>
                        <!-- {{ clients }} -->
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-snackbar v-model="uploadSuccessful" :timeout="4000" top color="green darken-1 white--text">
            Your file have been uploaded successfully.
            <v-btn text color="white--text" @click="uploadSuccessful = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="uploadFailed" :timeout="10000" top color="red darken-1 white--text">
            Your file upload failed. Please ensure your file is in .csv format and that none of the data is already in the database before trying again.
            <v-btn text color="white--text" @click="uploadFailed = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            clients: [],
            isLoading: false,
            file: '',
            showFilePicker: true,
            isUploading: false,
            uploadSuccessful: false,
            uploadFailed: false,
        }
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
                    "Authorization": `Bearer ${this.authAdmin.token}`,
                    "content-type": 'multipart/form-data'
                }
            }
            return headers
        },
    },
    methods: {
        getClients(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_test_clients', this.adminHeaders).then((res) => {
                this.isLoading = false
                this.clients = res.data
            })
        },
        openUpload(){
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

                axios.post(this.api + `/auth-admin/clients/import/`, form, this.adminHeaders).then((res) => {
                    this.isUploading = false
                    this.showFilePicker = true
                    this.uploadSuccessful = true
                    this.getClients()
                }).catch(() => {
                    this.isUploading = false
                    this.uploadFailed = true
                })
            }
        }
    },
    mounted() {
        this.getClients()
    },
}
</script>

<style lang="css">
    .v-btn{
        text-decoration: none !important;
    }
</style>
