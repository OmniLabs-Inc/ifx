<template>
    <div>
        <div class=" row justify-content-center align-items-center">
            <!-- <div class="text-end">
                <p class="mb-1 text-danger">Note:- Codes that are not expired cannot be self claimed. Each code expires in
                    15
                    minutes</p>
                <button class="active_btn py-3 px-2" v-if="claim_loading">
                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Loading...
                </button>
                <button class="active_btn py-3 px-2" @click="claimExpired" v-else>Claim My Expired Codes</button>
            </div> -->
            <div class="col-md-11 col-lg-11 col-xl-10 col-xxl-7">
                <!-- PROFILE BOX  -->
                <div class="profile_box">
                    <div class="row justify-content-center">
                        <!-- QR IMAGE BOX -->

                        <!-- INFORMATION BOX -->
                        <div class="col-lg-6 px-0">
                            <div class="information_box p-4 rounded-end-3">
                                <!-- HEADING -->
                                <h5 class="mb-4">

                                    Transfer Crypto
                                </h5>
                                <!-- INFORMATION FORM  -->
                                <GenerateCode :getCode="getCode" />
                            </div>
                        </div>

                        <div class="col-lg-6 px-0">
                            <div class="information_box p-4 rounded-end-3">
                                <!-- HEADING -->
                                <h5 class="mb-4">

                                    Receive Crypto
                                </h5>
                                <!-- INFORMATION FORM  -->
                                <RedeemCode :getCode="getCode" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-md-12">
            <div class="head_table my-4">
                <h3 class="mb-0 text-capitalize">
                    <img src="/images/svg/life.svg" alt="text" class="img-fluid me-1" />
                    Generated codes
                </h3>
            </div>
        </div>
        <!-- table start -->
        <div class="col-md-12">
            <div class="activation-card p-2">
                <div class="activation-card-body table-responsive">
                    <table class="table text-nowrap align-middle text-center">
                        <thead>
                            <tr class="activation-card-head">
                                <th scope="col">Currency</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Code</th>
                                <th scope="col">Expires At</th>
                                <th scope="col">Redeem Status</th>
                            </tr>
                        </thead>
                        <tbody v-if="!loading">
                            <template v-if="codedata.length > 0">
                                <tr v-for="(data, index) in codedata" :key="index" class="activation-card-data">
                                    <td>{{ data.currency }}</td>
                                    <td>{{ data.amount }}</td>
                                    <td>{{ data.code }}</td>
                                    <td>
                                        {{ data.expired_at }}
                                    </td>
                                    <td>
                                        <span class="px-3 py-2 rounded-0"
                                            style="background-color: rgba(85, 189, 108, 0.1254901961);color:#66c37b"
                                            v-if="data.is_redeemed == 1">
                                            Redeemed
                                        </span>
                                        <span v-if="data.is_redeemed == 0" class="px-3 py-2 rounded-0"
                                            style="background-color:rgba(244, 67, 54, 0.1254901961);color:#f6685e">
                                            Not Redeemed
                                        </span>
                                        <span v-if="data.is_redeemed == 2" class="px-3 py-2 rounded-0"
                                            style="background-color: rgba(244, 67, 54, 0.1254901961);color:#f6685e">
                                            Expired
                                        </span>
                                    </td>

                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="10" class="text-center" style="color:var(--white)">No record found</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="i in 10" :key="i" class="activation-card-data">
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
        <!-- table end -->

        <!-- pagination start -->
        <div class="col-md-12">
            <div class="pagination_box d-flex justify-content-end mt-4" style="color: white">
                <pagination v-model="page" :records="recordData" :per-page="perPageData" :options="options"
                    @paginate="getCode" />
            </div>
        </div>
        <!-- pagination end -->
    </div>
</template>
  
<script>

import ApiClass from "../../api/api";
import GenerateCode from "../../components/ExchangeTransfer/GenerateCode.vue";
import RedeemCode from "../../components/ExchangeTransfer/RedeemCode.vue";
export default {
    name: "ExchangeTransfer",
    components: {
        GenerateCode,
        RedeemCode
    },
    data() {
        return {
            codedata: [],
            loading: false,
            page: 1,
            recordData: '',
            perPageData: 7,
            claim_loading: false
        }
    },
    mounted() {
        this.getCode();
    },
    methods: {
        async getCode() {
            this.loading = true;
            let response = await ApiClass.getRequest(`get_code?page=${this.page}&per_page=${this.perPageData}`, true);
            this.loading = false;
            if (response?.data.status_code == "1") {
                this.codedata = response?.data?.data?.data;
                this.recordData = parseInt(response?.data?.data?.total)
                // this.perPageData = parseInt(response?.data?.data?.per_page)
            }
        },
        // async claimExpired() {

        //     this.claim_loading = true;
        //     let response = await ApiClass.getRequest(`claim_expired_code`, true);
        //     this.claim_loading = false;
        //     if (response?.data.status_code == "1") {
        //         this.success(response?.data?.message)
        //         this.getCode();
        //         return
        //     }
        //     if (response?.data.status_code == "0") {
        //         this.failed(response?.data?.message)
        //         return
        //     }

        // }
    }
};
</script>
  
