<?php

namespace App\Http\Controllers\API\App\Account;

use App\Exceptions\API\App\Accounts\Characters\CharacterDoNotBelongsToActualAccountException;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\App\Accounts\Characters\CharacterResource as AccountCharactersCharacterResource;
use App\Models\Account;
use App\Models\Account\Character as AccountCharacter;
use App\Models\Account\CharacterList as AccountCharacterList;
use Illuminate\Http\Request;

class CharactersListController extends Controller
{

    public function add(Request $request)
    {
        // Agregar a la lista
        $model = AccountCharacter::find($request->id);
        if($model->account_id != $request->actualAccount->id) throw new CharacterDoNotBelongsToActualAccountException();
        
        if(AccountCharacterList::whereAccountId($request->actualAccount->id)->whereAccountCharacterId($request->id)->count() > 0) return false;

        $position = AccountCharacterList::whereAccountId($request->actualAccount->id)->count();

        $account_character_list = new AccountCharacterList();
        $account_character_list->account_id = $request->actualAccount->id;
        $account_character_list->account_character_id = $model->id;
        $account_character_list->order = $position;
        $account_character_list->save();

        self::reorderAccountCharacterList($request->actualAccount);

        $model = AccountCharacter::find($request->id);
        $model = CharactersController::loadAccountCharacterData($model);

        return new AccountCharactersCharacterResource($model);
    }

    public function remove(Request $request)
    {
        // Remode de la lista 
        $model = AccountCharacter::find($request->id);
        if($model->account_id != $request->actualAccount->id) throw new CharacterDoNotBelongsToActualAccountException();

        $account_character_list = AccountCharacterList::whereAccountId($request->actualAccount->id)->whereAccountCharacterId($request->id)->first();
        $account_character_list->delete();

        self::reorderAccountCharacterList($request->actualAccount);

        $model = AccountCharacter::find($request->id);
        $model = CharactersController::loadAccountCharacterData($model);

        return new AccountCharactersCharacterResource($model);
    }

    private static function reorderAccountCharacterList(Account $account)
    {
        $account_character_list = $account->accountCharacterList;
        $position = 0;
        foreach($account_character_list as $account_character_list_item){
            $account_character_list_item->order = $position;
            $account_character_list_item->save();
            $position++;
        }
    }
}
