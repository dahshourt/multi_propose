<template>
    <div class="container">


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Responsive Hover Table</h3>

                        <div class="card-tools">
                          <button class="btn btn-success" @click="newmodal" >
                              add user <i class="fas fa-users"></i>
                          </button>
                            <!-- Button trigger modal -->

                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>name</th>
                                <th>email</th>

                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <tr v-for="(user,index) in users.data" :key="index">
                                <td>{{pag+ index+1}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td><a @click="deleteuser(user.id)" class="btn">
                                    <i class="fas fa-trash"></i> </a>
                                    /
<a class="btn" @click="editmodal(user)">
    <i class="fas fa-edit"></i>

</a>


                                </td>
                            </tr>



                            </tbody>
                        </table>
                    </div>
                    <pagination align="center" :data="users" @pagination-change-page="list"></pagination>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="edituser?update():login()" @keydown="form.onKeydown($event)">

                    <div class="modal-body">
                            <div class="form-group">
                                <input  class="form-control" v-model="form.name" type="text" name="name" placeholder="name">
                                <div v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
                            </div>

<div class="form-group">
                            <input  class="form-control" v-model="form.email" type="email" name="email" placeholder="email">
                            <div v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
</div>
                        <div class="form-group">
                            <select  class="form-control" v-model="form.role"  name="role">

                                <option value="admin" >admin</option>
                                <option value="user">user</option>

                            </select>



                        </div>


                            <div class="form-group">

                            <input  class="form-control" v-model="form.password" type="password" name="password" placeholder="Password">
                            <div v-if="form.errors.has('password')" v-html="form.errors.get('password')" />
                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" v-show="!edituser">created</button>
                        <button type="submit" class="btn btn-primary" v-show="edituser">update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import Form from 'vform'
    import pagination from 'laravel-vue-pagination'

    export default {
        components:{
            pagination
        },
        data: () => ({

            users:{
                type:Object,
                default:null
            },
            pag:1,
            edituser:false,
            form: new Form({
                id:'',
                name: '',
                email:'',
                password: '',
                role:''
            })
        }),

        methods: {
            editmodal(user){
                this.edituser=true;
                this.form.reset();
                $('#exampleModal').modal('show');
                this.form.fill(user);

            }
            ,
            newmodal(){
                 this.edituser=false;
                this.form.reset();
                $('#exampleModal').modal('show');

            },

            deleteuser(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.form.delete('anpmpi/user/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.$emit('aftercreate');
                            }

                        ).catch(()=>{
                            Swal.fire(
                                'something!',
                                'Your file has been deleted.',
                                'warning'
                            )
                            }

                        )




                    }
                })
            },

            async list(page=1){

                await axios.get(`/api/user?page=${page}`).then(({data})=>{
                    this.pag=(page*5)-5
                    this.users = data
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
            loaduser(){

                this.form.get('/api/user').then((data)=>(this.users=data.data));

            },
            async login () {

                this.$Progress.start();
                const response = await this.form.post('/api/user').then(()=>{
                    Toast.fire({
                        icon: 'success',
                        title: 'created successfully'
                    })

                    this.$Progress.finish();
                    this.$emit('aftercreate');
                    $('#exampleModal').modal('hide');
                    this.form.reset();
                }).catch(()=>{
                    this.$Progress.fail();

                });

                $(".modal-backdrop").remove();


                // ...
            },
            update(){
                this.form.put('/api/user/'+this.form.id).then(()=>{
                     $('#exampleModal').modal('hide');
                     Swal.fire(
                                'updated',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.$emit('aftercreate');
                }).catch();
            }
        },
        created(){
            this.loaduser();
            this.$on('aftercreate',()=>{
                this.loaduser();
            });

            Fire.$on('searchuser',()=>{

                let query=this.$parent.search;

              axios.get('api/finduser?q='+query).then((data)=>{
                  this.users=data.data;
              })
            });
        },
        mounted() {
            this.list();
            console.log('Component mounted.')
        }
    }
</script>
