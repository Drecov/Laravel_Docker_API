<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountService;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\AccountEventRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function getAllAccounts() 
    {
        $accounts = $this->accountService->getAllAccounts();
        return AccountResource::collection($accounts);
    }

    public function getBalance(Request $request)
    {
        $accountId = $request->query('account_id');
        if(!$accountId) {
            return response()->json([
                'message' => 'Parâmetro "account_id" é obrigatório.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $balance = $this->accountService->getBalance($accountId);
        if(! $balance) {
            return response(0, Response::HTTP_NOT_FOUND);
        }

        return response($balance, Response::HTTP_OK);
    }

    public function processEvent (AccountEventRequest $request)
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

    private function processDeposit(AccountEventRequest $request)
    {
        $destination = $request->input('destination');
        $amount = $request->input('amount');

        if(!($destination) || !($amount)) {
            return response()->json([
                'message'=> 'Dados de destino e valor são obrigatórios.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        $ret = $this->accountService->deposit($destination, $amount);
        return response(
            $ret['payload'], 
            $ret['status']=='success' ? 
                Response::HTTP_CREATED : 
                Response::HTTP_NOT_FOUND
        );
    }

    private function processWithdraw(AccountEventRequest $request)
    {
        $origin = $request->input('origin');
        $amount = $request->input('amount');

        if(is_null($origin) || is_null($amount)) {
            return response()->json([
                'message'=> 'Dados de origem e valor são obrigatórios.',
                'status' => Response::HTTP_BAD_REQUEST
            ], Response::HTTP_BAD_REQUEST);
        }

        $ret = $this->accountService->withdraw($origin, $amount);
        return response(
            $ret['payload'], 
            $ret['status']=='success' ? 
                Response::HTTP_CREATED : 
                Response::HTTP_NOT_FOUND
        );
    }

    private function processTransfer(AccountEventRequest $request)
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

        $ret = $this->accountService->transfer($destination, $origin, $amount);
        return response(
            $ret['payload'], 
            $ret['status']=='success' ? 
                Response::HTTP_CREATED : 
                Response::HTTP_NOT_FOUND
        );
    }

    public function resetAccount(Request $request) {
        $this->accountService->resetAccounts();
        return response('OK', Response::HTTP_OK);
    }
}
