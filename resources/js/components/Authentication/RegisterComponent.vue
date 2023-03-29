<template>
    <div class="container row justify-content-center mt-5 pt-4">
        <div class="col-md-5">
            <form @submit.prevent="resgisterUser">
                <div class="card">
                    <div class="card-header text-center bg-primary">
                        <h4 class="text-white">Register</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group my-2">
                            <input id="name" type="text" class="form-control" placeholder="Enter Name" v-model="users.name">
                            <span class="text-danger fst-italic fw-bold" v-if="errors.name">{{ errors.name[0] }}</span>
                        </div>
                        <div class="form-group my-2">
                            <input id="email" type="email" v-model="users.email" class="form-control"
                                placeholder="Enter email">
                            <span class="text-danger fst-italic fw-bold" v-if="errors.email">{{ errors.email[0] }}</span>
                        </div>

                        <div class="form-group my-2">
                            <input id="password" type="password" v-model="users.password" class="form-control"
                                placeholder="Enter password">
                            <span class="text-danger fst-italic fw-bold" v-if="errors.password">{{ errors.password[0]
                            }}</span>
                        </div>


                        <div class="form-group my-2">
                            <input type="password" id="password_confirmation" v-model="users.password_confirmation"
                                class="form-control" placeholder="confirm password">

                        </div>
                        <div class="form-group my-2">
                            <button type="submit" class="btn btn-primary mr-5">Register</button>
                            <span class="mx-5">Have an account? <router-link
                                    :to="{ name: 'LoginComponent' }">Login</router-link> </span>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'RegisterComponent',
    data() {
        return {
            users: {
                name: '',
                email: '',
                password: ''
            },
            errors: []
        }
    },
    methods: {
        /* Register users */
        async resgisterUser() {
            await axios.post('/api/register', this.users)
                .then(() => {
                    this.$router.push({ name: 'TodoComponent' })
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        }
    }
}
</script>

<style scoped></style>