<?php

namespace Jaffle\Support\Database\Eloquent;

use App;
use Jaffle\Support\Exception\TranslationFieldNotFound;

class Model extends \Illuminate\Database\Eloquent\Model{

    /**
     * SECTION TRANSLATIONS
     */

    /**
     * An array of translatable fields
     * Keep it static so it wouldn't be duplicated for each model
     * @var array
     */
    protected static $translations = array();

    /**
     * Helper to remember the default locale.
     * @var bool|string
     */
    protected static $defaultLocale = false;

    /**
     * We override the get method so we can find translations and return that translation
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
        return array_search($attribute, static::$translations) !== FALSE;
    }

    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        if (count($this->fillable) > 0 && ! static::$unguarded)
        {
            return array_intersect_key($attributes, array_flip($this->getFillable()));
        }

        return $attributes;
    }

    /**
     * Determine if the given attribute may be mass assigned.
     *
     * @param  string  $key
     * @return bool
     */
    public function isFillable($key)
    {
        if (static::$unguarded) return true;

        // If the key is in the "fillable" array, we can of course assume that it's
        // a fillable attribute. Otherwise, we will check the guarded array when
        // we need to determine if the attribute is black-listed on the model.
        if (in_array($key, $this->getFillable())) return true;

        if ($this->isGuarded($key)) return false;

        return empty($this->fillable) && ! starts_with($key, '_');
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
            $locale = $this->defaultLocale();
        }

        if($this->canBetranslated($attribute))
        {
            return $this->attributes[$attribute . '_' . $locale];
        }

        throw new TranslationFieldNotFound($attribute . ' cannot be found on this model.');

    }

    protected function defaultLocale()
    {
        if(!static::$defaultLocale)
        {
            //this was added to allow testing (without the need of our App)
            //but in fact this is probably bad practise. (hell i've just started doing tests)
            static::$defaultLocale = class_exists('App') ? App::getLocale() : 'en';
        }

        return static::$defaultLocale;
    }

    public function getFillable()
    {
        $fillables = parent::getFillable();

        $translatableFields = array_intersect(static::$translations, $fillables);

        $locales = class_exists('App') ? App::getLocales() : array('nl', 'fr', 'en');

        foreach($translatableFields as $index => $key)
        {
            unset($fillables[$index]);

            foreach($locales as $locale)
            {
                array_push($fillables, $key . '_' . $locale);
            }
        }

        return array_values($fillables);
    }


}