import './bootstrap';
import { createApp } from 'vue';
import '../sass/app.scss'
import router from './router';
import Swal from 'sweetalert2'

const app = createApp({});

/* Sweet alert function */
window.Swal=Swal;
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  
window.Toast=Toast


/* Components */

import ExampleComponent from './components/ExampleComponent.vue';
import HomeComponent from './components/HomeComponent.vue';
import TodoComponent from './components/TodoComponent.vue'

app.component('example-component', ExampleComponent);
app.use(router)
app.component('home-component',HomeComponent);
app.component('todo-component', TodoComponent);

app.mount('#app');
