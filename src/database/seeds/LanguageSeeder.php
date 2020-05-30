<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public const DEFAULT_LANGUAGES = [
        'ru',
        'uk',
        'en',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::DEFAULT_LANGUAGES as $language) {
            \App\Models\Language::create([
                'code' => $language,
            ]);
        }
    }
}
