<?php

namespace Slavic\MissingPersons\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class Person extends Model
{
    protected $table = 'persons';
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'hash', 'type', 'name', 'last_seen',
        'found', 'date_found'
    ];
    /**
     * The rules for validation.
     *
     * @var array
     */
    public static $rules = array(
        'name' => 'required'
    );
    
    protected $dates = ['created_at'];
    
    /**
     * Associations.
     *
     * @var object | collect
     */
    public function region()
    {
        return $this->belongsTo('Slavic\MissingPersons\Model\Region', 'region_id');
    }
    
    public function settlement()
    {
        return $this->belongsTo('Slavic\MissingPersons\Model\Settlement', 'settlement_id');
    }
    
    public function photos()
    {
        return $this->hasMany('Slavic\MissingPersons\Model\PersonPhoto', 'person_id');
    }
    
    public function last_place()
    {
        return $this->hasOne('Slavic\MissingPersons\Model\LastPlace', 'person_id');
    }
    
    public function profile()
    {
        return $this->hasOne('Slavic\MissingPersons\Model\PersonProfile', 'person_id');
    }
    
    public function found()
    {
        return $this->hasOne('Slavic\MissingPersons\Model\PersonFound', 'person_id');
    }
    
    public function eyes_color()
    {
        return $this->hasOne('Slavic\MissingPersons\Model\EyesColor', 'eyes_color');
    }
    
    public function hair_color()
    {
        return $this->hasOne('Slavic\MissingPersons\Model\HairColor', 'hair_color');
    }
    
    /**
     * Get latest published persons
     *
     * @return collect
     */
    public static function getLatest($number = 16)
    {
        $records = self::select(DB::raw(
            DB::getTablePrefix().'persons.*, ' .
            DB::getTablePrefix() . 'person_photo.thumb, ' .
            DB::getTablePrefix() . 'person_last_place.*, ' .
            DB::getTablePrefix() . 'person_profile.*, ' .
            DB::getTablePrefix() . 'person_found.* '
        ))
            ->leftJoin('person_found', 'persons.id', '=', 'person_found.person_id')
            ->leftJoin('person_last_place', 'persons.id', '=', 'person_last_place.person_id')
            ->leftJoin('person_profile', 'persons.id', '=', 'person_profile.person_id')
            ->leftJoin('person_photo', 'persons.id', '=', 'person_photo.person_id');
        $records = $records->whereNull('person_found.person_id')->groupBy('persons.id')->orderBy('persons.last_seen', 'DESC')->get();
        return $records->take($number);
    }
    
    /**
     * Get statistics
     *
     * @return collect
     */
    public static function getStatistics($number = 16)
    {
        $stats = [];
        $minutes = 5;
        
        $stats['missing_persons'] = Cache::remember('missing_persons', $minutes, function () {
            $persons = DB::table('persons')->where('type', '=', 'missing_person')->get();
            return $persons->count();
        });
        
        $stats['wanted_persons'] = Cache::remember('wanted_persons', $minutes, function () {
            $persons = DB::table('persons')->where('type', '=', 'wanted_criminal')->get();
            return $persons->count();
        });
        
        $stats['persons_found'] = Cache::remember('persons_found', $minutes, function () {
            $persons_found = DB::table('person_found')->get();
            return $persons_found->count();
        });
        
        $stats['persons_found_dead'] = Cache::remember('persons_found_dead', $minutes, function () {
            $persons_found_dead = DB::table('person_found')->where('dead', '=', 1)->get();
            return $persons_found_dead->count();
        });
        
        $stats['top_region'] = Cache::remember('top_region', $minutes, function () {
            $persons_found_dead = DB::table('person_found')->where('dead', '=', 1)->get();
            return $persons_found_dead->count();
        });
        
        return (object)$stats;
    }
    
    /**
     * Find item by hash.
     *
     * @return object
     */
    public static function getByHash($hash)
    {
        return self::where('hash', '=', $hash)->first();
    }    
    
    /**
     * Get all items.
     *
     * @return collect
     */
    public static function getAll()
    {
        return self::select('*');
    }
}
