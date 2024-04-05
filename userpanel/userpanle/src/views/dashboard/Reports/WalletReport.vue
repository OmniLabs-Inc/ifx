<template>
    <div>
        <div class="activation-income cus_card-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="activation-card">
                            <div
                                class="activation-card-header d-flex justify-content-between align-items-center pt-4 px-3 pb-3 rounded-3">
                                <h2 class="m-0">Wallet Report</h2>
                                <div class="search-input">
                                    <div class="input-group rounded-2">
                                        <input type="text" class="form-control bg-transparent  border-0 shadow-none"
                                            placeholder="Search" aria-label="Username" aria-describedby="basic-addon1"
                                            v-model="search" style="color:var(--white)">
                                        <span class="input-group-text bg-transparent shadow-none border-0 "
                                            id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 24 24"
                                                style="fill: var(--place-holder);transform: ;msFilter:;">
                                                <path
                                                    d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z">
                                                </path>
                                            </svg></span>

                                    </div>
                                </div>
                            </div>
                            <div class="activation-card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead>
                                            <tr class="activation-card-head">
                                                <th>date</th>
                                                <th>comment</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="!loading">
                                            <tr class="activation-card-data" v-for="(data, index) in filteredReport"
                                                :key="index">
                                                <td>{{ new Date(data.created_at).toLocaleDateString() }}</td>
                                                <td>{{ data.comment }}</td>
                                            </tr>

                                        </tbody>

                                        <tbody v-else>
                                            <tr class="activation-card-data" v-for="i in 10" :key="i">
                                                <td>
                                                    <Skeletor />
                                                </td>
                                                <td>
                                                    <Skeletor />
                                                </td>
                                                
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- pagination start -->
        <div class="col-md-12">
            <div class="pagination_box d-flex justify-content-end mt-4" style="color:white">
                <pagination v-model="page" :records="total" :per-page="perPageData" :options="options"
                    @paginate="getReport" />
            </div>
        </div>
        <!-- pagination end -->
    </div>
</template>
  
<script>
import ApiClass from '../../../api/api.js';
export default {
    name: 'WalletReport',


    data() {
        return {
            loading: true,
            page: 1,
            perPageData: 10,
            options: {
                edgeNavigation: false,
                chunksNavigation: false,
                chunk: 3,
                texts: false,
                format: false,
            },
            reportdata: [],
            total: 10,
            search: ""
        }
    },
    computed: {
        filteredReport: function () {
            var search = this.search.toLowerCase();
            return this.reportdata.filter(function (item) {
                return item.comment.toLowerCase().indexOf(search) !== -1
            });
        }
    },
    mounted() {
        this.getReport()
    },
    methods: {
        async getReport() {
            this.loading = true;
            let res = await ApiClass.getRequest(`wallet/report?page=${this.page}&per_page=${this.perPageData}`, true);
            this.loading = false;
            if (res.data.status_code == 1) {
                this.reportdata = res.data.data.data
                this.total = res.data.data.total;
                return
            }
        },
    }



}
</script>
  
<style scoped></style>
  