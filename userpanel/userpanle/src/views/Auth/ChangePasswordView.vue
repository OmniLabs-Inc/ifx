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

                    <div class="col-md-6 col-lg-6 col-xl-4 mx-auto login_boxx">
                        <div class="logo-box">
                            <img src="/images/logo.png" alt="logo" class="img-fluid" style="width:74px;height:74px;" @click="$router.push('/')" />
                        </div><!--logo-box-->
                        <div class="auth_form">
                            <form class="row form-row">


                                <div class="col-md-12 mb-2">
                                    <div class="label-box mb-2">
                                        <label>Password</label>
                                    </div>
                                    <div class="input-group mb-2">

                                        <input :type="hidden ? 'password' : 'text'" class="form-control border-end-0"
                                            id="password" placeholder="Password" aria-label="Password"
                                            aria-describedby="basic-addon2">

                                        <span class="input-group-text border-start-0" id="basic-addon2">
                                            <img :src="hidden ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="hidden = !hidden" />
                                        </span>
                                    </div>


                                </div><!--col-md-12 mb-3-->

                                <div class="col-md-12 mb-3">
                                    <div class="label-box mb-2">
                                        <label>Confirm Password</label>
                                    </div>
                                    <div class="input-group mb-2">

                                        <input :type="hidden2 ? 'password' : 'text'" class="form-control border-end-0"
                                            id="password" placeholder="Confirm Password" aria-label="Confirm Password"
                                            aria-describedby="basic-addon2">

                                        <span class="input-group-text border-start-0" id="basic-addon2">
                                            <img :src="hidden2 ? hide : show" alt="" class="img-fluid"
                                                style="cursor: pointer;" @click="hidden2 = !hidden2" />
                                        </span>
                                    </div>

                                </div><!--col-md-12 mb-3-->



                                <div class="col-md-12 mb-3">
                                    <div class="submit-box">
                                        <button v-if="loading" type="submit" class="btn btn-primary">Submit</button>
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
import hide from "../../../public/images/auth/hide.svg"
import show from "../../../public/images/auth/show.svg"
export default {
    name: 'ChangePasswordView',

    data() {
        return {
            hidden: true,
            hidden2: true,
            hide: hide,
            show: show,
            loading: true
        }
    }
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
