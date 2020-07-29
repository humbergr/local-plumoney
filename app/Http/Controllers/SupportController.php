<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewSupportTicket;
use App\Departments;
use App\SupportOnlyUsers;
use App\SupportAgents;
use App\SupportGroups;
use App\Tickets;
use App\TicketPriority;
use App\TicketStatus;
use App\TicketSource;
use App\TicketAttachment;
use App\TicketCollaborator;
use App\TicketDues;
use App\TicketFavorites;
use App\TicketNumberCodes;
use App\TicketThread;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use QuickBooksOnline\API\Facades\Department;
use DB;
use Carbon\Carbon;

class SupportController extends Controller
{
    public function getIndex()
    {
        if (Auth::user()) {
            return $this->getMyTickets();
        }
        else {
            return $this->getCreateTicket();
        }
    }

    public function getTicketsApp()
    {
        $priorities = $this->getPriorities(1);
        $statuses = $this->getStatuses();
        $departments = $this->getDepartments(1);
        $agents = $this->getAgents();
        $groups = $this->getGroups();
        $sources = $this->getSources(1);
        $dues = $this->getDues(1);
        return response()->json([
            "statuses" => $statuses,
            "priorities" => $priorities,
            "departments" => $departments,
            "agents" => $agents,
            "groups" => $groups,
            "sources" => $sources,
            "dues" => $dues,
        ],200);
    }

    public function getSupportApp()
    {
        $departments = $this->getDepartments(0,0);
        $priorities = $this->getPriorities();
        $statuses = $this->getStatuses();
        $sources = $this->getSources();
        $numbercodes = $this->getNumberCodes();
        $dues = $this->getDues();
        return response()->json([
            "departments" => $departments,
            "priorities" => $priorities,
            "statuses" => $statuses,
            "sources" => $sources,
            "numbercodes" => $numbercodes,
            "dues" => $dues,
        ],200);
    }

    public function getTicketsCount()
    {
        $date = now();
        $overdue = Tickets::where('status','=',1)->get();
        $overduecount = 0;
        if ($overdue != null) {
            $timedate = strtotime($date);
            foreach ($overdue as $overduedate) {
                $overduedatetime = strtotime($overduedate->duedate);
                if ($overduedatetime > $timedate) {
                    $overduecount++;
                }
            }
        }
        $total = Tickets::all()->count();
        $open = Tickets::where('status','=',1)->count();
        $closed = Tickets::where('status','=',2)->count();
        $resolved = Tickets::where('status','=',3)->count();
        $deleted = Tickets::where('is_deleted','=',1)->count();
        return response()->json([
            "total" => $total,
            "overdue" => $overduecount,
            "open" => $open,
            "closed" => $closed,
            "resolved" => $resolved,
            "deleted" => $deleted
        ],200);
    }

    public function getAgents()
    {
        $active = request()['active'];
        $agents = SupportAgents::join('users','users.id','support_agents.user_id')
            ->leftjoin('departments', function ($join) {
                $join->on('support_agents.type_id', '=', 'departments.id')
                ->where('support_agents.type','=',1);
                })
            ->leftjoin('support_groups', function ($join) {
                $join->on('support_agents.type_id', '=', 'support_groups.id')
                ->where('support_agents.type','=',2);
                })
            ->select('support_agents.id as id','users.name as name','departments.name as department','support_groups.name as group')
            ->where('support_agents.is_active','=',$active)
            ->get();
        return $agents;
    }

    public function getAgents2()
    {
        $agents = SupportAgents::join('users','users.id','support_agents.user_id')
        ->select('support_agents.id as id','users.name as name')
        ->groupBy('support_agents.id')
        ->get();
        return $agents;
    }

    public function getGroups()
    {
        $active = request()['active'];
        $groups = SupportGroups::where('is_active','=',$active)
        ->select('id','name')
        ->get();
        return $groups;
    }

    public function getAgentsOLD()
    {
        $agents = SupportAgents::join('users','users.id','support_agents.user_id')
            ->join('departments','departments.id','support_agents.dept_id')
            ->select('users.id','users.name as name','departments.name as department','support_agents.created_by','is_manager','support_agents.is_active','support_agents.created_at')->get();
            // dd($agents->get()->toArray());
        return view('support.admin.agents-admin', compact('agents'));
    }

    public function getDepartments($is_active = null,$is_public = null)
    {
        $where = [];
        if ($is_active!=null) {
            array_push($where,['is_active','=',$is_active]);
        }
        if ($is_public!=null) {
            array_push($where,['is_public','=',$is_public]);
        }
        $departments = Departments::where($where)->get();
        return $departments;
    }

    // public function getDepartmentName()
    // {
    //     $id = request()['id'];
    //     $department = Departments::findOrFail($id);
    //     return $department->name;
    // }

