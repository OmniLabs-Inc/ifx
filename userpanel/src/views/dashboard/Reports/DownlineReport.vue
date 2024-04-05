<template>
  <div>
      <div class="activation-income cus_card-1">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-md-12">
                      <div class="activation-card">
                          <div
                              class="activation-card-header d-flex justify-content-between align-items-center pt-4 px-3 pb-3 rounded-3">
                              <h2 class="m-0">Downline Report</h2>
                              <div class="search-input">
                                  <div class="input-group rounded-2">
                                      <input type="text" class="form-control bg-transparent  border-0 shadow-none"
                                          placeholder="Search Unique Id" aria-label="Username" aria-describedby="basic-addon1"
                                          v-model="search" @change="getReport({ page : 1 , perpage : 15})" style="color:var(--white)">
                                      <span class="input-group-text bg-transparent shadow-none border-0 "
                                          id="basic-addon1" ><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
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
                                              <th>Sr No</th>
                                              <th>Unique Id</th>
                                              <th>Sponser Id</th>
                                              <th>name</th>
                                              <th>email</th>
                                              <th>Active</th>
                                              <th>View</th>
                                          </tr>
                                      </thead>
                                      <tbody v-if="!loading">
                                          <tr class="activation-card-data" v-for="(data, index) in reportdata"
                                              :key="index">
                                              <td>{{ index + 1 }}</td>
                                              <td>{{ data.user_unique_id }}</td>
                                              <td>{{ data.user_sponser_id }}</td>
                                              <td>{{ data.name }}</td>
                                              <td>{{ data.email }}</td>
                                            
                                              <td>
                                                  <span class="px-3 py-2 rounded-0" v-if="data.plan_active == 1"
                                                      style="background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b">
                                                      Active
                                                  </span>
                                                  <span class="px-3 py-2 rounded-0" v-else
                                                      style="background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e">
                                                      Not Active
                                                  </span>
                                              </td>
                                              <td>
                                                 <router-link :to="'/direct-detail/'+data.id">
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target=".bd-example-modal-lg">
                                                    view
                                                </button>
                                                </router-link>
                                              </td>
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
                                              <td>
                                                  <Skeletor />
                                              </td>
                                              <td>
                                                  <Skeletor />
                                              </td>
                                              <td>
                                                  <Skeletor />
                                              </td>
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

      <div class="search-input">
            <label for="inputGroupSelect01">Per Page</label>
            <div class="input-group rounded-2">
                
                <select class="mt-2" id="inputGroupSelect01" v-model="perPageData" @change="getReport({ page : 1, perpage : perPageData})">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select> 
            </div>
        </div>


      <!-- pagination start -->
      <div class="col-md-12">
          <div class="pagination_box d-flex justify-content-end mt-4" style="color:white">
              <pagination v-model="page" :records="total" :per-page="perPageData" :options="options"
                  @paginate="getReport({ page : page, perpage : perPageData})" />
          </div>
      </div>
      <!-- pagination end -->


      
  </div>
</template>

<script>
import ApiClass from '../../../api/api.js';
export default {
  name: 'DownlineReport',


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
//   computed: {
//       filteredReport: function () {
//           var search = this.search.toLowerCase();
//           return this.reportdata.filter(function (item) {
//               return item.user_unique_id.toLowerCase().indexOf(search) !== -1
//           });
//       }
//   },
  mounted() {
    this.getReport({ page : this.page , perpage: this.perPageData});
       
  },
  methods: {
      async getReport({ page , perpage}) {
          this.loading = true;
          this.page = page;
          let res = await ApiClass.getRequest(`downline_report?page=${page}&per_page=${perpage}&unique_id=${this.search}`, true);
          this.loading = false;
          if (res.data.status_code == 1) {
              this.reportdata = res.data.data.data
              this.total = res.data.data.total;
              return
          }
      },
      handleRank(v) {
          let result = {
              0: "background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e",
              1: "background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b"
          }
          return result[v];
      }
  }



}
</script>

<style scoped></style>
