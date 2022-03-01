<template>
    <span>
        <v-btn dark small :color="loc.is_popular ? 'primary' : 'admin lighten-1'" @click="toggleStatus(loc, index)" :loading="isToggling">{{ loc.is_popular ? 'Disable' : 'Enable' }}</v-btn>
    </span>
</template>

<script>
export default {
    props: ['loc', 'index'],
    data() {
        return {
            isToggling: false
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
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    methods: {
        toggleStatus(loc, i){
            this.isToggling = true
            axios.post(this.api + `/auth-admin/toggle_loc_popularity/${loc.id}`, {}, this.adminHeaders).then((res) => {
                this.isToggling = false
                this.loc.is_popular = res.data.is_popular
                this.$store.commit('updateLocationStatus')
            })
        }
    },
}
</script>
