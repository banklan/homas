<template>
    <v-container>
        <v-row justify="start" class="mt-5">
            <v-col cols="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
            <!-- <v-col cols="8">
                <div class="subtitle-1">Services for {{ pageLoc }} <v-chip>{{ total }}</v-chip></div>
            </v-col> -->
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="7" :size="70" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <template>
                <v-row justify="space-around" class="mt-5 mb-3">
                    <v-col cols="12" md="4" class="d-flex">
                        <v-select solo tile label="Choose Category" dense :items="categories" item-text="name" item-value="id" v-model="filter.cat"></v-select>
                        <!-- <v-btn dark color="primary" @click="filterByCat">Go!</v-btn> -->
                    </v-col>
                    <v-col cols="12" md="4" class="d-flex">
                        <v-select solo return-object label="Change State" dense :items="locations" item-text="state" v-model="filter.loc"></v-select>
                        <!-- <v-btn dark small class="py-4" color="primary" @click.prevent="changeLoc">Go</v-btn> -->
                        <v-btn v-if="showFilterClear" icon color="red darnen-2" class="" @click="clearFilter"><v-icon>clear</v-icon></v-btn>
                    </v-col>
                    <v-col cols="12" md="4" class="d-flex">
                        <service-search-bar />
                    </v-col>
                </v-row>
                <template v-if="services && services.length > 0">
                    <v-row justify="start" class="mt-3">
                        <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                            <single-service-card :service="service"></single-service-card>
                        </v-col>
                    </v-row>
                    <v-row justify="start" class="mt-3" v-if="!hidePagination">
                        <v-col cols="12" md="6">
                            <span class="pl-4">
                                <v-btn color="primary" @click.prevent="getServices(pagination.first_link)" :disabled="!pagination.prev_link">&lt;&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.prev_link)" :disabled="!pagination.prev_link">&lt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.next_link)" :disabled="!pagination.next_link">&gt;</v-btn>
                                <v-btn color="primary" @click.prevent="getServices(pagination.last_link)" :disabled="!pagination.next_link">&gt;&gt;</v-btn>
                            </span>
                            <span class="pl-8">
                                Page: {{ pagination.current_page }} of {{ pagination.last_page }}
                            </span>
                        </v-col>
                    </v-row>
                </template>
                <template v-else>
                    <v-row justify="center" class="mt-5">
                        <v-col cols="12" md="6">
                            <v-alert type="error" border="left">
                                There are no services for the selected state.
                            </v-alert>
                        </v-col>
                    </v-row>
                </template>
            </template>
        </template>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            isLoading: false,
            services: [],
            total: null,
            locations: [],
            categories: [],
            pagination: {},
            filter: {
                loc: null,
                cat: null
            },
            pageLoc: null,
            hidePagination: false,
            showFilterClear: false
        }
    },
    computed:{
        api(){
            return this.$store.getters.api
        },
        locServices(){
            return this.$store.getters.locServices
        }
    },
    watch: {
        '$route.params.id': {
            handler(newVal){
                this.q = newVal
                this.isLoading = true
                this.getPageLoc()
                axios.get(this.api + `/get_services_by_locs/${this.$route.params.id}`).then((res) => {
                    this.isLoading = false
                    this.services = res.data.data
                    this.total = res.data.total
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
            immediate: true
        }
    },
    methods: {
        getServices(pag){
            pag = pag || `${this.api}/get_pntg_services`
            this.isLoading = true
            axios.get(pag).then((res) => {
                this.services = res.data.data
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
                // this.$store.commit('setLocServices', res.data)
            })
        },
        getLocations(){
            axios.get(this.api + '/locations').then((res)=>{
                this.locations = res.data
            })
        },
        getCategories(){
            axios.get(this.api + '/categories').then((res) => {
                console.log(res.data)
                this.categories = res.data
            })
        },
        changeLoc(){
            console.log(this.filter.loc)
            // this.filter.cat = null
            this.$router.push({name: 'ServicesByLocation', params: {id: this.filter.loc.id, slug: this.filter.loc.slug}})
        },
        filterByCat(){
            this.hidePagination = true
            let cat = this.locServices.data.filter(item => item.category_id == this.filter.cat)
            this.services = cat
            this.total = cat.length
            this.showFilterClear = true
        },
        getPageLoc(){
            axios.get(this.api + `/get_loc_by_id/${this.$route.params.id}`)
            .then((res) => {
                this.pageLoc = res.data.state
            })
        },
        clearFilter(){
            this.services = this.locServices.data
            this.total = this.locServices.total
            this.filter.cat = ''
            this.hidePagination = false
            this.showFilterClear = false
        },
        // getLocName(){
        //     axios.get(this.api + '/get_loc_name/${this.params.id}')
        // }
    },
    created(){
        this.getServices()
        this.getLocations()
        this.getCategories()
        // this.getPageLoc()
    }
}
</script>
