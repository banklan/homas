import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';
import { totalmem } from 'os';
import router from './routes'

Vue.use(Vuex, axios);

const user_loggedin = window.localStorage.getItem('userIsLoggedIn')
const userIsLoggedIn = user_loggedin ? true : false

const auth_user = window.localStorage.getItem('authUser')
const authUser = auth_user ? JSON.parse(auth_user) : null

const redirect = window.localStorage.getItem('redirOnlogin')
const redirectOnLogin = redirect ? redirect : null

const my_service = window.localStorage.getItem('myService')
const authService = my_service ? JSON.parse(my_service) : null

const loc_services = window.localStorage.getItem('locServices')
const locServices = loc_services ? JSON.parse(loc_services) : null

const auth_admin = window.localStorage.getItem('authAdmin')
const authAdmin = auth_admin ? JSON.parse(auth_admin) : null

const adminlogged_in = window.localStorage.getItem('adminIsLoggedIn')
const adminIsLoggedIn = adminlogged_in ? true : false

const authServ = window.localStorage.getItem('authUserService')
const authUserService = authServ ? JSON.parse(authServ) : null

const enq_notif = window.localStorage.getItem('adminEnquiryNotif')
const adminEnquiryNotif = enq_notif ? JSON.parse(enq_notif) : null

const notif = window.localStorage.getItem('notifs')
const adminNotifications = notif ? JSON.parse(notif) : null

const notf = window.localStorage.getItem('newNotif')
const newNotif = notf ? JSON.parse(notf) : null

const notfs = window.localStorage.getItem('adminNotifs')
const adminNotifs = notf ? JSON.parse(notfs) : []

const enq_notf = window.localStorage.getItem('enquiryNotif')
const enquiryNotif = enq_notf ? enq_notf : 0

const user_notf = window.localStorage.getItem('userNotif')
const userNotif = user_notf ? user_notf : 0

const serv_notf = window.localStorage.getItem('servNotif')
const servNotif = serv_notf ? serv_notf : 0

