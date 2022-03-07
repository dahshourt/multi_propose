<template>
    <div class="container">
        <div class="row justify-content-center" >
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header text-white" style="background: url('./dist/img/photo1.png') center center;">
                        <h3 class="widget-user-username text-right">{{this.form.name}}</h3>
                        <h5 class="widget-user-desc text-right">{{this.form.role}}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="profile-user-img img-fluid img-circle" :src="getPhoto()" alt="User profile picture">

                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>





            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">

                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">


                        <div class="tab-content">

                            <div class="tab-pane active" id="settings">
                                <form class="form-horizontal" @submit.prevent="uploaddata">
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text"  v-model="form.name" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email"  v-model="form.email" class="form-control" id="inputEmail" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">profile</label>
                                        <div class="col-sm-10">
                                            <input type="file" class="form-control" id="inputName2"  @change="uploadPhoto">
                                        </div>
                                    </div>



                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger" >Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>

        </div>
    </div>
</template>

<script>
    import Form from "vform";

    export default {
        data(){
      return {

                form: new Form({
                    id:'',
                    name: '',
                    email:'',
                    password: '',
                    photo:  '',
                    role:'',
                })
            }
        }
    ,
        methods:{
            uploaddata(){
                this.form.post('api/user')
                    .then(()=>{

                        Toast.fire({
                            icon: 'success',
                            title: 'File uploaded successfully'
                        })
                        this.loadTableData()
                    })
                    .catch(()=>{
                        console.log("Error.....")
                    })
            },
            uploadPhoto(e){
                let file = e.target.files[0];
                let reader = new FileReader();

                if(file['size'] < 2111775)
                {
                    reader.onloadend = (file) => {
                        //console.log('RESULT', reader.result)
                        this.form.photo = reader.result;
                    }
                    reader.readAsDataURL(file);
                }else{
                    alert('File size can not be bigger than 2 MB')
                }
            },

            //For getting Instant Uploaded Photo
            getPhoto(){



                let photo = (this.form.photo.length > 100) ?this.form.photo : "img/profile/"+ this.form.photo;
                return photo;
            }

        },
        mounted() {

        },
        created() {
            axios.get('/api/profile').then(({data})=>(this.form.fill(data)));
        }
    }
</script>
