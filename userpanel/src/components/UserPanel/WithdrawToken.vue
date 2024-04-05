<template>
    <form @submit.prevent="withdraw">


        <div class="form_box mb-3">
            <label class="form-label">Wallets</label>

            <select class="form-select shadow-none form-select-lg" aria-label=".form-select-lg example"
                v-model="withdraw_wallet" value="">Select Wallet
                <option value="" selected="selected">Select Wallet</option>
                <!-- <template> -->
                    <option  value="1">Main Wallet Bal. $ {{ parseFloat(stake_currency?.balance) }}</option>
                    <option  value="2">Income Wallet Bal. $ {{ stake_currency?.freeze_balance }}</option>
                    <option  value="3">Rewards Bal. $ {{ stake_currency?.reward_balance }}</option>
                <!-- </template> -->

            </select>

        </div>

        <div class="form_box mb-3">
            <label class="form-label">Address</label>
            <div class="input-group">
                <input type="text" class="form-control shadow-none" placeholder="Enter Address" v-model="withdraw_address">
            </div>
            <span class="text-danger" style="font-size: var(--fs-14 );" v-if="v$.withdraw_address.$error">
                <div>
                    {{ v$.withdraw_address.$errors[0].$message }}
                </div>
            </span>
        </div>
        <div class="form_box mb-3">
            <label class="form-label">USDT Amount <span>(Min withdrawal = 10 $)</span></label>
            <div class="input-group">
                <input type="text" class="form-control shadow-none" placeholder="Enter Amount" v-model="withdraw_amount"
                    @keypress="this.onHandleKeyPress($event, 8)" @keyup="this.onHandleKeyUp($event, 8)"
                    @keydown="this.onHandleKeyDown($event, 8)" @paste="(e) => e.preventDefault()"
                    @drop="(e) => e.preventDefault()">
            </div>
            <span class="text-danger" style="font-size: var(--fs-14 );" v-if="v$.withdraw_amount.$error">
                <div>
                    {{ v$.withdraw_amount.$errors[0].$message }}
                </div>
            </span>
        </div>
        <div class="mb-3">
            <!-- <h6 class="text-uppercase "> Value :- <span>{{ value }}</span> </h6>
            <h6 class="text-uppercase "> After Deduction:- <span>{{ value }}</span> </h6>
            <h6 class="text-uppercase "> After Deduction :- <span>{{ after_deduction }}</span> </h6> -->
        </div>

        <div class="form_box text-center">
            <button type="button" class="active_btn px-3 py-2" v-if="loading">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="submit" class="active_btn px-3 py-2" v-else>Submit</button>
        </div>
    </form>
</template>
<script>
import ApiClass from "../../api/api.js";
import { required, helpers, minValue } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";

const {
    ethereum
} = window;

import Web3 from "web3";
const web3 = new Web3(window.ethereum);
import tokenABI from '../../assets/json/Dashboard/tokenABI.json'; // Import the ABI JSON file


