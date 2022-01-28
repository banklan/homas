<template>
    <v-navigation-drawer v-model="sidebarmenu" floating :permanent="sidebarmenu" :mini-variant.sync="mini" color="admin">
        <v-list dense>
            <v-list-item>
                <v-list-item-action>
                    <v-icon @click.stop="sidebarMenu = !sidebarmenu">mdi-chevron-left</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title class="white--text"></v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <v-list-item class="px-2" dark @click="toggleMini = !toggleMini">
            <v-list-item-avatar>
                <v-icon>mdi-account-outline</v-icon>
            </v-list-item-avatar>
            <v-list-item-content class="text-truncate">
                Carol Skelly
            </v-list-item-content>
            <v-btn icon small>
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
        </v-list-item>
        <v-divider></v-divider>
        <v-list>
            <v-list-item v-for="menu in authMenu" :key="menu.title" link :to="menu.path" dark class="white--text" exact>
                <v-list-item-icon>
                    <v-icon color="white--text">{{ menu.icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                    <v-list-item-title class="white--text">{{ menu.title }}</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
export default {
    data(){
        return{
            sidebarmenu: true,
            adminNavdrawer: false,
            toggleMini: false,
            // mini: true,
            authMenu: [
                { title: "Admin DashBoard", path: "/admin", icon:"account_balance"},
                {title: "Admins", path: "/admin/super-users", icon:"supervisor_account"},
                {title: "Daily Tips", path: "/admin/daily-tips", icon:"today"},
                {title: "Experts", path: "/admin/experts", icon:"mdi-account-circle"},
                {title: "Users", path: "/admin/users", icon: "mdi-account"},
                {title: "Forecasts", path: "/admin/forecasts-by-experts", icon: "view_list"},
                {title: "Subscriptions", path: "/admin/subscriptions", icon: "receipt"},
            ],
        }
    },
    computed: {
        adminIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        api(){
            return this.$store.getters.api
        },
        authAdmin(){
            return this.$store.getters.authUser
        },
        mini(){
            return (this.$vuetify.breakpoint.smAndDown) || this.toggleMini
        }
    },
    methods: {
        logout(){
            if(this.authAdmin){
                axios.post(this.api + `/auth-admin/logout`, {}, {
                    headers: {
                        "Authorization": `Bearer ${this.authAdmin.token}`
                    }
                }).then(() =>{
                    // this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
            this.$router.push('/')
        },
    },
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none;
        text-transform:capitalize;
    }
</style>

