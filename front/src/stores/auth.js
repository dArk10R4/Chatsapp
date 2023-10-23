import { ref, computed } from 'vue'
import { defineStore } from 'pinia';
import axios from 'axios'
export const useAuthStore = defineStore('auth',{
    state: ()=>({
        authUser: null,
        isLogedIn: Boolean(document.cookie.split('; ').find((val)=>val.startsWith('token='))?.slice('token='.length)),
    }),
    getters: {
        user: (state) => state.authUser,
        // isLogedIn: (state) => state.isLogedIn,

    },
    actions: {
        async login(params) {
            return await axios.post(import.meta.env.VITE_APP_API_SERVER_URL+'/api/login',params,{
                headers: {
                    "Accept":"application/json"
                }
            })
        },
        setUser (user) {
            this.authUser = user;
            
        },
        setToken (token) {
            document.cookie = `token=${token}; expires=${Date(Date.now()+2*24*60*60*1000)}; path=/`;
        },
        getUser() {
            return  axios.get(import.meta.env.VITE_APP_API_SERVER_URL+'/api/v1/user',{
                headers: {
                    "Accept":"application/json",
                    'Authorization': 'Bearer ' + document.cookie.split('; ').find((val)=>val.startsWith('token='))?.slice('token='.length),
                }
            })
        },
        removeToken() {
            console.log(Date(Date.now()-2*24*60*60*1000))
            document.cookie = `token=test; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/`;
            this.isLogedIn = false;
        },
    }
})