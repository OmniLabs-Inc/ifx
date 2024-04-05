<template>
    <div>
        <footer class="footer_section pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xl-12">
                        <div class="footer_content text-center mt-4">
                            <div class=" mb-3 mb-lg-4">
                                <img src="@/assets/new/images/logo.png" alt="" class="img-fluid">
                            </div>
                            <!-- logo -->
                            <div class="Social_list mb-2 mb-lg-3">
                                <ul class="list-group list-group-horizontal justify-content-center">
                                    <li class="list-group-item"><a href=""><img src="@/assets/new/images/home/facebok.png" alt=""
                                                class=""></a></li>
                                    <li class="list-group-item"><a href=""><img src="@/assets/new/images/home/linkdin.png" alt=""
                                                class=""></a></li>
                                    <li class="list-group-item"><a href=""><img src="@/assets/new/images/home/insta.png" alt=""
                                                class=""></a></li>
                                    <li class="list-group-item"><a href=""><img src="@/assets/new/images/home/twitter.png" alt=""
                                                class=""></a></li>
                                </ul>
                            </div>
                            <div class="Social_list1">
                                <ul
                                    class="list-group list-group-horizontal justify-content-center text-nowrap flex-wrap">
                                    <li class="list-group-item"><a href=""
                                            class="text-capitalize text-decoration-none">home</a></li>
                                    <li class="list-group-item"><a href="" class="text-capitalize text-decoration-none">
                                            features </a></li>
                                    <li class="list-group-item"><a href="" class="text-capitalize text-decoration-none">
                                            about </a></li>
                                    <li class="list-group-item"><a href=""
                                            class="text-capitalize text-decoration-none">advantages</a></li>
                                    <li class="list-group-item"><a href=""
                                            class="text-capitalize text-decoration-none">services </a></li>
                                    <li class="list-group-item"><a href="/MetGainWhitepaper.pdf" target="_blank"
                                            class="text-capitalize text-decoration-none"> white paper </a></li>
                                    <li class="list-group-item"><a href="" class="text-capitalize text-decoration-none">
                                            token </a></li>
                                    <li class="list-group-item"><a href="" class="text-capitalize text-decoration-none">
                                            Roadmap </a> </li>
                                    <li class="list-group-item"><a href="" class="text-capitalize text-decoration-none">
                                            FAQ </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- footer top -->
        <div class="bottom_footer p-2">
            <div class="container">
                <div class="main_bottom_footer d-flex align-items-center justify-content-between">
                    <div class="left_side_footer mb-2 mb-md-0">
                        <p class="mb-0">Copyright ©{{ new Date().getFullYear() }}<a href=""
                                class="text-decoration-none"> BTCBULLZ </a> all rights reserved.</p>
                    </div>
                    <div class="ride_side_footer d-flex align-items-center gap-3 ">
                        <div>
                            <p class="mb-0 text-capitalize"><a href="/MetGainReferralPlan.pdf" target="_blank"
                                    class="text-decoration-none ">Referral Plan</a></p>
                        </div>
                        <div>
                            <p class="mb-0 text-capitalize"><a href="/MetGainPrivacyPolicy.pdf" target="_blank"
                                    class="text-decoration-none ">Privacy Policy</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- bottom-footer -->

    </div>
</template>

<script>
    export default {
        name: 'FooterComponent',
    }
</script>

<style scoped>
    footer {
        padding: 70px 0;
    }

    .footer_section {
        background-image: url("@/assets/new/images/home/footer_bg.png");
        background-position: top center;
        background-repeat: no-repeat;
        background-size: cover;
        background-color: var(--section-pro);
    }

    .footer_section .footer_content .Social_list .list-group .list-group-item {
        background-color: transparent;
        border: unset !important;
        padding: 10px;
    }

    .footer_section .footer_content .Social_list .list-group .list-group-item a img {
        height: 55px;
        width: 55px;
    }

    .footer_section .footer_content .Social_list1 .list-group .list-group-item {
        background-color: transparent;
        border: unset !important;
    }

    .footer_section .footer_content .Social_list1 .list-group .list-group-item a {
        font-size: var(--fs-15);
        font-weight: 500;
        color: var(--white);
        opacity: 0.7;
    }

    .bottom_footer {
        background-color: var(--section-pro);
        border-top: 3px solid var(--sky-blue);
    }

    .bottom_footer .left_side_footer p {
        font-size: var(--fs-15);
        font-weight: 400;
        color: var(--white);
        opacity: 0.7;
    }

    .bottom_footer .left_side_footer p a {
        color: var(--sky-blue);
    }

    .bottom_footer .ride_side_footer p a {
        font-size: var(--fs-15);
        font-weight: 400;
        color: var(--white);
        opacity: 0.7;
    }

    .bottom_footer .ride_side_footer p a:hover {
        color: var(--sky-blue);
    }

    @media all and (min-width: 768px) and (max-width: 991px) {
        .footer_section {
            background-image: unset !important;
        }

        .bottom_footer {
            border-top: unset !important;
        }
    }

    @media all and (min-width: 320px) and (max-width: 767px) {
        .footer_section {
            background-image: unset !important;
        }

        .bottom_footer {
            border-top: unset !important;
        }

        .bottom_footer .main_bottom_footer {
            flex-direction: column;
            text-align: center;
        }

        .bottom_footer .main_bottom_footer .left_side_footer p,
        .bottom_footer .main_bottom_footer .ride_side_footer p {
            font-size: var(--fs-14);
        }
    }
</style>