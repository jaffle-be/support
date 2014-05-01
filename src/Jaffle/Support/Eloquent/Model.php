<?php

namespace Jaffle\Support\Eloquent;

use App;
use Jaffle\Support\Exception\TranslationFieldNotFound;

class Model extends \Illuminate\Database\Eloquent\Model{

    /**
     * SECTION TRANSLATABLES
     */

    /**
     * An array of translatable fields
     * Keep it static so it wouldn't be duplicated for each model
     * @var array
     */
    protected static $translatables = array();

    /**
     * We override the get method so we can find translatables and return that translation
     * This is simply for convenience.
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if($this->canBeTranslated($attribute))
        {
            return $this->translate($attribute);
        }

        return parent::__get($attribute);
    }

    protected function canBeTranslated($attribute)
    {
        return array_search($attribute, static::$translatables) !== FALSE;
    }

    /**
     * A translation function to translate a field
     * @param $attribute
     * @param null $locale
     * @return mixed
     * @throws \Jaffle\Support\Exception\TranslationFieldNotFound
     */
    public function translate($attribute, $locale = null)
    {
        if(empty($locale))
        {
            $locale = App::getLocale();
        }

        if($this->canBetranslated($attribute))
        {
            return $this->{$attribute . '_' . $locale};
        }

        throw new TranslationFieldNotFound($attribute . ' cannot be found on this model.');

    }

}