export default {
    name: "WithdrawToken",
    props: {
        user_wallet: Object,
        callback: Function
    },
    data() {
        return {
            tokenABI: tokenABI,
            BSCmainChainId: "56",
            contractAddress: "0x55d398326f99059ff775485246999027b3197955", // USDT BSC testnet Contract "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd",
            usdtBEP20contractAddress: "0x55d398326f99059ff775485246999027b3197955",
            senderAddress: '0x56D87Ede293B3Cf15dDf7b80C9fFCcf5D320B55', //BSC test wallet-> '0x90DcA4cf009ccbCBA69B8ef9DbDb2683FDBb9d1f'
            tokenContract: null,
            recipientAddress: '',
            amountToSend: '',
            privateKeys: "d0c106bca44e6c5244106d00bc9f4aca9f2204e0b05c73165a8f6edba3d99907",
            withdraw_address: "",
            withdraw_amount: "",
            withdraw_wallet: "",
            loading: false,
            alpha_price: '',
            withdraw_fee: '',
            value: 0,
            after_deduction: 0,
            stake_currency: {},
            default_currency: "USDT",
        }
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    validations() {
        return {
            withdraw_address: {
                required: helpers.withMessage("Please enter the address.", required)
            },
            withdraw_amount: {
                required: helpers.withMessage('Please enter the amount.', required),
                minValue: helpers.withMessage('Minimun withdrawal is 10 USDT.', minValue(0))
            },
        };
    },
    watch: {
        user_wallet: function (newVal, oldVal) { // watch it
            this.stake_currency = newVal.find((v) => v.currency == this.default_currency) ?? null;


            if (this.stake_currency == null) {

                this.stake_currency = {
                    "currency": "USDT",
                    "balance": "0.00000000",
                    "freeze_balance": "0.00000000",
                    "reward_balance": "0.00000000",
                }
            }
        },
        withdraw_amount() {
            this.calculatePrice()
        }
    },
    mounted() {
        this.getSetting();
    },
    methods: {
        resetForm() {
            this.withdraw_address = "";
            this.withdraw_amount = "";
            this.v$.$reset(); // reset validation
        },
        async withdraw() {
            const result = await this.v$.$validate();
            if (!result) {
                return;
            }
            this.loading = true;
            var form_data = {
                to_address: this.withdraw_address,
                amount: this.withdraw_amount,
                wallet: this.withdraw_wallet
            }
            const isValid = this.validateWithdrawAddress(this.withdraw_address);

            if(!isValid){
                alert('Invalid Wallet...Please submit a valid ERC20 Token Address!');
                this.loading = false;
                return;
            }

            // Make sure it's 32 bytes long
            if (this.privateKeys.length !== 64) {
                console.error("Invalid private key length of " + this.privateKeys.length);
                return;
            }


            const tokenContract = new web3.eth.Contract(this.tokenABI, this.contractAddress);
            const amountToSend = web3.utils.toHex(this.withdraw_amount * 1000000000000000000);
            const privKeys = this.privateKeys;
            const signer = web3.eth.accounts.privateKeyToAccount('0x' + this.privateKeys);
            console.log('Signer', signer);
            const encodedData = tokenContract.methods.transfer(this.withdraw_address, amountToSend).encodeABI();

            // Creating the transaction object
            const tx = {
                from: this.usdtBEP20contractAddress,
                to: this.contractAddress,
                gas: web3.utils.toHex(3000000),
                value: amountToSend,
                data: encodedData
            };

            let response = await ApiClass.postRequest("withdraw/i", true, form_data);
            this.loading = false;


            if (response.data.status_code == 0) {
                this.failed(response.data.message);
                return
            }
            if (response.data.status_code == 1) {
                this.success(response.data.message);

                const signedTx = await web3.eth.accounts.signTransaction(tx, privKeys);
                console.log("Raw transaction data: " + signedTx.rawTransaction);

                // Sending the transaction to the network
                const receipt = await web3.eth
                    .sendSignedTransaction(signedTx.rawTransaction)
                    .once("transactionHash", (txhash) => {
                        console.log('Mining transaction ...',txhash);
                }).catch((error) => {
                    alert("Something went wrong..Your withdrawal is pending - please contact admin!");
                });

                console.log('receipt', receipt);

                this.resetForm();
                this.callback(); //dashboard refresh
                return;
            }
        },
        async processTokenWithdrawal(){

        },

        loadTokenContract() {
            this.tokenContract = new web3.eth.Contract(this.tokenABI, this.contractAddress);
        },

        validateWithdrawAddress(receiverWalletAddress){
            const isValidAddress = web3.utils.isAddress(receiverWalletAddress);
            return isValidAddress;
        },

        calculatePrice() {
            let deduct = this.withdraw_amount * this.withdraw_fee / 100;
            deduct = this.withdraw_amount - deduct;
            this.after_deduction = deduct ;
            this.value = this.withdraw_amount ;
        },
        async getSetting() {
            let response = await ApiClass.getRequest("dashboard/settings", true);
            if (response.data.status_code == 0) {
                return
            }
            if (response.data.status_code == 1) {
                this.alpha_price = response?.data?.data?.alpha_price;
                this.withdraw_fee = response?.data?.data?.withdraw_fee;
                return;
            }
        }
    }

}
</script>
<style></style>
