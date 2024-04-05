<template>
    <div>
        <div class="row">
            <div class="col-12 col-xl-9">
                <div class="row">
                    <div class="col-md-12 col-xl-7">
                        <div class="main_box  cus_card justify-content-between py-3 mb-4">
                            <div class="dashboard-info">
                                <h2 class="text-capitalize mb-3">Welcome
                                    <span class="text-white">
                                        {{ User?.name }}
                                    </span>
                                </h2>

                                <div class="card-info">
                                    <label class="mb-2">Refferal Link</label>
                                    <div class="d-md-flex gap-2 mb-2 align-items-center">
                                        <label class="mb-1">Left</label>
                                        <p class="mb-1" id="left-refferal">
                                            {{ User?.lrl }}
                                        </p>
                                        <button type="button" @click="() => copyorder('left-refferal')"
                                            class="rounded-pill px-3 py-1 border-0"> {{ copyLeft }}</button>
                                    </div>
                                    <div class="d-md-flex gap-2 mb-2 align-items-center">
                                        <label class="mb-1">Right</label>
                                        <p class="mb-1" id="right-refferal">
                                            {{ User?.rrl }}
                                        </p>
                                        <button type="button" @click="() => copyorder('right-refferal')"
                                            class="rounded-pill px-3 py-1 border-0 p"> {{ copyRight }}</button>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="dashboard_img text-end">
                                <img src="images/dashboard/coin.png" alt="">
                            </div> -->
                        </div>
                    </div>

                    <!-- ========================================= -->

                    <div class="col-md-12 col-xl-5">
                        <div class="row cus_card p-2 py-3 mb-4 align-items-center">
                            <div class="col-md-12">
                                <div><label>Account</label></div>
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="form_box1 d-flex align-items-center mb-2">
                                    <input
                                        class="form-control px-0 border-0 bg-transparent shadow-none refferal-link rounded"
                                        type="text" value="Https://metgain.io/Register/Metgain/Register/Metgain"
                                        aria-label="Disabled input example" readonly>
                                    <svg class="" height="17px" width="17px" style="fill:rgba(255,255,255,1)"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path
                                            d="M224 0c-35.3 0-64 28.7-64 64V288c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H224zM64 160c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H288c35.3 0 64-28.7 64-64V384H288v64H64V224h64V160H64z" />
                                    </svg>
                                </div>
                            </div> -->

                            <!-- <div class="col-md-3">
                                <div><img src="/images/dashboard/scan.png" alt="" class="img-fluid"></div>
                            </div> -->
                            <div class="col-md-9">
                                <template v-if="user_wallet?.length > 0">
                                    <div class="account_info_box" v-for="(data, index) in user_wallet" :key="index">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2"><img
                                                    :src="AllData.walet_images[data.currency]" style="width:20px"
                                                    alt="icon">{{
                                                        data?.currency }}</div>
                                            <h6 class="balance_box">Bal:- {{ parseFloat(data?.balance) }}</h6>
                                        </div>

                                    </div>
                                </template>
                                <template v-else>
                                    <div class="account_info_box" v-for="(data, index) in defaultWallet" :key="index">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2"><img :src="data.image"
                                                    style="width:20px" alt="icon">{{
                                                        data?.currency }}</div>
                                            <h6 class="balance_box">Bal:- {{ parseFloat(data?.balance) }}</h6>
                                        </div>

                                    </div>
                                </template>

                            </div>

                        </div>



                    </div>
                </div>
                <!-- ========================================= -->

            </div>
        </div>

    </div>
</template>

<script>
import ApiClass from '../../api/api';
import StakingComponent from '../../components/UserPanel/StakingComponent.vue';
 