    public function postNewDepartment(Request $request)
    {
        $name = request()['name'];
        $is_active = request()['is_active'];
        if (!isset($is_active)) {
            $is_active = 0;
        }
        $is_public = request()['is_public'];
        if (!isset($is_public)) {
            $is_public = 0;
        }
        $department = new Departments();
        $department->name = $name;
        $department->is_active = $is_active;
        $department->is_public = $is_public;
        $department->created_by = Auth::user()->id;
        $department->created_at = now();
        $department->updated_at = now();
        if ($department->save()) {
            // return $department;
            return 'ok';
        }
    }

    public function postEditDepartment(Request $request)
    {
        $id = request()['id'];
        $name = request()['name'];
        $is_active = request()['is_active'];
        $is_public = request()['is_public'];
        $department = Departments::findOrFail($id);
        $department->name = $name;
        $department->is_active = $is_active;
        $department->is_public = $is_public;
        $department->updated_at = now();
        if ($department->save()) {
            // return $department;
            return 'ok';
        }
    }

    public function postChangeDepartment(Request $request)
    {
        $id = request()['id'];
        $department = request()['department'];
        $ticket = Tickets::findOrFail($id);
        $ticket->dept_id = $department;
        if ($ticket->save()) {
            return 'ok';
        }
    }

    public function getStatuses()
    {
        $statuses = TicketStatus::get();
        return $statuses;
    }

    public function postNewStatus(Request $request)
    {
        $name = request()['name'];
        $state = request()['state'];
        $message = request()['message'];

        $status = new TicketStatus();
        $status->name = $name;
        $status->state = $state;
        $status->message = $message;
        $status->created_by = Auth::user()->id;
        $status->created_at = now();
        $status->updated_at = now();
        if ($status->save()) {
            return 'ok';
        }
    }

    public function postEditStatus(Request $request)
    {
        $id = request()['id'];
        $name = request()['name'];
        $state = request()['state'];
        $message = request()['message'];

        $status = TicketStatus::findOrFail($id);
        $status->name = $name;
        $status->state = $state;
        $status->message = $message;
        $status->updated_at = now();
        if ($status->save()) {
            return 'ok';
        }
    }

    public function postChangeStatus(Request $request)
    {
        $id = request()['id'];
        $status = request()['status'];
        $ticket = Tickets::findOrFail($id);
        $ticket->status = $status;
        if ($ticket->save()) {
            return 'ok';
        }
    }

    public function getPriorities($is_active = null)
    {
        if ($is_active != null) {
            $priorities = TicketPriority::where('status','=',$is_active)
            ->get();
        }
        else{
            $priorities = TicketPriority::get();
        }
        return $priorities;
    }

    public function postNewPriority(Request $request)
    {
        $name = request()['name'];
        $status = request()['status'];
        if (!isset($status)) {
            $status = 0;
        }
        $desc = request()['desc'];
        $color = request()['color'];
        if (!isset($color)) {
            $color = 'black';
        }
        $urgency = request()['urgency'];
        if (!isset($urgency)) {
            $urgency = 3;
        }
        $public = request()['public'];
        if (!isset($public)) {
            $public = 0;
        }
        $default = request()['default'];
        if (isset($default)||$default==1) {
            $default_priority = TicketPriority::where('is_default','=',1)->first();
            $default_priority->is_default = 0;
            $default_priority->save();
        }

        $priority = new TicketPriority();
        $priority->priority = $name;
        $priority->status = $status;
        $priority->priority_desc = $desc;
        $priority->priority_color = $color;
        $priority->priority_urgency = $urgency;
        $priority->is_public = $public;
        $priority->is_default = $default;
        $priority->created_by = Auth::user()->id;
        $priority->created_at = now();
        $priority->updated_at = now();
        if ($priority->save()) {
            return 'ok';
        }
    }

    public function postEditPriority(Request $request)
    {
        $id = request()['id'];
        $name = request()['name'];
        $status = request()['status'];
        $desc = request()['desc'];
        $color = request()['color'];
        $urgency = request()['urgency'];
        $public = request()['public'];
        $default = request()['default'];
        if ($default==1) {
            $default_priority = TicketPriority::where('is_default','=',1)->first();
            if ($default_priority!=null) {
                $default_priority->is_default = 0;
                $default_priority->save();
            }
        }

        $priority = TicketPriority::findOrFail($id);
        $priority->priority = $name;
        $priority->status = $status;
        $priority->priority_desc = $desc;
        $priority->priority_color = $color;
        $priority->priority_urgency = $urgency;
        $priority->is_public = $public;
        $priority->is_default = $default;
        $priority->updated_at = now();
        if ($priority->save()) {
            return 'ok';
        }
    }

