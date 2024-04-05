<template>
    <div>
        <footer class="footer_section py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="footer_item d-flex align-items-center justify-content-center justify-content-md-start gap-3 gap-md-5 mb-3 mb-md-5 ">
                            <div class="footer_img">
                                <img src="/images/logo.png" alt="footer" class="img-fluid">
                            </div>
                            <div class="footer_text color_white">
                                <h4><router-link to="">IFX GLOBAL</router-link></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-7">
                        <div class="footer_text mb-4 mb-md-5">
                            <ul class="list-inline flex-wrap d-flex align-items-center justify-content-center gap-3 gap-lg-5">
                                <li>
                                    <router-link to="">Home </router-link>
                                </li>
                                <li>
                                    <router-link to="">About </router-link>
                                </li>
                                <li>
                                    <router-link to="">Blockchain </router-link>
                                </li>
                                <li>
                                    <router-link to="">Wallets </router-link>
                                </li>
                                <li>
                                    <router-link to="">Exchange </router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="footer_data position-relative d-md-flex align-items-center justify-content-between mb-3">
                        <div class="footer_icons">
                            <ul class="list-inline d-flex align-items-center justify-content-center justify-content-md-start gap-3 mb-3 mb-md-0">
                                <li>
                                    <router-link to=""> <img src="../assets/images/facebook.png" alt="facebook"> </router-link>
                                </li>
                                <li>
                                    <router-link to=""> <img src="../assets/images/linkedin.png" alt="linkedin"> </router-link>
                                </li>
                                <li>
                                    <router-link to=""> <img src="../assets/images/instagram.png" alt="instagram">
                                    </router-link>
                                </li>
                                <li>
                                    <router-link to=""> <img src="../assets/images/twitter.png" alt="twitter"> </router-link>
                                </li>
                            </ul>
                        </div>

                        <div class="copy_text mb-3 mb-md-0 text-center">
                            <router-link to="">2023 @ Copy Right</router-link>
                        </div>

                        <div class="Privacy_text text-center text-md-end mb-3 mb-md-0">
                            <router-link to="">Privacy Policy</router-link>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
<script>
export default{
    name:'FooterComponent',

}
</script>
<style scoped>

.footer_section {
    /* background-color: var(--black); */
    background-color: #5c354c;
}

.footer_text a,
.copy_text a,
.Privacy_text a {
    color: var(--white);
    text-decoration: none;
}
.footer_data:after{
    position: absolute;
    content: '';
     background: url(../assets/images/lines.png) no-repeat;
    background-size: contain;
    width: 100%;
    top: -29px;
    height: 1px;
}

@media all and (min-width:320px) and (max-width:767px){
    .footer_data:after {
        top: -17px;
        left:0;
    }
}
</style>
