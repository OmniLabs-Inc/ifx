<template>
    <div>
        <section class="forgot-sec auth_sec p-0">
            <div class="container-fluid p-xl-0">

                <div class="row align-items-center">
                    <!-- <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="login-image">
                            <img src="/images/auth/auth.jpg" alt="auth" class="img-fluid" />
                        </div>
                    </div> -->

                    <div class="col-md-6 col-lg-6 col-xl-4 mx-auto">
                        <div class="logo-box">
                            <img src="/images/logo.png" alt="logo" class="img-fluid" style="width:74px;height:74px;" @click="$router.push('/')" />
                        </div><!--logo-box-->
                        <div class="auth_form">
                            <form class="row form-row" @submit.prevent="setNew">




                                <div class="col-md-12 mb-4">
                                    <div class="label-box mb-2">
                                        <label>OTP</label>
                                    </div>
                                    <div class="otp-input-group">

                                        <OtpInput ref="otpInput" class="otp-input" input-classes="otp-input" separator="-"
                                            :num-inputs="6" :should-auto-focus="true" :is-input-num="false"
                                            @on-change="handleOnChange" v-model="otp" />


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
                                    <div class="label-box mb-2">
                                        <label>New Password</label>
                                    </div>
                                    <div class="input-group">
                                        <input :type="!new_password_type ? 'text' : 'password'" class="form-control"
                                            placeholder="New Password" v-model="new_password">
                                        <span class="input-group-text">
                                            <img :src="!new_password_type ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="new_password_type = !new_password_type" />
                                        </span>
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.new_password.$error">
                                        {{ v$.new_password.$errors[0].$message }}
                                    </span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Confirm New Password</label>
                                    </div>
                                    <div class="input-group">
                                        <input :type="!confirm_new_password_type ? 'text' : 'password'" class="form-control"
                                            placeholder="Confirm New Password" v-model="confirm_new_password">
                                        <span class="input-group-text">
                                            <img :src="!confirm_new_password_type ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;"
                                                @click="confirm_new_password_type = !confirm_new_password_type" />
                                        </span>
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.confirm_new_password.$error">
                                        {{ v$.confirm_new_password.$errors[0].$message }}
                                    </span>
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

            </div><!--container-fluid-->
        </section><!--login-sec-->
    </div>
</template>

<script>
import hide from "/images/auth/hide.svg"
import show from "/images/auth/show.svg"
import OtpInput from 'vue3-otp-input';

import {
    required,
    helpers,
    minLength,
    maxLength,
    sameAs
} from "@vuelidate/validators";
import {
    useVuelidate
} from "@vuelidate/core";
import ApiClass from "../../api/api";
export default {
    name: 'SetForgotPassword',
    components: {
        OtpInput,
    },
    data() {
        return {
            loading: false,
            hide,
            show,
            new_password_type: true,
            confirm_new_password_type: true,
            new_password: '',
            confirm_new_password: '',
            otp: ''
        }
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            otp: {
                required: helpers.withMessage("Otp is Required", required),
                maxLength: maxLength(6),
            },
            new_password: {
                minLength: minLength(6),
                maxLength: maxLength(12),
                required: helpers.withMessage("Password is Required", required),
                valid: helpers.withMessage("Password Should Contain A Capital Letter, A Special Charcater And A Number", function (value) {
                    const containsUppercase = /[A-Z]/.test(value)
                    const containsLowercase = /[a-z]/.test(value)
                    const containsSpecial = /[#?!@$%^&*-]/.test(value)
                    return containsUppercase && containsLowercase && containsSpecial
                })
            },
            confirm_new_password: {
                sameAsPassword: helpers.withMessage(
                    "Password and Confirm Password should match",
                    sameAs(this.new_password)
                ),
            },
        };
    },
    mounted() {
        if (this.$route.query.user_unique_id && this.$route.query.otp) {
            this.query_user_unique_id = this.$route.query.user_unique_id
            // this.otp = this.$route.query.otp
        } else {
            this.$router.push('forgotpassword')
        }

    },
    methods: {
        resetForm() {
            this.new_password = "";
            this.confirm_new_password = "";
            this.v$.$reset(); // reset validation
        },
        handleOnChange(ee){
            this.otp = ee;
        },
        async resendOTP() {
            if (!this.$route.query.user_unique_id) {
                this.failed("Please click on the otp link provided in you mailbox")
                return;
            }
            this.resend_loading = true;

            let resp = await ApiClass.postRequest('resend_otp', false, { user_unique_id: this.$route.query.user_unique_id, type: 'verify_otp' });

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

        async setNew() {
            console.log(this.otp , 'dd');
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }

            if (!this.v$.$invalid) {
                let form_data = {
                    user_unique_id: this.query_user_unique_id,
                    otp: this.otp,
                    new_password: this.new_password,
                    confirm_new_password: this.confirm_new_password,
                };
                this.loading = true;

                let resp = await ApiClass.postRequest("change_password", false, form_data);
                // console.log(resp);

                if (resp?.data) {
                    this.loading = false;
                    if (resp.data.status_code == "0") {
                        this.failed(resp.data.message);
                        this.v$.$reset();
                        return;
                    }
                    if (resp.data.status_code == "1") {
                        this.resetForm();
                        this.success(resp.data.message);
                        this.$router.push('login')
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

span.input-group-text.username {
    padding: 0;

}

span.input-group-text.username .btn-primary {
    border-radius: 0;
    background-image: var(--gradient);
    min-height: 43px;
    font-size: var(--fs-17);
    font-weight: 500;
}

.login-text {
    text-align: center;
}

.login-text p {
    color: var(--white);
    font-size: var(--fs-15);
}
</style>
