<template>
  <div>
    <div class=" row justify-content-center align-items-center">
      <div class="col-md-11 col-lg-11 col-xl-10 col-xxl-7">
        <!-- PROFILE BOX  -->
        <div class="profile_box">
          <div class="row justify-content-center">
            <!-- QR IMAGE BOX -->
            <div class="col-lg-5 px-0 qr_box rounded-start-3">
              <div
                class="qr_inner_box p-2 p-md-4 px-5 w-100 h-100 d-flex justify-content-center align-items-center flex-column gap-3">
                <div class="user-img-box position-relative">
                  <img src="/images/dashboard/profile/user-img2.png" alt="qr-image" class="rounded-circle user-img" />
                  <!-- <input type="file" class="form-control user-input shadow-none position-absolute"
                    aria-describedby="basic-addon1" disabled /> -->
                  <!-- <img class="upload-img-box rounded-circle border border-2 p-1 position-absolute"
                    src="/images/dashboard/icon/upload-img.svg" alt="upload-img" /> -->
                </div>
                <div class="user-data-box" v-if="user != null">
                  <p class="user-data text-center mb-1">{{ user.name }}</p>
                  <p class="user-data text-center mb-1">
                    {{ user.email }}
                  </p>
                </div>
                <div class="user-data-box" v-else>
                  <p class="user-data text-center mb-1">name not found</p>
                  <p class="user-data text-center mb-1">
                    email not found
                  </p>
                </div>
              </div>
            </div>
            <!-- INFORMATION BOX -->
            <div class="col-lg-7 px-0">
              <div class="information_box p-4 rounded-end-3">
                <!-- HEADING -->
                <h5 class="mb-4">
                  <img class="mb-1" src="/images/dashboard/icon/alert.svg" alt="alert" />

                  Edit Information
                </h5>
                <!-- INFORMATION FORM  -->
                <form action="" class="row" v-if="user != null">
                  <div class="col-md-12">
                    <!-- USERNAME INPUT -->
                    <div class="form_box">
                      <p class="user-label p-0 d-flex align-items-center gap-2">
                        <img src="/images/dashboard/icon/user.svg" alt="icon" />
                        Username
                        <!--username change Button trigger modal -->

                      </p>

                      <div class="input-group user-input-box mb-4">
                        <input type="text" class="user-input-main form-control shadow-none"  v-model="name"
                          aria-describedby="basic-addon1" />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <!-- EMAIL INPUT -->
                    <div class="form_box mb-4">
                      <p class="user-label p-0 d-flex align-items-center gap-2">
                        <img src="/images/dashboard/icon/email.svg" alt="icon" />
                        Email
                        <!--email change Button trigger modal -->
                      </p>

                      <div class="input-group user-input-box">
                        <input type="text" class="user-input-main form-control shadow-none" v-model="email"
                          aria-describedby="basic-addon1" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <!-- EMAIL INPUT -->
                    <div class="form_box mb-4">
                      <p class="user-label p-0 d-flex align-items-center gap-2">
                        <img src="/images/dashboard/icon/user.svg" alt="icon" />
                        Mobile no.
                        <!--email change Button trigger modal -->
                      </p>

                      <div class="input-group user-input-box">
                        <input type="text" class="user-input-main form-control shadow-none" v-model="mobile"
                          aria-describedby="basic-addon1" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <!-- EMAIL INPUT -->
                    <div class="form_box mb-4">
                      <p class="user-label p-0 d-flex align-items-center gap-2">
                        <img src="/images/dashboard/icon/swap.svg" alt="icon" />
                        Wallet Adrress
                        <!--email change Button trigger modal -->
                      </p>

                      <div class="input-group user-input-box">
                        <input type="text" class="user-input-main form-control shadow-none" v-model="wallet"
                          aria-describedby="basic-addon1" />
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <!-- PASSWORD INPUT -->
                    <div class="form_box mb-4">
                      <p class="user-label p-0 d-flex align-items-center gap-2">
                        <img src="/images/dashboard/icon/eye.svg" alt="icon" />
                        Password
                        <!--password change Button trigger modal -->
                        <button type="button" class="bg-transparent border-0 mb-1 ms-auto" data-bs-toggle="modal"
                          data-bs-target="#staticBackdrop3">
                          <img class="" src="/images/dashboard/icon/edit.svg" alt="edit" />
                        </button>
                      </p>
                      <div class="input-group user-input-box">
                        <input :type="type" class="user-input-main form-control shadow-none" value="************" readonly
                          aria-describedby="basic-addon1" />
                        <!-- <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-if="type == 'password'"
                          @click="type = 'text'"><img src="/images/dashboard/icon/eye-close.svg" alt="icon" /></span>
                        <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-else
                          @click="type = 'password'"><img src="/images/dashboard/icon/eye.svg" alt="icon" /></span> -->
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <button type="button" class="active_btn px-3 py-2" disabled v-if="changeprofile_loading">
                      <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                      Loading...
                    </button>
                    <button type="submit" class="btn user-submit" @click="updatep" v-else>Edit Profile</button>

                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- password change modal -->
  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel3" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="staticBackdropLabel">
            <img class="me-1 " src="/images/dashboard/icon/eye.svg" alt="icon" />
            Change Password
          </h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
            id="changepasswordmodalclose"></button>
        </div>
        <div class="modal-body">
          <form class="row" @submit.prevent="changePassword">

            <div class="col-md-12">
              <!-- Email INPUT -->
              <div class="form_box mb-4">
                <p class="user-label p-0 d-flex align-items-center gap-2">
                  Enter Old Password
                </p>

                <div class="input-group user-input-box ">
                  <input :type="typeModal1" class="user-input-main form-control shadow-none"
                    placeholder="Enter New Password " aria-describedby="basic-addon1" v-model="old_password" />
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-if="typeModal1 == 'password'"
                    @click="typeModal1 = 'text'"><img src="/images/dashboard/icon/eye-close.svg" alt="icon" /></span>
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-else
                    @click="typeModal1 = 'password'"><img src="/images/dashboard/icon/eye.svg" alt="icon" /></span>
                </div>
                <span class="error_msg text-danger" v-if="v$.old_password.$error">
                  {{ v$.old_password.$errors[0].$message }}
                </span>
              </div>
              <div class="form_box mb-4">
                <p class="user-label p-0 d-flex align-items-center gap-2">
                  Enter New Password
                </p>

                <div class="input-group user-input-box">
                  <input :type="typeModal3" class="user-input-main form-control shadow-none"
                    placeholder="Enter New Password " aria-describedby="basic-addon1" v-model="new_password" />
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-if="typeModal3 == 'password'"
                    @click="typeModal3 = 'text'"><img src="/images/dashboard/icon/eye-close.svg" alt="icon" /></span>
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-else
                    @click="typeModal3 = 'password'"><img src="/images/dashboard/icon/eye.svg" alt="icon" /></span>
                </div>
                <span class="error_msg text-danger" v-if="v$.new_password.$error">
                  {{ v$.new_password.$errors[0].$message }}
                </span>
              </div>
              <div class="form_box mb-4">
                <p class="user-label p-0 d-flex align-items-center gap-2">
                  Confirm New Password
                </p>

                <div class="input-group user-input-box">
                  <input :type="typeModal2" class="user-input-main form-control shadow-none"
                    placeholder="Confirm New Password " aria-describedby="basic-addon1" v-model="confirm_password" />
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-if="typeModal2 == 'password'"
                    @click="typeModal2 = 'text'"><img src="/images/dashboard/icon/eye-close.svg" alt="icon" /></span>
                  <span class="d-flex align-items-center password-eye px-2 rounded-end-2" v-else
                    @click="typeModal2 = 'password'"><img src="/images/dashboard/icon/eye.svg" alt="icon" /></span>
                </div>
                <span class="error_msg text-danger" v-if="v$.confirm_password.$error">
                  {{ v$.confirm_password.$errors[0].$message }}
                </span>
              </div>
              <!-- <div class="form_box">
                <p class="user-label p-0 d-flex align-items-center gap-2">
                  Enter OTP
                </p>

                <div class="input-group user-input-box mb-4">
                  <input type="text" class="user-input-main form-control shadow-none" placeholder="Enter OTP "
                    aria-describedby="basic-addon1" />
                  <button class="btn user-btn">Send OTP</button>
                </div>
              </div> -->
            </div>
            <div class="modal-footer justify-content-center">
              <button type="button" class="active_btn px-3 py-2" disabled v-if="changepassword_loading">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
              </button>
              <button type="submit" class="btn user-submit" v-else>Update Password</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import ApiClass from "../../api/api";
