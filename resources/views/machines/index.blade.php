<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Machines') }}
        </h2>
    </x-slot>
   
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-12">
            <button data-url="{{$urls['new']}}" class="modal-open bg-transparent border border-gray-500 hover:border-indigo-500 text-gray-500 hover:text-indigo-500 font-bold py-2 px-4 rounded-full">
                New Machines
            </button>
            <div class="ajaxTable">
                  @include('machines.table')
            </div>
            </div>
        </div>
    </div>

    @include('machines.modal')
</x-app-layout>
