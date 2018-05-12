<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'body', 'folder_id'];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function folder()
    {
        return $this->belongsTo('App\Folder')->withDefault([
            'title' => 'No folder'
        ]);
    }

    public function getAllFoldersForSelectAttribute()
    {
        $result = array();

        $items = auth()->user()->folders;

        foreach($items as $item) {
            $result[$item->id] = $item->title;
        }

        return $result;
    }
}
