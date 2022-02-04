import NotFound from './components/NotFound';
import Welcome from './components/Welcome';
import ServiceShow from './components/ServiceShow'
import Login from './components/Login'
import Register from './components/Register'
import UserEmailConfirmation from './components/UserEmailConfirmation'
import Dashboard from './components/user/Dashboard'
import CreateService from './components/user/CreateService'
import CreateServiceAddImage from './components/user/CreateServiceAddImage'
import MyService from './components/user/MyService'
import CreatePortFolio from './components/user/CreatePortFolio'
import MyPortFolio from './components/user/MyPortFolio'
import MyPortFolioShow from './components/user/MyPortFolioShow'
import MyProfile from './components/user/MyProfile'
import MyServiceReviews from './components/user/MyServiceReviews'
import ServiceReviewList from './components/ServiceReviewList'
import PortfolioShowSingle from './components/PortfolioShowSingle';
import ServicesByLocation from './components/ServicesByLocation';
import ServiceSearchResult from './components/ServiceSearchResult';
import ForgotPassword from './components/ForgotPassword';
import PasswordReset from './components/PasswordReset';
import Slider from './components/Slider';
import AdminLogin from './components/admin/AdminLogin';
import AdminDashboard from './components/admin/AdminDashboard';
import AdminSuperUsersList from './components/admin/AdminSuperUsersList';
import AdminUpdateSuperUser from './components/admin/AdminUpdateSuperUser';
import AdminCreateSuperUser from './components/admin/AdminCreateSuperUser';
import AdminSuperUserDetail from './components/admin/AdminSuperUserDetail';
import AdminUsersList from './components/admin/AdminUsersList';
import AdminUserDetail from './components/admin/AdminUserDetail';
import AdminUserUpdate from './components/admin/AdminUserUpdate';
import AdminCreateUser from './components/admin/AdminCreateUser';
import AdminSearchUserRes from './components/admin/AdminSearchUserRes';
import AdminServicesList from './components/admin/AdminServicesList';
import AdminServiceDetail from './components/admin/AdminServiceDetail';
import AdminServiceUpdate from './components/admin/AdminServiceUpdate';
import AdminServiceReviewList from './components/admin/AdminServiceReviewList';
import AdminSearchServiceRes from './components/admin/AdminSearchServiceRes';
import AdminPortfolioDetail from './components/admin/AdminPortfolioDetail';
import AdminPortfolioList from './components/admin/AdminPortfolioList';
import AdminPortfolioUpdate from './components/admin/AdminPortfolioUpdate';
import AdminSearchPortfolioRes from './components/admin/AdminSearchPortfolioRes';
import AdminReviewList from './components/admin/AdminReviewList';
import AdminReviewDetail from './components/admin/AdminReviewDetail';
import AdminSearchReviewRes from './components/admin/AdminSearchReviewRes';
import AdminCategoriesList from './components/admin/AdminCategoriesList';
import AdminLocationList from './components/admin/AdminLocationList';
import AdminClientsTest from './components/admin/AdminClientsTest';
import AdminProfile from './components/admin/AdminProfile';
import AdminChart from './components/admin/AdminChart';
import AdminPasswordResetLogs from './components/admin/AdminPasswordResetLogs';
import AdminEnquiries from './components/admin/AdminEnquiries';
import AdminEnquiryShow from './components/admin/AdminEnquiryShow';
import ContactUs from './components/Contact';
import Notification from './components/NotificationTest';
import AboutUs from './components/AboutUs';
import FreqAskedQuestions from './components/FreqAskedQuestions';
import Advertise from './components/Advertise';
import TermsAndConditions from './components/TermsAndConditions';
import PrivacyPolicy from './components/PrivacyPolicy';
import Testimonials from './components/Testimonials';
import MyTestimonial from './components/user/MyTestimonial';
import EditTestimonial from './components/user/EditTestimonial';
import NewTestimonial from './components/user/NewTestimonial';
import AdminTestimonialList from './components/admin/AdminTestimonialList';
import AdminTestimonialUpdate from './components/admin/AdminTestimonialUpdate';
import AdminTestimonialShow from './components/admin/AdminTestimonialShow';

// import AdminServiceDetail from './components/admin/AdminServiceDetail';

