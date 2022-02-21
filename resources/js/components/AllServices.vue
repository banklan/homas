<template>
    <v-container>
        <v-row justify="start" class="mt-5">
            <v-col cols="4">
                <v-btn rounded color="primary lighten--2" dark elevation="4" left @click.prevent="$router.go(-1)"><v-icon left>arrow_left</v-icon> Back</v-btn>
            </v-col>
        </v-row>
        <v-progress-circular v-if="isLoading" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
        <template v-else>
            <v-row justify="space-around" class="mt-5 mb-3">
                <v-col cols="12" md="4" class="d-flex">
                    <v-select solo tile label="Choose Category" dense :items="categories" item-text="name" item-value="id" v-model="filter.cat" @change="filterWithCat"></v-select>
                    <v-btn v-if="CatFilter" icon color="red darken-2" class="" @click="clearCatFilter"><v-icon>clear</v-icon></v-btn>
                </v-col>
                <v-col cols="12" md="4" class="d-flex">
                    <v-select solo return-object label="Change State" dense :items="locations" item-text="state" item-value="id" v-model="filter.loc" @change="filterWithLoc"></v-select>
                    <v-btn v-if="LocFilter" icon color="red darken-2" class="" @click="clearLocFilter"><v-icon>clear</v-icon></v-btn>
                </v-col>
                <v-col cols="12" md="4" class="d-flex">
                    <service-search-bar />
                </v-col>
            </v-row>
            <template v-if="CatFilter == false && LocFilter == false">
                <template v-if="services.length > 0">
                    <v-row justify="start" class="mt-3">
                        <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                            <single-service-card :service="service"></single-service-card>
                        </v-col>
                    </v-row>
                    <v-row justify="start" class="mt-3">
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
                <v-row v-else justify="center" class="mt-5">
                    <v-col cols="12" md="6">
                        <v-alert type="error" border="left" class="mt-5">
                            There are no services for the selection.
                        </v-alert>
                    </v-col>
                </v-row>
            </template>
            <template v-if="CatFilter || LocFilter">
                <v-progress-circular v-if="isFiltering" indeterminate color="primary" :width="4" :size="40" justify="center" class="mx-auto"></v-progress-circular>
                <template v-else>
                    <v-row justify="center">
                        <v-col cols="12" md="6">
                            <div class="primary white--text pa-3 mt-n4 mb-6 subtitle-1 text-center">
                                Your filter returned {{ total | pluralize('service') }}.
                            </div>
                        </v-col>
                    </v-row>
                    <v-row justify="start" class="mt-3">
                        <v-col cols="12" md="4" v-for="service in services" :key="service.id">
                            <single-service-card :service="service"></single-service-card>
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
            filtered: false,
            filter: {
                cat: null,
                loc: null
            },
            categories: [],
            locations: [],
            CatFilter: false,
            LocFilter: false,
            isFiltering: false,
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
    methods: {
        getServices(pag){
            this.LocFilter = false
            this.CatFilter = false
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
        filterWithCat(){
            // console.log(this.filter.cat)
            this.CatFilter = true
            this.isFiltering = true
            var filt = this.services.filter(serv => serv.category_id === this.filter.cat)
            this.isFiltering = false
            this.services = filt
            this.total = filt.length
        },
        filterWithLoc(){
            this.LocFilter = true
            this.isFiltering = true
            var filt = this.services.filter((serv) => serv.location_id === this.filter.loc.id)
            this.isFiltering = false
            this.services = filt
            this.total = filt.length
        },
        clearCatFilter(){
            this.CatFilter = false
            this.getServices()
        },
        clearLocFilter(){
            this.LocFilter = false
            this.getServices()
        }
    },
    created(){
        this.getServices()
        this.getLocations()
        this.getCategories()
    }
}
</script>
