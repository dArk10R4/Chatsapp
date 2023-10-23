<template>
        <div class="container border-1 border p-5 inline-block rounded mt-5">
            <form @submit.prevent="login">
                <h2 class="mb-3">Login</h2>
                <div class="input">
                <label for="email">Email address</label>
                <input
                    class="form-control"
                    type="email"
                    name="email"
                    placeholder="email@adress.com"
                />
                </div>
                <div class="input">
                <label for="password">Password</label>
                <input
                    class="form-control"
                    type="password"
                    name="password"
                    placeholder="password123"
                />
                </div>
                <div class="alternative-option mt-4">
                You don't have an account? <span @click="moveToRegister" style="cursor: pointer; color: blue;">Register</span>
                </div>
                <button type="submit" class="mt-4 btn-pers" id="login_button">
                Login
                </button>
                <div
                class="alert alert-warning alert-dismissible fade show mt-5 d-none"
                role="alert"
                id="alert_1"
                >
                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                ></button>
                </div>
            </form>
        </div>
  </template>
  
<script>
//   import { getAuth, signInWithEmailAndPassword } from "firebase/auth";
import {useAuthStore} from '../stores/auth';
export default {
data() {
    return {
    email: "",
    password: "",
    };
},
methods: {
    login(submitEvent) {
    this.email = submitEvent.target.elements.email.value;
    this.password = submitEvent.target.elements.password.value;

    let authStore = useAuthStore();
    authStore.login({
        'email':this.email,
        'password':this.password
    }).then((response) => {
        console.log(response.data.user)
        authStore.setUser(response.data.user);
        authStore.setToken(response.data.user.token)
        authStore.isLogedIn = true;
        this.$router.push("/");
      })
      .catch((error) => {
        const errorCode = error.code;
        const errorMessage = error.response.data.message;
        console.log(errorCode);
        console.log(errorMessage);
        let alert_1 = document.querySelector("#alert_1");
        alert_1.classList.remove("d-none");
        alert_1.innerHTML = errorMessage;
        console.log(alert_1);
        setTimeout(() => {
          alert_1.classList.add("d-none");
        },5000);
      });
    // const auth = getAuth();
    // signInWithEmailAndPassword(auth, this.email, this.password)
    //   
    },
    moveToRegister() {
    this.$router.push("/register");
    },
},
};
</script>

<style scoped>
.sing_in_container {
    display: inline-block !important;

}
</style>