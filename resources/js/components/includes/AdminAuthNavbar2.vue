<template>
    <nav>
        <v-app-bar :clipped-right="true" color="admin primary--text" light elevation="10">
            <v-app-bar-nav-icon @click="sidebarmenu = !sidebarmenu" class="primary--text"></v-app-bar-nav-icon>
            <v-toolbar-title class="primary--text">
                <router-link to="/" style="cursor:pointer" exact>
                    <span class="font-weight-bold headline primary--text">Homas</span>
                </router-link>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <span>Admin</span>
            <v-icon color="primary" class="mx-5">mdi-account</v-icon>
            <v-toolbar-items class="">
            <v-menu offset-y bottom nudge-bottom="10" left>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn @click="notificationsBadge ? notificationsBadge = !notificationsBadge : ''" v-bind="attrs" v-on="on" style="font-size: 28px" icon class="mr-2">
                        <!-- <v-badge :value="notificationsBadge" color="error" :content="notifications.length > 0 ? notifications.length : '0'" overlap> -->
                        <v-badge :value="notificationsBadge" color="error" :content="notifs.length > 0 ? notifs.length : '0'" overlap>
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
        <!-- <v-row> -->
            <!-- <v-col cols="3"> -->
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
                                Carol Skelly
                            </v-list-item-content>
                            <v-btn icon small>
                                <v-icon>mdi-chevron-left</v-icon>
                            </v-btn>
                        </v-list-item>
                    </v-list>
                    <v-divider light class="my-4"></v-divider>
                    <v-list dark>
                        <!-- <v-list-group v-for="menu in authMenu" :key="menu.title" v-model="menu.active" :prepend-icon="menu.icon" no-action class="white--text">
                            <template v-slot:activator>
                                <v-list-item-content  link :to="menu.path">
                                    <v-list-item-title v-text="menu.title" class="white--text"></v-list-item-title>
                                </v-list-item-content>
                            </template>
                            <v-list-item v-for="child in menu.items" :key="child.title" link :to="menu.path">
                                <v-list-item-content>
                                    <v-list-item-title v-text="child.title"></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </v-list-group> -->
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
                        <!-- <v-list-item v-if="adminNotifs">
                            <v-list-item-icon>
                                <v-icon>message</v-icon>
                            </v-list-item-icon>
                            <v-list-item-title class="body-2">{{ newNotif }}</v-list-item-title>
                        </v-list-item> -->
                    </v-list>
                </v-navigation-drawer>
                </span>
            <!-- </v-col> -->
            <!-- <v-col cols="9"> -->
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
            <!-- </v-col> -->
        <!-- </v-row> -->
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
                {title: "Profile", path: "/admin/profile", icon: "lock"},
                {title: "Charts", path: "/admin/charts", icon: "lock"},
                // {title: "Clients", path: "/admin/clients", icon: "mdi-group"},
            ],
            notificationsBadge: true,
            notifs: [],
            enquiryNotif: 0,
            userNotif: 0,
            servNotif: 0,
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
            let nofifs = [this.enquiryNotif, this.userNotif, this.servNotif]
            notifs.forEach(el => {
                el > 0 ? len++ : len
            });
            return len
        },
        newNotif(){
            return this.$store.getters.newNotif
        },
        adminNotifs(){
            return this.$store.getters.adminNotifs
        }
    },
    methods: {
        logout(){
            // console.log('now logged out')
            if(this.authAdmin){
                axios.post(this.api + `/auth-admin/logout`, {}, this.adminHeaders).then(() =>{
                    this.$store.commit('logOutAdmin')
                    this.$router.push('/')
                })
            }
            // this.$router.push('/')
        },
        newUserAlert(){
            axios.get(this.api + '/auth-admin/get_new_users_alert', this.adminHeaders).then((res)=>{
                console.log(res.data)
            })
        },
        getNotifications(){
            axios.get(this.api +'/auth-admin/get_admin_notifications', this.adminHeaders).then((res) =>{
                let notifs  = []
                let user_notif = res.data.filter(item => item.model === 'User')
                if(user_notif.length > 0){
                    let user_n = {color: 'purple', icon: 'mdi-account-group', text: 'New Users', path: '/admin/users', count: user_notif.length}
                    // this.notifications.push(user_n)
                    notifs.push(user_n)
                }
                let serv_notif = res.data.filter(item => item.model === 'Service')
                if(serv_notif.length > 0){
                    let serv_n = {color: 'green', icon: 'mdi-tools', text: 'New Services', path: '/admin/services', count: serv_notif.length}
                    // this.notifications.push(serv_n)
                    notifs.push(serv_n)
                }
                let enq_notif = res.data.filter(item => item.model === 'Enquiry')
                if(enq_notif.length > 0){
                    let enq_n = {color: 'orange', icon: 'message', text: 'New Enquiries', path: '/admin/enquiries', count: enq_notif.length}
                    // this.notifications.push(enq_n)
                    notifs.push(enq_n)
                }
                this.$store.commit('setAdminNotifications', notifs)
                if(notifs.length == 0){
                    this.$store.commit('resetAdminNotifs')
                }
            })
        },
        listenForNewRequest(){
            Echo.channel('notif')
                .listen('TestNameSaved', (msg) => {
                    console.log("received data from pusher", msg.notif)
                    // this.notifs.push({
                    //     color: 'green', icon: 'mdi-tools', text: 'New Services', path: '/admin/services'
                    // })
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
                    console.log(enq)
                    if(enq){
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
        listenForNewUser(){
            Echo.channel('newUser')
                .listen('NewUserRegistered', (user) => {
                    console.log(user)
                    if(user){
                        let userNotif = window.localStorage.getItem('userNotif')
                        if(userNotif){
                            userNotif ++
                            window.localStorage.setItem('userNotif', userNotif)
                            this.userNotif = userNotif
                        }else{
                            let userNotif = 1
                            window.localStorage.setItem('userNotif', userNotif)
                            this.userNotif = userNotif
                        }
                    }
                })
        },
        listenForNewService(){
            Echo.channel('newService')
                .listen('NewServiceCreated', (serv) => {
                    console.log(serv)
                    if(serv){
                        let servNotif = window.localStorage.getItem('servNotif')
                        if(servNotif){
                            servNotif ++
                            window.localStorage.setItem('servNotif', servNotif)
                            this.servNotif = servNotif
                        }else{
                            let servNotif = 1
                            window.localStorage.setItem('servNotif', servNotif)
                            this.servNotif = servNotif
                        }
                    }
                })
        }
    },
    created(){
        this.getNotifications()
        this.listenForNewRequest()
        this.listenForNewEnquiry()
        this.listenForNewService()
        this.listenForNewUser()

        this.enquiryNotif = window.localStorage.getItem('enquiryNotif')
        this.servNotif = window.localStorage.getItem('servNotif')
        this.userNotif = window.localStorage.getItem('userNotif')
    }
}
</script>

<style lang="css" scoped>
    a{
        text-decoration: none;
        text-transform:capitalize;
    }
    .contents{
        display: flex;
        justify-content: flex-start;
        background: #f4f2ff;
        /* align-items: center; */
    }
    .navdrawer{
        max-width: 25%;
        transition: all .4s;
    }
    .main{
        /* flex-wrap: wrap; */
        width: 100%;
    }

    /* a.router-link-exact-active{
        color: #08F7EB !important;
    } */
</style>
