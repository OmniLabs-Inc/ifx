<template>
    <div>
        <div class="row">
            <!--<div class="col-12 col-xl-9">

                <div class="pricing-table">
                    <div v-for="plan in plans" :key="plan.name" class="swap_tab cus_card p-0 pt-2 pricing-column col-12 col-xl-3">
                        <h2>{{ plan.name }}</h2>
                        <p class="price">{{ plan.min_price }} - {{ plan.max_price }}</p>
                        <h3>{{ plan.price }} Per month</h3>
                        <ul class="features">
                        <li v-for="feature in plan.features" :key="feature">{{ feature }}</li>
                        </ul>
                    <button @click="openModal(plan.name)">activate</button>
                    </div>
                </div>

            </div>-->
            <div v-for="plan in plans" :key="plan.name" class="col-12 col-md-3"> <!-- Adjust the column width for mobile -->
                <div class="swap_tab cus_card p-0 pt-2 pricing-column">
                    <h2>{{ plan.name }}</h2>
                    <p class="price">{{ plan.min_price }} - {{ plan.max_price }}</p>
                    <h3>{{ plan.price }} Per month</h3>
                    <ul class="features">
                        <li v-for="feature in plan.features" :key="feature">{{ feature }}</li>
                    </ul>
                    <button @click="openModal(plan.name)">activate</button>
                </div>
            </div>

            <div class="col-md-12 col-xl-3">

                <!-- SWAPING AND STAKING TABS BOX -->

                <StakingComponent :user_wallet="user_wallet" :usdt_price="AllData?.prices?.AFC"
                    :Dashboard="Dashboard" />
            </div>
        </div>

        <!-- ==================== ===================== -->
        <!-- Modal -->
    <div class="modal" v-if="showModal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <span class="close" @click="closeModal">&times;</span>
                <h2>Activate a {{ selectedPlan }}</h2>
                <form @submit.prevent="submitForm">
                <div class="modal-body">
                <!-- Add your form fields here -->
                    <div class="form-group">
                        <label>Enter amount between {{ planRange }}</label>
                        <br />
                        <input type="number" class="form-control" name="invest" id="invest" required/>
                        <br />
                        <input type="hidden" id="userId" v-model="userID" />

                        <button class="close-button" @click="closeModal">Close</button>
                        <button type="submit">Confirm Activation</button>
                    </div>
                </div>
                </form>
            </div>
      </div>
    </div>




    </div>
</template>

<script>
import ApiClass from '../../api/api';
import StakingComponent from '../../components/UserPanel/StakingComponent.vue';


export default {
    name: 'Packages',
    name: 'modal',
    components: {
        StakingComponent
    },
    data() {
        return {
            plans: [
                    { name: 'Starter', min_price: '$100', max_price: '$500', price: '5%', price2: '10%', features: ['Up To 12 months', '10% Next 12 months'] },
                    { name: 'Trader', min_price: '$600', max_price: '$1000', price: '6%', price2: '12%', features: ['Up To 12 months', '12% Next 12 months'] },
                    { name: 'Premium', min_price: '$1100', max_price: '$$$', price: '7%', price2: '14%', features: ['Up To 12 months', '14% Next 12 months'] }
            ],
            showModal: false,
            selectedPlan: '',
            planRange: '',
            planMin: '',
            planMax: '',
            inputAmount: '',
            investAmount: '',
            userID: '',

            user_wallet: [],
            User: {},
            AllData: [],

            defaultWallet: [
                {
                    image: "/images/currency/usd.png",
                    currency: "USDT",
                    balance: 0
                },

            ],

        }
    },
    mounted() {
        this.Dashboard()
    },
    methods: {

        subscribe(planName) {
            // Add your subscribe logic here
            const inputAmount = document.getElementById("invest");
            this.investAmount = 100; // inputAmount.value;
            if($this.planMin){

            }
            alert(`Subscribed toeeew ${this.investAmount} ${planName} plan!`);
        },
        openModal(planName) {
            this.showModal = true;
            this.selectedPlan = planName;
            if(planName == "Starter"){
                this.planRange = "$100 - $500";
                this.planMin   = 100;
                this.planMax   = 500;
            }
            if(planName == "Trader"){
                this.planRange = "$600 - $1000";
                this.planMin   = 600;
                this.planMax   = 1000;
            }
            if(planName == "Premium"){
                this.planRange = "$1100 - $$$";
                this.planMin   = 1100;
                this.planMax   = 10000000;
            }
            },
            closeModal() {
            this.showModal = false;
            },
            async submitForm() {
            // Add your form submission logic here
            const inputAmount = document.getElementById("invest");
            this.investAmount = inputAmount.value;
            if(this.investAmount == ''){
                alert(`Error: Invalid Amount entered`);
                return false;
            }
            if(this.investAmount < this.planMin){
                alert(`Error: The minimum Investment for ${this.selectedPlan} plan is $ ${this.planMin}`);
                return false;
            }
            if(this.investAmount > this.planMax){
                alert(`Error: The maximun Investment for ${this.selectedPlan} plan is $ ${this.planMax}`);
                return false;
            }

            let form_data = {
                    amount: this.investAmount,
            };

            let resp = await ApiClass.postRequest("stake/plan", true, form_data);
                // console.log(resp);

                if (resp?.data) {

                    if (resp.data.status_code == "0") {
                        // return Error Message Here
                        this.failed(resp.data.message);
                        alert(`Something went wrong: ${resp.data.message} `);
                        return;
                    }
                    if (resp.data.status_code == "1") {
                        //this.resetForm();
                        //this.success(resp.data.message);
                        //this.callback();

                        alert(`Subscription confirmed for $ ${this.investAmount} on ${this.selectedPlan} plan!`);
                        this.closeModal();
                        location.reload();
                    }
                } else {

                    alert(`Something went wrong!`);
                    location.reload();

                }

        },
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

    }
}
</script>

<style scoped>

  .modal {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    min-width: 300px;
    background-color: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
    z-index: 1;
  }

  .modal-content {
    background-color: #fefefe;
    padding: 20px;
    border-radius: 8px;
    position: relative;
    /* width: 60%; */
    max-width: 400px;
  }

  .modal-content label {
    margin-bottom: 15px;
  }

  .close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
  }

  .close-button {
      background-color: #f44336;
      color: white;
      margin-right: 10px;
    }

    .close-button:hover {
      background-color: #d32f2f;
    }

  .pricing-table {
    display: flex;
    justify-content: space-around;
  }

  .pricing-column {
    text-align: center;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin: 10px 0;
  }

  .price {
    font-size: 24px;
    font-weight: bold;
    margin: 10px 0;
  }

  .features {
    list-style: none;
    padding: 0;
  }

  .features li {
    margin: 5px 0;
  }

  button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
</style>

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


