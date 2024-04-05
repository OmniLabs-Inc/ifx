<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Traits\{Reply, Common, CurlRequest, Variables, Calculation, Security};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\{DepositCryptoLog};
use App\Custom\TronGrid;

class DepositController extends Controller
{

    use Reply, Variables, Common, CurlRequest, Calculation, Security;

    private $tron;
    private $deposit_wallet_address;

    const STATUS = [
        "pending" => 0,
        "rejected" => 1,
        "completed" => 2,
    ];

    const TOKEN = [
        "trx" => ["symbol" => "TRX", "decimal" => 6, "decimal_power" => 1000000],
        "cmax" => ["symbol" => "CMAX", "decimal" => 8, "decimal_power" => 100000000],
        "rmn" => ["symbol" => "RMN", "decimal" => 8, "decimal_power" => 100000000],
        "usdt" => ["symbol" => "USDT", "decimal" => 6, "decimal_power" => 1000000]
    ];

    const INVALID_MESSAGE = "Invalid Data!";

    public function __construct()
    {
        $this->tron = new TronGrid();
        $this->deposit_wallet_address = '0x4EeEB5F531B86681510753e43faa76C1A43130f4';
    }

    public function initiate(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'amount'   => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'currency' => 'required|in:USDT,TRX,CMAX,RMN'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $live_price = $this->live_price();

            $rr = [];

            $deposit_currency_price = $live_price[$request->currency];

            $usdt_amount = $this->lbm_mul($request->amount, $deposit_currency_price);


            $rr['user_id'] = Auth::id();
            $rr['deposit_currency'] = $request->currency;
            $rr['deposit_currency_price'] = $deposit_currency_price;
            $rr['deposit_currency_amount'] = $request->amount;
            $rr['usdt_amount'] = $usdt_amount;
            $rr['qbc_amount'] = $this->lbm_div($usdt_amount, $live_price['QBC']);
            $rr['qbc_price'] = $live_price['QBC'];
            $rr['token_address'] = ($request->currency != "TRX") ? $this->token_address[$request->currency] : null;
            $rr['token_type'] = ($request->currency != "TRX") ? "TRC20" : null;
            $rr['to_wallet_address'] = $this->deposit_wallet_address;
            $rr['transaction_id'] = $this->generateRandomString(50);

            // $rr;
            # Create deposit record
            $deposit = DepositCryptoLog::create($rr);

            if (!$deposit) {
                throw new Exception('Try it after some time!');
            }

            $response = [
                "currency" => $request->currency,
                "amount"   => $request->amount,
                "transaction_id" => $rr['transaction_id'],
                "to_wallet_address" => $rr['to_wallet_address'],
                "token_address"  => ($request->currency != "TRX") ? $rr['token_address'] : "SELF"
            ];

            return $this->success("Transaction Initialized!!", $response);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }


