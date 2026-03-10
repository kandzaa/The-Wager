<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CosmeticsSeeder extends Seeder
{
    public function run(): void
    {
        $cosmetics = [
            // Frames
            ['key' => 'frame_gold',     'name' => 'Gold Frame',     'type' => 'frame', 'rarity' => 'rare',      'price' => 500,  'meta' => ['gradient' => 'linear-gradient(135deg,#f59e0b,#d97706,#fbbf24)']],
            ['key' => 'frame_crimson',  'name' => 'Crimson Frame',  'type' => 'frame', 'rarity' => 'uncommon',  'price' => 300,  'meta' => ['gradient' => 'linear-gradient(135deg,#ef4444,#b91c1c)']],
            ['key' => 'frame_void',     'name' => 'Void Frame',     'type' => 'frame', 'rarity' => 'epic',      'price' => 800,  'meta' => ['gradient' => 'linear-gradient(135deg,#7c3aed,#4c1d95)']],
            ['key' => 'frame_aurora',   'name' => 'Aurora Frame',   'type' => 'frame', 'rarity' => 'legendary', 'price' => 1500, 'meta' => ['gradient' => 'linear-gradient(135deg,#10b981,#3b82f6,#8b5cf6,#ef4444)']],

            // Titles
            ['key' => 'title_whale',    'name' => 'The Whale',      'type' => 'title', 'rarity' => 'rare',      'price' => 600,  'meta' => ['color' => 'text-blue-400',  'bg' => 'bg-blue-500/10 border-blue-500/30']],
            ['key' => 'title_legend',   'name' => 'Legend',         'type' => 'title', 'rarity' => 'legendary', 'price' => 2000, 'meta' => ['color' => 'text-amber-400', 'bg' => 'bg-amber-500/10 border-amber-500/30']],
            ['key' => 'title_ghost',    'name' => 'Ghost Bettor',   'type' => 'title', 'rarity' => 'common',    'price' => 200,  'meta' => ['color' => 'text-slate-400', 'bg' => 'bg-slate-500/10 border-slate-500/30']],
            ['key' => 'title_shark',    'name' => 'Card Shark',     'type' => 'title', 'rarity' => 'rare',      'price' => 700,  'meta' => ['color' => 'text-red-400',   'bg' => 'bg-red-500/10 border-red-500/30']],

            // Themes
            ['key' => 'theme_midnight', 'name' => 'Midnight',       'type' => 'theme', 'rarity' => 'common',    'price' => 250,  'meta' => ['gradient' => 'linear-gradient(135deg,#0f0c29,#302b63)', 'bg_class' => 'bg-midnight']],
            ['key' => 'theme_crimson',  'name' => 'Crimson',        'type' => 'theme', 'rarity' => 'uncommon',  'price' => 450,  'meta' => ['gradient' => 'linear-gradient(135deg,#1a0505,#2d0a0a)', 'bg_class' => 'bg-crimson']],
            ['key' => 'theme_void',     'name' => 'The Void',       'type' => 'theme', 'rarity' => 'epic',      'price' => 700,  'meta' => ['gradient' => 'linear-gradient(135deg,#050510,#0d0520)', 'bg_class' => 'bg-void']],

            // Charms
            ['key' => 'charm_fire',     'name' => 'Fire',           'type' => 'charm', 'rarity' => 'common',    'price' => 100,  'meta' => ['emoji' => '🔥']],
            ['key' => 'charm_skull',    'name' => 'Skull',          'type' => 'charm', 'rarity' => 'uncommon',  'price' => 200,  'meta' => ['emoji' => '💀']],
            ['key' => 'charm_crown',    'name' => 'Crown',          'type' => 'charm', 'rarity' => 'rare',      'price' => 400,  'meta' => ['emoji' => '👑']],
            ['key' => 'charm_gem',      'name' => 'Gem',            'type' => 'charm', 'rarity' => 'rare',      'price' => 350,  'meta' => ['emoji' => '💎']],
            ['key' => 'charm_eye',      'name' => 'Evil Eye',       'type' => 'charm', 'rarity' => 'epic',      'price' => 600,  'meta' => ['emoji' => '👁️']],
            ['key' => 'charm_aurora',   'name' => 'Aurora Star',    'type' => 'charm', 'rarity' => 'legendary', 'price' => 1000, 'meta' => ['emoji' => '🌟']],
        ];

        foreach ($cosmetics as $item) {
            DB::table('cosmetics')->updateOrInsert(
                ['key' => $item['key']],
                [
                    'name'       => $item['name'],
                    'type'       => $item['type'],
                    'rarity'     => $item['rarity'],
                    'price'      => $item['price'],
                    'meta'       => json_encode($item['meta']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}