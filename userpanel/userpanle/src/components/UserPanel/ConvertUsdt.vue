<template>
    <form @submit.prevent="stakeForm">

        
        <div class="form_box mb-3">
            
            <div class="d-flex justify-content-between">
                <label class="form-label">Amount</label>
                <h6 class="text-uppercase mb-0 text-center">{{ stake_currency?.currency }} :-
                    <span>{{ stake_currency?.balance }}</span>
                </h6>
            </div>
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
        </div>
        <div class="d-flex justify-content-between">
            <h6 class="text-uppercase mb-0 text-center">Convert AFC :-

                <span>
                    {{ inUSDT }} 
                </span>
            </h6>
        </div>
        <br/>
        <div class="d-flex justify-content-between">
            <h6 class="text-uppercase mb-0 text-center">

                <span>
                    AFC Price : <span style="color:red;">${{ usdt_price}}</span>
                </span>
            </h6>
        </div>

        <br/>
        
        <div class="form_box text-center">
            <button v-if="!loading" type="submit" class="active_btn px-3 py-2" :disabled="loading">Submit</button>

            <button type="submit" class="active_btn px-3 py-2" v-else>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
        </div>
    </form>
</template>
<script>
import ApiClass from "../../api/api";
import { required, helpers, maxValue } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
    name: "ConvertUsdt",
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
            loading: false,
        }
    },
    // mounted(){
    //     console.log({
    //         'props':true,
    //         'wallet': this.user_wallet,
    //         'usdt_price' : this.usdt_price

    //     });
    // },
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
                maxValue: helpers.withMessage(`Amount should be less than ${this.stake_currency?.balance}`, maxValue(this.stake_currency?.balance)),
            }
        };
    },
    methods: {
        resetForm() {
            this.amount = 0;
            this.loading = false;
            this.v$.$reset(); // reset validation
        },
        async stakeForm() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }

            if (!this.v$.$invalid) {
                let form_data = {
                    amount: this.amount,
                };
                this.loading = true;
                // Submit Form In Backend

                let resp = await ApiClass.postRequest("stake/convert", true, form_data);
                // console.log(resp);

                if (resp?.data) {
                    this.loading = false;

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

            this.loading = false;


        }
    }
}
</script>
<style></style>