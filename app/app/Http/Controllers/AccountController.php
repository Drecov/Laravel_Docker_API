<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function getAllAccounts() 
    {
        return AccountResource::collection(Account::all());
    }

    public function getBalance(Request $request)
    {
        $accountId = $request->query('account_id');
        if(!$accountId) {
            return response()->json([
                'message' => 'Parâmetro "account_id" é obrigatório.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $account = Account::where('account_id', $accountId)->first();
        if(! $account) {
            return response(0, Response::HTTP_NOT_FOUND);
        }

        return response($account->balance, Response::HTTP_OK);
    }

    public function processEvent (Request $request)
    {
        $type = $request->input('type');

        if (is_null($type)) {
            return response()->json([
                'message'=> 'O Tipo de evento é obrigatório.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        switch ($type) {
            case 'deposit':
                return $this->processDeposit($request);

            case 'withdraw':
                return $this->processWithdraw($request);

            case 'transfer':
                return $this->processTransfer($request);

            default:
                return response()->json([
                    'message'=> 'Tipo de evento \"' . $type . '\" não encontrado.',
                    'status' => Response::HTTP_BAD_REQUEST
                ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function processDeposit(Request $request)
    {
        $destination = $request->input('destination');
        $amount = $request->input('amount');

        if(is_null($destination) || is_null($amount)) {
            return response()->json([
                'message'=> 'Dados de destino e valor são obrigatórios.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function processWithdraw(Request $request)
    {
        $origin = $request->input('origin');
        $amount = $request->input('amount');

        if(is_null($origin) || is_null($amount)) {
            return response()->json([
                'message'=> 'Dados de origem e valor são obrigatórios.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function processTransfer(Request $request)
    {
        $destination = $request->input('destination');
        $origin = $request->input('origin');
        $amount = $request->input('amount');

        if(is_null($destination) || is_null($origin) || is_null($amount)) {
            return response()->json([
                'message'=> 'Dados de destino, origem e valor são obrigatórios.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function resetAccount(Request $request) {
        Account::query()->delete();
        return response('OK', Response::HTTP_OK);
    }
}
