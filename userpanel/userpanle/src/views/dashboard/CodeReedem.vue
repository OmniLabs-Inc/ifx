<template>
    <div>

        <div class="activation-income cus_card-1">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="activation-card">
                            <div
                                class="activation-card-header d-flex justify-content-between align-items-center p-4 rounded-3">
                                <h2 class="m-0">P2p Codes Reedem</h2>
                                <div class="search-input">
                                    <div class="input-group rounded-2">
                                        <input type="text" class="form-control  bg-transparent  border-0 shadow-none"
                                            placeholder="Search Currency" aria-label="Username"
                                            aria-describedby="basic-addon1" v-model="searchQuery" style="color:aliceblue">
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
                                                <th>code</th>
                                                <th>currency</th>
                                                <th>expired at</th>
                                                <th>Received From</th>
                                                <th>redeem status</th>
                                            </tr>
                                        </thead>
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
                                                            placeholder="Search Code" v-model="code">
                                                    </form>
                                                </th>
                                                <th>
                                                    <form @submit.prevent="getBinary">
                                                        <input type="text" class="text-search-input form-control p-2"
                                                            placeholder="Search Currency" v-model="currency">
                                                    </form>
                                                </th>
                                                <th>
                                                    <input type="date" class="text-search-input form-control p-2"
                                                        v-model="expired_at" @change="getBinary">
                                                </th>
                                                <th>
                                                    <input type="text" class="text-search-input form-control p-2"
                                                        placeholder="Search Redeemed By" v-model="redeemed_by"
                                                        @change="getBinary">
                                                </th>
                                                <th>
                                                    <form class="search-select">
                                                        <select name="" class="form-select" v-model="is_redeemed"
                                                            @change="getBinary">
                                                            <option value="" selected>Choose</option>
                                                            <option value="0">Not Redeemed</option>
                                                            <option value="1">Redeemed</option>
                                                            <option value="2">Redeem Expired</option>
                                                        </select>
                                                    </form>
                                                </th>


                                                <!-- <th>
                                                    <form class="search-select">
                                                        <select name="" class="form-select" v-model="status"
                                                            @change="getBinary">
                                                            <option value="" selected>Choose</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="completed">Completed</option>
                                                            <option value="expired">Expired</option>
                                                        </select>
                                                    </form>
                                                </th> -->
                                            </tr>
                                        </thead>
                                        <tbody v-if="!loading">
                                            <tr v-if="filteredItems.length == 0">
                                                <td colspan="10" class="text-center" style="color:var(--white)">No Record
                                                    Found</td>
                                            </tr>
                                            <template v-else>
                                                <tr class="activation-card-data" v-for="(data, index) in filteredItems"
                                                    :key="index">




                                                    <td>{{ parseFloat(data.amount) }}</td>
                                                    <td>{{ data.code }}</td>
                                                    <td>{{ data.currency }}</td>

                                                    <td>{{ data.expired_at }}</td>

                                                    <td>{{ data?.user_created?.user_unique_id  }}</td>
                                                    <td>
                                                        <span class="px-3 py-2 rounded-0" v-if="data.is_redeemed == 0"
                                                            style="background-color: rgba(85, 189, 108, 0.1254901961);color:#33ccc4">
                                                            Not redeemed
                                                        </span>
                                                        <span class="px-3 py-2 rounded-0" v-if="data.is_redeemed == 1"
                                                            style="background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b">
                                                            Redeemed
                                                        </span>
                                                        <span class="px-3 py-2 rounded-0" v-if="data.is_redeemed == 2"
                                                            style="background-color:rgba(45, 219, 205, 0.125);color:#f6685e">
                                                            Redeem Expired
                                                        </span>
                                                    </td>
                                                    <!-- <td>
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
                                                            v-if="data.status.toLowerCase() == 'expired'"
                                                            style="background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e">
                                                            {{ data.status }}
                                                        </span>
                                                    </td> -->
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
import ApiClass from '../../api/api';
export default {
    name: 'CodeReedem',

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
            code: '',
            currency: '',
            expired_at: '',
            is_redeemed: '',
            redeemed_by: ''
            // status: '',
        }
    },
    computed: {
        filteredItems: function () {
            var searchQuery = this.searchQuery.toLowerCase();
            return this.tree.filter(function (item) {
                return item.currency.toLowerCase().indexOf(searchQuery) !== -1
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
            let response = await ApiClass.getRequest(`p2p/get-reedeem?page=${this.page}&per_page=${this.perPageData}&amount=${this.amount}&code=${this.code}&currency=${this.currency}&expired_at=${this.expired_at}&is_redeemed=${this.is_redeemed}&redeemed_by=${this.redeemed_by}`, true);
            console.log({ response })
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
  