<script >
import { RouterLink, RouterView } from 'vue-router';
import { useAuthStore } from './stores/auth';
export default {
  mounted() {
    console.log("app");
    let authStore = useAuthStore();
    authStore.getUser().then((response) => {
        // console.log(response.data.user)
        authStore.setUser(response.data);
        console.log("salam")
        console.log(authStore.user)
        // console.log(authStore.user)
        // this.$router.push("/");
      }).catch((error)=>{
        // console.log(error)
        authStore.removeToken()
        this.$router.push("/login");
        // browser.cookies.remove({
        //   name:'token'
        // })
      })
      
  },
  methods: {
    logout() {
      let authStore = useAuthStore();
      authStore.removeToken();
      this.$router.push("/login");
    },


  }

}
</script>

<template>
  <header>
      <h1>Chatsapp</h1>
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/chat">Chat</RouterLink>
        <RouterLink to="/login">Login</RouterLink>
        <button @click="logout">Logout</button>
      </nav>
  </header>

  <RouterView />
</template>

<style scoped>
header {
  line-height: 1.5;
  display: flex;
  justify-content: space-between;
  align-items: center;
}


nav {
  width: 100%;
  font-size: 12px;
  text-align: right;
  /* margin-top: 2rem; */
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  /* border-left: 1px solid var(--color-border); */
}

nav a:first-of-type {
  border: 0;
}
</style>
