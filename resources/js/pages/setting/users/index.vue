<template>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data List Users</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <app-datatable
                        :items="datas"
                        :fields="fields"
                        :meta="meta"
                        :title="'Delete Users'"
                        @per_page="handlePerPage"
                        @pagination="handlePagination"
                        @search="handleSearch"
                        @sort="handleSort"
                        @delete="handleDelete"
                        @edit="handleEdit"
                    />
                </div>
                <!-- /.card-body -->
            </div>
        </div>
</template>

<script>
import Datatable from '../../../components/layouts/DataTable.vue'
import axios from 'axios'
import {mapActions} from "vuex";
export default {
    name: "Users",
    created() {
        //MAKA AKAN MENJALANKAN FUNGSI BERIKUT
        this.loadPostsData()
    },
    data() {
        return {
            //UNTUK VARIABLE FIELDS, DEFINISIKAN KEY UNTUK MASING-MASING DATA DAN SORTABLE BERNILAI TRUE JIKA INGIN MENAKTIFKAN FITUR SORTING DAN FALSE JIKA TIDAK INGIN MENGAKTIFKAN
            fields: [
                {key: 'name', sortable: true},
                {key: 'username', sortable: true},
                {key: 'email', sortable: true},
                {key: 'created_at', sortable: true},
                {key: 'actions', sortable: false},
            ],
            datas: [
            ],

            meta: [], //JUGA BERLAKU UNTUK META
            current_page: 1, //DEFAULT PAGE YANG AKTIF ADA PAGE 1
            per_page: 10, //DEFAULT LOAD PERPAGE ADALAH 10
            search: '',
            sortBy: 'created_at', //DEFAULT SORTNYA ADALAH CREATED_AT
            sortByDesc: false //ASCEDING
        }
    },
    components: {
        'app-datatable': Datatable //REGISTER COMPONENT DATATABLE
    },
    methods: {
        ...mapActions('user', [
            'getUserListsDataTable',
            'deleteUser'
        ]),
        //METHOD INI AKAN MENGHANDLE REQUEST DATA KE API
        loadPostsData() {
            let current_page = this.search == '' ? this.current_page:1

            this.getUserListsDataTable({
                page: current_page,
                per_page: this.per_page,
                q: this.search,
                sortby: this.sortBy,
                sortbydesc: this.sortByDesc ? 'DESC':'ASC'
            }).then((response) => {
                let getData = response.data
                this.datas = getData.data //MAKA ASSIGN DATA POSTINGAN KE DALAM VARIABLE ITEMS
                //DAN ASSIGN INFORMASI LAINNYA KE DALAM VARIABLE META
                this.meta = {
                    total: getData.total,
                    current_page: getData.current_page,
                    per_page: getData.per_page,
                    from: getData.from,
                    to: getData.to
                }

            });

        },
        //JIKA ADA EMIT TERKAIT LOAD PERPAGE, MAKA FUNGSI INI AKAN DIJALANKAN
        handlePerPage(val) {
            this.per_page = val //SET PER_PAGE DENGAN VALUE YANG DIKIRIM DARI EMIT
            this.loadPostsData() //DAN REQUEST DATA BARU KE SERVER
        },
        //JIKA ADA EMIT PAGINATION YANG DIKIRIM, MAKA FUNGSI INI AKAN DIEKSEKUSI
        handlePagination(val) {
            this.current_page = val //SET CURRENT PAGE YANG AKTIF
            this.loadPostsData()
        },
        handleSearch(val) {
            this.search = val
            this.loadPostsData()
        },
        handleSort(val) {
            //MAKA SET SORT-NYA
            this.sortBy = val.sortBy
            this.sortByDesc = val.sortDesc

            this.loadPostsData()
        },
        handleDelete(val) {
            this.deleteUser(val).then((response) => {
                this.loadPostsData()
            })
        },
        handleEdit(val) {
            this.$router.push({ name: 'users.edit', params: {userId:val.item.id } })
        }
    }
}
</script>

<style scoped>

</style>
