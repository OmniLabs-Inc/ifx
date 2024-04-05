<template>
  <div>

    <div class="activation-income cus_card-1">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="activation-card">
              <div class="activation-card-header d-flex justify-content-between align-items-center p-4 rounded-3">
                <h2 class="m-0">self investment</h2>
                <div class="search-input">
                  <div class="input-group rounded-2">
                    <input type="text" class="form-control  bg-transparent  border-0 shadow-none"
                      placeholder="Search by Usdt Amount" aria-label="Username" aria-describedby="basic-addon1"
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
                        <!-- <th>plan name</th>
                              <th>plan days</th>
                               -->
                        <th>amount</th>
                        <th>daily roi </th>
                        <th>joined date</th>
                        <th>mature date</th>
                        <th>status</th>

                      </tr>
                    </thead>
                    <tbody v-if="!loading">
                      <tr v-if="filteredItems.length == 0">
                        <td colspan="10" class="text-center" style="color:var(--white)">No Record Found</td>
                      </tr>
                      <template v-else>
                        <tr class="activation-card-data" v-for="(data, index) in filteredItems" :key="index">
                          <!-- <td>{{ data.staked_plan.plan.name }}</td>
                            <td>{{ data.staked_plan.plan.days }}</td> -->



                          <td>${{ parseFloat(data.usdt_amount) }}</td>
                          <td>${{ data.daily_roi_income }}</td>
                          <td>{{ data.plan_start_at }}</td>
                          <td>  {{ this.getMaturityDate(data.plan_start_at) }} </td>
                          <td>
                            <span class="px-3 py-2 rounded-0"
                              :style="data.status == 'opened' ? 'background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b' : 'background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e'">
                              {{ data.status }}
                            </span>
                          </td>

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
  name: 'StakePlan',

  data() {
    return {
      tree: [],
      loading: true,
      stakeDate:'',
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
  },
  computed: {
    filteredItems: function () {
      var searchQuery = this.searchQuery.toLowerCase();
      return this.tree.filter(function (item) {
        return item.usdt_amount.toLowerCase().indexOf(searchQuery) !== -1
      });
    }
  },
  setup() {
  },
  async mounted() {
    await this.getBinary()
    await this.getMaturityDate()
  },
  methods: {
    async getBinary() {
      let response = await ApiClass.getRequest(`stake/report?page=${this.page}&per_page=${this.perPageData}`, true);

      if (response?.status == 200) {
        this.tree = response?.data?.data?.data
        this.loading = false;
        this.recordData = parseInt(response?.data?.data?.total)
        this.perPageData = parseInt(response?.data?.data?.per_page)

      }
    },
     getMaturityDate(stake_date){
        let specificDate = new Date(stake_date);
        // Add 2 years to the specific date
        specificDate = specificDate.setFullYear(specificDate.getFullYear() + 2);
        var maturity_date = new Date(specificDate);
        console.log('sepcificDate',maturity_date.toDateString());
        console.log('currentDate',stake_date);

        return maturity_date.toDateString();
    }
  },


}


</script>
