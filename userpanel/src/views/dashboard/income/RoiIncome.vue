<template>
  <div>

    <div class="activation-income cus_card-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="activation-card">
              <div class="activation-card-header d-flex justify-content-between align-items-center p-4 rounded-3">
                <h2 class="m-0">Dividend</h2>
                <div class="search-input">
                  <div class="input-group rounded-2">
                    <input type="text" class="form-control  bg-transparent  border-0 shadow-none"
                      placeholder="Search By Roi bonus" aria-label="Username" aria-describedby="basic-addon1"
                      v-model="searchQuery" style="color:aliceblue">
                    <span class="input-group-text bg-transparent shadow-none border-0 " id="basic-addon1"><svg
                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        style="fill: var(--place-holder);transform: ;msFilter:;">
                        <path
                          d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z">
                        </path>
                      </svg></span>
                  </div>
                </div>
              </div>
              <div class="activation-card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr class="activation-card-head">
                        <th>date</th>
                        <th>daily dividend</th>
                        <th>package</th>
                        <!--<th>plan started</th>-->
                        <!--<th>status</th>-->
                      </tr>
                    </thead>
                    <!--
                    <thead>
                      <tr class="activation-card-head">

                        <th>
                          <input type="date" class="text-search-input form-control p-2" v-model="date"
                            @change="getBinary">
                        </th>
                        <th>
                          <form @submit.prevent="getBinary">
                            <input type="text" class="text-search-input form-control p-2" placeholder="Search Roi Bonus"
                              v-model="roi_income">
                          </form>
                        </th>

                        <th>
                          <form @submit.prevent="getBinary">
                            <input type="text" class="text-search-input form-control p-2" placeholder="Search USDT Amount"
                              v-model="usdt_amount">
                          </form>
                        </th>

                        <th>
                          <input type="date" class="text-search-input form-control p-2" v-model="plan_start_at"
                            @change="getBinary">
                        </th>

                        <th>
                          <form class="search-select">
                            <select name="" class="form-select" v-model="status" @change="getBinary">
                              <option value="">Choose</option>
                              <option value="opened">Opened</option>
                              <option value="expired">Expired</option>
                            </select>
                          </form>
                        </th>
                      </tr>
                    </thead> -->

                    <tbody v-if="!loading">
                      <tr v-if="filteredItems.length == 0">
                        <td colspan="10" class="text-center" style="color:var(--white)">No Record Found</td>
                      </tr>
                      <template v-else>
                        <tr class="activation-card-data" v-for="(data, index) in filteredItems" :key="index">




                          <td>{{ data.date }}</td>
                          <td>${{ parseFloat(data.roi_income) }}</td>
                          <td>${{ parseFloat(data.staked_plan.usdt_amount) }}</td>
                          <!--<td>{{ data.staked_plan.plan_start_at }}</td>-->
                          <!--
                          <td>
                            <span class="px-3 py-2 rounded-0"
                              :style="data.staked_plan.status == 'opened' ? 'background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b' : 'background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e'">
                              {{ data.staked_plan.status }}
                            </span>
                          </td>
                          -->
                        </tr>
                      </template>
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


    <div class="row">
      <div class="col-md-12">
        <div class="pagination_box d-flex justify-content-end mt-4" style="color:white">
          <pagination v-model="page" :records="recordData" :per-page="perPageData" :options="options"
            @paginate="getBinary" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ApiClass from '../../../api/api';
export default {
  name: 'RoiIncome',

  data() {
    return {
      tree: [],
      loading: true,
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
      date: '',
      roi_income: '',
      usdt_amount: '',
      plan_start_at: '',
      status: ''
    }
  },
  computed: {
    filteredItems: function () {
      var searchQuery = this.searchQuery.toLowerCase();
      return this.tree.filter(function (item) {
        return item.roi_income.toLowerCase().indexOf(searchQuery) !== -1
      });
    }
  },
  setup() {
  },
  async mounted() {
    await this.getBinary()
  },
  methods: {
    async getBinary() {
      this.loading = true;
      let response = await ApiClass.getRequest(`income/roi?page=${this.page}&per_page=${this.perPageData}&date=${this.date}&roi_income=${this.roi_income}&usdt_amount=${this.usdt_amount}&plan_start_at=${this.plan_start_at}&status=${this.status}`, true);

      if (response?.status == 200) {
        this.tree = response?.data?.data?.data
        this.loading = false;
        this.recordData = parseInt(response?.data?.data?.total)
        this.perPageData = parseInt(response?.data?.data?.per_page)

      }
    },
  },


}
</script>
