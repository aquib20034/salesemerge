<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
    ];

    public function getViewButton($mod){
        if(isset($this->id)){
            return '<div class="btn-group btn-group">
                        <a class="btn btn-info btn-xs" href="'.$mod.'s/'.$this->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>';
        }
    }

    public function getEditButton($mod){
        if(isset($this->id)){
            return '<div class="btn-group btn-group">
                        <a class="btn btn-info btn-xs" href="'.$mod.'s/'.$this->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>';
        }
    }

    public function getDeleteButton($mod){
        if(isset($this->id)){
            return '<div class="btn-group btn-group">
                        <button
                            class="btn btn-danger btn-xs delete_all"
                            data-url="'. url($mod.'s_delete') .'" data-id="'.$this->id.'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>';
        }
    }

    public function getAllButton($mod){
        if(isset($this->id)){
            return '<div class="btn-group btn-group">
                        <a class="btn btn-info btn-xs" href="'.$mod.'s/'.$this->id.'">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a class="btn btn-info btn-xs" href="'.$mod.'s/'.$this->id.'/edit" id="'.$this->id.'">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <button
                            class="btn btn-danger btn-xs delete_all"
                            data-url="'. url($mod.'s_delete') .'" data-id="'.$this->id.'">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>';
        }
    }
}
