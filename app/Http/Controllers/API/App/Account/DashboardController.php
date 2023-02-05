<?php

namespace App\Http\Controllers\API\App\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\App\Accounts\Dashboard\TalentBookResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    private static $account_character_max_level = 9;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $talent_books = self::getTalentBooks($request);
        $boss_materials = self::getBossMaterials($request);
        $weap_primary_materials = self::getWeaponPrimaryMaterials($request);
        $char_elemental_stones = self::getCharacterElementalStones($request);
        $char_jewels = self::getCharacterJewels($request);
        $char_local_materials = self::getCharacterLocalMaterials($request);
        $char_common_items = self::getCharacterCommonItems($request);
        $weap_secondary_materials = self::getWeaponSecondaryMaterials($request);
        $weap_common_items = self::getWeaponCommonItems($request);

        return [
            'talent_books' => $talent_books,
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private static function getTalentBooks(Request $request)
    {
        $list = DB::select("SELECT b.id AS `character_id`, b.name AS `character_name`, d.url AS `character_icon`, c.id AS `talent_book_id`, c.name AS `talent_book_name`, c.icon AS `talent_book_icon`, SUM(a.quantity) AS `quantity` FROM 
            ((SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'basic' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.basic_talent_level AND e.`level` <= ".self::$account_character_max_level."
                WHERE b.account_id = ".$request->actualAccount->id."
                GROUP BY b.character_id, e.talent_book_item_id) UNION ALL 
            (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'elemental' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.elemental_talent_level AND e.`level` <= ".self::$account_character_max_level."
                WHERE b.account_id = ".$request->actualAccount->id."
                GROUP BY b.character_id, e.talent_book_item_id) UNION ALL 
            (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_book_item_id, SUM(e.talent_book_item_quantity) AS `quantity`, 'burst' AS `talent_type` FROM account_characters_list AS a
                INNER JOIN account_characters AS b
                    ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
                INNER JOIN character_skill_ascensions AS e
                    ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.burst_talent_level AND e.`level` <= ".self::$account_character_max_level."
                WHERE b.account_id = ".$request->actualAccount->id."
                GROUP BY b.character_id, e.talent_book_item_id)) as a
            INNER JOIN characters AS b
                ON a.character_id = b.id AND b.deleted_at IS NULL 
            INNER JOIN ascension_materials AS c
                ON a.talent_book_item_id = c.id AND c.deleted_at IS NULL 
            INNER JOIN character_images AS d
                ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
            GROUP BY b.id, c.id;");

        return TalentBookResource::collection($list);
    }

    private static function getBossMaterials(Request $request)
    {
        $list = DB::select("SELECT b.id AS `character_id`, b.name AS `character_name`, d.url AS `character_icon`, c.id AS `talent_boss_id`, c.name AS `talent_boss_name`, c.icon AS `talent_boss_icon`, SUM(a.quantity) AS `quantity` FROM 
        ((SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'basic' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.basic_talent_level AND e.`level` <= ".self::$account_character_max_level."
            WHERE b.account_id = ".$request->actualAccount->id."
            GROUP BY b.character_id, e.talent_boss_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'elemental' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.elemental_talent_level AND e.`level` <= ".self::$account_character_max_level."
            WHERE b.account_id = ".$request->actualAccount->id."
            GROUP BY b.character_id, e.talent_boss_item_id) UNION ALL 
        (SELECT b.character_id AS `character_id`, GROUP_CONCAT(e.`level`) AS `account_character_skill_level`, e.talent_boss_item_id, SUM(e.talent_boss_item_quantity) AS `quantity`, 'burst' AS `talent_type` FROM account_characters_list AS a
            INNER JOIN account_characters AS b
                ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
            INNER JOIN character_skill_ascensions AS e
                ON e.character_id = b.character_id AND e.deleted_at IS NULL AND e.`level` > b.burst_talent_level AND e.`level` <= ".self::$account_character_max_level."
            WHERE b.account_id = ".$request->actualAccount->id."
            GROUP BY b.character_id, e.talent_boss_item_id)) as a
        INNER JOIN characters AS b
            ON a.character_id = b.id AND b.deleted_at IS NULL 
        INNER JOIN ascension_materials AS c
            ON a.talent_boss_item_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
        GROUP BY b.id, c.id;");
        return $list;
    }

    private static function getWeaponPrimaryMaterials(Request $request)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url as `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_primary_material_id AS `weap_primary_material_id`, g.name AS `weap_primary_material_name`, g.icon AS `weap_primary_material_icon`, SUM(f.weap_primary_material_quantity) AS `quantity` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id AND c.deleted_at IS NULL 
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY e.weapon_id, f.weap_primary_material_id;");
        return $list;
    }

    private static function getCharacterElementalStones(Request $request)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `elemental_stone_id`, e.name AS `elemental_stone_name`, e.icon AS `elemental_stone_icon`, SUM(d.char_elemental_stone_quantity) AS `quantity` FROM account_characters_list AS a 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY c.id, e.id;");
        return $list;
    }

    private static function getCharacterJewels(Request $request)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `char_jewel_id`, e.name AS `char_jewel_name`, e.icon AS `char_jewel_icon`, SUM(d.char_jewel_quantity) AS `quantity` FROM account_characters_list AS a 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY c.id, e.id;");
        return $list;
    }

    private static function getCharacterLocalMaterials(Request $request)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `local_material_id`, e.name AS `local_material_name`, e.icon AS `local_material_icon`, SUM(d.char_local_material_quantity) AS `quantity` FROM account_characters_list AS a 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY c.id, e.id;");
        return $list;
    }

    private static function getCharacterCommonItems(Request $request)
    {
        $list = DB::select("SELECT c.id AS `character_id`, c.name AS `character_name`, f.url AS `character_icon`, e.id AS `common_item_id`, e.name AS `common_item_name`, e.icon AS `common_item_icon`, SUM(d.char_common_item_quantity) AS `quantity` FROM account_characters_list AS a 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY c.id, e.id;");
        return $list;
    }

    private static function getWeaponSecondaryMaterials(Request $request)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url AS `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_secondary_material_id AS `weap_secondary_material_id`, g.name AS `weap_secondary_material_name`, g.icon AS `weap_secondary_material_icon`, SUM(f.weap_secondary_material_quantity) AS `quantity` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY e.weapon_id, f.weap_secondary_material_id;");
        return $list;
    }

    private static function getWeaponCommonItems(Request $request)
    {
        $list = DB::select("SELECT e.weapon_id AS `weapon_id`, h.name AS `weapon_name`, i.url AS `weapon_icon`, c.id AS `character_id`, c.name AS `character_name`, d.url AS `character_icon`, f.weap_common_item_id AS `weap_common_item_id`, g.name AS `weap_common_item_name`, g.icon AS `weap_common_item_icon`, SUM(f.weap_common_item_quantity) AS `quantity` FROM account_characters_list AS a
        INNER JOIN account_characters AS b
            ON a.account_character_id = b.id AND a.deleted_at IS NULL AND b.deleted_at IS NULL 
        INNER JOIN characters AS c 
            ON b.character_id = c.id
        INNER JOIN character_images AS d
            ON b.id = d.character_id AND d.`type` = 'icon'  AND d.deleted_at IS NULL 
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
        WHERE b.account_id = ".$request->actualAccount->id."
        GROUP BY e.weapon_id, f.weap_common_item_id;");
        return $list;
    }
}
