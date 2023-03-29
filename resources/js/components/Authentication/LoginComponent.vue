<template>
      <div class="container row justify-content-center mt-5 pt-4">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header text-center bg-primary">
                    <h4 class="text-white">Login</h4>
                </div>
                <div class="card-body">                    
                    <form @submit.prevent="loginUser">
                        <div class="form-group my-2">
                            <input class="form-control" type="text" placeholder="Enter Email" v-model="fields.email" >
                            <span v-if="errors.email"  class="text-danger fw-bold fst-italic">{{ errors.email[0] }}</span>
                        </div>
                        <div class="form-group my-2">
                            <input type="password" class="form-control" placeholder="Enter Password"  v-model="fields.password">
                            <span v-if="errors.password"  class="text-danger fw-bold fst-italic">{{ errors.password[0] }}</span>
                        </div>
                        <button class="btn btn-primary" type="submit">Log In</button>
                         <span class="mx-5">Don't have an account? <router-link :to="{name:'RegisterComponent'}">Register</router-link> </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        name:'LoginComponent',
        data(){
        return{
            fields:{
                email:'',
                password:''
            },
            errors:[]
        }
    },
    methods:{
        /* Login user */
       async loginUser(){
        await axios.post('/api/login',this.fields)
        .then(()=>{
            this.$router.push({name:'TodoComponent'})
            //check weather user already logged in
            localStorage.setItem('authenticated','true');
            this.$emit('updateNavbar');
        })
        .catch((error)=>{
            this.errors=error.response.data.errors;
        })
        }
    }
    }
</script>

<style scoped>

</style>