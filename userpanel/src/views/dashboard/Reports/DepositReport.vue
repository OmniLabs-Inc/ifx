<template>
    <div>
        <div class="activation-income cus_card-1">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="activation-card">
                  <div class="activation-card-header d-flex justify-content-between align-items-center pt-4 px-3 pb-3 rounded-3">
                      <h2 class="m-0">Deposit Report</h2>
                      <div class="search-input">
                        <div class="input-group rounded-2">
                          <input type="text" class="form-control bg-transparent  border-0 shadow-none" placeholder="Search By USDT amount" aria-label="Username" aria-describedby="basic-addon1" v-model="searchQuery"  style="color:var(--white)">
                          <span class="input-group-text bg-transparent shadow-none border-0 " id="basic-addon1"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="fill: var(--place-holder);transform: ;msFilter:;"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"></path></svg></span>

                        </div>
                      </div>
                  </div>
                  <div class="activation-card-body">
                    <div class="table-responsive text-nowrap">
                      <table class="table">
                        <thead>
                          <tr class="activation-card-head">
                            <th>date</th>
                            <th>amount</th>
                            <th>transaction id</th>
                            <th>wallet </th>


                          </tr>
                        </thead>
                        <tbody  v-if="!loading">
                          <tr v-if="filteredItems.length == 0">
                            <td colspan="10" class="text-center" style="color:var(--white)">No Record Found</td>
                          </tr>
                          <template v-else>
                          <tr class="activation-card-data"  v-for="(data, index) in filteredItems" :key="index">
                            <td>{{ new Date(data.created_at).toLocaleDateString() }}</td>
                            <td>${{ parseFloat(data.usdt_amount) }}</td>
                            <td>{{ data.transaction_id.slice(0, 20) + '...' }}</td>
                            <td>{{data.from_wallet_address}}</td>
                            <!--<td>
                              <a :href="`https://bscscan.com/tx/${data.hash}`" style="color:var(--white)" target="_blank"> {{ data.hash }}</a>
                             </td>-->

                          </tr>
                        </template>

                        </tbody>

                        <tbody v-else>
                          <tr class="activation-card-data"  v-for="i in 10" :key="i">
                            <td><Skeletor /></td>
                            <td><Skeletor /></td>
                            <td><Skeletor /></td>
                            <td><Skeletor /></td>
                            <td><Skeletor /></td>
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
                    <pagination v-model="page" :records="recordData" :per-page="perPageData" :options="options" @paginate="referrals" />
                </div>
            </div>
        <!-- pagination end -->
    </div>
  </template>

  <script>
  import ApiClass from '../../../api/api';
import TransferReportJson from '../../../assets/json/Dashboard/Reports/TransferReport.json';
  export default {
    name: 'DepositReport',


      data(){
        return{
          tree:[],
          loading:true,
          page: 1,
          recordData: 100,
          perPageData: 10,
          searchQuery: '',
          options: {
              edgeNavigation: false,
              chunksNavigation: false,
              chunk: 3,
              texts: false,
              format: false,
        },
        }
      } ,
      computed: {
filteredItems: function() {
  var searchQuery = this.searchQuery.toLowerCase();
  return this.tree.filter(function(item) {
    return item.usdt_amount.toLowerCase().indexOf(searchQuery) !== -1
  });
}
},
setup() {
  },
      async mounted(){
      await this.getBinary()
  },
  methods: {
      async getBinary(){
          let response = await ApiClass.getRequest(`deposit/report?page=${this.page}&per_page=${this.perPageData}`, true);

          if(response?.status==200){
              this.tree = response?.data?.data?.data
              this.loading = false;
             console.log(response?.data?.data?.per_page);
              this.recordData = parseInt(response?.data?.data?.total)
              this.perPageData = parseInt(response?.data?.data?.per_page)

          }
      },

  },



  }
  </script>

  <style scoped>
  </style>
