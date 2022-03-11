<template>
    <span>
        <v-app-bar flat light color="transparent white--text" class="mb-n5">
            <span class="hidden-md-and-up">
                <v-app-bar-nav-icon class="primary--text hidden-md-and-up" @click="navdrawer = true"></v-app-bar-nav-icon>
            </span>
            <v-toolbar-title class="ml-5 my-2 pt-3 white--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <!-- <span class="font-weight-bold headline primary--text">Hozulinks</span> -->
                    <img src="/images/shared/logo2-orange.png" height="65" />
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <span class="primary--text mt-1 mr-5"> {{ authUser.fullname }}</span>
            <v-toolbar-items class="hidden-sm-and-down align-center">
                <template>
                    <v-btn text v-for="menu in authMenu" :key="menu.title">
                        <router-link class="transparent" :class="$route.path == '/' ? 'white--text' : 'primary--text' "  :to="menu.path">{{ menu.title }}</router-link>
                    </v-btn>
                    <v-btn dark color="primary" text @click="logout">Logout</v-btn>
                </template>
            </v-toolbar-items>
        </v-app-bar>
        <v-navigation-drawer absolute v-model="navdrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
            <v-toolbar-title class="white--text headline font-weight-bold ml-4 my-5">
                <router-link to="/" style="cursor: pointer" exact>
                    <!-- <span class="white--text">Hozulinks</span> -->
                    <img src="/images/shared/logo2-white.png" height="65" />
                </router-link>
            </v-toolbar-title>
            <v-divider></v-divider>
            <v-list dark class="ml-4">
                <template>
                    <v-list-item dark color="white--text" v-for="menu in authMenu" :key="menu.title" link :to="menu.path">
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
    </span>
</template>

<script>
export default {
    data(){
        return{
            navdrawer: false,
            authMenu: [
                { title: "DashBoard", path: "/dashboard"},
                { title: "My Service", path: "/my-service"},
                { title: "My Profile", path: "/my-profile"},
            ],
        }
    },
    computed: {
        userIsLoggedIn(){
            return this.$store.getters.userIsLoggedIn
        },
        api(){
            return this.$store.getters.api
        },
        authUser(){
            return this.$store.getters.authUser
        },
    },
    methods: {
        logout(){
            if(this.authUser){
                axios.post(this.api + `/auth/logout`, {}, {
                    headers: {
                        "Authorization": `Bearer ${this.authUser.token}`
                    }
                }).then(() =>{
                    this.$store.commit('logOutAuthUser')
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
    a.router-link-exact-active{
        color: #ff5200 !important;
    }
</style>
