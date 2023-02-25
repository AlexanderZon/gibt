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
        if($request->title != null){
            $account->title = $request->title;
        } else {
            $account->title = '(No named account)';
        }
        $account->game_server = $request->game_server;
        $account->is_active = false;
        $account->save();

        return $account;
    }

    public function updateAccounts(Request $request, $account_id)
    {
        $account = Account::where('user_id','=',auth()->user()->id)->find($account_id);
        if($account){
            if($request->title != null){
                $account->title = $request->title;
            } else {
                $account->title = '(No named account)';
            }
            $account->game_server = $request->game_server;
            $account->save();
    
            return $account;
        }
        abort(404);
    }

    public function deleteAccounts(Request $request, $account_id)
    {
        $account = Account::where('user_id','=',auth()->user()->id)->find($account_id);
        if($account){
            $account->delete();
    
            return $account;
        }
        abort(404);
    }

    public function setActiveAccount(Request $request, $account_id)
    {
        $accounts = Account::where('user_id','=',auth()->user()->id)->get();
        foreach($accounts as $account){
            $account->is_active = false;
            $account->save();
        }

        $active_account = Account::where('user_id','=',auth()->user()->id)->find($account_id);
        if($active_account){
            $active_account->is_active = true;
            $active_account->save();
        }

        return $this->index($request);
    }
}
