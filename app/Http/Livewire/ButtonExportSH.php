<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\MachinesController;

class ButtonExportSH extends Component
{
    public $token;
    public function export()
    {
        return response()->streamDownload(function () {
            echo MachinesController::generateInstallPreparationScript($this->token);
        }, 'script.sh');
        
    }

}
