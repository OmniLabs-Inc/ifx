<template>
    <form action="" class="row" @submit.prevent="generate">
        <div class="col-md-12">
            <!-- USERNAME INPUT -->
            <div class="form_box">
                <p class="user-label p-0 d-flex justify-content-between align-items-center gap-2">
                    Amount
                    <span class="text-danger" v-if="v$.amount.$error">
                        <div>
                            {{ v$.amount.$errors[0].$message }}*
                        </div>
                    </span>
                </p>

                <div class="input-group user-input-box mb-4">
                    <input type="text" class="user-input-main form-control shadow-none" v-model="amount" />
                    <span class="input-group-text p-0">
                        <select class="form-select" aria-label=".form-select-lg example" v-model="currency" value="">
                            <template v-for="(data, index) in token" :key="index">
                                <option :value="data.symbol">{{ data.symbol }}</option>
                            </template>

                        </select>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <!-- USERNAME INPUT -->
            <div class="form_box">
                <p class="user-label p-0 d-flex align-items-center gap-2">
                    <button type="button" class="active_btn px-3 py-2" disabled v-if="loading">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                    <button type="submit" class="active_btn px-3 py-2" v-else>
                        Generate Code
                    </button>
                </p>


            </div>
        </div>
    </form>
</template>

<script>
import ApiClass from "../../api/api";
import {
    required,
    helpers,
} from "@vuelidate/validators";
import {
    useVuelidate
} from "@vuelidate/core";
export default {
    name: "GenerateCode",
    props: {
        getCode: Function,
    },
    data() {
        return {
            amount: "",
            currency: "USDT",
            token: [
                {
                    symbol: "USDT",
                },
                {
                    symbol: "TRX",
                },
                {
                    symbol: "QBC",
                },
                {
                    symbol: "RMN",
                }
            ],

            loading: false
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
                required: helpers.withMessage("Amount is required", required)
            },
            currency: {
                required: helpers.withMessage("Currency is required", required)
            }
        };
    },
    methods: {
        resetForm() {
            this.amount = "";
            this.v$.$reset(); // reset validation
        },
        async generate() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }
            this.loading = true

            let response = await ApiClass.postRequest("create_exchange_code", true, { amount: this.amount, currency: this.currency });
            this.loading = false
            if (response.data.status_code == 0) {
                this.failed(response?.data?.message);
                this.resetForm()
                return
            }
            if (response.data.status_code == 1) {
                this.getCode()
                this.success(response?.data?.message);
                this.resetForm()
                return
            }
        }
    }
}
</script>
<style scoped>
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

.user-input-box .input-group-text {
    background: var(--text-blue) !important;
    border: none;
    color: var(--white);
}

.user-input-box .input-group-text .form-select {
    background-color: transparent;
    color: var(--white);
    font-size: var(--fs-13);
    border: none;
}

.user-input-box .input-group-text .form-select option {
    background-color: var(--d-bg);
    color: var(--white);
    font-size: var(--fs-13);
    border: none;
}

.user-input-box .user-input-main::placeholder {
    color: var(--place-holder);
    font-size: var(--fs-13);
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
</style>