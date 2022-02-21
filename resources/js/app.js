
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VeeValidate from 'vee-validate';
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.css'
import 'animate.css'
import Moment from 'vue-moment'
import VueAwesomeSwiper from 'vue-awesome-swiper'
import VueSlickCarousel from 'vue-slick-carousel'
import 'vue-slick-carousel/dist/vue-slick-carousel.css'
import VueSocialSharing from 'vue-social-sharing'

import App from './components/App'
import vuetify from './plugins/vuetify';
import Routes from './routes';
import { store } from './store';
import './filters'
import Vue from 'vue';

Vue.use(VueRouter)
Vue.use(VeeValidate)
Vue.use(Moment)
Vue.use(VueAwesomeSwiper)
Vue.use(VueSlickCarousel)
Vue.use(VueSocialSharing)

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    linkActiveClass: 'font-semibold'
})

router.beforeEach((to, from, next) => {
    const requireAdminsAuth = to.matched.some(rec => rec.meta.requireAdminsAuth);
    const authAdmin = store.state.authAdmin
    if (requireAdminsAuth && authAdmin == null) {
        next('/admin/login')
    }else if(to.path == '/admin/login' && authAdmin){
        next('/admin')
    }else if(to.path == '/login' && authAdmin){
        next('/')
    }else if(to.path == '/register' && authAdmin){
        next('/')
    }else {
        next()
    }
});


router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(rec => rec.meta.requiresAuth);
    const authUser = store.state.authUser
    if (requiresAuth && authUser == null) {
        store.commit('redirectOnLogin', to.path)
        next('/login')
    }else if(to.path == '/login' && authUser){
        next('/dashboard')
    }else if(to.path == '/register' && authUser){
        next('/dashboard')
    }else if(to.path.substring(0, 6) == '/admin' && authUser){
        next('/dashboard')
    }else {
        next()
    }
});


//axios interceptor for expired token
axios.interceptors.response.use(null, (err) =>
{
    if (err.response.status == 401) {
        store.commit('logOutAuthUser')
        store.commit('logOutAdmin')
        if (router.currentRoute.name !== ('AdminLogin' || 'Login')) {
            router.push('/')
        }
        router.push('/')
    }
    return Promise.reject(err)
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('welcome-banner', require('./components/children/WelcomeBanner.vue').default);
Vue.component('welcome-cards', require('./components/children/WelcomeCards.vue').default);
Vue.component('featured-sps', require('./components/children/FeaturedServices.vue').default);
Vue.component('popular-sps', require('./components/children/PopularServices.vue').default);
Vue.component('popular-locs', require('./components/children/PopularLocations.vue').default);
Vue.component('latest-sps', require('./components/children/FreshServices.vue').default);
// Vue.component('how-to', require('./components/Children/HowTo.vue').default);
Vue.component('portfolio-carousel', require('./components/children/PortfolioCarousel.vue').default);
Vue.component('auth-navbar', require('./components/includes/AuthNavbar.vue').default);
Vue.component('review-item', require('./components/Children/ReviewItem.vue').default);
Vue.component('review-item-gen', require('./components/Children/ReviewItemGeneral.vue').default);
Vue.component('single-service-card', require('./components/Children/SingleServiceCard.vue').default);
Vue.component('service-search-bar', require('./components/Children/ServiceSearchBar.vue').default);
Vue.component('gen-portfolio-carousel', require('./components/children/GenPortfolioCarousel.vue').default);
// Vue.component('similar-services-slide', require('./components/children/SimilarServicesSlide.vue').default);
Vue.component('admin-top-panel', require('./components/children/AdminTopPanel.vue').default);
Vue.component('admin-auth-navbar', require('./components/includes/AdminAuthNavbar.vue').default);
Vue.component('admin-dash-card', require('./components/children/AdminDashboardCard.vue').default);
Vue.component('admin-dialog', require('./components/children/AdminDialog.vue').default);
Vue.component('admin-search', require('./components/children/AdminSearch.vue').default);
Vue.component('admin-review-item', require('./components/children/AdminReviewItem.vue').default);
Vue.component('admin-location-popular', require('./components/children/AdminLocationPopular.vue').default);
Vue.component('my-service-review', require('./components/children/MyServiceReview.vue').default);
Vue.component('how-it-works', require('./components/children/HowItWorks.vue').default);
Vue.component('sp-testimonials', require('./components/children/SpTestimonials.vue').default);
// Vue.component('slider-test', require('./components/children/SliderTest.vue').default);
Vue.component('similar-services', require('./components/children/SimilarServices.vue').default);
Vue.component('loc-top-services', require('./components/children/LocTopServices.vue').default);

// Vue.component('swipe', Swipe)
// Vue.component('swipe-item', SwipeItem)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    vuetify,
    router,
    store
});
