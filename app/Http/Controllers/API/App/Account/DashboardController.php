<?php

namespace App\Http\Controllers\API\App\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\App\Accounts\Dashboard\CharAscensionMaterialResource;
use App\Http\Resources\API\App\Accounts\Dashboard\WeapAscensionMaterialResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private static $account_character_max_skill_level = 9;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $account_id = $request->actualAccount->id;
        $day = now()->dayOfWeek+1;

        $talent_books = self::getTalentBooks($account_id, $day);
        $talent_common_items = self::getTalentCommonItems($account_id);
        $boss_materials = self::getBossMaterials($account_id);
        $weap_primary_materials = self::getWeaponPrimaryMaterials($account_id, $day);
        $char_elemental_stones = self::getCharacterElementalStones($account_id);
        $char_jewels = self::getCharacterJewels($account_id);
        $char_local_materials = self::getCharacterLocalMaterials($account_id);
        $char_common_items = self::getCharacterCommonItems($account_id);
        $weap_secondary_materials = self::getWeaponSecondaryMaterials($account_id);
        $weap_common_items = self::getWeaponCommonItems($account_id);

        $day_farming = [];
        $server_timezone = 0;
        $server_reset_time = 4;
        switch($request->actualAccount->game_server){
            case 'NA':
                $server_timezone = -5;
                break;
            case 'EU':
                $server_timezone = 1;
                break;
            default:
                $server_timezone = 8;
                break;
        }
        for($i = -3; $i <= 3; $i++){
            $server_time = now()->addDays($i)->addHours($server_timezone);
            $reset_day = $server_time->subHours($server_reset_time)->dayOfWeek+1;
            $actual_day_farming = [
                'date' => $server_time,
                'reset_timestamp' => $server_time->addHours($server_reset_time),
                'talent_books' => self::getTalentBooks($account_id, $reset_day),
                'weap_primary_materials' => self::getWeaponPrimaryMaterials($account_id, $reset_day),
            ];
            $day_farming[$i] = $actual_day_farming;
        }

        return [
            'day_farming' => $day_farming,
            'talent_books' => $talent_books,
            'talent_common_items' => $talent_common_items,
            'boss_materials' => $boss_materials,
            'weap_primary_materials' => $weap_primary_materials,
            'char_elemental_stones' => $char_elemental_stones,
            'char_jewels' => $char_jewels,
            'char_local_materials' => $char_local_materials,
            'char_common_items' => $char_common_items,
            'weap_secondary_materials' => $weap_secondary_materials,
            'weap_common_items' => $weap_common_items,
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $date)
    {
        $account_id = $request->actualAccount->id;
        $day = (new Carbon($date))->dayOfWeek+1;
        
        $talent_books = self::getTalentBooks($account_id, $day);
        $weap_primary_materials = self::getWeaponPrimaryMaterials($account_id, $day);

        return [
            'talent_books' => $talent_books,
            'weap_primary_materials' => $weap_primary_materials,
        ];
    }

    private static function getTalentBooks($account_id, $day)
    {        
        $list = DB::select("SELECT b.id AS `character_id`, b.name AS `character_name`, d.url AS `character_icon`, c.id AS `ascension_material_id`, c.name AS `ascension_material_name`, c.icon AS `ascension_material_icon`, c.rarity AS `ascension_material_rarity`, SUM(a.quantity) AS `quantity`, j.`day` IS NOT NULL AS `can_farm_today` FROM 
        ((SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'basic' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.basic_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_book_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'elemental' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.elemental_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_book_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'burst' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.burst_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_book_item_id)) as a
        INNER JOIN characters AS b
            ON a.character_id = b.id AND b.deleted_at IS NULL 
        INNER JOIN ascension_materials AS c
            ON a.talent_book_item_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        LEFT JOIN ascension_material_farming_days AS j
            ON c.id = j.ascension_material_id AND j.`day` = ".$day." AND j.deleted_at IS NULL 
        GROUP BY b.id, c.id;");

        return CharAscensionMaterialResource::collection($list);
    }

    private function getTalentCommonItems($account_id)
    {
        $list = DB::select("SELECT b.id AS `character_id`, b.name AS `character_name`, d.url AS `character_icon`, c.id AS `ascension_material_id`, c.name AS `ascension_material_name`, c.icon AS `ascension_material_icon`, c.rarity AS `ascension_material_rarity`, SUM(a.quantity) AS `quantity` FROM 
            ((SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.char_common_item_id, SUM(e.char_common_item_quantity) AS `quantity`, 'basic' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.basic_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
                WHERE b.account_id = ".$account_id."
                GROUP BY b.character_id, e.char_common_item_id) UNION ALL 
            (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.char_common_item_id, SUM(e.char_common_item_quantity) AS `quantity`, 'elemental' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.elemental_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
                WHERE b.account_id = ".$account_id."
                GROUP BY b.character_id, e.char_common_item_id) UNION ALL 
            (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.char_common_item_id, SUM(e.char_common_item_quantity) AS `quantity`, 'burst' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.burst_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
                WHERE b.account_id = ".$account_id."
                GROUP BY b.character_id, e.char_common_item_id)) as a
            INNER JOIN characters AS b
                ON a.character_id = b.id AND b.deleted_at IS NULL 
            INNER JOIN ascension_materials AS c
                ON a.char_common_item_id = c.id AND c.deleted_at IS NULL 
            INNER JOIN character_images AS d
                ON b.id = d.character_id AND d.`type` = 'icon' AND d.deleted_at IS NULL 
            GROUP BY b.id, c.id;");
    
        return CharAscensionMaterialResource::collection($list);
    }

    private static function getBossMaterials($account_id)
    {
        $list = DB::select("SELECT b.id AS `character_id`, b.name AS `character_name`, d.url AS `character_icon`, c.id AS `ascension_material_id`, c.name AS `ascension_material_name`, c.icon AS `ascension_material_icon`, c.rarity AS `ascension_material_rarity`, SUM(a.quantity) AS `quantity` FROM 
        ((SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'basic' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.basic_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_boss_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'elemental' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.elemental_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_boss_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'burst' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.burst_talent_level AND e.`level` <= ".self::$account_character_max_skill_level."
            WHERE b.account_id = ".$account_id."
            GROUP BY b.character_id, e.talent_boss_item_id)) as a
        INNER JOIN characters AS b
            ON a.character_id = b.id AND b.deleted_at IS NULL 
        INNER JOIN ascension_materials AS c
            ON a.talent_boss_item_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        GROUP BY b.id, c.id;");

        return CharAscensionMaterialResource::collection($list);
    }

    private static function getWeaponPrimaryMaterials($account_id, $day)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url as `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_primary_material_id AS `ascension_material_id`, g.name AS `ascension_material_name`, g.icon AS `ascension_material_icon`, g.rarity AS `ascension_material_rarity`, SUM(f.weap_primary_material_quantity) AS `quantity`, j.`day` IS NOT NULL AS `can_farm_today` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_images AS d
		    ON c.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        INNER JOIN account_weapons AS e
            ON b.account_weapon_id = e.id AND e.deleted_at IS NULL 
        INNER JOIN weapon_stats AS f
            ON e.weapon_id = f.weapon_id AND f.deleted_at IS NULL AND f.`level` > e.`level`
        INNER JOIN ascension_materials AS g
            ON f.weap_primary_material_id = g.id AND g.deleted_at IS NULL 
        INNER JOIN weapons AS h
            ON e.weapon_id = h.id AND h.deleted_at IS NULL 
        INNER JOIN weapon_images AS i
            ON h.id = i.weapon_id AND i.`type` = 'icon'  AND i.deleted_at IS NULL 
        LEFT JOIN ascension_material_farming_days AS j
            ON g.id = j.ascension_material_id AND j.`day` = ".$day." AND j.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY e.weapon_id, f.weap_primary_material_id;");
        
        return WeapAscensionMaterialResource::collection($list);
    }

    private static function getCharacterElementalStones($account_id)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `ascension_material_id`, e.name AS `ascension_material_name`, e.icon AS `ascension_material_icon`, e.rarity AS `ascension_material_rarity`, SUM(d.char_elemental_stone_quantity) AS `quantity` FROM account_characters_list AS a 
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_stats AS d
            ON c.id = d.character_id AND d.deleted_at IS NULL AND d.`level` > b.`level`
        INNER JOIN ascension_materials AS e
            ON d.char_elemental_stone_id = e.id AND e.deleted_at IS NULL 
        INNER JOIN character_images AS f
            ON c.id = f.character_id AND f.`type` = 'icon'  AND f.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY c.id, e.id;");
        
        return CharAscensionMaterialResource::collection($list);
    }

    private static function getCharacterJewels($account_id)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `ascension_material_id`, e.name AS `ascension_material_name`, e.icon AS `ascension_material_icon`, e.rarity AS `ascension_material_rarity`, SUM(d.char_jewel_quantity) AS `quantity` FROM account_characters_list AS a 
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_stats AS d
            ON c.id = d.character_id AND d.deleted_at IS NULL AND d.`level` > b.`level`
        INNER JOIN ascension_materials AS e
            ON d.char_jewel_id = e.id AND e.deleted_at IS NULL
        INNER JOIN character_images AS f
            ON c.id = f.character_id AND f.`type` = 'icon'  AND f.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY c.id, e.id;");
        
        return CharAscensionMaterialResource::collection($list);
    }

    private static function getCharacterLocalMaterials($account_id)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `ascension_material_id`, e.name AS `ascension_material_name`, e.icon AS `ascension_material_icon`, e.rarity AS `ascension_material_rarity`, SUM(d.char_local_material_quantity) AS `quantity` FROM account_characters_list AS a 
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_stats AS d
            ON c.id = d.character_id AND d.deleted_at IS NULL AND d.`level` > b.`level`
        INNER JOIN ascension_materials AS e
            ON d.char_local_material_id = e.id AND e.deleted_at IS NULL
        INNER JOIN character_images AS f
            ON c.id = f.character_id AND f.`type` = 'icon'  AND f.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY c.id, e.id;");
        
        return CharAscensionMaterialResource::collection($list);
    }

    private static function getCharacterCommonItems($account_id)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `ascension_material_id`, e.name AS `ascension_material_name`, e.icon AS `ascension_material_icon`, e.rarity AS `ascension_material_rarity`, SUM(d.char_common_item_quantity) AS `quantity` FROM account_characters_list AS a 
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_stats AS d
            ON c.id = d.character_id AND d.deleted_at IS NULL AND d.`level` > b.`level`
        INNER JOIN ascension_materials AS e
            ON d.char_common_item_id = e.id AND e.deleted_at IS NULL
        INNER JOIN character_images AS f
            ON c.id = f.character_id AND f.`type` = 'icon'  AND f.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY c.id, e.id;");
        
        return CharAscensionMaterialResource::collection($list);
    }

    private static function getWeaponSecondaryMaterials($account_id)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url AS `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_secondary_material_id AS `ascension_material_id`, g.name AS `ascension_material_name`, g.icon AS `ascension_material_icon`, g.rarity AS `ascension_material_rarity`, SUM(f.weap_secondary_material_quantity) AS `quantity` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id
        INNER JOIN character_images AS d
            ON c.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        INNER JOIN account_weapons AS e
            ON b.account_weapon_id = e.id AND e.deleted_at IS NULL 
        INNER JOIN weapon_stats AS f
            ON e.weapon_id = f.weapon_id AND f.deleted_at IS NULL AND f.`level` > e.`level`
        INNER JOIN ascension_materials AS g
            ON f.weap_secondary_material_id = g.id AND g.deleted_at IS NULL 
        INNER JOIN weapons AS h
            ON e.weapon_id = h.id
        INNER JOIN weapon_images AS i
            ON h.id = i.weapon_id AND i.`type` = 'icon'  AND i.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY e.weapon_id, f.weap_secondary_material_id;");
        
        return WeapAscensionMaterialResource::collection($list);
    }

    private static function getWeaponCommonItems($account_id)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url AS `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_common_item_id AS `ascension_material_id`, g.name AS `ascension_material_name`, g.icon AS `ascension_material_icon`, g.rarity AS `ascension_material_rarity`, SUM(f.weap_common_item_quantity) AS `quantity` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id
        INNER JOIN character_images AS d
            ON c.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        INNER JOIN account_weapons AS e
            ON b.account_weapon_id = e.id AND e.deleted_at IS NULL 
        INNER JOIN weapon_stats AS f
            ON e.weapon_id = f.weapon_id AND f.deleted_at IS NULL AND f.`level` > e.`level`
        INNER JOIN ascension_materials AS g
            ON f.weap_common_item_id = g.id AND g.deleted_at IS NULL 
        INNER JOIN weapons AS h
            ON e.weapon_id = h.id
        INNER JOIN weapon_images AS i
            ON h.id = i.weapon_id AND i.`type` = 'icon'  AND i.deleted_at IS NULL 
        WHERE b.account_id = ".$account_id."
        GROUP BY e.weapon_id, f.weap_common_item_id;");
        
        return WeapAscensionMaterialResource::collection($list);
    }
}
