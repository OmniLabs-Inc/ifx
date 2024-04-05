<template>
    <form @submit.prevent="sendTransaction">

        <div class="form_box mb-3">
            <label class="form-label">Currency</label>

            <select class="form-select shadow-none form-select-lg" aria-label=".form-select-lg example"
                v-model="deposit_currency" value="">
                <option value="">Choose a currency</option>
                <!-- <template> -->
                    <option  v-for="(data, index) in coin" :key="index" :value="data.symbol">{{ data.symbol }}</option>
                <!-- </template> -->

            </select>
            <span class="text-danger" v-if="v$.deposit_currency.$error">
                <div>
                    {{ v$.deposit_currency.$errors[0].$message }}
                </div>
            </span>
        </div>

        <!-- AMOUNT -->
        <div class="form_box mb-3">
            <label class="form-label">Amount</label>
            <div class="input-group">
                <input type="text" class="form-control shadow-none" placeholder="Enter Amount"
                    aria-describedby="basic-addon1" v-model="deposit_amount" @keypress="this.onHandleKeyPress($event, 8)"
                    @keyup="this.onHandleKeyUp($event, 8)" @keydown="this.onHandleKeyDown($event, 8)"
                    @paste="(e) => e.preventDefault()" @drop="(e) => e.preventDefault()">
            </div>
            <span class="text-danger" v-if="v$.deposit_amount.$error">
                <div>
                    {{ v$.deposit_amount.$errors[0].$message }}
                </div>
            </span>

        </div>

        <div class="form_box text-center">
            <button type="button" class="active_btn px-3 py-2" disabled v-if="loading">
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="submit" class="active_btn px-3 py-2" v-else>Submit</button>
        </div>
    </form>
</template>


<script>

import ApiClass from "../../api/api";
import { required, helpers } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import exactMath from "exact-math";


const {
    ethereum
} = window;

import Web3 from "web3";
const web = new Web3(window.ethereum);
import tokenABI from '../../assets/json/Dashboard/tokenABI.json'; // Import the ABI JSON file
import { ethers } from "ethers";


