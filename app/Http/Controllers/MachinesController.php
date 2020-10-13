<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Machine;
use Illuminate\Support\Str;


class MachinesController extends Controller
{
    public function index(Request $request){
        $machines = Machine::where('parent', \Auth::user()->id)->get();

        $data = [
            'title' => 'Наша компания',
            'description' => 'Наша компания - самая лучшая в своём роде',
            'machines' => $machines,
            'urls' => [
                'new' => route('machines.create'),
                'create' => route('machines'),
            ],
        ];
        
        if($request->ajax()){
            return view('machines.table', $data);
        } else {
            return view('machines.index', $data);
        }
    }

    public function create(Request $request){
        if($request->ajax()){
            return view('machines.form');
        } else {
            die('no ajax');
        }
    }

    public function store(Request $request){
        $machines = new Machine;

        $machines->name = $request->input('name');
        $machines->status = 'pending-install';
        $machines->token = Str::random(32);
        $machines->parent = \Auth::user()->id;

        $machines->save();
    }

    public static function generateInstallPreparationScript($token)
    {
        $script = view('templates.prepare-install', [
            'installer_url' => config('app.url') . '/api/machines/get-installer/' . $token
        ]);

        return $script;
    }

    public static function generateInstallScript(Machine $machine)
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
                'privkey' => $ca->key,
                'originca' => Storage::disk('core')->get('origin-pull-ca.pem'),
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

    public function getInstallScript(Request $request, $token)
    {
        $otherMachine = Machine::where('ip', $request->ip)->first();

        if ($otherMachine) {
            return response('Invalid token!', 400);
        }
        
        $machine = Machine::where('token', $token)->whereNull('ip')->first();

        if (!$machine) {
            return response('Invalid token!', 400);
        }

        $machine->ip = $request->ip();
        $machine->status = 'installing';

        $machine->save();

        $script = self::generateInstallScript($machine);

        if ($machine->ip != '127.0.0.1' || $machine->ip != 'localhost') {
            $domain = $machine->id . '.machines.rustscp.net';
            CloudFlareController::addDNSRecord('A', $domain, $machine->ip);
        }
        
        $response = Response::make($script, 201);
        $response->header('Content-Type', 'text/plain');
        return $response;
    }
}
