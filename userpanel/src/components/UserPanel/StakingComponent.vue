<template>
    <div>
        <div class="row">


<!-- TRANSACTION AND WITHDRAWAL TABS BOX -->
<div class="col-md-12 mb-4">
                <div class="swap_tab cus_card p-0 pt-2">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-transfer-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-transfer" type="button" role="tab" aria-controls="pills-transfer"
                                aria-selected="true">DEPOSIT</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-withdrawal-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-withdrawal" type="button" role="tab" aria-controls="pills-withdrawal"
                                aria-selected="false">WITHDRAWAL</button>
                        </li>
                    </ul>
                    <div class="tab-content cus_tab px-4 py-3" id="pills-tabContent">
                        <!-- TRANSFER TAB -->
                        <div class="tab-pane fade show active" id="pills-transfer" role="tabpanel"
                            aria-labelledby="pills-transfer-tab">
                            <DepositToken :callback="callback" />

                        </div>
                        <!-- WITHDRAWAL TAB -->
                        <div class="tab-pane fade" id="pills-withdrawal" role="tabpanel"
                            aria-labelledby="pills-withdrawal-tab">
                            <WithdrawToken :callback="callback" :user_wallet="user_wallet" />
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</template>

<script>

import DepositToken from "./DepositToken.vue";
import StakeForm from "./StakeForm.vue";
import ConvertUsdt from "./ConvertUsdt.vue";
import P2PCodeGen from "./P2PCodeGen.vue";
import P2PCodeRedeem from "./P2PCodeRedeem.vue";
import WithdrawToken from "./WithdrawToken.vue";
export default {
    name: "StakingComponent",
    components: {
        DepositToken,
        StakeForm,
        WithdrawToken,
        P2PCodeGen,
        P2PCodeRedeem,
        ConvertUsdt
    },
    props: {
        user_wallet: Object,
        usdt_price: String,
        Dashboard: Function
    },
    methods: {
        callback() {
            this.Dashboard()
        }
    }

}
</script>

<style></style>
