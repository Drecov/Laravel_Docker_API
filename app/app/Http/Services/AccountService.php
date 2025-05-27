<?php

namespace App\Http\Services;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Account;

class AccountService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllAccounts()
    {
        return Account::all();
    }

    public function getBalance(string $accountId): ?float
    {
        $account = Account::where('account_id', $accountId)->first();
        return $account ? $account->balance : null;
    }

    public function resetAccounts(): void
    {
        Account::query()->delete();
    }

    public function deposit($destination, $amount)
    {
        $account = Account::firstOrCreate(['account_id' => $destination]);
        $account->balance += $amount;
        $account->save();
        $payload = '{"destination": {"id":"'.$account->account_id.'", "balance":'.$account->balance.'}}';
        return [
            'account_id' => $account->account_id,
            'balance' => $account->balance,
            'payload'=> $payload,
            'status' => Response::HTTP_CREATED
        ];
    }

    public function withdraw($origin, $amount)
    {
        $account = Account::where('account_id', $origin)->first();
        if(!$account) {
            return [
                'account_id' => $origin,
                'balance' => 0,
                'payload' => 0,
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        $account->balance -= $amount;
        $account->save();
        $payload = '{"origin": {"id":"'.$account->account_id.'", "balance":'.$account->balance.'}}';
        return [
            'account_id' => $account->account_id,
            'balance' => $account->balance,
            'payload'=> $payload,
            'status' => Response::HTTP_CREATED
        ];
    }

    public function transfer($destination, $origin, $amount)
    {
        $originRet = $this->withdraw($origin, $amount);
        if($originRet['status'] != Response::HTTP_CREATED) {
            return [
                'payload' => 0,
                'status' => Response::HTTP_NOT_FOUND
            ];
        }

        $destinationRet = $this->deposit($destination, $amount);
        $payload = '{"origin": {"id":"'.$originRet['account_id'].'", "balance":'.$originRet['balance'].'}, "destination": {"id":"'.$destinationRet['account_id'].'", "balance":'.$destinationRet['balance'].'}}';
        return [
            'payload' => $payload,
            'status' => Response::HTTP_CREATED
        ];
    }
}
