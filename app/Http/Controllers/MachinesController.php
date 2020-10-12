<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use App\Models\Machine;


class MachinesController extends Controller
{
    public function index(){
        $machines = Machine::where('parent', \Auth::user()->id)->get();

        $data = [
            'title' => 'Наша компания',
            'description' => 'Наша компания - самая лучшая в своём роде',
            'machines' => $machines,
        ];
        return view('machines.index', $data);
    }

    public function create(Request $request){
        $machines = new Machine;

        $machines->name = $request->input('name');
        $machines->ip = $request->input('ip');
        $machines->status = 'installing';
        $machines->parent = \Auth::user()->id;

        $machines->save();
    }

    public static function prepareInstall($machine)
    {
        $domain = $machine->id . '.machines.rustscp.net';

        $ca = CloudFlareController::createOriginCA($domain);

        $nginx_conf = view('templates.nginx', [
            'domain' => $domain,
        ])->render();

        $script = view('templates.install', [
            'machine' => $machine,
            'nginx_conf' => $nginx_conf,
            'ssl' => [
                'cert' => $ca->cert,
                'key' => $ca->key,
                'origin_ca' => Storage::disk('core')->get('origin-pull-ca.pem'),
            ],
            'env_data' => view('templates.env', [
                'machine' => $machine,
                'key' => 'base64:' . base64_encode(
                    Encrypter::generateKey('AES-256-CBC')
                )
            ])->render(),
            'supervisor_conf' => view('templates.supervisor')->render(),
        ])->render();

        return $script;
    }
}
