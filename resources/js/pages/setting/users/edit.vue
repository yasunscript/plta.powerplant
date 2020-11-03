<template>

    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Quick Example</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form">
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" v-model="datas.name" id="name" placeholder="Type your name">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" v-model="datas.username" id="username" placeholder="Type Username">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" v-model="datas.email" placeholder="Type Email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" v-model="datas.password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" v-model="datas.role">
                            <option value="">Pilih</option>
                            <option v-for="(row, index) in roles" :value="row.id" :key="index" >{{ row.name }}</option>
                        </select>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <router-link :to="{ name: 'users' }" class="btn btn-default">Back</router-link>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    name: "editUser",
    data() {
        return {
            datas: {
                name: '',
                username: '',
                email: '',
                role: ''
            },
        }
    },
    computed: {
        ...mapState(['errors']), //ME-LOAD STATE ERRORS
        ...mapState('user', {
            roles: state => state.roles, //ME-LOAD STATE ROLES
        })
    },
    created() {
        this.getUserById(this.$route.params.userId).then((response)=>{
            this.datas = response.data
        })
        this.getRoles()
    },
    methods:{
        ...mapActions('user', [
            'getUserById',
            'getRoles',
        ]),
    }
}
</script>

<style scoped>

</style>
