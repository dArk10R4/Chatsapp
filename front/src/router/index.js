import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '../stores/auth';
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/chat',
      name: 'chat',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/ChatView.vue'),
      meta: {
        authRequired: true,
      },
      children: [{
        path: ':id',
        component: () => import('../views/ChatByIdView.vue')
      }]
    },
    {
      path: '/login',
      name: 'login',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/LoginView.vue'),
      meta : {
        redirectIfAuthenticated: true
      }
    },
    {
      path: '/register',
      name: 'register',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/RegisterView.vue'),
      meta : {
        redirectIfAuthenticated: true
      }
    },
    // { 
    //   path: '/chat/',
    //   name: 'chat_by_id', 
    //   component: () => import('../views/ChatByIdView.vue'),
    //   meta: {
    //     authRequired: true,
    //   },
      
    // },
  ]
});


router.beforeEach((to, from, next) => {
  let auth = useAuthStore();
  if (to.matched.some((record) => record.meta.authRequired)) {
    if (auth?.isLogedIn) {
      next();
    } else {
      alert("You've must been logged to access this area");
      router.push("/login");
    }
  } else if (to.matched.some((record) => (auth?.isLogedIn && record.meta.redirectIfAuthenticated))) {
    router.push("/");
  }else {
    next();
  }
});

export default router
