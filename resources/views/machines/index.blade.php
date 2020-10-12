<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Machines') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12">
            <button class="modal-open bg-transparent border border-gray-500 hover:border-indigo-500 text-gray-500 hover:text-indigo-500 font-bold py-2 px-4 rounded-full">
                New Machines
            </button>
                <table class="table-auto">
                    <thead>
                        <tr>
                        <th class="px-4 py-2">№</th>
                        <th class="px-4 py-2">Name machine</th>
                        <th class="px-4 py-2">IP</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($machines as $machine)
                          <tr>
                            <td class="border">{{$machine->index}}</td>
                            <td class="border">{{$machine->name}}</td>
                            <td class="border">{{$machine->ip}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('machines.modal')
</x-app-layout>