    public function postChangePriority(Request $request)
    {
        $id = request()['id'];
        $priority_id = request()['priority'];
        $ticket = Tickets::findOrFail($id);
        $ticket->priority_id = $priority_id;
        $ticket->save();
        if ($ticket->save()) {
            return 'ok';
        }
    }

    public function getSources($is_active = null)
    {
        if ($is_active != null) {
            $sources = TicketSource::where('is_active','=',$is_active)
            ->get();
        }
        else{
            $sources = TicketSource::get();
        }
        return $sources;
    }

    public function postNewSource(Request $request)
    {
        $name = request()['name'];
        $active = request()['active'];
        if (!isset($active)) {
            $active = 0;
        }

        $source = new TicketSource();
        $source->name = $name;
        $source->created_by = Auth::user()->id;
        $source->created_at = now();
        $source->updated_at = now();
        $source->is_active = $active;
        if ($source->save()) {
            return 'ok';
        }
    }

    public function postEditSource(Request $request)
    {
        $id = request()['id'];
        $name = request()['name'];
        $active = request()['active'];

        $source = TicketSource::findOrFail($id);
        $source->name = $name;
        $source->updated_at = now();
        $source->is_active = $active;
        if ($source->save()) {
            return 'ok';
        }
    }

    public function getNumberCodes($is_active = null)
    {
        if ($is_active != null) {
            $ncodes = TicketNumberCodes::join('departments','departments.id','ticket_number_codes.dept_id')
            ->select('departments.name','ticket_number_codes.id','ticket_number_codes.dept_id','ticket_number_codes.number_code','ticket_number_codes.is_active','ticket_number_codes.created_by','ticket_number_codes.created_at','ticket_number_codes.updated_at')
            ->where('is_active','=',$is_active)
            ->get();
        }
        else{
            $ncodes = TicketNumberCodes::join('departments','departments.id','ticket_number_codes.dept_id')
            ->select('departments.name','ticket_number_codes.id','ticket_number_codes.dept_id','ticket_number_codes.number_code','ticket_number_codes.is_active','ticket_number_codes.created_by','ticket_number_codes.created_at','ticket_number_codes.updated_at')
            ->get();
        }
        return $ncodes;
    }

    public function postNewNumberCode(Request $request)
    {
        $dept = request()['dept'];
        $ncode = request()['ncode'];
        $is_active = request()['is_active'];
        if (!isset($is_active)) {
            $is_active = 0;
        }

        $number_code = new TicketNumberCodes();
        $number_code->dept_id = $dept;
        $number_code->number_code = $ncode;
        $number_code->is_active = $is_active;
        $number_code->created_by = Auth::user()->id;
        $number_code->created_at = now();
        $number_code->updated_at = now();
        if ($number_code->save()) {
            return 'ok';
        }
    }

    public function postEditNumberCode(Request $request)
    {
        $id = request()['id'];
        $dept = request()['dept'];
        $ncode = request()['ncode'];
        $is_active = request()['is_active'];

        $number_code = TicketNumberCodes::findOrFail($id);
        $number_code->dept_id = $dept;
        $number_code->number_code = $ncode;
        $number_code->is_active = $is_active;
        $number_code->updated_at = now();
        if ($number_code->save()) {
            return 'ok';
        }
    }

    public function getDues($is_active = null)
    {
        if ($is_active != null) {
            $dues = TicketDues::where('is_active','=',$is_active)
                // ->select('id','name')
            ->get();
        }
        else{
            $dues = TicketDues::get();
        }
        return $dues;
    }

    public function postEditDue(Request $request)
    {
        $id = request()['id'];
        $name = request()['name'];
        $time = request()['time'];
        $period = request()['period'];
        $active = request()['active'];

        $due = TicketDues::findOrFail($id);
        $due->name = $name;
        $due->time = $time;
        $due->period = $period;
        $due->is_active = $active;
        $due->updated_at = now();
        if ($due->save()) {
            return 'ok';
        }
    }

    public function postNewDue(Request $request)
    {
        $name = request()['name'];
        $time = request()['time'];
        $period = request()['period'];
        $active = request()['active'];
        if (!isset($active)) {
            $active = 0;
        }

        $due = new TicketDues();
        $due->name = $name;
        $due->time = $time;
        $due->period = $period;
        $due->is_active = $active;
        $due->created_by = Auth::user()->id;
        $due->created_at = now();
        $due->updated_at = now();
        if ($due->save()) {
            return 'ok';
        }
    }