<style scoped>
.profile_box {
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}

.qr_box {
    background: var(--bg-secondary);
}

.user-img {
    max-width: 130px;
}

.user-input {
    right: 0;
    width: 36px;
    height: 36px;
    z-index: 1;
    bottom: 0;
    opacity: 0;
}

.upload-img-box {
    right: 0;
    bottom: 0;
    background: var(--bg-secondary) !important;
}

.user-data {
    color: var(--white);
    font-weight: 500;
    font-size: var(--fs-14);
}

.information_box {
    background: var(--d-bg);
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}

.information_box h5 {
    color: var(--white);
}

.form_box .user-label {
    color: var(--white);
    font-size: var(--fs-15);
    margin-bottom: 6px;
}

.user-input-box .user-input-main {
    background-color: var(--bg-secondary);
    color: var(--white);
    font-size: var(--fs-15);
    border: unset;
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}

.user-input-box .user-input-main::placeholder {
    color: var(--place-holder);
    font-size: var(--fs-13);
}

.password-eye {
    background: var(--bg-secondary);
}

/* modals css */
.modal-title {
    font-size: var(--fs-17);
}

.modal-content {
    background: var(--d-bg);
    color: var(--white);
}

.modal-header {
    border-bottom-color: var(--modal-border);
}

.modal-footer {
    border-top-color: var(--modal-border);
}

.modal-header .btn-close {
    filter: invert(1);
}

.user-btn {
    background: var(--gradient);
    color: var(--white);
    font-size: var(--fs-13);
}

.user-submit {
    background: var(--gradient);
    color: var(--white);
    font-size: var(--fs-15);
}



.head_table h3 {
    color: var(--sky-blue);
    font-size: var(--fs-22);
    font-weight: 600;
}

.activation-card {
    background: var(--bg-secondary);
    color: var(--white);
    box-shadow: var(--card-shadow);
    border-radius: 7px;
}

.activation-card-header {
    background: var(--bg-secondary);
}

.activation-card-header h2 {
    text-transform: capitalize;
    font-size: var(--fs-16);
}

.form-control:focus {
    border-color: var(--place-holder);
}

.form-control {
    color: var(--place-holder);
    border: 1px solid var(--place-holder);
    font-size: var(--fs-14);
    padding: 10px 20px;
}

.input-group {
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
    background-color: var(--d-bg);
    width: 263px;
    margin: auto;
    margin-right: 0;
}

.input-group-text {
    border: 1px solid var(--place-holder);
}

.activation-card-head th {
    text-transform: capitalize;
    color: var(--white);
    font-weight: 600;
    font-size: var(--fs-14);
}

.activation-card-data td {
    color: var(--white);
    font-size: var(--fs-13);
    font-weight: 500;
}

.table> :not(caption)>*>* {
    padding: 13px 13px;
    border-bottom-width: 1px;
    border-color: #5e505026;
}

::placeholder {
    color: var(--place-holder);
}

.activation-card-data .badge {
    height: 20px;
    width: 80px;
    background-color: var(--light-sblue);
    border-radius: 5px;
    font-size: var(--fs-12)
}

.head_table_content p {
    color: var(--white);
    font-size: var(--fs-14);
    font-weight: 500;
}

.head_table_content .form-select {
    color: var(--place-holder);
    border: 1px solid var(--place-holder);
    font-size: var(--fs-14);
    box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
    background-color: var(--d-bg);
}

.activation-card-data:hover {
    background-color: var(--d-bg);
}

.active_btn {
    background: var(--gradient);
    color: var(--white);
    border: 1px solid var(--text-blue);
    font-size: var(--fs-14);
    font-weight: 500;
    min-width: 100px;
    border-radius: 6px;
}

@media all and (min-width: 320px) and (max-width: 576px) {
    .qr_box {
        border-radius: 0 !important;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }

    .information_box {
        border-radius: 0 !important;
        border-bottom-left-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
    }
}
</style>
  