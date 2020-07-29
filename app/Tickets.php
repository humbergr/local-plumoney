<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        // 'id',
        'ticket_number',
        'user_id',
        'dept_id',
        'priority_id',
        'title',
        'status',
        'rating',
        'ratingreply',
        'assigned_to',
        'source',
        'is_answered',
        'is_deleted',
        'is_transfer',
        'transfer_at',
        'duedate',
        'closed_at',
        'last_message_at',
        'last_response_at',
        'is_registered',
        'created_at',
        'updated_at',
    ];

//        public function attach(){
//            return $this->hasMany('App\Model\helpdesk\Ticket\Ticket_attachments',);
//
//        }
    // public function thread()
    // {
    //     return $this->hasMany('App\Ticket_Thread', 'ticket_id');
    // }

    // public function collaborator()
    // {
    //     return $this->hasMany('App\Model\helpdesk\Ticket\Ticket_Collaborator', 'ticket_id');
    // }

    // public function helptopic()
    // {
    //     $related = 'App\Model\helpdesk\Manage\Help_topic';
    //     $foreignKey = 'help_topic_id';

    //     return $this->belongsTo($related, $foreignKey);
    // }

    // public function formdata()
    // {
    //     return $this->hasMany('App\Model\helpdesk\Ticket\Ticket_Form_Data', 'ticket_id');
    // }

    // public function extraFields()
    // {
    //     $id = $this->attributes['id'];
    //     $ticket_form_datas = \App\Model\helpdesk\Ticket\Ticket_Form_Data::where('ticket_id', '=', $id)->get();

    //     return $ticket_form_datas;
    // }

    // public function source()
    // {
    //     $source_id = $this->attributes['source'];
    //     $sources = new Ticket_source();
    //     $source = $sources->find($source_id);

    //     return $source;
    // }

    // public function sourceCss()
    // {
    //     $css = 'fa fa-comment';
    //     $source = $this->source();
    //     if ($source) {
    //         $css = $source->css_class;
    //     }

    //     return $css;
    // }

    // public function delete()
    // {
    //     $this->thread()->delete();
    //     $this->collaborator()->delete();
    //     $this->formdata()->delete();
    //     parent::delete();
    // }

    // public function setAssignedToAttribute($value)
    // {
    //     if (!$value) {
    //         $this->attributes['assigned_to'] = null;
    //     } else {
    //         $this->attributes['assigned_to'] = $value;
    //     }
    // }

    // public function getAssignedTo()
    // {
    //     $agentid = $this->attributes['assigned_to'];
    //     if ($agentid) {
    //         $users = new \App\User();
    //         $user = $users->where('id', $agentid)->first();
    //         if ($user) {
    //             return $user;
    //         }
    //     }
    // }

    // public function user()
    // {
    //     $related = "App\User";
    //     $foreignKey = 'user_id';

    //     return $this->belongsTo($related, $foreignKey);
    // }
}
