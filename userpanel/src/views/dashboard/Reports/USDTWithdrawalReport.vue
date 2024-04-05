<template>
    <div>
        <div class="activation-income cus_card-1">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="activation-card">
                  <div class="activation-card-header d-flex justify-content-between align-items-center pt-4 px-3 pb-3 rounded-3">
                      <h2 class="m-0">USDT Withdrawal Report</h2>
                      <div class="search-input">
                        <div class="input-group rounded-2">
                          <input type="text" class="form-control bg-transparent  border-0 shadow-none" placeholder="Search" aria-label="Username" aria-describedby="basic-addon1">
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
                            <th>Statistics</th>
                            <th>Credit</th>
                            <th>Debit</th>
                            <th>Amount</th>
                            <th>Transaction Hash</th>
                          </tr>
                        </thead>
                        <tbody v-if="loading">
                          <tr class="activation-card-data"  v-for="(data, index) in USDTWithdrawalReport" :key="index">
                            <td>{{ data.Date }}</td>
                            <td>{{ data.Statistics}}</td>
                            <td>{{ data.Credit }}</td>
                            <td>{{ data.Debit }}</td>
                            <td>{{ data.Amount }}</td>
                            <td>{{ data.Transaction }}</td>
                          </tr>
                          
                        </tbody>
  
                        <tbody v-else>
                          <tr class="activation-card-data"  v-for="i in 10" :key="i">
                            <td><Skeletor /></td>
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
  import USDTWithdrawalReportJson from '../../../assets/json/Dashboard/Reports/USDTWithdrawalReport.json';
  export default {
    name: 'TransferReport',
      data(){
        return{
            USDTWithdrawalReport: USDTWithdrawalReportJson.USDTWithdrawalReport,
          loading:true,
          page: 1,
          recordData: 100,
        perPageData: 10,
        options: {
            edgeNavigation: false,
            chunksNavigation: false,
            chunk: 3,
            texts: false,
            format: false,
        },
        }
      }
      
   
  
  }
  </script>
  
  <style scoped>
 
  </style>
  