import { createRouter,createWebHistory } from "vue-router";
import HomeComponent from '../components/HomeComponent.vue';
import TodoComponent from '../components/TodoComponent.vue';
import LoginComponent from '../components/Authentication/LoginComponent.vue';
import RegisterComponent from '../components/Authentication/RegisterComponent.vue';

const routes=[
    {
        path:"/",
        name:'HomeComponent',
        component:HomeComponent
    },
    {
        path:'/todo-list',
        name:'TodoComponent',
        component:TodoComponent,
        meta:{requiresAuth:true}
    },
    {
        path:'/login',
        name:'LoginComponent',
        component:LoginComponent,
        meta:{requiresGuest:true}
    },
    {
        path:'/register',
        name:'RegisterComponent',
        component:RegisterComponent,
        meta:{requiresGuest:true}
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
  })

  /* Router authentication */
  router.beforeEach((to,from)=>{
    const authenticated=localStorage.getItem('authenticated');

    if(to.meta.requiresGuest && authenticated){
        return {
            name:'TodoComponent'
        }
    }else if(to.meta.requiresAuth && !authenticated){
        return{
            name:'LoginComponent'
        }
    }
})

export default router;