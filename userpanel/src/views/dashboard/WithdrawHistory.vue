<template>
    <div>

        <div class="activation-income cus_card-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="activation-card">
                            <div
                                class="activation-card-header d-flex justify-content-between align-items-center p-4 rounded-3">
                                <h2 class="m-0">Withdraw History</h2>
                                <div class="search-input">
                                    <div class="input-group rounded-2">
                                        <input type="text" class="form-control  bg-transparent  border-0 shadow-none"
                                            placeholder="Search Amount" aria-label="Username"
                                            aria-describedby="basic-addon1" v-model="searchQuery" style="color:aliceblue"
                                            @keypress="this.onHandleKeyPress($event, 8)"
                                            @keyup="this.onHandleKeyUp($event, 8)"
                                            @keydown="this.onHandleKeyDown($event, 8)" @paste="(e) => e.preventDefault()"
                                            @drop="(e) => e.preventDefault()">
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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="activation-card-head">




                                                <th>amount</th>
                                                 <th>Amount After Deduction</th>
                                               <!-- <th>withdraw fee (USDT)</th> -->
                                                <!-- <th>withdraw fee(%)</th> -->
                                                <!-- <th>AFC recieve</th> -->
                                                <th>to address</th>
                                                <th>Txn Hash</th>
                                                <th>failed reason</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                        <!--
                                        <thead>
                                            <tr class="activation-card-head">
                                                <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Amount" v-model="amount">
                                                    </form>
                                                </th>
                                                 <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Usdt After Fees" v-model="usdt_after_fees">
                                                    </form>
                                                </th>
                                                <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Withdraw Fees Percentage"
                                                            v-model="withdraw_fees_percentage">
                                                    </form>
                                                </th> -->
                                                <!-- <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search AFC Quantity" v-model="alpha_qty">
                                                    </form>
                                                </th>
                                                <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Address" v-model="to_address">
                                                    </form>
                                                </th>
                                                <th>
                                                </th>
                                                <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Reason" v-model="reason">
                                                    </form>
                                                </th>

                                                <th>
                                                    <form class="search-select">
                                                        <select name="" class="form-select" v-model="status"
                                                            @change="getBinary">
                                                            <option value="">Choose</option>
                                                            <option value="completed">Completed</option>
                                                            <option value="cancelled">Cancelled</option>
                                                        </select>
                                                    </form>
                                                </th>
                                            </tr>
                                        </thead>-->
                                        <tbody v-if="!loading">
                                            <tr v-if="filteredItems.length == 0">
                                                <td colspan="10" class="text-center" style="color:var(--white)">No Record
                                                    Found</td>
                                            </tr>
                                            <template v-else>
                                                <tr class="activation-card-data" v-for="(data, index) in filteredItems"
                                                    :key="index">




                                                    <td>${{ parseFloat(data?.amount) }}</td>
                                                    <td>${{ parseFloat(data?.usdt_after_fees) }}</td>
                                                    <!-- <td>${{ parseFloat(data?.total_fees) }}</td> -->
                                                    <!-- <td>{{ parseFloat(data?.withdraw_fees_percentage) }}%</td> -->
                                                    <!-- <td>{{ parseFloat(data?.alpha_qty) }}</td> -->
                                                    <td>{{ (data?.to_address) }}</td>
                                                    <td>
                                                        <!-- {{ data.transaction_detail.txid }} -->
                                                        <span v-if="data.transaction_detail != null">
                                                            <a target="_blank"
                                                                :href="`https://bscscan.com/tx/${data.transaction_detail}`"> Transaction Detail</a>
                                                        </span>
                                                    </td>
                                                    <td>{{ (data?.reason) != '' ? data?.reason : '-' }}</td>

                                                    <td>
                                                        <span class="px-3 py-2 rounded-0"
                                                            v-if="data.status.toLowerCase() == 'pending'"
                                                            style="background-color: rgba(17, 234, 241, 0.125);color:#26ddce">
                                                            {{ data.status }}
                                                        </span>
                                                        <span class="px-3 py-2 rounded-0"
                                                            v-if="data.status.toLowerCase() == 'completed'"
                                                            style="background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b">
                                                            {{ data.status }}
                                                        </span>
                                                        <span class="px-3 py-2 rounded-0"
                                                            v-if="data.status.toLowerCase() == 'failed'"
                                                            style="background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e">
                                                            {{ data.status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>

                                        <tbody v-else>
                                            <tr class="activation-card-data" v-for="i in 8" :key="i">
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
import ApiClass from '../../api/api.js';
export default {
    name: 'WithdrawHistory',

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
            amount: '',
            usdt_after_fees: '',
            withdraw_fees_percentage: '',
            alpha_qty: '',
            to_address: '',
            reason: '',
            status: ''
        }
    },
    computed: {
        filteredItems: function () {
            var searchQuery = this.searchQuery.toLowerCase();
            return this.tree.filter(function (item) {
                return item.amount.indexOf(searchQuery) !== -1
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
            let response = await ApiClass.getRequest(`withdraw/history?page=${this.page}&per_page=${this.perPageData}&amount=${this.amount}&usdt_after_fees=${this.usdt_after_fees}&withdraw_fees_percentage=${this.withdraw_fees_percentage}&alpha_qty=${this.alpha_qty}&to_address=${this.to_address}&reason=${this.reason}&status=${this.status}`, true);
            if (response?.data?.status_code == "1") {
                this.tree = response?.data?.data?.data
                this.loading = false;
                this.recordData = parseInt(response?.data?.data?.total)
                this.perPageData = parseInt(response?.data?.data?.per_page)
            }
        },
    },


}
</script>
