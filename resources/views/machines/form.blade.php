<form id="modalForm">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Name Machines
            </label>
            <input placeholder="Name Machines" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text">

            {{ csrf_field() }}
            </form>