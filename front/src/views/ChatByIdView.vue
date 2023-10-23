<template>
    <div class="col-md-6 col-lg-7 col-xl-8">

        <ul class="list-unstyled messages-box" id="messagebox">
            <div v-for="(message, index) in this.messages?.messages.get(+this.friend)">

                <li v-if="message.from == user?.user?.id" class="d-flex justify-content-end mb-4">
                    <img src="https://beforeigosolutions.com/wp-content/uploads/2021/12/dummy-profile-pic-300x300-1.png"
                        alt="avatar" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60">
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between p-3">
                            <p class="fw-bold mb-0">{{ message.name }}</p>
                        </div>
                        <div class="card-body flex-grow-1">
                            <p class="mb-0">
                                {{ message.content }}
                            </p>
                        </div>
                    </div>
                </li>
                <li v-if="message.from == friend" class="d-flex justify-content-start mb-4">
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between p-3">
                            <p class="fw-bold mb-0">{{ message.name }}</p>
                        </div>
                        <div class="card-body flex-grow-1">
                            <p class="mb-0">
                                {{ message.content }}
                            </p>
                        </div>
                    </div>
                    <img src="https://beforeigosolutions.com/wp-content/uploads/2021/12/dummy-profile-pic-300x300-1.png"
                        alt="avatar" class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="60">
                </li>
            </div>
        </ul>
        <form @click.prevent="sendMessage">

            <div class="form-outline">
                <textarea class="form-control" id="textAreaExample2" rows="4" placeholder="Message"></textarea>
                <!-- <label class="form-label" for="textAreaExample2">Message</label> -->
            </div>


            <button type="button" class="btn btn-info btn-rounded float-end mt-2">Send</button>
        </form>

    </div>
</template>
<style scoped>
.messages-box {
    height: 400px;
    overflow-y: scroll;
}
</style>
<script>
import { storeToRefs } from 'pinia';

import { useUsersStore } from '../stores/users';
import { useAuthStore } from '../stores/auth';
import { createPinia } from 'pinia';
import { createApp } from 'vue'
import { ref } from 'vue';
import App from '../App.vue'



export default {
    setup() {
        const pinia = createPinia()
        const app = createApp(App)
        app.use(pinia)
    },

    data: () => ({
        messageChannel: null,
        user: null,
        friend: null,
        // messages: [{
        //     from : 1,
        //     to: 2,
        //     content: "fafasdkfasdfjdsafds ffafasdkfasdfjdsafdsfafasdkfasdfjdsafdsfafasdkfasdfjdsafdsfafasdkfasdfjdsafdsfafasdkfasdfjdsafdsfafasdkfasdfjdsafdsfafasdkfasdfjdsafds",
        //     name: "Kenan"
        // },
        // {
        //     from : 2,
        //     to: 1,
        //     content: "fafasdkfasdfjfdsaffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff",
        //     name: "Nihad"
        // }],
        userStore: null
    }),
    mounted() {

        this.friend = this.$route.params.id;
        let userStore = useUsersStore();
        this.userStore = storeToRefs(userStore);
        // this.messages = userStore.getMessagesById(this.friend);
        console.log(userStore.getMessagesById(1));
        let authStore = useAuthStore();
        this.user = ref(authStore);
        console.log();
        this.messageChannel = new WebSocket(`ws://${window.location.hostname}:6001/socket/update-post?appKey=${import.meta.env.VITE_APP_WEBSOCKETS_KEY}`);
        this.messageChannel.onopen = (e) => {
            console.log("open")
        }

        this.messageChannel.onmessage = (e) => {
            // console.log(e);
        }
    },
    methods: {
        sendMessage() {
            // alert("Message sended")
            let messageContent = document.getElementById("textAreaExample2").value;
            if (messageContent) {
                // console.log(messageContent);
                this.sendMessageToServer(messageContent);
                useUsersStore().newMessage({
                    from: this.user?.user.id,
                    to: this.friend,
                    content: messageContent,
                    name: this.user?.user.name
                }, +this.friend);
                console.log(this.userStore.messages);
                // this.userStore.newMessage({
                //     from: this.user?.user.id,
                //     to: this.friend,
                //     content: messageContent,
                //     name: this.user?.user.name
                // }, this.friend);
                document.getElementById("textAreaExample2").value = "";
            }
            // console.log(this.messages.messages.get(+this.friend));
            // console.log()
        },
        sendMessageToServer(messageContent) {


            this.messageChannel?.send(JSON.stringify({
                from: this.user?.user.id,
                to: this.friend,
                content: messageContent
            }))

        }
    },
    updated() {
        this.friend = this.$route.params.id;
        console.log("updated");
        let a = document.getElementById('messagebox'); 
        a.scrollTop = a.scrollHeight;
        this.messages;
    },
    computed: {
        messages() {
            return this.userStore
        }
    }
}

</script>