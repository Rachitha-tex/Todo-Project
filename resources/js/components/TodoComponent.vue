<template>
    <div class="d-flex justify-content-center ">
        <h3 class="text-center mt-4">Hello {{ name }}, Welocme Back!!!</h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="card w-100">
                <div class="card-header bg-dark">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="h4 text-white float-start fw-bold">All Tasks</div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                data-bs-target="#taskModal">New Task</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(task, index) in tasks" :key="task.id">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ task.title }}</td>
                                    <td>{{ task.date }}</td>
                                    <td>
                                        <span :class="`badge ${task.status == 0 ? 'bg-danger' : 'bg-success'}`">
                                            {{ task.status == 0 ? 'Incomplete' : 'Completed' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm mx-2" data-bs-target="#taskModal"
                                            data-bs-toggle="modal" @click="edit(task)">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                     
                                        <button class="btn btn-danger btn-sm mx-2" @click="deleteTask(task.id)">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                        <button class="btn btn-success btn-sm mx-1" v-if="task.status == 0"
                                            @click="marksCompleted(task.id)">
                                            Mark Completed
                                        </button>
                                        <button class="btn btn-warning btn-sm mx-1" v-if="task.status == 1"
                                            @click="marksInCompleted(task.id)">
                                            Mark Incompleted
                                        </button>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display modal to create new task -->
    <div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModallabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h1 class="modal-title fs-5 text-white" id="taskModallabel" v-if="isEdit">Edit Task</h1>
                    <h1 class="modal-title fs-5 text-white" id="taskModallabel" v-else>Create New Task</h1>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label fw-bold">Title</label>
                                <input type="text" :class="['form-control', errors.title ? 'is-invalid' : '']"
                                    v-model="fields.title" placeholder="Enter task">
                                <span class="text-danger fst-italic" v-if="errors.title">{{ errors.title[0] }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="" class="form-label fw-bold">Date</label>
                                <input type="date" :class="['form-control', errors.date ? 'is-invalid' : '']"
                                    placeholder="Enter date" v-model="fields.date">
                                <span class="text-danger fst-italic" v-if="errors.date">{{ errors.date[0] }}</span>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="form-label fw-bold">Description</label>
                                <textarea :class="['form-control', errors.description ? 'is-invalid' : '']"
                                    v-model="fields.description"></textarea>
                                <span class="text-danger fst-italic" v-if="errors.description">{{ errors.description[0]
                                }}</span>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeModal" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="editTask()" v-if="isEdit">Edit Task</button>
                    <button type="button" class="btn btn-primary" @click="saveTask" v-else>Create Task</button>
                </div>
            </div>
        </div>
    </div>
   
</template>

<script>
import axios from 'axios';

export default {
    name: 'TodoComponent',
    data() {
        return {
            fields: {
                id: '',
                title: '',
                date: '',
                description: ''
            },
            tasks: [],
            errors: [],
            isEdit: false,
            name: '',
        }
    },
    mounted() {
        /* Display data on mounted */
        this.getTasks();

        /* get user details  */
        axios.get('/api/user')
            .then((response) => {
                this.name = response.data.name
            })
            .catch((error) => {
                if (error.response.status === 401) {
                    this.$emit('updateNavbar')
                    localStorage.removeItem('authenticated');
                    this.$router.push({ name: 'LoginComponent' })
                }
            })
    },
    methods: {
        /* Get all tasks from the database */
        async getTasks() {
            await axios.get('/api/tasks')
                .then((response) => {
                    this.tasks = response.data
                }).catch((error)=>{
                    console.log(error);
                })
        },

        //Save new task data
        async saveTask()
         {
            await axios.post('/api/tasks', this.fields)
                .then((response) => {
                    if (this.fields.title != '' && this.fields.date != '' && this.description != '' && response.data.status == 'success') {
                        this.fields = ''
                        this.errors = []
                        this.isEdit = false
                        this.getTasks();
                        Toast.fire({
                            icon: 'success',
                            title: 'Created successfully!!'
                        })
                    }
                    document.getElementById('closeModal').click();
                }).catch((error) => {
                    this.errors = error.response.data.errors;
                })
        },
        /* auto filled task selected for editing  */
        edit(task) {
            this.fields = task
            this.isEdit = true
        },

        /* Update task */
        async editTask() 
        {
            await axios.put('/api/tasks/' + this.fields.id, this.fields)
                .then((response) => {
                    if (response.data.status == 'success') {
                        this.fields = ''
                        this.errors = []
                        this.getTasks();
                        this.isEdit = false

                        Toast.fire({
                            icon: 'success',
                            title: 'Updated successfully!!'
                        })
                    }

                    document.getElementById('closeModal').click();

                })
                .catch((error) => {
                    this.errors = error.response.data.errors;
                })
        },

        /* Delete selected data from the database */
        async deleteTask(task) 
        {
            Swal.fire({
                title: 'Are you sure to delete?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/tasks/' + task)
                        .then((response) => {
                            if (response.data.status == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            }
                            this.getTasks();
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            })
        },
        /* Marked the incompleted task as completed task */
        async marksCompleted(task) {
            Swal.fire({
                title: 'Are you sure to make task as completed?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/tasks/markcompleted/' + task)
                        .then((response) => {
                            if (response.data.status == 'success') {
                                Swal.fire(
                                    'Completed!',
                                    'Your file has been updated.',
                                    'success'
                                )
                            }
                            this.getTasks();
                        })
                        .catch((error) => {
                            console.log(error);
                        })
                }
            })
        },

        /* Mark the completed data as incompleted */
        async marksInCompleted(task)
        {
            Swal.fire({
                title: 'Are you sure to make task as Incompleted?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/tasks/markincompleted/' + task)
                        .then((response) => {
                            if (response.data.status == 'success') {
                                Swal.fire(
                                    'Completed!',
                                    'Your file has been updated.',
                                    'success'
                                )
                            }
                            this.getTasks();
            }).catch((error) => {
                            console.log(error);
                        })
                }
            })
        },

    }
}
</script>

<!-- mark borders with red border for empty input  -->
<style scoped>
.is-invalid {
    border: 1px solid red;
}
</style>