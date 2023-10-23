<template>
  <section style="background-color: #eee;">
    <div class="container py-5">

      <div class="row">

        <div class="col-md-6 col-lg-5 col-xl-4 mb-4 mb-md-0">

          <h5 class="font-weight-bold mb-3 text-center text-lg-start">Member</h5>
          <div class="card">
            <div class="card-body">

              <ul class="list-unstyled mb-0" v-for="user in users">
                <router-link :to="`/chat/${user.id}`" custom
                  v-slot="{ href, route, navigate, isActive, isExactActive }">
                  <li class="p-2" :class="{
                    'active': isActive,
                    'border-bottom': false
                  }">
                    <a :href="href" @click="navigate" class="d-flex justify-content-between">
                      <div class="d-flex flex-row">
                        <img
                          src="https://beforeigosolutions.com/wp-content/uploads/2021/12/dummy-profile-pic-300x300-1.png"
                          alt="avatar" class="rounded-circle d-flex align-self-center me-3 shadow-1-strong" width="60">
                        <div class="pt-1">
                          <p class="fw-bold mb-0">{{ user.name }}</p>
                        </div>
                      </div>

                    </a>
                  </li>
                </router-link>
              </ul>
            </div>
          </div>

        </div>
        <router-view></router-view>


      </div>

    </div>
  </section>
  <button onclick="click">click</button>
</template>

<style>
.active {
  background-color: #eee;
}
</style>

<script>
import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import { RouterView, RouterLink, useLink } from 'vue-router';
import { useUsersStore } from '../stores/users';
import { useAuthStore } from '../stores/auth';
export default {
  data: () => ({
    users: [],
    user: null,
    userStore: null,
  }),
  async mounted() {
    this.userStore = useUsersStore();
    await this.userStore.setUsers();
    await this.userStore.setMessages();
    this.users = this.userStore.users;
    let authStore = useAuthStore();
    console.log(this.userStore.messages)
    this.user = authStore.user;
    window.Pusher = Pusher;

    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: import.meta.env.VITE_APP_WEBSOCKETS_KEY,
      wsHost: import.meta.env.VITE_APP_WEBSOCKETS_SERVER,
      wsPort: 6001,
      forceTLS: false,
      disableStats: true,
      cluster: 'mt1',
      authEndpoint: 'http://127.0.0.1:8000/broadcasting/auth',
      transports: ['websocket', 'polling', 'flashsocket'],
      auth: {
        headers: {
          Authorization: "Bearer " + document.cookie.split('; ').find((val)=>val.startsWith('token='))?.slice('token='.length),
          accept: 'aplication/json'
        }
      }
    });
    console.log("chat")
    window.Echo.private('chat.'+this.user.id).listen('.new.message', (e) => {
      console.log(e);
      this.displayChatMessage(e.body, e.body.from);
    });
    // this.update();


  },
  methods: {
    displayChatMessage(message, userId) {
      // console.log(message, userId)
      this.userStore.newMessage(message, userId);
      // console.log(this.userStore.messages)
    },
    click() {
      // this.update();
    }
  }


}

</script>
