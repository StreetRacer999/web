<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServerEvent;

class InformController extends Controller
{
    public function analyze(Request $request)
    {
        $this->processEvents($request->input('events'), $request->machine);
        $this->processServers($request->input('servers'), $request->machine);
        $this->processMachineStats($request->input('machine'), $request->machine);

        return response();
    }

    private function processEvents($nevents, $machine)
    {
        $events = collect($nevents);

        $mevents = collect();
        foreach ($events as $event) {
            unset($event->id);
            $event->server_id = $machine->servers->where('msid', $event->server_id);
            $mevents->push($event);
        }

        ServerEvent::insert($mevents);
    }

    private function processServers($nservers, $machine)
    {
        foreach ($nservers as $nserver) {
            $server = $machine->servers->where('msid', $nserver->id)->first();
            $server->status = $nserver->status;
            $server->version = $nserver->verison;
            $server->players = $nserver->players;
            $server->data = $nserver->data;
            $server->save();
        }
    }

    private function processMachineStats($stats, $machine)
    {
        $machine->stats = $stats->system;
        $machine->save();
    }
}