export default {
    name: 'DashboardView',
    components: {
        StakingComponent
    },
    data() {
        return {
            IncomeData: [],
            General: [],
            user_wallet: [],
            User: {},
            AllData: [],
            members:{},
            business:{},
            today_business:[],
            defaultWallet: [
                {
                    image: "/images/currency/usd.png",
                    currency: "USDT",
                    balance: 0
                },
                // {
                //     image: "/images/currency/afc.png",
                //     currency: "AFC",
                //     balance: 0
                // }
            ],
            copyLeft: 'copy',
            copyRight: 'copy',
            need_msg:''
        }
    },
    mounted() {
        this.Dashboard()
        this.rewardOneTime();
    },
    methods: {

        async Dashboard() {
            let res = await ApiClass.getRequest("dashboard/get", true)
            if (res.data.status_code == 1) {
                this.General = res.data.data.general
                this.IncomeData = res.data.data.income
                this.user_wallet = res.data.data.user_wallet
                this.User = res.data.data.user
                this.members = res.data.data.members
                this.business = res.data.data.business
                this.today_business = res.data.data.today_business
                this.AllData = res.data.data
            }

        },
        copyorder(w) {
            var text = document.getElementById(w).innerText;
            var elem = document.createElement("textarea");
            document.body.appendChild(elem);
            elem.value = text;
            elem.select();
            document.execCommand("copy");
            document.body.removeChild(elem);
            if (w == "left-refferal") {
                this.copyLeft = "copied";
                setTimeout(() => {
                    this.copyLeft = "copy";
                }, 2000)
            } else {
                this.copyRight = "copied";
                setTimeout(() => {
                    this.copyRight = "copy";
                }, 2000)
            }

        },
        async rewardOneTime(){
            let res = await ApiClass.getRequest("dashboard/reward_available", true)
            if (res.data.status_code == 1) {
                this.need_msg = res.data.message; 
                const exampleModal = document.getElementById('dd')
                if (exampleModal) {
                    exampleModal.click()
                }
            }
        }
    }
}
</script>

<style>
.card-info label {
    font-size: var(--fs-15);
    font-weight: 700;
}

.card-info span {
    text-decoration: underline;
    font-size: var(--fs-14);
}

.card-info button {
    font-size: var(--fs-13);
}

.card-info .form-control {
    font-size: var(--fs-15);
}

.balance_box {
    color: var(--white);
    font-size: var(--fs-13);
    font-weight: 500;
}

.account_info_box {
    color: var(--white);
    font-size: var(--fs-13);
}

.account_info_box h6 {
    color: var(--white);
    font-size: var(--fs-13);
}

.form_box1 .form-control {
    min-height: 40px;
    font-size: var(--fs-14);
    color: var(--white);
    background-color: var(--d-bg);
    border: 1px solid var(--d-bg);
    border-radius: 4px;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
}

.cus_tab {
    padding: 35px 35px;
}

.spinner-border {
    --bs-spinner-width: 20px;
    --bs-spinner-height: 20px;
}

.swap_tab {
    min-height: 389px;
}

.swaping_box .show_info {
    background: var(--d-bg);
    color: var(--white);
    box-shadow: var(--card-shadow);
    border-radius: 7px;
    padding: .375rem .75rem;
}

.swaping_box .show_info p {
    color: var(--white);
    font-size: var(--fs-14);
}

.swaping_box .show_info span {
    color: var(--grey);
    font-size: var(--fs-13);
}

.swaping_box .show_info ul li {
    color: var(--white);
    font-size: var(--fs-13);
    border: 1px solid var(--text-blue);
    padding: 1px 10px;
    border-radius: 7px;

}

.swap_info h5 {
    color: var(--white);
    font-size: var(--fs-13);
}

.swap-arrow span {
    background: #ffffff29;
    height: 35px;
    width: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border-radius: 7px;
}

/* .swaping_box h6 {
    color: var(--text-blue) !important;
    font-size: var(--fs-14) !important;
} */

.dashboard-info {
    z-index: 99
}

.dashboard-info h2 {
    color: var(--text-blue);
    font-size: var(--fs-20);
    font-weight: 600;
    line-height: 29px;
}

.dashboard_img img {
    position: absolute;
    right: -25px;
    bottom: -25px;
}

.dashboard_img {
    position: relative;
}

.dashboard_img::before {
    background: url('images/dashboard/mask.png');
    bottom: -6.5rem;
    content: "";
    height: 347px;
    position: absolute;
    left: -135px;
    width: 351px;
    background-size: contain;
}

.dashboard-info p {
    color: var(--grey);
}

.main_box img {
    transform: matrix(-1, 0, 0, 1, 0, 0);
    width: 300px;
}

