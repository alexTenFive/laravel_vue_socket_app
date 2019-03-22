import VueRouter from 'vue-router';

let routes = [
    {
        path: '/login',
        component: require('./views/Login.vue').default,
        meta: { isLogged: true }
    },
    {
        path: '/chat',
        component: require('./views/ChatForm.vue').default,
        meta: { middlewareAuth: true }
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.middlewareAuth)) {
        if (! auth.check()) {
            next({
                path: '/login',
                query: { redirect: to.fullPath}
            });

            return;
        }
        next();

    }

    if (to.matched.some(record => record.meta.isLogged)) {
        if (auth.check()) {
            next({
                path: '/home',
                query: { redirect: to.fullPath}
            });

            return;
        }
    }

    next();
});

export default router;