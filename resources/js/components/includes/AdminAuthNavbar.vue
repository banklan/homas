<template>
    <nav>
        <v-app-bar :clipped-right="true" color="admin primary--text" light elevation="10">
            <v-app-bar-nav-icon @click="sidebarmenu = !sidebarmenu" class="primary--text"></v-app-bar-nav-icon>
            <v-toolbar-title class="primary--text pt-3">
                <router-link to="/" style="cursor:pointer" exact>
                    <!-- <span class="font-weight-bold headline primary--text">Hozulinks</span> -->
                    <img src="/images/shared/logo2-orange.png" height="65" />
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <span>Admin</span>
            <v-icon color="primary" class="mx-5">mdi-account</v-icon>
            <v-toolbar-items class="">
                <v-menu offset-y bottom nudge-bottom="3" right>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn v-bind="attrs" v-on="on" style="font-size: 28px" icon class="mr-2" :disabled="notifications == 0">
                            <v-badge :value="notifications > 0" color="primary" :content="notifications" overlap>
                                <v-icon style="font-size: 28px" color="primary">mdi-bell-outline</v-icon>
                            </v-badge>
                        </v-btn>
                    </template>
                    <v-list dense>
                        <v-list-item-group color="primary">
                            <v-list-item v-if="enquiryNotif > 0" link :to="{name: 'AdminEnquiries'}">
                                <v-list-item-icon class="mr-3">
                                    <v-icon color="green">mdi-comment</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>New Enquiry <v-chip small color="green white--text">{{ enquiryNotif }}</v-chip></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item v-if="servNotif > 0" link :to="{name: 'AdminServicesList'}">
                                <v-list-item-icon class="mr-3">
                                    <v-icon color="orange">mdi-tools</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>New Service <v-chip small color="orange white--text">{{ servNotif }}</v-chip></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <v-list-item v-if="userNotif > 0" link :to="{name: 'AdminUsersList'}">
                                <v-list-item-icon class="mr-3">
                                    <v-icon color="purple">mdi-account</v-icon>
                                </v-list-item-icon>
                                <v-list-item-content>
                                    <v-list-item-title>New User <v-chip small color="purple white--text">{{ userNotif }}</v-chip></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-item-group>
                    </v-list>
                </v-menu>
            </v-toolbar-items>
        </v-app-bar>
        <span class="contents">
            <span class="navdrawer">
                <v-navigation-drawer clipped v-model="sidebarmenu" :permanent="sidebarmenu" :mini-variant.sync="mini" color="admin">
                    <v-list class="mt-5">
                        <v-list-item dark class="px-2">
                            <v-list-item-action>
                                <v-icon @click="sidebarmenu = !sidebarmenu">mdi-chevron-left</v-icon>
                            </v-list-item-action>
                            <v-list-item-content class="text-truncate">
                                <v-list-item-title class="white--text">{{ $vuetify.breakpoint.mobile ? '' : 'Admin Dashboard' }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item class="px-2" dark @click.stop="toggleMini = !toggleMini">
                            <v-list-item-avatar>
                                <v-icon>mdi-account-outline</v-icon>
                            </v-list-item-avatar>
                            <v-list-item-content class="text-truncate">
                                {{ authAdmin.fullname }}
                            </v-list-item-content>
                            <v-btn icon small>
                                <v-icon>mdi-chevron-left</v-icon>
                            </v-btn>
                        </v-list-item>
                    </v-list>
                    <v-divider light class="my-4"></v-divider>
                    <v-list dark>
                        <v-list-item v-for="menu in authMenu" :key="menu.title" link :to="menu.path" dark class="white--text" exact>
                            <v-list-item-icon>
                                <v-icon color="white--text">{{ menu.icon }}</v-icon>
                            </v-list-item-icon>
                            <v-list-item-content>
                                <v-list-item-title class="white--text">{{ menu.title }}</v-list-item-title>
                            </v-list-item-content>
                        </v-list-item>
                        <v-list-item  @click.prevent="logout">
                            <v-list-item-icon>
                                <v-icon @click="logout">logout</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title class="body-2">Logout</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-navigation-drawer>
            </span>
            <span class="main">
                <v-main>
                    <v-container class="px-4 py-5 fill-height" fluid>
                        <v-row class="fill-height">
                            <v-col cols="12">
                                <transition mode="out-in" enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut">
                                    <router-view :toggleMini="toggleMini" :smAndDown="smAndDown"></router-view>
                                </transition>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-main>
            </span>
        </span>
    </nav>
</template>

<script>
export default {
    data(){
        return{
            sidebarmenu: true,
            toggleMini: false,
            // mini: true,
            // expandOnHover: true,
            // title: this.$vuetify.breakpoint.mobile ? ' M-Dashboard' : 'M-Dashboard by Materialfy',
            authMenu: [
                { title: "Admin DashBoard", path: "/admin", icon:"dashboard"},
                {title: "Admins", path: "/admin/super-users", icon:"supervisor_account"},
                {title: "Users", path: "/admin/users", icon:"mdi-account-group"},
                {title: "Services", path: "/admin/services", icon:"mdi-tools"},
                {title: "Portfolios", path: "/admin/portfolios", icon: "mdi-briefcase"},
                {title: "Reviews", path: "/admin/reviews", icon: "mdi-comment"},
                {title: "Categories", path: "/admin/categories", icon: "category"},
                {title: "Locations", path: "/admin/locations", icon: "navigation"},
                {title: "Password Resets", path: "/admin/password-reset-logs", icon: "lock"},
                {title: "Enquiries", path: "/admin/enquiries", icon: "message"},
                {title: "Testimonials", path: "/admin/testimonials", icon: "mdi-comment"},
                {title: "Profile", path: "/admin/profile", icon: "lock"},
                {title: "Charts", path: "/admin/charts", icon: "lock"},
                // {title: "Clients", path: "/admin/clients", icon: "mdi-group"},
            ],
            notificationsBadge: true,
            // enquiryNotif: 0,
            // userNotif: 0,
            // servNotif: 0,
        }
    },
    computed: {
        adminIsLoggedIn(){
            return this.$store.getters.adminIsLoggedIn
        },
        api(){
            return this.$store.getters.api
        },
        authAdmin(){
            return this.$store.getters.authAdmin
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
        smAndDown(){
            if(this.$vuetify.breakpoint.smAndDown){
                return true
            }else{
                return false
            }
        },
        itoggleMini(){
            if (this.smAndDown){
                return false
            }else{
                return true
            }
        },
        mini(){
            if(this.smAndDown && this.toggleMini){
                return false
            }else if(!this.smAndDown && this.toggleMini){
                return true
            }else if(this.smAndDown && !this.toggleMini){
                return true
            }else if(!this.smAndDown && !this.toggleMini){
                return false
            }else{
                return false
            }
        },
        notifications(){
            let len = 0;
            let notifs = [this.enquiryNotif, this.userNotif, this.servNotif]
            notifs.forEach(el => {
                el > 0 ? len++ : len
            });
            return len
        },
        enquiryNotif(){
            return this.$store.getters.enquiryNotif
        },
        userNotif(){
            return this.$store.getters.userNotif
        },
        servNotif(){
            return this.$store.getters.servNotif
        },
    },
    methods: {
        logout(){
            if(this.authAdmin){
                axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() =>{
                    this.$store.commit('logOutAdmin')
                    this.$router.push('/')
                })
            }
        },
        listenForNewRequest(){
            Echo.channel('notif')
                .listen('TestNameSaved', (msg) => {
                    // console.log("received data from pusher", msg.notif)
                    if(msg){
                        let enqNotif = window.localStorage.getItem('enquiryNotif')
                        if(enqNotif){
                            enqNotif ++
                            window.localStorage.setItem('enquiryNotif', enqNotif)
                            this.enquiryNotif = enqNotif
                        }else{
                            let enqNotif = 1
                            window.localStorage.setItem('enquiryNotif', enqNotif)
                            this.enquiryNotif = enqNotif
                        }
                    }
            })
        },
        listenForNewEnquiry(){
            Echo.channel('enquiries')
                .listen('EnquiryReceived', (enq) => {
                    if(enq){
                        this.$store.commit('setEnqNotif')
                    }
                })
        },
        listenForNewUser(){
            Echo.channel('newUser')
                .listen('NewUserRegistered', (user) => {
                    if(user){
                        this.$store.commit('setUserNotif')
                    }
                })
        },
        listenForNewService(){
            Echo.channel('newService')
                .listen('NewServiceCreated', (serv) => {
                    if(serv){
                        this.$store.commit('setServiceNotif')
                    }
                })
        }
    },
    created(){
        this.listenForNewRequest()
        this.listenForNewEnquiry()
        this.listenForNewService()
        this.listenForNewUser()
    }
}
</script>

<style lang="css" scoped>
    nav{
        width: 100vw !important
    }
    a{
        text-decoration: none;
        text-transform:capitalize;
    }
    .contents{
        display: flex;
        justify-content: flex-start;
        background: #f4f2ff;
    }
    .navdrawer{
        max-width: 25%;
        transition: all .4s;
        /* height: 100vh !important; */
    }
    .main{
        width: 100%;
    }
</style>