.main_box {
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.main_box::after {
    background: url(images/dashboard/mask2.png);
    content: "";
    height: 347px;
    position: absolute;
    right: -20%;
    transform: rotateY(180deg);
    top: -59%;
    width: 351px;
    z-index: -1;
    background-size: contain;
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

.nav-link,
.nav-link:focus,
.nav-link:hover {
    color: var(--white);
    font-weight: 500;
    font-size: var(--fs-14);

}

.nav-pills .nav-link.active,
.nav-pills .show>.nav-link {
    color: var(--text-blue);
    /* background: var(--gradient); */
    background: transparent;
    border-bottom: 1px solid var(--text-blue);

}

.nav-pills .nav-link {
    border-bottom: 1px solid var(--grey);
    border-radius: 0;
}

.form_box label {
    color: var(--white);
    font-size: var(--fs-14);
    font-weight: 500;
    margin-bottom: 5px;
}

.form_box label span {
    color: var(--text-blue);
    font-size: var(--fs-13);
}

.form_box .form-control {
    min-height: 40px;
    font-size: var(--fs-14);
    color: var(--white);
    background-color: var(--d-bg);
    border: 1px solid var(--d-bg);
    border-radius: 4px;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;

}

.form_box .input-group-text {
    background: var(--gradient);
    color: var(--white);
    border: 1px solid var(--text-blue);
    font-size: var(--fs-13);
    font-weight: 500;
    padding: 8px 8px;
    cursor: pointer;
    min-height: 40px;

}

.form_box .input-group {
    border-radius: 4px;
    box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px, rgba(0, 0, 0, 0.24) 0px 1px 2px;
}

.form_box .form-select {

    min-height: 40px;
    background-color: var(--d-bg);
    border: 1px solid var(--d-bg);
    border-radius: 4px;
    box-shadow: rgb(0 0 0 / 12%) 0px 1px 3px, rgb(0 0 0 / 24%) 0px 1px 2px;
    font-size: var(--fs-13);
    color: var(--white);
    padding: .375rem .75rem;
    --bs-form-select-bg-img: url('images/dashboard/icon/arrow-down.svg');
    background-size: 25px 30px;
}

.form-select:focus {
    border: 1px solid var(--d-bg);
}

option {
    font-size: var(--fs-14);
    background-color: var(--d-bg);
    border: none !important;
}

.form_box ::placeholder {
    color: var(--grey);
    font-size: var(--fs-13);
}

.cus_card h6 {
    font-size: var(--fs-13);
    color: var(--grey);
}

.cus_card h6 span {
    color: var(--text-blue);
}

.dashbord_card_main {
    color: var(--white);
    min-height: 220px;
}

.dashbord_card_main h5 {
    /* font-weight: 600; */
    font-size: var(--fs-14);
}

.dashbord_card_main .form-control {
    border: 1px solid var(--white);
    color: var(--white);
    font-size: var(--fs-14);
}

.rounded-pill {
    background: #ffffff29;
    color: var(--white);
    font-size: var(--fs-14);
    box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;
}

.income_box p {
    color: var(--grey);
    font-size: var(--fs-14);
    /* display: -webkit-box; */
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.income_box h5 {
    color: var(--white);
    font-size: var(--fs-14);
}

.main_income_box {
    /* background: var(--bg-secondary); */
    color: var(--white);
    /* background-image: linear-gradient(to right, #de4232, #bd1c52, #8a1962, #4e2060, #0d1c4b); */
    /* background-image: linear-gradient(to left, #bd1c52, #8a1962, #4e2060); */
    background-image: linear-gradient(to left, #8a1962, #4e2060);
    box-shadow: var(--card-shadow);
    border-radius: 7px;
}

@media all and (min-width:768px) and (max-width:991px) {
    .dashboard-info h2 {
        font-size: var(--fs-12);
    }

    .dashboard-info h2 br {
        display: none;
    }
}

@media all and (min-width:320px) and (max-width:767px) {
    .dashboard-info h2 {
        font-size: var(--fs-12);
    }

    .dashboard-info h2 br {
        display: none;
    }

    .dashboard_img {
        display: none;
    }

    .dashboard-info {
        text-align: center;
    }
}
</style>