export default [
    {path: '*', name: 'NotFound', component: NotFound},
    { path: '/', name: 'Welcome', component: Welcome },
    { path: '/service/:id/:slug', name: 'ServiceShow', component: ServiceShow },
    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/about-us', name: 'AboutUs', component: AboutUs },
    { path: '/email-confirmation', name: 'UserEmailConfirmation', component: UserEmailConfirmation, props: true },
    {
        path: '/dashboard', name: 'Dashboard', component: Dashboard,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/create-service', name: 'CreateService', component: CreateService,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/create-service/add-image', name: 'CreateServiceAddImage', component: CreateServiceAddImage,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/my-service', name: 'MyService', component: MyService,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/create-portfolio', name: 'CreatePortFolio', component: CreatePortFolio,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/my-portfolio', name: 'MyPortFolio', component: MyPortFolio,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/my-portfolio/:id/:slug', name: 'MyPortFolioShow', component: MyPortFolioShow,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/profile', name: 'MyProfile', component: MyProfile,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/my-service-reviews', name: 'MyServiceReviews', component: MyServiceReviews,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/review/:service/', name: 'ServiceReviewList', component: ServiceReviewList
    },
    {
        path: '/portfolio/:id/:slug', name: 'PortfolioShowSingle', component: PortfolioShowSingle
    },
    {
        path: '/services/:id/:slug', name:'ServicesByLocation', component: ServicesByLocation
    },
    {
        path: '/services/search', name:'ServiceSearchResult', component: ServiceSearchResult, props: true
    },
    {
        path: '/forgot-password', name:'ForgotPassword', component: ForgotPassword
    },
    {
        path: '/password-reset', name: 'PasswordReset', component: PasswordReset, props: true,
    },
    {
        path: '/slider', name:'Slider', component: Slider,
    },
    { path: '/admin/login', name: 'AdminLogin', component: AdminLogin },
    {
        path: '/admin', name: 'AdminDashboard', component: AdminDashboard,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users', name: 'AdminSuperUsersList', component: AdminSuperUsersList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/:id/update', name: 'AdminUpdateSuperUser', component: AdminUpdateSuperUser,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/create', name: 'AdminCreateSuperUser', component: AdminCreateSuperUser,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/super-users/:id', name: 'AdminSuperUserDetail', component: AdminSuperUserDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/users/search', name: 'AdminSearchUserRes', component: AdminSearchUserRes, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/users', name: 'AdminUsersList', component: AdminUsersList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/users/:id', name: 'AdminUserDetail', component: AdminUserDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/users/update/:id', name: 'AdminUserUpdate', component: AdminUserUpdate,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/users/create', name: 'AdminCreateUser', component: AdminCreateUser,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/services/:id/:slug', name: 'AdminServiceDetail', component: AdminServiceDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/services/search', name: 'AdminSearchServiceRes', component: AdminSearchServiceRes, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/services', name: 'AdminServicesList', component: AdminServicesList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/service/:id/update', name: 'AdminServiceUpdate', component: AdminServiceUpdate,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/portfolios/search', name: 'AdminSearchPortfolioRes', component: AdminSearchPortfolioRes, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/portfolios/:id/update', name: 'AdminPortfolioUpdate', component: AdminPortfolioUpdate,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/portfolios/:id/:slug', name: 'AdminPortfolioDetail', component: AdminPortfolioDetail,
        meta: {
            requireAdminsAuth: true
        }
    },

    {
        path: '/admin/portfolios', name: 'AdminPortfolioList', component: AdminPortfolioList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/reviews/search', name: 'AdminSearchReviewRes', component: AdminSearchReviewRes, props: true,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/reviews/:service', name: 'AdminServiceReviewList', component: AdminServiceReviewList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/reviews/:id', name: 'AdminReviewDetail', component: AdminReviewDetail,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/reviews', name: 'AdminReviewList', component: AdminReviewList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/categories', name: 'AdminCategoriesList', component: AdminCategoriesList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/locations', name: 'AdminLocationList', component: AdminLocationList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/clients', name: 'AdminClientsTest', component: AdminClientsTest,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/profile', name: 'AdminProfile', component: AdminProfile,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/charts', name: 'AdminChart', component: AdminChart,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/password-reset-logs', name: 'AdminPasswordResetLogs', component: AdminPasswordResetLogs,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/enquiries', name: 'AdminEnquiries', component: AdminEnquiries,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/enquiries/:id', name: 'AdminEnquiryShow', component: AdminEnquiryShow,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/testimonials', name: 'AdminTestimonialList', component: AdminTestimonialList,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/testimonials/:id/update', name: 'AdminTestimonialUpdate', component: AdminTestimonialUpdate,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/admin/testimonials/:id', name: 'AdminTestimonialShow', component: AdminTestimonialShow,
        meta: {
            requireAdminsAuth: true
        }
    },
    {
        path: '/contact-us', name: 'ContactUs', component: ContactUs,
    },
    {
        path: '/notification', name: 'Notification', component: Notification,
    },
    {
        path: '/frequently-asked-questions', name: 'FreqAskedQuestions', component: FreqAskedQuestions,
    },
    {
        path: '/advertise', name: 'Advertise', component: Advertise,
    },
    {
        path: '/terms-conditions', name: 'TermsAndConditions', component: TermsAndConditions,
    },
    {
        path: '/privacy-policy', name: 'PrivacyPolicy', component: PrivacyPolicy,
    },
    {
        path: '/testimonials', name: 'Testimonials', component: Testimonials,
    },
    {
        path: '/my-testimonial', name: 'MyTestimonial', component: MyTestimonial,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/edit-testimonial/:id', name: 'EditTestimonial', component: EditTestimonial,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/new-testimonial', name: 'NewTestimonial', component: NewTestimonial,
        meta: {
            requiresAuth: true
        }
    },
]
