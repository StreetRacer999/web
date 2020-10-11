<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Server;

class ServerCard extends Component
{
    public $server;

    public function render()
    {
        return view('livewire.server-card');
    }

    public function mount($server_id)
    {
        $this->server = Server::find($server_id);
    }

    public function restart()
    {
        RestartServer::dispatch($this->server)
        $this->setStatus('restarting');
    }

    public function stop()
    {
        StopServer::dispatch($this->server);
        $this->setStatus('stopping');
    }

    public function start()
    {
        StartServer::dispatch($this->server);
        $this->setStatus('starting');
    }

    private function setStatus($status)
    {
        $this->server->status = $status;
        $this->server->save();
    }
}