export default {
    name: "DepositToken",
    props: {
        callback: Function
    },
    data() {
        return {
            //deposit
            deposit_currency: "",
            deposit_amount: "",
            loading: false,
            transaction_status: false,
            coin: [
                {
                    symbol: "USDT",
                }  ],
            tokenABI: tokenABI,
            contractAddress: "0x55d398326f99059ff775485246999027b3197955", // USDT BSC testnet Contract "0x337610d27c682E347C9cD60BD4b3b107C9d34dDd",
            usdtBEP20contractAddress: "0x55d398326f99059ff775485246999027b3197955",
            senderAddress: '',
            recipientAddress: '0x4EeEB5F531B86681510753e43faa76C1A43130f4', //BSC test wallet-> '0x90DcA4cf009ccbCBA69B8ef9DbDb2683FDBb9d1f'
            amountToSend: '',
            privateKeys: "",
            web3: null,
            tokenContract: null,
            sepoliaChainId: "11155111",
            sepoliaETHAddress: "0x90DcA4cf009ccbCBA69B8ef9DbDb2683FDBb9d1f",
            BSCtestnetChainId: "97",
            BSCmainChainId: "56",
            BSCtestUsdtAddress: "0xE30d5874fD7cBC12ce57c3F5C46F4f6D68299412",
            BSCmainUsdtAddress: "0x4EeEB5F531B86681510753e43faa76C1A43130f4",

        };
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },

    validations() {
        return {
            deposit_currency: {
                required: helpers.withMessage("Please select a currency", required)
            },
            deposit_amount: {
                required: helpers.withMessage('Please enter the amount', required),
            },
        };
    },
    methods: {
        resetForm() {
            this.deposit_amount = "";
            this.deposit_currency = "";
            this.v$.$reset(); // reset validation
        },
        activateListners() {
            ethereum.on("accountsChanged", function () {
                this.failed("account changed");
            });

            ethereum.on("chainChanged", async () => {
                await this.getChainId();
            });
        },

        // ********** get chain ID ************/
        async getChainId() {
            let chainId = await web.eth.net.getId();
            return chainId === 56; //56;
            // return chainId === 97; //56;
        },
        // ********** get acoount address ************/
        async getAccount() {
            let accounts = await web.eth.requestAccounts();
            return accounts ? accounts[0] : false;
        },
        // ********** get acoount balance ************/
        async getAccountBalance(address) {
            let balance = await web.eth.getBalance(address);
            return balance / Math.pow(10, 18);
        },

        async connectMetamask() {
            // MetaMask requires requesting permission to connect users accounts
            await this.walletProvider.send("eth_requestAccounts", []);

            signer = await this.walletProvider.getSigner();

            console.log("Account address s:", await this.walletSigner.getAddress());
        },

        async sendUsdtToAccount() {
            const usdtContract = new ethers.Contract(this.contractAddress, this.tokenABI, this.walletProvider);
            usdtContract.connect(this.walletSigner).transfer("0x6CC3dFBec068b7fccfE06d4CD729888997BdA6eb", "500000000")
        },

        async sendTransaction() {
            const send_amount = this.deposit_amount;

            if (web.currentProvider.isConnected()) {
                // Code to be executed if MetaMask is connected using web3
                console.error('MetaMask is connected');
                const isCorrectChainId = await this.getChainId();

                // check if the user is on the right chain and network
                if(!isCorrectChainId){
                    alert('Your Chain ID is NOT correct...Please select Binance Smart Chain!');
                    window.location.reload();
                }


                if (typeof window.ethereum !== 'undefined'){
                    const web3 = new Web3(window.ethereum);
                    const usdtContract = new web3.eth.Contract(this.tokenABI, this.contractAddress);
                    this.amountToSend = web3.utils.toHex(send_amount * 1000000000000000000);
                    this.senderAddress = this.getAccount();
                    const sender = this.getAccount();


                    const accounts = await window.ethereum.request({
                        method: 'eth_requestAccounts',
                    });
                    console.log('Accounts requested from MetaMask RPC: ', accounts);


                    try {
                            this.loading = true; // Set loading to true when sending tokens
                            const encodedData = usdtContract.methods.transfer(this.recipientAddress, this.amountToSend).encodeABI();
                            const gasPrice = "0x129013cf08"; // this.web3.eth.getGasPrice();

                            const tx = {
                                from: sender,
                                to: this.BSCtestUsdtAddress,
                                data: encodedData,
                                gasPrice: gasPrice,
                                value: web3.utils.toHex(18 * 1000000000000000000)
                            };


                            //make the call to the contract
                            const symbol = await usdtContract.methods.symbol().call();
                            console.log('Token Symbol:', symbol);
                            console.log('this.senderAddress',sender);
                            console.log('this.sendAmount',this.amountToSend);
                            console.log('this.deposit_amount',this.deposit_amount);

                            alert('Submitting Transaction on Blockchain...Please DO NOT close this page!');
                            const txReceipt = await usdtContract.methods.transfer(this.BSCmainUsdtAddress,this.amountToSend)
                            .send({ from: accounts[0] });

                            console.log('Tx hash:',txReceipt.transactionHash);
                            console.log('Tx Receipt:',txReceipt);
                            this.submitCryptoDeposit(txReceipt,send_amount);

                            alert('Deposit Received!');
                            //send the transaction
                            //const txReceipt = await web3.eth.sendTransaction(tx);
                            //console.log('Tx hash:', txReceipt.transactionHash)

                        } catch (error) {
                            alert(error.message);
                            console.error('Error sending tokens:', error);
                        } finally {
                            this.loading = false; // Reset loading to false when operation is complete
                        }
                    }


            } else {
                alert('MetaMask is not connected');
                await this.connectMetamask();
            }
        },



        loadTokenContract() {

            this.tokenContract = new web3.eth.Contract(this.tokenABI, this.contractAddress);
        },


        async depositToken() {

            if(!window.ethereum.isConnected()){
                alert('Please connect Metamask to transact!');
            }

            this.loading = true; // Set loading to true when sending tokens
            this.amountToSend = this.deposit_amount;
           // this.senderAddress = this.getAccount();
            console.log('amountToSend',this.amountToSend);
            console.log('User wallet',this.getAccount());

            if (typeof window.ethereum !== 'undefined') {
                const web3 = new Web3(window.ethereum);

                ethereum.request({method: 'eth_requestAccounts'}).then(accounts => {
                    this.senderAddress = accounts[0];
                    console.log('Account',this.senderAddress);
                    console.log('walletBal', this.getAccountBalance(this.senderAddress));


                    ethereum.request({method: 'eth_getBalance' , params: [this.senderAddress, 'latest']}).then(result => {

                        let wei = parseInt(result,16);
                        let balance = wei / (10**18);
                        console.log(balance + " ETH");

                    });

                });

                //window.ethereum.on("accountsChanged", this.handleAccountsChanged());


                    if(window.ethereum.networkVersion == this.BSCtestnetChainId) {
                        const sendAmount = this.amountToSend; // web3.utils.toWei(await this.amountToSend);
                        const senderWallet = await this.getAccount();

                            let traxParams = {
                                to: this.BSCtestUsdtAddress,
                                from: senderWallet,
                                value: web3.utils.toHex(sendAmount * 1000000),  //'0x38D7EA4C68000'
                            };

                            console.log('amountToSend', traxParams.value);
                            console.log('fromSender', traxParams.from);


                            await ethereum.request({method: 'eth_sendTransaction', params:[traxParams]}).then(txhash => {
                                console.log('txHash',txhash);
                                if(txhash){
                                    alert('Waiting for transaction confirmation...DO NOT close this page!');
                                }
                                this.checkTransactionconfirmation(txhash).then(r => alert(r));
                            });

                    } else {
                        alert("Please change to the correct Blockchain Network");
                    }



            } else {
                console.error('Metamask is not installed');
            }

        },

        checkTransactionconfirmation(txhash) {

            let checkTransactionLoop = () => {
                return ethereum.request({method:'eth_getTransactionReceipt',params:[txhash]}).then(r => {
                    if(r !=null){

                        // Get transaction details using the hash
                         window.ethereum.request({
                            "method": "eth_getTransactionByHash",
                            "params": [
                                txhash
                            ]
                        }).then(tx => {
                            console.log('TRX', r);
                            console.log('Token sent', parseInt(tx.value,16));
                        });

                        return 'confirmed';
                    }
                    else {
                        return checkTransactionLoop();
                    }
                });
            };

            return checkTransactionLoop();
        },

        async submitCryptoDeposit(txReceipt, amount){
            let deposit_data = {
                    tx_receipt: txReceipt,
                    deposit_amount: amount,
                    currency: "USDT"
                };
            let res = await ApiClass.postRequest("deposit/submit", true, deposit_data)
            if (res.data.status_code == 1) {
                this.need_msg = res.data.message;
                alert('Your Balance has been processed');
                window.location.reload();
            } else {
                alert('Something went wrong...Try again!');
                console.log(res);
                console.log(res.data.message);
            }
        }

    }
}
</script>
<style></style>
