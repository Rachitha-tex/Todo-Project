<template>
  <div class="container-fluid w-100">
    <nav class="navbar-expand-lg navbar bg-success  bg-body-tertiary">
      <div class="container ">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item ">
              <router-link class="nav-link text-white fw-bold active " :to="{ name: 'HomeComponent' }">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link text-white fw-bold " v-if="isLoggedIn" :to="{ name: 'TodoComponent' }">ToDo
                List</router-link>

            </li>
          </ul>

          <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
            <li class="nav-item">
              <router-link class="nav-link text-white fw-bold " v-if="!isLoggedIn"
                :to="{ name: 'LoginComponent' }">LogIn</router-link>

            </li>
            <li class="nav-item">
              <router-link class="nav-link text-white fw-bold " v-if="!isLoggedIn"
                :to="{ name: 'RegisterComponent' }">Register</router-link>

            </li>

            <li class="nav-item">
              <a href="" class="nav-link text-white fw-bold " v-if="isLoggedIn" @click="logout">LogOut</a>
            </li>
          </ul>

        </div>
      </div>
    </nav>
    <router-view @update-navbar="updateNavbar"></router-view>
  </div>
</template>

<script>
export default {
  name: 'ExampleComponent',
  data() {
    return {
      isLoggedIn: false
    }
  },
  methods: {
    /* Update navbar with user logging */
    updateNavbar() {
      this.isLoggedIn = !this.isLoggedIn
    },
    /* Logout user */
    async logout() {
      await axios.post('/api/logout')
        .then((response) => {
          this.$router.push({ name: 'LoginComponent' })
          localStorage.removeItem('authenticated');
          this.$emit('updateNavbar');
        })
        .catch((error) => {
          console.log(error);
        })
    }
  },
      /* Check if user already logged in */
  mounted() {
    if (localStorage.getItem('authenticated')) {
      this.isLoggedIn = true
    } else {
      this.isLoggedIn = false
    }
  }
}
</script>