    public function postDeleteConfig(Request $request)
    {
        $type = request()['type'];
        $id = request()['id'];

        switch ($type) {
            case 'department':
                $model = new Departments();
                break;
            case 'priority':
                $model = new TicketPriority();
                break;
            case 'status':
                $model = new TicketStatus();
                break;
            case 'source':
                $model = new TicketSource();
                break;
            case 'numbercode':
                $model = new TicketNumberCodes();
                break;
            case 'due':
                $model = new TicketDues();
                break;
        }

        $delete_type = $model::findOrFail($id);
        if ($delete_type->delete()) {
            return 'ok';
        }
    }

    public function getFavorite()
    {
        $user_id = Auth::user()->id;
        $favorite = TicketFavorites::where('user_id','=',$user_id)
        ->first()->count();
        return $favorite;
    }

    public function postSetFavorite(Request $request)
    {
        $ticket_id = request()['id'];
        $favorite = request()['favorite'];
        $user_id = Auth::user()->id;
        $tfavorite = TicketFavorites::where([
            ['user_id','=',$user_id],
            ['ticket_id','=',$ticket_id]
            ])->first();
        if ($tfavorite != null) {
            $tfavorite->delete();
            return 0;
        }
        else {
            $nfavorite = new TicketFavorites();
            $nfavorite->user_id = $user_id;
            $nfavorite->ticket_id = $ticket_id;
            $nfavorite->save();
            return 1;
        }
    }

    public function postTicketAction(Request $request)
    {
        $ticket_id = request()['id'];
        $action = request()['action'];
        $value = request()['value'];
        $user_id = Auth::user()->id;
        // $ticket = Tickets::where('id','=',$ticket_id)->first();
        $ticket = Tickets::find($ticket_id);
        if ($ticket!=null) {

        }
    }

    public function getCreateTicket()
    {
        try {
            $departments = $this->getDepartments(1,1);
            // dd($departments);
        } catch (\Throwable $th) {
            throw $th;
        }
        if (isset($departments)) {
            return view('support.create-ticket', compact('departments'));
        }
        else{//si no se puede conectar con akbs
            return view('support.error');
        }
    }