    public function submitCryptoDeposit(Request $request)
    {
        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'deposit_amount'   => 'required|regex:/^\d+(\.\d{1,8})?$/|gt:0',
                    'currency' => 'required|in:USDT,TRX,CMAX,RMN'
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $txReceipt = (object) $request->tx_receipt;

            $rr = [];

            $deposit_currency_price = $request->deposit_amount;
            $usdt_amount = $deposit_currency_price;


            $rr['user_id'] = Auth::id();
            $rr['deposit_currency'] = $request->currency;
            $rr['deposit_currency_price'] = 1.00000000;
            $rr['deposit_currency_amount'] = $deposit_currency_price;
            $rr['usdt_amount'] = $usdt_amount;
            $rr['qbc_amount'] = 1.00000000;
            $rr['qbc_price'] = 1.00000000;
            $rr['token_address'] = $txReceipt->from;
            $rr['token_type'] = "BEP20";
            $rr['from_wallet_address'] = $txReceipt->from;
            $rr['hash'] = $txReceipt->transactionHash;
            $rr['to_wallet_address'] = ($txReceipt->to) ? $txReceipt->to : $this->deposit_wallet_address;
            $rr['transaction_id'] = $this->generateRandomString(50);

            // $rr;
            # Create deposit record
            $deposit = DepositCryptoLog::create($rr);

            if (!$deposit) {
                throw new Exception('Try it after some time!');
            }

            $comment = "Crypto deposit of {$usdt_amount} from user via Dashboard Metamask";

            $this->addUserCrypto($rr['user_id'], $rr['usdt_amount'], self::TOKEN['usdt']['symbol'], $comment);

            $response = [
                "currency" => $request->currency,
                "amount"   => $request->deposit_amount,
                "transaction_id" => $rr['transaction_id'],
                "to_wallet_address" => $rr['to_wallet_address'],
                "token_address"  => ($request->currency != "TRX") ? $rr['token_address'] : "SELF"
            ];

            return $this->success("Transaction Initialized!!", $response);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function canceled(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'tx_id'  => 'required|exists:deposit_crypto_logs,transaction_id,status,0', // transaction should be pending
                    'reason' => 'required|max:255'
                ],
                [
                    'tx_id.required' => self::INVALID_MESSAGE,
                    'tx_id.exists' => self::INVALID_MESSAGE
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $updated = DepositCryptoLog::where(['transaction_id' => $request->tx_id, 'user_id' => Auth::id()])->update([
                'reason' =>  $request->reason,
                'status' => self::STATUS["rejected"]
            ]);

            return ($updated) ? $this->success("Transaction Cancelled!!") : $this->failed(self::INVALID_MESSAGE);
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function verification(Request $request)
    {

        try {
            $validator  = Validator::make(
                $request->all(),
                [
                    'txhash'   => 'required|unique:deposit_crypto_logs,hash',
                    'tx_id'  => 'required|exists:deposit_crypto_logs,transaction_id,status,0',
                    'from_address' => 'required'
                ],
                [
                    'txhash.required' => self::INVALID_MESSAGE,
                    'txhash.unique' => self::INVALID_MESSAGE,
                    'tx_id.required' => self::INVALID_MESSAGE,
                    'tx_id.exists' => self::INVALID_MESSAGE,
                    'from_address.required' => self::INVALID_MESSAGE
                ]
            );

            if ($validator->errors()->all()) {
                throw new Exception($validator->errors()->first());
            }

            $initiate = DepositCryptoLog::where("transaction_id", $request->tx_id)->first();

            if (!$initiate) {
                throw new Exception(self::INVALID_MESSAGE . 'txid not found');
            }

            // call api for verification transaction hash
            $transaction = $this->tron->validateTransaction($request->txhash);

            if ($transaction == 0) {
                # Transaction Rejected successfully
                $initiate->reason = self::INVALID_MESSAGE;
                $initiate->status = self::STATUS['rejected'];
                $initiate->save();
                throw new Exception(self::INVALID_MESSAGE . 'not started');
            }

            $result = $this->transactionVerify($transaction, $initiate, $request);

            if ($result["tx_status"] == 1) {

                # Transaction completed successfully
                $initiate->hash = $result["hash"];
                $initiate->from_wallet_address = $result["from_wallet_address"];
                $initiate->status = $result["status"];

                $initiate->save();

                # Add Crypto In User Wallet
                $comment = "User Id " . $initiate->user_id . " deposit " . $initiate->deposit_currency . " with qty " . $initiate->deposit_currency_amount . " is equal to " . $initiate->qbc_amount . " " . self::TOKEN['qbc']['symbol'];

                $this->addUserCrypto($initiate->user_id, $result['amount'], self::TOKEN['usdt']['symbol'], $comment);
            }

            if ($result["tx_status"] == 0) {
                # Transaction Rejected successfully
                $initiate->reason = $result["message"];
                $initiate->status = $result["status"];
                $initiate->save();

                return $this->success(self::INVALID_MESSAGE);
            }

            return $this->success("Transaction Completed!!");
        } catch (Exception $e) {
            return  $this->failed($e->getMessage());
        }
    }

    public function transactionVerify($transaction, $initiate, $request)
    {

        if ($transaction['contractRet'] != 'SUCCESS') {
            return [
                "tx_status" => 0,
                "status"    => self::STATUS['rejected'],
                "message" => self::INVALID_MESSAGE . 'not completed'
            ];
        }

        # TRX TRANSACTION
        if ($initiate->deposit_currency == self::TOKEN['trx']['symbol']) {

            # Deposit user Wallet Address Validate
            if ($transaction['contractData']['owner_address'] != $request->from_address) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'from address not match'
                ];
            }

            # Deposit Admin Wallet Address Validate
            if ($transaction['contractData']['to_address'] != $this->deposit_wallet_address) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'admin address not match'
                ];
            }

