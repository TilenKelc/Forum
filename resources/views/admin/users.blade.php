<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight" id="header-txt">
            Admin panel for users
        </h2>
    </x-slot>

    <x-auth-validation-errors :errors="$errors" />

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white max-w-7xl mx-auto px-8 py-8 mt-4">
        <table class="w-full pt-8 mx-auto text-sm text-left text-gray-500 dark:text-gray-400" id="dataTable">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Priviliges
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Blocked?
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Block/Unblock
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.fetch') }}",
                columns: [
                    {data: 'username', name: 'username'},
                    {data: 'privileges', name: 'privileges'},
                    {data: 'email', name: 'email'},
                    {data: 'deleted', name: 'deleted'},
                    {data: 'block', name: 'block', orderable: false, searchable: false}
                ],
                createdRow: function(row, data, dataIndex){
                    $(row).addClass("bg-white");
                    $(row).addClass("border-b");
                    $(row).addClass("dark:bg-gray-800");
                    $(row).addClass("dark:border-gray-700");
                }
            });
            
        });
    </script>
</x-app-layout>