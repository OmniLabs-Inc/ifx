<template>
    <form @submit.prevent="genCode">
        <div class="form_box mb-3">
            <label class="form-label">Currency</label>

            <select class="form-select shadow-none form-select-lg" aria-label=".form-select-lg example" v-model="currency"
                value="">
                <template v-for="(data, index) in coin" :key="index">
                    <option :value="data.symbol">{{ data.symbol }}</option>
                </template>

            </select>
            <span class="text-danger" v-if="v$.currency.$error">
                <div>
                    {{ v$.currency.$errors[0].$message }}
                </div>
            </span>
        </div>

        <!-- AMOUNT -->
        <div class="form_box mb-3">
            <label class="form-label">Amount (in $)</label>
            <div class="input-group">
                <input type="text" class="form-control shadow-none" placeholder="Enter Amount"
                    aria-describedby="basic-addon1" v-model="amount" @keypress="this.onHandleKeyPress($event, 8)"
                    @keyup="this.onHandleKeyUp($event, 8)" @keydown="this.onHandleKeyDown($event, 8)"
                    @paste="(e) => e.preventDefault()" @drop="(e) => e.preventDefault()">
            </div>
            <span class="text-danger" v-if="v$.amount.$error">
                <div>
                    {{ v$.amount.$errors[0].$message }}
                </div>
            </span>
        </div>

        <div class="d-flex justify-content-between">
            <h6 class="text-uppercase mb-0 text-center">SEND AFC :-

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


        <div class="form_box text-center">
            <button type="button" class="active_btn px-3 py-2" disabled v-if="loading">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="submit" class="active_btn px-3 py-2" v-else>Submit</button>
        </div>
    </form>
</template>
<script>
import ApiClass from "../../api/api";
import { required, helpers, minValue } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
    name: "P2PCodeGen",
    data() {
        return {
            currency: "AFC",
            amount: "",
            loading: false,
            coin: [
                {
                    symbol: "AFC",
                }
            ]
        }
    },
    props: {
        callback: Function,
        usdt_price: String,
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
     watch: {
        amount: function (newVal, oldVal) {

            let dd = (newVal / this.usdt_price);
            this.inUSDT = dd.toFixed("2");
        }
    },
    validations() {
        return {
            amount: {
                required: helpers.withMessage("Amount is Required", required),
            },
            currency: {
                required: helpers.withMessage('Please select currency', required),
            },
        };
    },
    methods: {
        resetForm() {
            this.currency = "AFC";
            this.amount = "";
            this.loading = false;
            this.v$.$reset(); // reset validation
        },
        async genCode() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }

            if (!this.v$.$invalid) {
                let form_data = {
                    currency: this.currency,
                    amount: this.inUSDT,
                };
                this.loading = true;
                // Submit Form In Backend

                let resp = await ApiClass.postRequest("p2p/create", true, form_data);
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

        }
    }
}
</script>
<style></style>