import {
  required,
  helpers,
  minLength,
  maxLength,
  not,
  sameAs,
  email
} from "@vuelidate/validators";
import {
  useVuelidate
} from "@vuelidate/core";
export default {
  name: "ProfileView",
  data() {
    return {

      type: "password",
      typeModal1: "password",
      typeModal3: "password",
      typeModal2: "password",
      old_password: '',
      new_password: '',
      confirm_password: '',
      changepassword_loading: false,
      changeprofile_loading: false,
      user: null,
      email:'',
      name:'',
      mobile:'',
      wallet:''
    }
  },
  setup() {
    return {
      v$: useVuelidate(),
    };
  },
  validations() {
    return {
      old_password: {
        required: helpers.withMessage("Old password is Required", required)
      },
      new_password: {
        minLength: minLength(6),
        maxLength: maxLength(12),
        required: helpers.withMessage("New password is Required", required),
        notSameAsPassword: helpers.withMessage(
          "New Password and Old Password must not match",
          not(sameAs(this.old_password))
        ),
      },
      confirm_password: {
        required: helpers.withMessage("Confirm password is Required", required),
        sameAsPassword: helpers.withMessage(
          "New Password and Confirm Password should match",
          sameAs(this.new_password)
        ),
      },
    };
  },
  mounted() {
    this.user = localStorage.getItem("user") ? JSON.parse(localStorage.getItem("user")) : null;

    if(this.user != null){
      this.name = this.user.name;
      this.email = this.user.email;
      this.mobile = this.user.mobile;
      this.wallet = this.user.wallet
    }
  },
  methods: {
    async changePassword() {
      const result = await this.v$.$validate();
      if (!result) {
        // notify user form is invalid
        return;
      }
      let form_data = {
        old_password: this.old_password,
        new_password: this.new_password,
        confirm_password: this.confirm_password
      };
      this.changepassword_loading = true;
      let response = await ApiClass.postRequest("changepassword", true, form_data);
      this.changepassword_loading = false;
      if (response?.data) {
        if (response.data.status_code == 0) {
          // return Error Message Here
          this.failed(response.data.message);
          return;
        }
        if (response.data.status_code == 1) {
          this.success(response.data.message);
          document.getElementById("changepasswordmodalclose").click();
          localStorage.removeItem("token");
          localStorage.removeItem("user")
          return this.$router.push("/login");
        }
      }
    },

    validateEmail(email){
      return email.match(
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
      );
    },

    async updatep(){

      let ee = this.email.trim();
      let nn = this.name.trim();
      let ph = this.mobile.trim();
      let wa = this.wallet.trim();

      if(nn == ''){
        alert('Name should not be empty.');
        return;
      }

      if(ee == ''){
        alert('Email should not be empty.');
        return;
      }

      if(!this.validateEmail(ee)){
        alert('Email is not valid.');
        return;
      }

      this.changeprofile_loading = true;

      // here we will update name and email
      let response = await ApiClass.postRequest("changeprofile", true, {
        email:ee,
        name:nn,
        mobile:ph,
        wallet:wa
      });

      if (response?.data) {
        if (response.data.status_code == 0) {
          this.changeprofile_loading = false;
          // return Error Message Here
          this.failed(response.data.message);
          return;
        }
        if (response.data.status_code == 1) {
          this.changeprofile_loading = false;
          let dd = localStorage.getItem("user");
          let c =  JSON.parse(dd);
          c.name = nn;
          c.email = ee;
          c.mobile = ph;
          c.wallet = wa;
          localStorage.setItem("user", JSON.stringify(c))
          this.success(response.data.message);
        }
      }

    }


  }
};
</script>

