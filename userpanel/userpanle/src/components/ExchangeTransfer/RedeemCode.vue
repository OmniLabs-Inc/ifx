<template>
    <form @submit.prevent="claim" action="" class="row">
        <div class="col-md-12">
            <!-- USERNAME INPUT -->
            <div class="form_box mb-4">
                <p class="user-label p-0 d-flex align-items-center justify-content-between gap-2">
                    Claim Code
                    <span class="text-danger" v-if="v$.code.$error">
                        <div>
                            {{ v$.code.$errors[0].$message }}*
                        </div>
                    </span>
                </p>

                <div class="input-group user-input-box ">
                    <input type="text" class="user-input-main form-control shadow-none" v-model="code" />
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
                        Claim
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
    name: "RedeemCode",
    data() {
        return {
            code: "",
            loading: false
        }
    },
    props: {
        getCode: Function
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            code: {
                required: helpers.withMessage("Code is required", required)
            }
        };
    },
    methods: {
        resetForm() {
            this.code = "";
            this.v$.$reset(); // reset validation
        },
        async claim() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }
            this.loading = true
            let response = await ApiClass.postRequest("get_exchange_code", true, { code: this.code });
            this.loading = false;
            if (response.data.status_code == 0) {
                this.failed(response?.data?.message);
                this.resetForm()
                return
            }
            if (response.data.status_code == 1) {
                this.success(response?.data?.message);
                this.getCode();
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