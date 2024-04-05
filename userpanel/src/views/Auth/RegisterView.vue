<template>
    <div>
        <section class="register-sec auth_sec p-0">
            <div class="container-fluid p-xl-0">
                <div class="row align-items-center">
                    <!-- <div class="col-md-6 col-lg-6 col-xl-6">
                        <div class="login-image">
                            <img src="/images/auth/auth.jpg" alt="auth" class="img-fluid" />
                        </div>
                    </div> -->

                    <div class="col-md-6 col-lg-6 col-xl-4 mx-auto login_boxx">
                        <div class="logo-box">
                            <img src="/images/logo.png" alt="logo" class="img-fluid" style="width:74px;height:74px;" @click="$router.push('/')" />
                        </div><!--logo-box-->

                        <div class="head_auth">
                            <h2>Registration</h2>
                        </div>

                        <div class="auth_form">
                            <form class="row form-row" @submit.prevent="registerForm">
                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Name</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Name" aria-label="Name"
                                            v-model="name" aria-describedby="basic-addon1">
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.name.$error">
                                        {{ v$.name.$errors[0].$message }}
                                    </span>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Email</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Email" aria-label="Email"
                                            v-model="email" aria-describedby="basic-addon1">
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.email.$error">
                                        {{ v$.email.$errors[0].$message }}
                                    </span>
                                </div><!--col-md-12 mb-3-->

                                <!-- <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Username</label>
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Username" id="username"
                                            aria-label="Username" aria-describedby="basic-addon1">
                                        <span class="input-group-text username border-start-0" id="basic-addon1">
                                            <button class="btn btn-primary">Generate Username</button>
                                        </span>
                                    </div>
                                </div>col-md-12 mb-3 -->

                                <div class="col-md-12 mb-2 form-1">
                                    <div class="label-box mb-2">
                                        <label>Password</label>
                                    </div>
                                    <div class="input-group mb-2">

                                        <input :type="hidden ? 'password' : 'text'" class="form-control input-1" id="password"
                                            v-model="password" placeholder="Password" aria-label="Password"
                                            aria-describedby="basic-addon2" @keyup="validatePassword(this.password)">

                                        <span class="input-group-text border-start-0" id="basic-addon2">
                                            <img :src="hidden ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="hidden = !hidden" />
                                        </span>

                                        

                                    </div>

                                    <span id="msg"></span>

                                 
                                    <span class="error_msg text-danger" v-if="v$.password.$error">
                                        {{ v$.password.$errors[0].$message }}
                                    </span>


                                </div><!--col-md-12 mb-3-->

                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Confirm Password</label>
                                    </div>
                                    <div class="input-group mb-2">

                                        <input :type="hidden2 ? 'password' : 'text'" class="form-control " id="password"
                                            v-model="confirm_password" placeholder="Confirm Password"
                                            aria-label="Confirm Password" aria-describedby="basic-addon2">

                                        <span class="input-group-text border-start-0" id="basic-addon2">
                                            <img :src="hidden2 ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="hidden2 = !hidden2" />
                                        </span>
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.confirm_password.$error">
                                        {{ v$.confirm_password.$errors[0].$message }}
                                    </span>

                                </div><!--col-md-12 mb-3-->

                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Invitation Code</label>
                                    </div>
                                    <div class="input-group" v-if="this.referredBy && (sponser = this.referredBy)">
                                        <input type="text" class="form-control" readonly :placeholder="sponser"
                                            v-model="sponser" aria-label="Invitation Code" aria-describedby="basic-addon1">
                                    </div>
                                    <div class="input-group" v-else>
                                        <input type="text" class="form-control" v-model="sponser"
                                            aria-label="Invitation Code" aria-describedby="basic-addon1">
                                    </div>
                                    <span class="error_msg text-danger" v-if="v$.sponser.$error">
                                        {{ v$.sponser.$errors[0].$message }}
                                    </span>
                                </div><!--col-md-12 mb-3-->

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

                                <div class="col-md-12">
                                    <div class="login-text">
                                        <p>Already have an account? <span @click="$router.push('/login')"
                                                style="background-image: linear-gradient(to bottom, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b); cursor: pointer;">Login</span></p>
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
import hide from "/public/images/auth/hide.svg"
import show from "/public/images/auth/show.svg"
import ApiClass from "../../api/api";
import { required, email, helpers, minLength, maxLength, sameAs } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
export default {
    name: 'RegisterView',

    data() {
        return {
            hidden: true,
            hidden2: true,
            hide: hide,
            show: show,
            loading: false,
            name: "",
            email: "",
            password: "",
            confirm_password: "",
            sponser: "",
            referredBy: ''
        }
    },
    mounted() {
        const sponsors = this.$route.query.sponser

        // Set sponsor value to the URL parameter value or an empty string
        this.referredBy = sponsors || ''
        console.log(this.referredBy);

         this.validatePassword(this.password)
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            name: {
                required: helpers.withMessage("Name is Required", required),
                minLength: minLength(4),
                maxLength: helpers.withMessage("Name should not exceed 30 letters", maxLength(30)),
            },
            email: {
                required: helpers.withMessage("Email is Required", required),
                email: helpers.withMessage("Invalid Email Address", email),
                maxLength: helpers.withMessage("Email should not exceed 30 letters", maxLength(36)),
            },
            password: {
                minLength: minLength(8),
                maxLength: maxLength(200),
                required: helpers.withMessage("Password is Required", required),
                // valid: helpers.withMessage("Password Should Contain A Capital Letter, A Special Charcater And A Number", function (value) {
                //     const containsUppercase = /[A-Z]/.test(value)
                //     const containsLowercase = /[a-z]/.test(value)
                //     const containsSpecial = /[#?!@$%^&*-]/.test(value)
                //     return containsUppercase && containsLowercase && containsSpecial
                // })
            },
            confirm_password: {
                sameAsPassword: helpers.withMessage(
                    "Password and Confirm Password should match",
                    sameAs(this.password)
                ),
            },
            sponser: {
                required: helpers.withMessage("Sponser Id is Required", required),
            },
        };
    },
    methods: {

        validatePassword(password) {
                
            // Do not show anything when the length of password is zero.
            if (password.length === 0) {
                document.getElementById("msg").innerHTML = "";
                return;
            }
            // Create an array and push all possible values that you want in password
            var matchedCase = new Array();
            matchedCase.push("[$@$!%*#?&]"); // Special Charector
            matchedCase.push("[A-Z]");      // Uppercase Alpabates
            matchedCase.push("[0-9]");      // Numbers
            matchedCase.push("[a-z]");     // Lowercase Alphabates

            // Check the conditions
            var ctr = 0;
            for (var i = 0; i < matchedCase.length; i++) {
                if (new RegExp(matchedCase[i]).test(password)) {
                    ctr++;
                }
            }
            // Display it
            var color = "";
            var strength = "";
            switch (ctr) {
                case 0:
                case 1:
                case 2:
                    strength = "Very Weak";
                    color = "red";
                    break;
                case 3:
                    strength = "Medium";
                    color = "orange";
                    break;
                case 4:
                    strength = "Strong";
                    color = "green";
                    break;
            }
            document.getElementById("msg").innerHTML = strength;
            document.getElementById("msg").style.color = color;
            document.getElementById("msg").style.fontWeight = 600;
        },

        
        resetForm() {
            this.name = "";
            this.email = "";
            this.password = "";
            this.confirm_password = "";
            this.sponser = "";
            this.loading = false;
            this.v$.$reset(); // reset validation
        },
        async registerForm() {
            const result = await this.v$.$validate();
            if (!result) {
                // notify user form is invalid
                return;
            }

            let form_data = {
                email: this.email,
                name: this.name,
                sponser: this.sponser,
                password: this.password,
                confirm_password: this.confirm_password,

            };

            // If Fileds are Valid

            this.loading = true;
            // Submit Form In Backend
            let response = await ApiClass.postRequest("register", false, form_data);

            if (response?.data) {
                this.loading = false;

                if (response.data.status_code == 0) {
                    // return Error Message Here
                    this.failed(response.data.message);

                    return;
                }
                if (response.data.status_code == 1) {
                    // return Success Message Here
                    this.success(response.data.message);

                    // Empty Form Data
                    this.$router.push({
                        name: 'otp',
                        query: {
                            type: "verify_email",
                            user_unique_id: response.data.data || ''
                        }
                    });
                    this.resetForm();
                    return

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



/* input[type='password'] {
  width: 100%;
  box-sizing: border-box;
  height: 55px;
  display: inline-block;
  border: 3px solid #2F96EF;
  border-radius: 5px;
  padding: 0 15px;
  margin: 10px 0;
  transition: .2s;
}

input[type='password']:focus {
  outline: none;
   -moz-outline: none;
   -webkit-outline: none;
} */

lable:before {
  content: "\f070";
  color: #aaa;
  font-size: 22px;
  font-family: FontAwesome;
  position: absolute;
  right: 25px;
  top: 44px;
}

.progress-bar_wrap {
  width: 300px;
  height: 5px;
  background: #F6F6FA;
  display: inline-block;
  vertical-align: middle;
  overflow: hidden;
  border-radius: 5px;
}



.form-1 .progress-bar_item {
  display: inline-block;
  height: 100%;
  width: 33.33%;
  float: left;
  visibility: hidden;
  transition: background-color .2s, visisility .1s;
}

.form-1 .active {
  visibility: visible;
}

.progress-bar_item-1 {
  
}

.progress-bar_item-2 {

}

.progress-bar_item-3 {

}

#progress-bar_text {
  display: inline-block;
  color: #aaa;
  margin-left: 5px;
  transition: .2s;
}

</style>