export const store = new Vuex.Store({
    state: {
        api: 'http://localhost:8000/api',
        isBusy: false,
        redirectOnLogin: redirectOnLogin,
        userIsLoggedIn: userIsLoggedIn,
        authUser: authUser,
        authService: authService,
        portfolioWasCreated: false,
        userDeletedService: false,
        userDeletedReview: false,
        reviewWasUpdated: false,
        reviewUpdate: null,
        locServices: locServices,
        adminIsLoggedIn: adminIsLoggedIn,
        authAdmin: authAdmin,
        adminUpdatedSuperUser: false,
        adminUserDeleted: false,
        newAdminCreated: false,
        adminUpdatedUser: false,
        adminDeletedUser: false,
        newUserCreated: false,
        adminDeletedService: false,
        reviewStatusChanged: false,
        adminDeletePortfolio: false,
        adminUpdatedPortfolio: false,
        adminDeleteReview: false,
        locationStatusUpdated: false,
        updatedAdminProfile: false,
        authUserService: authUserService,
        authProfileUpdated: false,
        adminDeleteEnquiry: false,
        adminNotifications: adminNotifications,
        newNotif: newNotif,
        adminNotifs: adminNotifs,
        enquiryNotif: enquiryNotif,
        userNotif: userNotif,
        servNotif: servNotif,
        testimonialUpdateSuccess: false,
        testimonialCreated: false,
        adminDelTestimonial: false,
        adminUpdatedTestimonial: false,
    },
    getters: {
        api(state)
        {
            return state.api
        },
        userIsLoggedIn(state)
        {
            return state.userIsLoggedIn
        },
        authUser(state)
        {
            return state.authUser
        },
        redirectOnLogin(state)
        {
            return state.redirectOnLogin
        },
        authService(state)
        {
            return state.authService
        },
        serviceImage(state)
        {
            if (state.authService.image == null) {
                return '/images/shared/no-image.png'
            } else {
                return `images/services/${state.authService.image}`
            }
        },
        portfolioCreated(state)
        {
            return state.portfolioWasCreated
        },
        userDeletedService(state)
        {
            return state.userDeletedService
        },
        userDeletedReview(state)
        {
            return state.userDeletedReview
        },
        reviewWasUpdated(state)
        {
            return state.reviewWasUpdated
        },
        locServices(state)
        {
            return state.locServices
        },
        adminIsLoggedIn(state)
        {
            return state.adminIsLoggedIn
        },
        authAdmin(state)
        {
            return state.authAdmin
        },
        adminUpdatedSuperUser(state)
        {
            return state.adminUpdatedSuperUser
        },
        adminUserDeleted(state)
        {
            return state.adminUserDeleted
        },
        adminCreated(state)
        {
            return state.newAdminCreated
        },
        adminUpdatedUser(state)
        {
            return state.adminUpdatedUser
        },
        adminDeletedUser(state)
        {
            return state.adminDeletedUser
        },
        newUserCreated(state)
        {
            return state.newUserCreated
        },
        userProfileUpdated(state)
        {
            return state.userProfileUpdated
        },
        adminDeletedService(state)
        {
            return state.adminDeletedService
        },
        reviewStatusChanged(state)
        {
            return state.reviewStatusChanged
        },
        adminDeletePortfolio(state)
        {
            return state.adminDeletePortfolio
        },
        adminUpdatedPortfolio(state)
        {
            return state.adminUpdatedPortfolio
        },
        adminDeleteReview(state)
        {
            return state.adminDeleteReview
        },
        locationStatusUpdated(state)
        {
            return state.locationStatusUpdated
        },
        updatedAdminProfile(state)
        {
            return state.updatedAdminProfile
        },
        authUserService(state)
        {
            return state.authUserService
        },
        authProfileUpdated(state)
        {
            return state.authProfileUpdated
        },
        adminDeleteEnquiry(state)
        {
            return state.adminDeleteEnquiry
        },
        adminNotifications(state)
        {
            return state.adminNotifications
        },
        adminEnquiryNotif(state)
        {
            return state.adminEnquiryNotif
        },
        adminNotifs(state)
        {
            return state.adminNotifs
        },
        enquiryNotif(state)
        {
            return state.enquiryNotif
        },
        userNotif(state)
        {
            return state.userNotif
        },
        servNotif(state)
        {
            return state.servNotif
        },
        testimonialUpdateSuccess(state)
        {
            return state.testimonialUpdateSuccess
        },
        testimonialCreated(state)
        {
            return state.testimonialCreated
        },
        adminDelTestimonial(state)
        {
            return state.adminDelTestimonial
        },
        adminUpdatedTestimonial(state)
        {
            return state.adminUpdatedTestimonial
        }
    },
    actions: {
        fetchService(context)
        {
            axios.get(context.state.api + '/auth/auth_service', {
                headers: {
                    "Authorization": `Bearer ${context.state.authUser.token}`
                }
            }).then((res) =>
            {
                context.commit('updateService', res.data)
            })
        },
        listenForNewRequest(context)
        {
            Echo.channel('enquiries').listen('EnquiryReceived', (enq) =>
            {
                Notification.requestPermission(permission =>
                {
                    let enq_notif = new Notification('New Enquiry Alert!', {
                        title: enquiry.title,
                        icon: 'mdi-comment'
                    })
                })
            })
            // context.commit
        }
    },
    mutations: {
        // updateAdminStats(state, payload)
        // {
        //     state.adminStats.admin = payload
        //     window.localStorage.setItem('adminStats', JSON.stringify(state.adminStats))
        // },
        redirectOnLogin(state, payload)
        {
            window.localStorage.setItem('redirOnLogin', payload)
            state.redirectOnLogin = payload
        },
        userLoginSuccess(state, payload)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authadmin = null
            state.userIsLoggedIn = true
            state.authUser = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('userIsLoggedIn', true)
        },
        logOutAuthUser(state)
        {
            localStorage.removeItem('authUser')
            localStorage.removeItem('userIsLoggedIn')
            localStorage.removeItem('authService')
            localStorage.removeItem('authUserService')
            state.userIsLoggedIn = false
            state.authUser = null
        },
        updateService(state, payload)
        {
            state.authUser.service = payload
            state.authService = payload
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            window.localStorage.setItem('authService', JSON.stringify(payload))
        },
        portfolioCreated(state, payload)
        {
            state.portfolioWasCreated = true
        },
        resetUpdatedFlashMsg(state)
        {
            state.serviceWasUpdated = false
            state.adminUpdatedUser = false
            state.adminUpdatedCategory = false
            state.adminUpdatedLocation = false
            state.resetDeleted = false
            state.adminProfileUpdated = false
            state.adminUpdatedSuperUser = false
            state.adminUserDeleted = false
            state.newAdminCreated = false
            state.adminDeletedUser = false
            state.adminDeletedService = false
            state.reviewStatusChanged = false
            state.adminUpdatedPortfolio = false
            state.adminDeletedPortfolio = false
            state.adminDeleteReview = false
            state.newUserCreated = false
            state.adminUpdatedTestimonial = false
        },
        serviceDeleted(state)
        {
            state.userDeletedService = true
        },
        userDeletedReview(state)
        {
            state.userDeletedReview = true
        },
        reviewUpdated(state, payload)
        {
            state.reviewUpdate = payload
            state.reviewWasUpdated = true
        },
        setLocServices(state, payload)
        {
            window.localStorage.setItem('locServices', JSON.stringify(payload))
            state.locServices = payload
        },
        adminLoginSuccess(state, payload)
        {
            state.adminIsLoggedIn = true
            state.authAdmin = Object.assign({}, payload.user, {token: payload.access_token})
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
            window.localStorage.setItem('adminIsLoggedIn', true)
        },
        logOutAdmin(state)
        {
            localStorage.removeItem('authAdmin')
            localStorage.removeItem('adminIsLoggedIn')
            state.adminIsLoggedIn = false
            state.authAdmin = null
        },
        adminUpdatedSuperUser(state)
        {
            state.adminUpdatedSuperUser = true
        },
        adminUserDeleted(state)
        {
            state.adminUserDeleted = true
        },
        newAdminCreated(state){
            state.newAdminCreated = true
        },
        adminDeletedUser(state)
        {
            state.adminDeletedUser = true
        },
        adminUpdatedUser(state)
        {
            state.adminUpdatedUser = true
        },
        newUserCreated(state)
        {
            state.newUserCreated = true
        },
        adminDeletedService(state)
        {
            state.adminDeletedService = true
        },
        reviewStatusChanged(state)
        {
            state.reviewStatusChanged = true
        },
        adminDeletePortfolio(state)
        {
            state.adminDeletePortfolio = true
        },
        adminUpdatedPortfolio(state)
        {
            state.adminUpdatedPortfolio = true
        },
        adminDeleteReview(state)
        {
            state.adminDeleteReview = true
        },
        updateLocationStatus(state)
        {
            state.locationStatusUpdated = true
        },
        updatedAdminProfile(state, payload)
        {
            state.authAdmin.first_name = payload.first_name
            state.authAdmin.last_name = payload.last_name
            state.authAdmin.phone = payload.phone
            state.authAdmin.picture = payload.picture
            state.authAdmin.updated_at = payload.updated_at
            state.authAdmin.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authAdmin', JSON.stringify(state.authAdmin))
            state.updatedAdminProfile = true
        },
        setAuthUserService(state, payload)
        {
            state.authUserService = payload
            window.localStorage.setItem('authUserService', JSON.stringify(state.authUserService))
        },
        updatedAuthProfile(state, payload)
        {
            state.authUser.first_name = payload.first_name
            state.authUser.last_name = payload.last_name
            state.authUser.phone = payload.phone
            state.authUser.picture = payload.picture
            state.authUser.fullname = payload.first_name + ' '+ payload.last_name
            window.localStorage.setItem('authUser', JSON.stringify(state.authUser))
            state.authProfileUpdated = true
        },
        resetAuthFlashMsg(state, payload)
        {
            state.authProfileUpdated = false
            state.testimonialUpdateSuccess = false
            state.testimonialCreated = false
        },
        adminDeleteEnquiry(state, payload)
        {
            state.adminDeleteEnquiry = true
        },
        setAdminNotifications(state, payload)
        {
            window.localStorage.setItem('notifs', JSON.stringify(payload))
            state.adminNotifications = payload
        },
        resetAdminNotifs(state, payload)
        {
            localStorage.removeItem('notifs')
            state.adminNotifications = []
        },
        updateAdminNotifs(state, payload)
        {
            let notifs = JSON.parse(window.localStorage.getItem('notifs'))
            if (notifs) {
                let notf = notifs.filter(item => item.model != payload)
                window.localStorage.setItem('notifs', JSON.stringify(notf))
                state.adminNotifications = notf
            }
        },
        adminEnquiryNotif(state, payload)
        {
            window.localStorage.setItem('adminEnquiryNotif', JSON.stringify(payload))
            state.adminEnquiryNotif = payload
        },
        newNotif(state, payload)
        {
            state.newNotif = payload
            window.localStorage.setItem('newNotif', JSON.stringify(payload))
        },
        setNotifs(state, payload)
        {
            let notifs = JSON.parse(window.localStorage.getItem('adminNotifs'))
            if (notifs) {
                notifs.push(payload)
                window.localStorage.setItem('adminNotifs', JSON.stringify(notifs))
                state.adminNotifs = notifs
            } else {
                window.localStorage.setItem('adminNotifs', JSON.stringify(payload))
                state.adminNotifs = payload
            }

        },
        setEnqNotif(state, payload)
        {
            let enqNotif = window.localStorage.getItem('enquiryNotif')
            if(enqNotif){
                enqNotif ++
                window.localStorage.setItem('enquiryNotif', enqNotif)
                state.enquiryNotif = enqNotif
            }else{
                let enqNotif = 1
                window.localStorage.setItem('enquiryNotif', enqNotif)
                state.enquiryNotif = enqNotif
            }
        },
        setUserNotif(state)
        {
            let userNotif = window.localStorage.getItem('userNotif')
            if(userNotif){
                userNotif ++
                window.localStorage.setItem('userNotif', userNotif)
                state.userNotif = userNotif
            }else{
                let userNotif = 1
                window.localStorage.setItem('userNotif', userNotif)
                state.userNotif = userNotif
            }
        },
        setServiceNotif(state)
        {
            let servNotif = window.localStorage.getItem('servNotif')
            if(servNotif){
                servNotif ++
                window.localStorage.setItem('servNotif', servNotif)
                state.servNotif = servNotif
            }else{
                let servNotif = 1
                window.localStorage.setItem('servNotif', servNotif)
                state.servNotif = servNotif
            }
        },
        resetEnqNotif(state)
        {
            window.localStorage.removeItem('enquiryNotif')
            state.enquiryNotif = 0
        },
        resetServiceNotif(state)
        {
            window.localStorage.removeItem('servNotif')
            state.servNotif = 0
        },
        resetUserNotif(state)
        {
            window.localStorage.removeItem('userNotif')
            state.userNotif = 0
        },
        TestimonialUpdated(state)
        {
            state.testimonialUpdateSuccess = true
        },
        TestimonialCreated(state)
        {
            state.testimonialCreated = true
        },
        adminDeleteTestimonial(state)
        {
            state.adminDelTestimonial = true
        },
        adminUpdatedTestimonial(state)
        {
            state.adminUpdatedTestimonial = true
        }
    },
})
