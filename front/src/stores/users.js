import { defineStore } from 'pinia';
import axios from 'axios';

export const useUsersStore = defineStore('users',{
    state: ()=>({
        users: [],
        messages: new Map()
    }),
    getters : {
     getUsers: (state) => state.users,
     getMessagesById: (state) => {
        return (userId) => state.messages.get(userId) ? state.messages.get(userId):[];
     }   
    },
    actions : {
        async setUsers() {
            try {
                
                let response = await axios.get(import.meta.env.VITE_APP_API_SERVER_URL + '/api/v1/users',{
                    headers: {
                        "Accept":"application/json",
                        "Authorization": 'Bearer ' + document.cookie.split('; ').find((val)=>val.startsWith('token='))?.slice('token='.length),
                    }
                });
                this.users = response.data; 
            } catch (error) {
                console.log(error);
            }
        },
        newMessage(content,userId) {
            let userMessage =  this.messages.get(userId) ? this.messages.get(userId):[];
            console.log("here");
            userMessage.push(content);
            this.messages = this.messages.set(userId,userMessage);
        },
        async setMessages () {
            try {
                
                let response = await axios.get(import.meta.env.VITE_APP_API_SERVER_URL + '/api/v1/messages',{
                    headers: {
                        "Accept":"application/json",
                        "Authorization": 'Bearer ' + document.cookie.split('; ').find((val)=>val.startsWith('token='))?.slice('token='.length),
                    }
                });
                let a = new Map();
                for (let [key, value] of Object.entries(response.data.messages)) {
                    a.set(parseInt(key),value);
                }
                this.messages = a; 
            } catch (error) {
                console.log(error);
            }
        },
        
    }

});