<style scoped>
.profile_box {
  box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}

.qr_box {
  background: var(--bg-secondary);
}

.user-img {
  max-width: 130px;
}

.user-input {
  right: 0;
  width: 36px;
  height: 36px;
  z-index: 1;
  bottom: 0;
  opacity: 0;
}

.upload-img-box {
  right: 0;
  bottom: 0;
  background: var(--bg-secondary) !important;
}

.user-data {
  color: var(--white);
  font-weight: 500;
  font-size: var(--fs-14);
}

.information_box {
  background: var(--d-bg);
  box-shadow: rgb(0 0 0 / 20%) 0px 5px 15px !important;
}

.information_box h5 {
  color: var(--white);
}

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

.password-eye {
  background: var(--bg-secondary);
}

/* modals css */
.modal-title {
  font-size: var(--fs-17);
}

.modal-content {
  background: var(--d-bg);
  color: var(--white);
}

.modal-header {
  border-bottom-color: var(--modal-border);
}

.modal-footer {
  border-top-color: var(--modal-border);
}

.modal-header .btn-close {
  filter: invert(1);
}

.user-btn {
  background: var(--gradient);
  color: var(--white);
  font-size: var(--fs-13);
}

.user-submit {
  background: var(--gradient);
  color: var(--white);
  font-size: var(--fs-15);
}
@media (max-width: 991px) {
  .profile_box .rounded-start-3,
  .profile_box .rounded-end-3 {
    border-radius: 0 !important;
  }
}
@media all and (min-width: 320px) and (max-width: 576px) {
  .qr_box {
    border-radius: 0 !important;
    border-top-left-radius: 0.5rem !important;
    border-top-right-radius: 0.5rem !important;
  }

  .information_box {
    border-radius: 0 !important;
    border-bottom-left-radius: 0.5rem !important;
    border-bottom-right-radius: 0.5rem !important;
  }
}
</style>
