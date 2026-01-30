import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: `/:username/product`,
        component: () => import('../Pages/Product/Index.vue'),
        name: 'product-list'
    },
    {
        path: `/:username/contact`,
        component: () => import('../Pages/Contact/Index.vue'),
        name: 'contact-list'
    },{
        path: `/:username/helpdesk`,
        component: () => import('../Pages/Contact/HelpdeskItem.vue'),
        name: 'helpdesk-list-item'
    },{
        path: `/:username/faq`,
        component: () => import('../Pages/FaqV2/Index.vue'),
        name: 'faq-list'
    },{
        path: `/:username/bot`,
        component: () => import('../Pages/ChatBot/Index.vue'),
        name: 'bot-start'
    },{
        path: `/:username/faq/:id`,
        component: () => import('../Pages/FaqV2/Index.vue'),
        name: 'faq-detail'
    },
    {
        path: `/:username/agent/:id/:category`,
        component: () => import('../Pages/Helpdesk/Index.vue'),
        name: 'helpdesk-list'
    },
    {
        path: `/:username/callback/:id/:category`,
        component: () => import('../Pages/Callback/Index.vue'),
        name: 'request-callback'
    },
    {
        path: `/:username/live/:agent_id`,
        component: () => import('../Pages/CallnChat/Index.vue'),
        name: 'live-session'
    },
    {
        path: `/:username/form`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-general',
        meta : {
            product_type : 'general',
            title : 'General Product'
        }
    },
    {
        path: `/:username/bookprof`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-professional',
        meta : {
            product_type : 'schedule_professional',
            title : 'Booking Schedule Professional'
        }
    },
    {
        path: `/:username/bookother`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-other',
        meta : {
            product_type : 'schedule_other',
            title : 'Booking Schedule Other'
        }
    },
    {
        path: `/:username/form/:product_json`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-general-product',
        meta : {
            product_type : 'general',
            title : 'General Product'
        }
    },
    {
        path: `/:username/bookprof/:product_json`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-professional-product',
        meta : {
            product_type : 'schedule_professional',
            title : 'Booking Schedule Professional'
        }
    },
    {
        path: `/:username/bookother/:product_json`,
        component: () => import('../Pages/Schedule/Index.vue'),
        name: 'schedule-other-product',
        meta : {
            product_type : 'schedule_other',
            title : 'Booking Schedule Other'
        }
    }
]

const router = createRouter({
    routes,
    history: createWebHistory()
})
export default router