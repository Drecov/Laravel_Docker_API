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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Account::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        //
    }

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
            return response()->json(0, Response::HTTP_NOT_FOUND);
        }

        return response($account->balance, Response::HTTP_OK);
    }

    public function processEvent (Request $request)
    {
        $type = $request->input('type');
        switch ($type) {
            case 'deposit':
                return $this->processDeposit($request);

            case 'withdraw':
                return $this->processWithdraw($request);

            case 'transfer':
                return $this->processTransfer($request);

            default:
                return response()->json([
                    'message'=> 'Tipo de evento não encontrado.'
                ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function processDeposit(Request $request)
    {

    }

    private function processWithdraw(Request $request)
    {

    }

    private function processTransfer(Request $request)
    {

    }

    public function resetAccount(Request $request) {
        Account::query()->delete();
        return response('OK', Response::HTTP_OK);
    }
}
