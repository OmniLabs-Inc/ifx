<template>
    <!--<form @submit.prevent="stakeForm">-->


        <div class="form_box mb-3">

            <div class="d-flex justify-content-between">
                <!--<label class="form-label">Amount</label>-->

                <h6 class="text-uppercase mb-0 text-center">{{ stake_currency?.currency }} :+
                    <span>{{ stake_currency?.balance }}</span>
                </h6>
            </div>
            <!--
            <div class="input-group">

                <input type="text" class="form-control shadow-none" placeholder="Enter Amount" v-model="v$.amount.$model"
                    aria-describedby="basic-addon1" @keypress="this.onHandleKeyPress($event, 0)"
                    @keyup="this.onHandleKeyUp($event, 0)" @keydown="this.onHandleKeyDown($event, 0)"
                    @paste="(e) => e.preventDefault()" @drop="(e) => e.preventDefault()">
            </div>
            <span class="text-danger" v-if="v$.amount.$error">

                <div>
                    {{ v$.amount.$errors[0].$message }}
                </div>
            </span>
        -->
        </div>


        <br/>

        <div class="form_box text-center">
            <!--
            <button v-if="!loading" type="submit" class="active_btn px-3 py-2" :disabled="loading">Submit</button>

            <button type="submit" class="active_btn px-3 py-2" v-else>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
        -->
        <a href="/packages">
        <button class="active_btn px-3 py-2" >Go To Packages</button>
        </a>
        </div>
    <!--</form>-->
</template>
<script>
import ApiClass from "../../api/api";
import { required, helpers, minValue } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
    name: "StakeForm",
    props: {
        user_wallet: Object,
        usdt_price: String,
        callback: Function
    },
    data() {
        return {
            stake_currency: {},
            default_currency: "USDT",
            amount: 0,
            inUSDT: 0,
            loading: false
        }
    },
    watch: {
        user_wallet: function (newVal, oldVal) { // watch it
            this.stake_currency = newVal.find((v) => v.currency == this.default_currency) ?? null;


            if (this.stake_currency == null) {

                this.stake_currency = {
                    "currency": "USDT",
                    "balance": "0.00000000"
                }
            }
        },
        amount: function (newVal, oldVal) {

            let dd = (newVal / this.usdt_price);
            this.inUSDT = dd.toFixed("2");
        }
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            amount: {
                required: helpers.withMessage("Amount is Required", required),
                minValue: helpers.withMessage("Amount should be greater than $99", minValue(20)),
            }
        };
    },
    async mounted(){
        await this.getStakedPlans()
    },
    methods: {

        async getStakedPlans() {

                let form_data = {
                    amount: this.amount,
                };

                let resp = await ApiClass.postRequest("stakedd/plan", true, form_data);
                // console.log(resp);

                if (resp?.data) {

                    if (resp.data.status_code == "0") {
                        // return Error Message Here
                        this.failed(resp.data.message);
                        this.v$.$reset();
                        return;
                    }
                    if (resp.data.status_code == "1") {
                        this.resetForm();
                        this.success(resp.data.message);
                        this.callback();
                    }
                }

        }
    }
}
</script>
<style></style>
