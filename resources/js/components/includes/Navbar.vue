<template>
    <nav>
        <template>
            <v-app-bar flat dark color="transparent white--text" class="mb-n6">
                <span class="hidden-md-and-up">
                    <v-app-bar-nav-icon class="primary--text hidden-md-and-up" @click="navdrawer = true"></v-app-bar-nav-icon>
                </span>
                <v-toolbar-title class="ml-5 my-5 pt-3 white--text">
                    <router-link to="/" style="cursor:pointer" exact>
                        <!-- <span class="font-weight-bold headline primary--text app_name">Hozulinks</span> -->
                        <img src="/images/shared/logo2-orange.png" height="65" />
                    </router-link>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <span v-if="$route.path !== '/'">
                    <v-toolbar-items>
                        <span v-if="authUser" class="primary--text mr-5"> {{ authUser.fullname }}</span>
                    </v-toolbar-items>
                </span>
                <v-toolbar-items class="hidden-sm-and-down menu align-center">
                    <template v-if="!userIsLoggedIn && !adminIsLoggedIn">
                        <v-btn text v-for="menu in menus" :key="menu.title">
                            <router-link class="transparent" :class="$route.path == '/' ? 'white--text' : 'primary--text'" :to="menu.path">{{ menu.title }}</router-link>
                        </v-btn>
                    </template>
                    <template v-if="userIsLoggedIn">
                        <v-btn text v-for="menu in authMenu" :key="menu.title">
                            <router-link class="transparent" :class="$route.path == '/' ? 'white--text' : 'primary--text'" :to="menu.path">{{ menu.title }}</router-link>
                        </v-btn>
                        <v-btn :class="$route.path == '/' ? 'white--text' : 'primary--text'" text @click="logout" class="logout transparent">Logout</v-btn>
                    </template>
                </v-toolbar-items>
            </v-app-bar>
            <v-navigation-drawer absolute v-model="navdrawer" color="primary white--text" class="hidden-md-and-up" height="100%">
                <v-toolbar-title class="headline ml-4 mt-3 pb-4">
                    <router-link to="/" style="cursor: pointer" exact>
                        <!-- <span class="white--text">Hozulinks</span> -->
                        <img src="/images/shared/logo2-white.png" height="65" />
                    </router-link>
                </v-toolbar-title>
                <v-divider></v-divider>
                <v-list dark class="ml-4">
                    <template v-if="!userIsLoggedIn">
                        <v-list-item class="" v-for="menu in menus" :key="menu.title" link :to="menu.path">
                            <v-list-item-content>
                                <v-list-item-title>{{ menu.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                    <template v-else>
                        <v-list-item class="auth_menu" v-for="menu in authMenu" :key="menu.title" link :to="menu.path">
                            <v-list-item-content>
                                <v-list-item-title>{{ menu.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item dark class="white--text"  @click="logout">
                            <v-list-item-title>Logout</v-list-item-title>
                        </v-list-item>
                    </template>
                </v-list>
            </v-navigation-drawer>
        </template>
    </nav>
</template>

<script>
import AdminAuthNavbar from './AdminAuthNavbar.vue'
export default {
  components: { AdminAuthNavbar },
    data(){
        return{
            navdrawer: false,
            menuColor: '',
            menus: [
                { title: "All Services", path: "/services"},
                { title: "About Us", path: "/about-us"},
                { title: "Contact Us", path: "/contact-us"},
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
            ],
            authMenu:[
                { title: "Dashboard", path: "/dashboard"},
                { title: "My Service", path: "/my-service"},
                { title: "My Portfolio", path: "/my-portfolio"},
                { title: "My Account", path: "/profile"},
            ],
            notLoggedInDrawer: false,
        }
    },
    computed: {
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        adminIsLoggedIn(){
            return this.$store.getters.adminIsLoggedIn
        },
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
        headers(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authUser.token}`
                }
            }
            return headers
        },
    },
    methods: {
        logout(){
            if(this.userIsLoggedIn){
                axios.post(this.api + `/auth/logout`, {}, this.headers).then(() =>{
                    this.$store.commit('logOutAuthUser')
                    this.$router.push('/')
                })
            }
        }
    },
}
</script>

<style lang="scss" scoped>
    button.v-btn{
        text-decoration: none !important;
        background-color: none !important;
        transition: all .3s !important;
        &:hover{
            background-color: none !important;
        }
        &:hover a{
            transition: all .3s !important;
            color: #ff5200 !important;
        }
        a{
            transition: all .3s !important;
            text-decoration: none !important;
            text-transform:capitalize !important;
        }
    }
    a{
        transition: all .3s !important;
        text-decoration: none !important;

        .app_name{
            transition: all .3s !important;
            &:hover{
                color: #c54104 !important;
            }
        }
    }
    button.logout{
        text-decoration: none !important;
        transition: all .3s !important;
        background: none !important;
        text-transform: capitalize !important;
        &:hover{
            background: none !important;
            color: #ff5200 !important;
        }
    }
    .v-toolbar__items button{
        transition: all 0.4s;
        &:hover{
            background-color: #ff5200 !important;
            padding: auto 12px !important;
            a{
                color: #fff !important;
            }
        }
    }
    .v-btn a.router-link-exact-active{
        background: #ff5200 !important;
        padding: 23.5px 12px;
        color: #fff !important;
    }
</style>