    public function postCreateTicket(Request $request)//crear ticket cliente
    {
        $inputs = request()->all();
        if (Auth::user()) {
            $user_id = Auth::user()->id;
            $email = Auth::user()->email;
            $is_registered = 1;
        }
        else{
            $email = $inputs['email'];
            $is_client = User::where('email','=',$email)->first();
            if ($is_client != null) {
                $user_id = $is_client->id;
                $is_registered = 1;
            }
            else {
                $supportOnly = new SupportOnlyUsers();
                $supportOnly->name = $inputs['name'];
                $email = $inputs['email'];
                $supportOnly->email = $email;
                $supportOnly->save();
                $user_id = $supportOnly->id;
                $is_registered = 0;
            }
        }
        $dept_id = $inputs['department'];
        $priority_id = TicketPriority::where('is_default','=',1)->first()->id;

        $ticket = new Tickets();
        // $ticket_number = strtoupper(str_random(4)).'-'.rand(1000,9999);
        $ticket_code = TicketNumberCodes::select('number_code')->where([
            ['dept_id','=',$dept_id],
            ['is_active','=','1']
        ])->first();
        if ($ticket_code!=null) {
            $code = $ticket_code->number_code;
        }
        else {
            $code = strtoupper(str_random(3));
        }
        $ticket_number = $code.strtoupper(str_random(5));
        $ticket->ticket_number = $ticket_number;
        $ticket->user_id = $user_id;
        $ticket->dept_id = $dept_id;
        $ticket->priority_id = $priority_id;
        $ticket->title = $inputs['subject'];
        $ticket->status = 1;
        $ticket->source = 1;
        $ticket->last_message_at = now();
        $ticket->created_at = now();
        $ticket->updated_at = now();
        $ticket->is_registered = $is_registered;

        if ($ticket->save()) {
            $thread = new TicketThread();
            $thread->ticket_id = $ticket->id;
            $thread->user_id = $user_id;
            $thread->source = 1;
            $thread->is_internal = 0;
            // $thread->title = $inputs['subject'];
            $thread->body = $inputs['message'];
            $thread->ip_address = $inputs['ip'];
            $thread->created_at = now();
            $thread->updated_at = now();

            if ($thread->save()) {
                if ($request->hasfile('file')) {
                    $attachments = new TicketAttachment();
                    $attachments->thread_id = $thread->id;
                    $attachments->file = $inputs['file'];
                    $files = "";
                    foreach ($request->file('file') as $file) {
                        $name = strtolower(str_replace(' ','',$file->getClientOriginalName()));
                        $name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $name);
                        $file->move(base_path().'/public/support_attachments/'.$ticket_number.'/'.'/',$name);
                        $routePath = '/support_attachments/'.$ticket_number .'/'. $name;
                        $files .= $routePath.",";
                    }
                    $attachments->file = $files;
                    if (!$attachments->save()) {
                        $error = 1;
                    }
                }
            }
        }
        if (isset($error)) {
            die('se ha producido un error en el proceso');
        }
        else {
            // $data = ['url' => URL::to('my-tickets'), 'ticket_number' => $ticket_number];
            // Mail::to($email)->send(new NewSupportTicket($data));
            // echo $email;
            Mail::to($email)->send(new NewSupportTicket($ticket_number));
            // if (Auth::user()) {
            //     $this->getTicket($ticket_number);
            // }
            // else{
                return view('support.ticket-created', compact('ticket_number'));
            // }
        }
    }

    public function getMyTickets()//obtener los tickets
    {
        if (Auth::user()) {
            // $data = [];
            $id = Auth::user()->id;
            $tickets = User::join('tickets','tickets.user_id','=','users.id')
                ->where('user_id', '=', $id)
                ->join('departments', 'departments.id', '=', 'tickets.dept_id')
                ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
                ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status')
                // ->join('ticket_threads', 'ticket_threads.ticket_id', '=', 'tickets.id')
                ->select('tickets.ticket_number','tickets.title','tickets.id','ticket_statuses.state as status','tickets.created_at','departments.name as department')
                ->orderBy('tickets.created_at','desc')
                ->groupBy('tickets.id')
                ->get();
            if ($tickets->isNotEmpty()) {
                return view('support.my-tickets', compact('tickets'));
            }
        }
        return view('support.my-tickets');
    }

    public function getLastThread()//obtener el ticket por #
    {
        $id = request()['id'];
        $ticket = Tickets::where('id', $id)->first();
        $ticket_id = $ticket->id;
        $threads = User::join('ticket_threads', 'ticket_threads.user_id', '=', 'users.id')
            ->leftjoin('ticket_attachments','ticket_threads.id','ticket_attachments.thread_id')
            ->leftjoin('tickets','tickets.id','ticket_threads.ticket_id')
            // ->leftjoin('departments', 'departments.id', '=', 'tickets.dept_id')
            ->select('ticket_id', 'title', 'body', 'is_internal', 'ticket_threads.created_at', 'users.name', 'users.email','file')
            ->where('ticket_id', $ticket_id)
            ->orderBy('ticket_threads.created_at', 'desc')
            // ->orderBy('ticket_threads.id', 'desc')
            ->firstOrFail();
        return $threads;
    }

    public function getTicketDetails()//obtener el ticket por #
    {
        $id = request()['id'];
        $ticket = Tickets::where('id', $id)->first();
        $ticket_id = $ticket->id;
        $threads = User::join('ticket_threads', 'ticket_threads.user_id', '=', 'users.id')
            ->leftjoin('ticket_attachments','ticket_threads.id','ticket_attachments.thread_id')
            ->leftjoin('tickets','tickets.id','ticket_threads.ticket_id')
            // ->leftjoin('departments', 'departments.id', '=', 'tickets.dept_id')
            ->select('ticket_id', 'title', 'body', 'is_internal', 'ticket_threads.created_at', 'users.name', 'users.email','file','ticket_number','ticket_threads.id','ticket_threads.user_id','ip_address','duedate')
            ->where('ticket_id', $ticket_id)
            ->orderBy('ticket_threads.created_at', 'asc')
            // ->orderBy('ticket_threads.id', 'desc')
            ->get();
        return $threads;
    }

    public function getTicket($number)//obtener el ticket por #
    {
        if (Auth::user()) {
            $ticket = Tickets::where('ticket_number', $number)->first();
            $ticket_id = $ticket->id;
            $status = $ticket->status;
            $threads = User::join('ticket_threads', 'ticket_threads.user_id', '=', 'users.id')
                ->leftjoin('ticket_attachments','ticket_threads.id','ticket_attachments.thread_id')
                ->leftjoin('tickets','tickets.id','ticket_threads.ticket_id')
                // ->leftjoin('departments', 'departments.id', '=', 'tickets.dept_id')
                ->select('ticket_id', 'title', 'body', 'is_internal', 'ticket_threads.created_at', 'users.name', 'users.email','file')
                ->where('ticket_id', $ticket_id)
                ->orderBy('ticket_threads.created_at', 'desc')
                // ->orderBy('ticket_threads.id', 'desc')
                ->get();
        }
        return view('support.ticket', compact('threads','number','status','ticket_id'));
    }

    public function postReplyTicket(Request $request)//crear ticket cliente
    {
        if (Auth::user()) {
            $inputs = request()->all();
            $ticketid = $inputs['id'];
            $ip = $inputs['ip'];
            // $title = $inputs['title'];
            $body = $inputs['message'];
            $ticket_number = $inputs['number'];
            $internal = $inputs['internal'];

            $thread = new TicketThread();
            $thread->ticket_id = $ticketid;
            $thread->user_id = Auth::user()->id;
            $thread->source = 1;
            // if ($internal == "internal") {
            //     $thread->is_internal = 1;
            // }
            // else {
            //     $thread->is_internal = 0;
            // }
            $thread->is_internal = $internal;
            // $thread->title = $title;
            $thread->body = $body;
            $thread->ip_address = $ip;
            $thread->created_at = now();
            $thread->updated_at = now();
            if ($thread->save()) {
                if ($request->hasfile('file')) {
                    $attachments = new TicketAttachment();
                    $attachments->thread_id = $thread->id;
                    $attachments->file = $inputs['file'];
                    $files = "";
                    foreach ($request->file('file') as $file) {
                        $name = strtolower(str_replace(' ','',$file->getClientOriginalName()));
                        $name = preg_replace('/[^A-Za-z0-9 _ .-]/', '', $name);
                        $file->move(base_path().'/public/support_attachments/'.$ticket_number.'/'.'/',$name);
                        $routePath = '/support_attachments/'.$ticket_number .'/'. $name;
                        $files .= $routePath.",";
                    }
                    $attachments->file = $files;
                    if (!$attachments->save()) {
                        $error = 1;
                    }
                }
            }
            if (isset($error)) {
                die('se ha producido un error en el proceso');
            }
            else {
                // return back();
                return 'OK';
            }
        }
    }

    public function getSeeders()
    {
        DB::table('ticket_statuses')->insert([
            [
                'id' => 1,
                'name' => 'open',
                'state' => 'Abierto',
                'message' => 'Ticket Abierto',
                'created_by' => 877
            ],
            [
                'id' => 2,
                'name' => 'closed',
                'state' => 'Cerrado',
                'message' => 'Ticket Cerrado',
                'created_by' => 877
            ],
            [
                'id' => 3,
                'name' => 'resolved',
                'state' => 'Resuelto',
                'message' => 'Ticket Resuelto',
                'created_by' => 877
            ]
        ]);
        DB::table('ticket_sources')->insert([
            'id' => 1,
            'name' => 'Web',
            'created_by' => 877
        ]);
        DB::table('ticket_priorities')->insert([
            [
                'id' => 1,
                'priority' => 'low',
                'status' => 1,
                'priority_desc' => 'Baja',
                'priority_color' => '#7ab4ff',
                'priority_urgency' => 1,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 2,
                'priority' => 'normal',
                'status' => 1,
                'priority_desc' => 'Normal',
                'priority_color' => '#6fcc3e',
                'priority_urgency' => 2,
                'is_public' => 1,
                'is_default' => 1,
                'created_by' => 877
            ],[
                'id' => 3,
                'priority' => 'high',
                'status' => 1,
                'priority_desc' => 'Alta',
                'priority_color' => '#dbd400',
                'priority_urgency' => 3,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 4,
                'priority' => 'urgent',
                'status' => 1,
                'priority_desc' => 'Urgente',
                'priority_color' => '#b57403',
                'priority_urgency' => 4,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 5,
                'priority' => 'emergency',
                'status' => 1,
                'priority_desc' => 'Emergencia',
                'priority_color' => '#ff0000',
                'priority_urgency' => 5,
                'is_public' => 0,
                'is_default' => 0,
                'created_by' => 877
            ]
        ]);
        DB::table('departments')->insert([
            [
                'id' => 1,
                'name' => 'Soporte',
                'is_active' => 1,
                'is_public' => 1,
                'created_by' => 877
            ],
            [
                'id' => 2,
                'name' => 'Soporte Privado',
                'is_active' => 1,
                'is_public' => 0,
                'created_by' => 877
            ],
            [
                'id' => 3,
                'name' => 'Soporte Publico Inactivo',
                'is_active' => 0,
                'is_public' => 1,
                'created_by' => 877
            ],
            [
                'id' => 4,
                'name' => 'Soporte Privado Inactivo',
                'is_active' => 0,
                'is_public' => 0,
                'created_by' => 877
            ]
        ]);
        DB::table('support_groups')->insert([
            [
                'id' => 1,
                'name' => 'Grupo Soporte 1',
                'dept_id' => 1,
                'is_active' => 1,
                'created_by' => 877
            ],
            [
                'id' => 2,
                'name' => 'Grupo Soporte 2',
                'dept_id' => 1,
                'is_active' => 1,
                'created_by' => 877
            ]
        ]);
        DB::table('support_agents')->insert([
            [
                'id' => 1,
                'user_id' => 877,
                'type' => 1,
                'type_id' => 1,
                'is_active' => 1,
                'created_by' => 877
            ],
            [
                'id' => 2,
                'user_id' => 877,
                'type' => 2,
                'type_id' => 2,
                'is_active' => 1,
                'created_by' => 877
            ]
        ]);
        DB::table('support_managers')->insert([
            'id' => 1,
            'user_id' => 877,
            'type' => 1,
            'type_id' => 1,
            'is_active' => 1,
            'created_by' => 877
        ]);
        return view('support.admin.index');
    }

    public function getAdmin()
    {
        return view('support.admin.index');
    }

    public function getTicketBody()
    {
        return TicketThread::where('ticket_id',request()['id'])->latest('created_at')->first()->body;
    }

    public function getTicketAgent()
    {
        $ticket_id = request()['id'];
        $agent = User::join('support_agents','support_agents.user_id','users.id')
            ->join('tickets','tickets.assigned_to','users.id')
            ->where('tickets.id',$ticket_id)->first();
        if ($agent!=null) {
            return $agent->name;
        }
        else {
            return null;
        }
    }

    function getUserById(){
        $id = request()['id'];
        $user = User::find($id);
        if ($user!=null) {
            return $user->name;
        }
        return null;
    }

    public function getTickets()
    {
        $status = request()['status'];
        $priority = request()['priority'];
        $department = request()['department'];
        $source = request()['source'];
        $agent = request()['agent'];
        $group = request()['group'];
        $due = request()['due'];

        $where = [];

        if ($status!=0) {
            array_push($where,['tickets.status','=',$status]);
        }
        if ($priority!=0) {
            array_push($where,['tickets.priority_id','=',$priority]);
        }
        if ($department!=0) {
            array_push($where,['tickets.dept_id','=',$department]);
        }
        if ($source!=0) {
            array_push($where,['tickets.source','=',$source]);
        }
        if ($agent!=0) {
            $assigned = SupportAgents::find($agent);
            if ($assigned!=null) {
                array_push($where,['tickets.assigned_to','=',$assigned->user_id]);
            }
        }
        if ($group!=0) {
            array_push($where,['tickets.group_id','=',$group]);
        }
        if ($due!=0) {
            // array_push($where,['tickets.duedate','=',$group]);
        }

        $tickets = User::rightjoin('tickets','tickets.user_id','=','users.id')
            ->join('departments', 'departments.id', '=', 'tickets.dept_id')
            ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
            ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status')
            ->leftjoin('support_groups','tickets.group_id','support_groups.id')
            ->select('tickets.ticket_number','tickets.id','tickets.status as status_id','ticket_statuses.state as status','tickets.created_at','departments.id as department_id','departments.name as department','users.name as name','users.id as user_id','tickets.is_deleted','ticket_priorities.id as priority_id','ticket_priorities.priority_desc as priority','ticket_priorities.priority_color as color','tickets.is_answered','tickets.duedate','users.email','title','support_groups.name as group','is_registered','tickets.assigned_to as agent','source')
            ->where($where)
            ->orderBy('tickets.created_at','desc')
            ->groupBy('tickets.id')
            ->get();
        return $tickets;
    }

    // public function getTickets()
    // {
    //     $type = request()['type'];
    //     // $type = 1;
    //     if ($type==0) {
    //         $tickets = User::rightjoin('tickets','tickets.user_id','=','users.id')
    //             ->join('departments', 'departments.id', '=', 'tickets.dept_id')
    //             ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
    //             ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status')
    //             ->leftjoin('support_groups','tickets.group_id','support_groups.id')
    //             ->select('tickets.ticket_number','tickets.id','tickets.status as status_id','ticket_statuses.state as status','tickets.created_at','departments.id as department_id','departments.name as department','users.name as name','users.id as user_id','tickets.is_deleted','ticket_priorities.id as priority_id','ticket_priorities.priority_desc as priority','ticket_priorities.priority_color as color','tickets.is_answered','tickets.duedate','users.email','title','support_groups.name as group','is_registered')
    //             ->orderBy('tickets.created_at','desc')
    //             ->groupBy('tickets.id')
    //             ->get();
    //     }
    //     else
    //     {
    //         switch ($type) {
    //             case '4': $where = [['tickets.is_deleted','=',1]];
    //             case '5': $where = [['tickets.is_answered','=',0]];
    //             case '6': $where = [['tickets.is_answered','=',1]];
    //             default: $where = [['tickets.status','=',$type]];
    //         }
    //         $tickets = User::rightjoin('tickets','tickets.user_id','=','users.id')
    //         ->where($where)
    //         ->join('departments', 'departments.id', '=', 'tickets.dept_id')
    //         ->join('ticket_priorities', 'ticket_priorities.id', '=', 'tickets.priority_id')
    //         ->join('ticket_statuses', 'ticket_statuses.id', '=', 'tickets.status')
    //         ->leftjoin('support_groups','tickets.group_id','support_groups.id')
    //         ->select('tickets.ticket_number','tickets.id','tickets.status as status_id','ticket_statuses.state as status','tickets.created_at','departments.id as department_id','departments.name as department','users.name as name','users.id as user_id','tickets.is_deleted','ticket_priorities.id as priority_id','ticket_priorities.priority_desc as priority','ticket_priorities.priority_color as color','tickets.is_answered','tickets.duedate','users.email','title','support_groups.name as group','is_registered')
    //         ->orderBy('tickets.created_at','desc')
    //         ->groupBy('tickets.id')
    //         ->get();
    //     }
    //     return $tickets;
    // }

    /*
     *
     * Cargamos todos los tickest del mes...
     * los contamos y los agrupamos... en orden descendente.
     * no necesita parametros
     *
     */
    public function getTicketsNowMonth(){

        // cargamos todos los tickets en el mes actual
        $tickets = Tickets::select(\DB::raw("COUNT(*) as count , DATE(updated_at) as date")) // contamos y buscamos segun updated_at considerar*
        ->where('user_id','=', Auth::user()->id) //segun el usuario actual
        ->where('updated_at', '>=', Carbon::now()->subMonth())
            ->groupBy('date') // agrupamos segun dia
            ->orderBy('date','ASC') // de forma ascendente
            ->get();

        // cargamos el primer dia del mes actual.
        $inicio = Carbon::now()->startOfMonth()->subMonth(0)->toDateString();
        // cargamos el ultimo dia del mes actual.
        $fin = Carbon::now()->subMonth(0)->endOfMonth()->toDateString();

        // creamos el array que tendra el pedro
        $ticketsDias = array();
        // creamos el array de categorias
        $ticketsCategorias = array();
        // cargamos el ultimo dia
        $e = substr($fin, 8);
        // contamos los tickets
        $ticketsLength = count($tickets)-1 ;

        //comienza el ciclo
        for($i = 1; $i <= $e; $i++ )
        {
            // cargamos los datos vacios segun el standar
            array_push($ticketsDias, 0);
            array_push($ticketsCategorias,date("Y-m-d",strtotime(substr($inicio, 0,8) . $i)));

            // agregamos al array los valores correctos..
            for ($t = 0; $t <= $ticketsLength; $t++)
            {
                if (substr($tickets[$t]['date'], 8) == $i)
                {
                    array_push($ticketsDias, $tickets[$t]->count);
                    array_push($ticketsCategorias,$tickets[$t]->date);
                }
            }
        }

        // arrray totals
        $ticketsTotals['tickets'] = $ticketsDias;
        $ticketsTotals['categorias'] = $ticketsCategorias;
        // retornamos el arreglo para interpretarlo
        return $ticketsTotals;

    }

    /*
     * Cargamos todos los tickest del dia actual y anterior
     *
     */
    public function getTicketsTodayYesterday(){

        // cargamos los ticktes de hoy
        $ticketshoy = Tickets::select(\DB::raw("COUNT(*) as count , HOUR(updated_at) as hour")) // contamos y buscamos segun updated_at considerar*
            ->where('user_id','=', Auth::user()->id) //segun el usuario actual
            ->whereDate('updated_at', '=', Carbon::today()->toDateString())
            ->groupBy('hour') // agrupamos segun dia
            ->orderBy('hour','ASC') // de forma ascendente
            ->get();

        // cargamos los ticktes de hoy
        $ticketsayer = Tickets::select(\DB::raw("COUNT(*) as count , HOUR(updated_at) as hour")) // contamos y buscamos segun updated_at considerar*
            ->where('user_id','=', Auth::user()->id) //segun el usuario actual
            ->whereDate('updated_at', '=', Carbon::today()->toDateString())
            ->groupBy('hour') // agrupamos segun dia
            ->orderBy('hour','ASC') // de forma ascendente
            ->get();

        $categorias = array();
        $tickets['hoy'] = array();
        $tickets['ayer'] = array();
        for ($i = 0; $i <= 23; $i++){
            array_push($categorias, $i);
            for ($thoy = 0; $thoy <= count($ticketshoy) -1; $thoy++)
            {
                array_push($tickets['hoy'], 0);
                if ($ticketshoy[$thoy]['hour'] == $i -1){
                    array_push($tickets['hoy'], $ticketshoy[$thoy]->count);
                }
            }
            for ($tayer = 0; $tayer <= count($ticketshoy) -1; $tayer++)
            {
                array_push($tickets['ayer'], 0);
                if ($ticketshoy[$tayer]['ayer'] == $i -1){
                    array_push($tickets['ayer'], $ticketshoy[$tayer]->count);
                }
            }
        }

        $tickets['categorias'] = $categorias;
        return $tickets;
    }

}
