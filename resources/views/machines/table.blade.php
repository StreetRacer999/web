<table class="table-auto">
                    <thead>
                        <tr>
                        <th class="px-4 py-2">№</th>
                        <th class="px-4 py-2">Name machine</th>
                        <th class="px-4 py-2">IP</th>
                        <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($machines as $machine)
                          <tr>
                            <td class="border">{{$machine->index}}</td>
                            <td class="border">{{$machine->name}}</td>
                            <td class="border">{{$machine->ip}}</td>
                            <td class="border">{{$machine->status}}
                                @if($machine->status == 'pending-install')
                                      @livewire('button-export-s-h', ['token' => $machine->token]) 
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>