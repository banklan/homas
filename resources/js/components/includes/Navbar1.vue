<template>
    <nav>
        <template v-if="userIsLoggedIn">
            <auth-navbar />
        </template>
        <template v-if="adminIsLoggedIn">
            <admin-auth-navbar />
        </template>
        <template v-if="!userIsLoggedIn && !adminIsLoggedIn">
            <v-app-bar flat dark color="transparent white--text" class="mb-n6">
                <span class="hidden-md-and-up">
                    <v-app-bar-nav-icon class="primary--text hidden-md-and-up" @click="navdrawer = true"></v-app-bar-nav-icon>
                </span>
                <v-toolbar-title class="ml-5 my-2 white--text">
                    <router-link to="/" style="cursor:pointer" exact>
                        <span class="font-weight-bold headline primary--text">Homas</span>
                    </router-link>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items class="hidden-sm-and-down align-center">
                    <template>
                        <v-btn text v-for="menu in menus" :key="menu.title">
                            <router-link class="transparent" :class="$route.path == '/' ? 'white--text' : 'primary--text' "  :to="menu.path">{{ menu.title }}</router-link>
                        </v-btn>
                    </template>
                </v-toolbar-items>
            </v-app-bar>
            <v-navigation-drawer absolute v-model="navdrawer" color="primary white--text" class="hidden-md-and-up" disable-resize-watcher>
                <v-toolbar-title class="headline ml-4 mt-3 pb-4">
                    <router-link to="/" style="cursor: pointer" exact>
                        <span class="primary--text">Homas</span>
                    </router-link>
                </v-toolbar-title>
                <v-divider></v-divider>
                <v-list dark class="ml-4">
                    <template>
                        <v-list-item class="" v-for="menu in menus" :key="menu.title" link :to="menu.path">
                            <v-list-item-content>
                                <v-list-item-title>{{ menu.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                    </template>
                </v-list>
            </v-navigation-drawer>
        </template>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            navdrawer: false,
            menuColor: '',
            menus: [
                { title: "About Us", path: "/about-us"},
                { title: "Contact Us", path: "/contact-us"},
                { title: "Login", path: "/login"},
                { title: "Register", path: "/register"},
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
    },
    created(){
        // console.log(this.userIsLoggedIn)
    }
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none;
        text-transform:capitalize;
    }
</style>
