<?php

use Illuminate\Database\Seeder;

class Support extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
                'priority_color' => 'green',
                'priority_urgency' => 1,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 2,
                'priority' => 'normal',
                'status' => 1,
                'priority_desc' => 'Normal',
                'priority_color' => 'blue',
                'priority_urgency' => 2,
                'is_public' => 1,
                'is_default' => 1,
                'created_by' => 877
            ],[
                'id' => 3,
                'priority' => 'high',
                'status' => 1,
                'priority_desc' => 'Alta',
                'priority_color' => 'yellow',
                'priority_urgency' => 3,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 4,
                'priority' => 'urgent',
                'status' => 1,
                'priority_desc' => 'Urgente',
                'priority_color' => 'red',
                'priority_urgency' => 4,
                'is_public' => 1,
                'is_default' => 0,
                'created_by' => 877
            ],[
                'id' => 5,
                'priority' => 'emergency',
                'status' => 1,
                'priority_desc' => 'Emergencia',
                'priority_color' => 'red',
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
    }
}