            # Deposit Amount Validate
            $balance = $this->lbm_div($transaction['contractData']['amount'], self::TOKEN['trx']['decimal_power']);

            if (ceil($balance) != ceil($initiate->deposit_currency_amount)) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'amount not match'
                ];
            }

            # timestamp check
            $initiate_timestamp = strtotime($initiate->created_at) . '000';

            if ($initiate_timestamp > $transaction['timestamp']) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'timestamp not match'
                ];
            }

            return [
                "tx_status" => 1,
                "amount" => $initiate->qbc_amount,
                "hash"   => $transaction['hash'],
                "from_wallet_address" => $transaction['contractData']['owner_address'],
                "status" => self::STATUS['completed'], // completed
            ];
        }

        $token_lowercase = strtolower($initiate->deposit_currency);

        # TOKEN TRANSACTION
        if ($initiate->deposit_currency == self::TOKEN[$token_lowercase]['symbol']) {

            # Deposit Symbol Validate
            if ($transaction['tokenTransferInfo']['symbol'] != $initiate->deposit_currency) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'symbol not match'
                ];
            }

            # Deposit user Wallet Address Validate
            if ($transaction['tokenTransferInfo']['from_address'] != $request->from_address) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'from address not match'
                ];
            }

            # Deposit Admin Wallet Address Validate
            if ($transaction['tokenTransferInfo']['to_address'] != $this->deposit_wallet_address) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'admin address not match'
                ];
            }

            # Deposit Contract Address Validate
            if ($transaction['tokenTransferInfo']['contract_address'] != $this->token_address[self::TOKEN[$token_lowercase]['symbol']]) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'contract address not match'
                ];
            }



            # Deposit Amount Validate
            $balance = $this->lbm_div($transaction['tokenTransferInfo']['amount_str'], self::TOKEN[$token_lowercase]['decimal_power']);

            if (ceil($balance) != ceil($initiate->deposit_currency_amount)) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'amount not match'
                ];
            }

            # timestamp check
            $initiate_timestamp = strtotime($initiate->created_at) . '000';

            if ($initiate_timestamp > $transaction['timestamp']) {
                return [
                    "tx_status" => 0,
                    "status"    => self::STATUS['rejected'],
                    "message" => self::INVALID_MESSAGE . 'timestamp not match'
                ];
            }

            return [
                "tx_status" => 1,
                "amount" => $initiate->qbc_amount,
                "hash"   => $transaction['hash'],
                "from_wallet_address" => $transaction['contractData']['owner_address'],
                "status" => self::STATUS['completed'] // completed
            ];
        }

        return [
            "tx_status" => 0,
            "status"    => self::STATUS['rejected'],
            "message" => self::INVALID_MESSAGE . 'not match any currency'
        ];

    }


    public function deposit_report(Request $request){
        $deposit = DepositCryptoLog::where(["user_id" => Auth::id(), "status" => "2"])->select(["transaction_id", "usdt_amount", "hash" ,"from_wallet_address" ,"created_at"])->paginate($request->per_page ?? 10);
        return $this->success("Deposit Reports Fetched Successfully.", $deposit);
    }
}












// user_id
// transaction_id
// deposit_currency
// deposit_currency_price
// deposit_currency_amount
// usdt_amount
// qbc_amount
// qbc_price
// hash
// chain_type
// token_address
// token_type
// reason
// from_wallet_address
// to_wallet_address
// status



// return $this->encrypt_decrypt('decrypt','ZTdhZ25DYjFMaFhYSUNPUlJyejdPeWMzQURCT1J0L1VrWTBCOHhxSTVPRHJvc2xsMHduc2ppNllrQzZEZGllWFRmQTREdTNSZ1M3KzFzd2ZaM3ZjZy9acU8vMkY5Y3hqK1dqTlk3MjlMZk0wQkd5Nm1zanppQjZHS1M5RGlJbjE=');
