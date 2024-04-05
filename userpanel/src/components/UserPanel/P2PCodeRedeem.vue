<template>
    <form @submit.prevent="genCode">


        <div class="form_box mb-3">
            <label class="form-label">Code</label>
            <div class="input-group">
                <input type="text" class="form-control shadow-none" placeholder="Enter Code" aria-describedby="basic-addon1"
                    v-model="code">
            </div>
            <span class="text-danger" v-if="v$.code.$error">
                <div>
                    {{ v$.code.$errors[0].$message }}
                </div>
            </span>
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
            code: "",
            loading: false,

        }
    },
    props: {
        callback: Function
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            code: {
                required: helpers.withMessage("Code is Required", required),
            },
        };
    },
    methods: {
        resetForm() {
            this.code = "";
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
                    code: this.code,
                };
                this.loading = true;
                // Submit Form In Backend

                let resp = await ApiClass.postRequest("p2p/redeem", true, form_data);
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