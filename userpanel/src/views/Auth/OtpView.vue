<template>
    <div>
        <section class="otp-sec auth_sec p-0">
            <div class="container-fluid p-xl-0">

                <div class="row align-items-center">
                    <!-- <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="login-image">
                            <img src="/images/auth/auth.jpg" alt="auth" class="img-fluid" />
                        </div>
                    </div> -->

                    <div class="col-md-6 col-lg-6 col-xl-4 mx-auto login_boxx">
                        <div class="logo-box">
                            <img src="/images/logo.png" alt="logo" style="width:74px;height:74px;" class="img-fluid" @click="$router.push('/')" />
                        </div><!--logo-box-->

                        <div class="head_auth">
                            <h2>Otp Verification</h2>
                        </div>
                        <div class="auth_form">
                            <form class="row form-row" @submit.prevent="vefify">


                                <div class="col-md-12 mb-4">
                                    <div class="label-box mb-2">
                                        <label>OTP</label>
                                    </div>
                                    <div class="otp-input-group">

                                        <OtpInput ref="otpInput" class="otp-input" input-classes="otp-input" separator="-"
                                            :num-inputs="6" :should-auto-focus="true" :is-input-num="false"
                                            @on-change="handleOnChange" @on-complete="handleOnComplete" />


                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.otp.$error">
                                        {{ v$.otp.$errors[0].$message }}
                                    </span>
                                </div><!--col-md-12 mb-3-->



                                <div class="text-end mb-3">
                                    <button type="button" class="btn p-0 border-0" style="color:var(--white)"
                                        @click="resendOTP" v-if="!resend_loading">Resend
                                        Otp</button>
                                    <button type="button" class="btn p-0 border-0" style="color:var( --white)" disabled
                                        v-else>Resending...</button>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="submit-box">
                                        <button v-if="!loading" type="submit" class="btn btn-primary">Submit</button>
                                        <button v-else type="button" class="btn btn-primary">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div><!--submit-box-->
                                </div><!--col-md-12-->


                            </form><!--row form-row-->
                        </div><!--auth_form-->
                    </div><!--class="col-md-6 col-lg-6 col-xl-6-->
                </div><!--row-->
            </div>

        </section><!--login-sec-->
    </div>
</template>

<script>
import OtpInput from 'vue3-otp-input';
import {
    required,
    helpers,
    maxLength
} from "@vuelidate/validators";
import {
    useVuelidate
} from "@vuelidate/core";
import ApiClass from "../../api/api";
export default {
    name: 'ForgotPasswordView',
    components: {
        OtpInput,
    },
    data() {
        return {
            otp: "",
            loading: false,
            query_unique_id: "",
            resend_loading: false,

        }
    },
    setup() {
        return {
            v$: useVuelidate(),
        }
    },
    validations() {
        return {
            otp: {
                required: helpers.withMessage("Otp is Required", required),
                maxLength: maxLength(6),
            }
        };
    },
    mounted() {
        if (this.$route.query.user_unique_id) {
            this.query_unique_id = this.$route.query.user_unique_id
        } else {
            if (this.$route.query.type == "verify_otp") {
                this.$router.push('forgotpassword')
            }
            if (this.$route.query.type == "verify_email") {
                this.$router.push('register')
            }

        }
    },
    methods: {
        handleOnComplete(value) {
            this.otp = value;
            this.vefify()
        },
        async resendOTP() {
            if (!this.$route.query.user_unique_id) {
                this.failed("Please click on the otp link provided in you mailbox")
                return;
            }
            this.resend_loading = true;

            let resp = await ApiClass.postRequest('resend_otp', false, { user_unique_id: this.$route.query.user_unique_id, type: this.$route.query.type });

            if (resp?.data) {
                this.resend_loading = false;
                if (resp.data.status_code == "0") {
                    this.failed(resp.data.message);
                    return;
                }
                if (resp.data.status_code == "1") {
                    this.success(resp.data.message)
                    return
                }
            }
        },
        resetForm() {
            this.otp = "";
            this.loading = false;
            this.v$.$reset(); // reset validation
        },
        async vefify() {
            if (!this.$route.query.user_unique_id) {
                this.failed("Please click on the otp link provided in you mailbox")
                return;
            }
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }

            if (!this.v$.$invalid) {
                let form_data = {
                    otp: this.otp,
                    user_unique_id: this.query_unique_id
                };
                this.loading = true;

                let resp = await ApiClass.postRequest(this.$route.query.type, false, form_data);

                if (resp?.data) {
                    this.loading = false;
                    if (resp.data.status_code == "0") {
                        this.failed(resp.data.message);
                        this.v$.$reset();
                        return;
                    }
                    if (resp.data.status_code == "1") {
                        this.success(resp.data.message)
                        if (this.$route.query.type == 'verify_email') {
                            this.$router.push({ name: 'login' })
                            return
                        }
                        if (this.$route.query.type == 'verify_otp') {
                            // console.log({
                            //     type:this.$route.query.type,
                            //     dd:resp?.data
                            // });
                            this.$router.push({
                                name: 'setforgotpassword',
                                query: {
                                    user_unique_id: form_data.user_unique_id,
                                    otp: form_data.otp
                                }
                            });
                            return
                        }
                        // this.resetForm();

                    }
                }
            }
            this.loading = false;

        },
    },
}
</script>

<style scoped>
.logo-box {
    text-align: center;
    margin-bottom: 30px;
}

.logo-box img {
    cursor: pointer;
}

.otp-input {
    display: flex;
    justify-content: space-between;
}
</style>
