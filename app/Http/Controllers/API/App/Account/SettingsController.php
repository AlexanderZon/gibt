<?php

namespace App\Http\Controllers\API\App\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $accounts = Account::where('user_id','=',$user->id)->get();
        $accounts->load(['accountCharacters', 'accountWeapons']);

        return [
            'accounts' => $accounts,
            'user' => $user
        ];
    }

    public function storeAccounts(Request $request)
    {
        $account = new Account();
        $account->user_id = auth()->user()->id;
        $account->title = $request->title;
        $account->game_server = $request->game_server;
        $account->is_active = false;
        $account->save();

        return $account;
    }

    public function setActiveAccount(Request $request, $account_id)
    {
        $accounts = Account::where('user_id','=',auth()->user()->id)->get();
        foreach($accounts as $account){
            $account->is_active = false;
            $account->save();
        }

        $active_account = Account::find($account_id);
        $active_account->is_active = true;
        $active_account->save();

        return $this->index($request);
    }
}
