<template>
    <div>
        <section class="login-sec auth_sec p-0">
            <div class="container-fluid p-xl-0">
                <div class="row align-items-center">
                    <!-- <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="login-image">
                            <img src="/images/auth/auth.jpg" alt="auth" class="img-fluid" />
                        </div>
                    </div> -->

                    <div class="col-md-6 col-lg-6 col-xl-4 mx-auto login_boxx">
                        <div class="logo-box">
                            <router-link to="/">
                                <img src="/images/logo.png" alt="logo" class="img-fluid" style="width:74px;height:74px;" />
                            </router-link>
                        </div>
                        <div class="head_auth">
                            <h2>Login</h2>
                        </div>
                        <div class="auth_form">
                            <form class="row form-row" @submit.prevent="loginForm">
                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Unique Id</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Unique Id" aria-label="unique_id"
                                            aria-describedby="basic-addon1" v-model="unique_id">
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.unique_id.$error">
                                        {{ v$.unique_id.$errors[0].$message }}
                                    </span>
                                </div><!--col-md-12 mb-3-->

                                <div class="col-md-12 mb-4">
                                    <div class="label-box mb-2">
                                        <label>Password</label>
                                    </div>
                                    <div class="input-group mb-2">

                                        <input :type="hidden ? 'password' : 'text'" class="form-control" id="password"
                                            placeholder="Password" aria-label="Password" aria-describedby="basic-addon2"
                                            v-model="password">

                                        <span class="input-group-text border-start-0" id="basic-addon2">
                                            <img :src="hidden ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="hidden = !hidden" />
                                        </span>
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.password.$error">
                                        {{ v$.password.$errors[0].$message }}
                                    </span>


                                </div><!--col-md-12 mb-3-->
                                <!-- <div> -->
                                    <!-- <div id="recaptcha" ref="recaptcha"></div> -->
                                    <!-- <button @click="submitForm">Submit</button> -->
                                    <!-- <span class="error_msg text-danger" v-if="v$.recaptcha.$error"> -->
                                        <!-- {{ v$.recaptcha.$errors[0].$message }} -->
                                    <!-- </span> -->
                                <!-- </div> -->

                                <div class="forgot_password text-end mb-2">
                                    <span class="mb-0" @click="$router.push('/forgotpassword')">Forgot Password ?</span>
                                </div><!--frogot_password-->
                                
                                <div class="col-md-12 mb-3">
                                    <div class="submit-box ">
                                        <button v-if="!loading" type="submit" class="btn btn-primary ">Submit</button>
                                        <button v-else type="button" class="btn btn-primary ">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </button>
                                    </div><!--submit-box-->
                                </div><!--col-md-12-->

                                <div class="col-md-12">
                                    <div class="register-text">
                                        <p>Don't have an account? <span @click="$router.push('/register')"
                                                style="background-image: linear-gradient(to bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);; cursor: pointer;">Register</span></p>
                                    </div><!--register-text-->
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
import hide from "/images/auth/hide.svg"
import show from "/images/auth/show.svg"
import ApiClass from "../../api/api";
// import VueRecaptcha from 'vue-recaptcha';
import {
    required,
    helpers
} from "@vuelidate/validators";
import {
    useVuelidate
} from "@vuelidate/core";
export default {
    name: "LoginView",
    // components: { VueRecaptcha },
    data() {
        return {
            hidden: true,
            hide: hide,
            show: show,
            type: "password",
            unique_id: "",
            password: "",
            recaptcha: '',
            verify: false,
            loading: false,
            sitekey: "6LeA8aMeAAAAAIZ430h3mJAoaOWKWOZJiIp_5Mag"
        }
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            unique_id: {
                required: helpers.withMessage("Unique id is Required", required),
            },
            password: {
                required: helpers.withMessage("Password is Required", required)
            },
            // recaptcha: {
            //     required: helpers.withMessage("Captcha is Required", required)
            // }
        };
    },
    mounted() {
        // Load the reCAPTCHA script
        // this.loadRecaptchaScript();
    },
    methods: {
        // loadRecaptchaScript() {
        //     // Load the reCAPTCHA script with your site key
        //     const script = document.createElement('script');
        //     script.src = `https://www.google.com/recaptcha/api.js?render=explicit&onload=onRecaptchaScriptLoad`;
        //     script.async = true;
        //     script.defer = true;
        //     document.head.appendChild(script);

        //     // Define the callback function for script load completion
        //     window.onRecaptchaScriptLoad = () => {
        //         // Render the reCAPTCHA widget
        //         window.grecaptcha.render('recaptcha', {
        //             sitekey: '6LeA8aMeAAAAAIZ430h3mJAoaOWKWOZJiIp_5Mag',
        //             callback: this.onRecaptchaVerify, // Callback function on successful verification
        //         });
        //     };
        // },
        // onRecaptchaVerify(response) {
        //     // Handle the reCAPTCHA response
        //     console.log('reCAPTCHA response:', response);
        //     this.recaptcha = response;
        // },
        // submitForm() {
        //     // Execute the reCAPTCHA verification manually
        //     const recaptchaResponse = window.grecaptcha.getResponse();
        //     if (recaptchaResponse) {
        //         // reCAPTCHA verification succeeded
        //         this.onRecaptchaVerify(recaptchaResponse);
        //     } else {
        //         // reCAPTCHA verification failed
        //         console.log('reCAPTCHA verification failed.');
        //     }
        // },

        resetForm() {
            this.unique_id = "";
            this.password = "";
            this.recaptcha = "";
            this.loading = false;
            this.v$.$reset(); // reset validation
        },
        async loginForm() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }

            if (!this.v$.$invalid) {
                let form_data = {
                    user_unique_id: this.unique_id,
                    password: this.password,
                    // captcha_response: this.recaptcha
                };
                this.loading = true;
                // Submit Form In Backend

                let resp = await ApiClass.postRequest("login", false, form_data);
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
                        console.log('ress===', resp.data.data)
                        if (resp.data.data.token) {
                            localStorage.setItem("token", resp.data.data.token);
                            localStorage.setItem("user", JSON.stringify(resp.data.data.user));
                            await new Promise((resolve) => setTimeout(resolve, 1000)); // for slow down
                            return this.$router.push("dashboard");

                        }
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

.forgot_password span {
    color: var(--white);
    cursor: pointer;
    font-size: var(--fs-14);
    font-weight: 500;
    transition: all 0.3s ease;
}

.forgot_password span:hover {
    /* color: var(--sky-blue); */
    background-image: linear-gradient(to bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b);
    transition: all 0.3s ease;
}

.register-text {
    text-align: center;
}

.register-text p {
    color: var(--white);
    font-size: var(--fs-15);
}
</style>