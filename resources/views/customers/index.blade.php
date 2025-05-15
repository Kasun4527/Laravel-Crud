@extends('layouts.app')

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your customer database</p>
            </div>
            <a href="{{ route('customers.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Add Customer
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mx-6 my-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="p-6">
        <!-- Active Customers Table -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Active Customers</h2>
            <table id="customers-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Contact Number
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Created At
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                            <span class="text-lg font-medium leading-none text-blue-700">
                                                {{ substr($customer->name, 0, 1) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    {{ $customer->contact_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                {{ $customer->created_at->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('customers.edit', $customer) }}" 
                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to move this customer to trash?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash mr-1"></i> Trash
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Trashed Customers Table -->
        @if($trashedCustomers->count() > 0)
        <div class="mt-8 border-t pt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-trash-alt text-gray-400 mr-2"></i>
                Trashed Customers
            </h2>
            <table id="trashed-customers-table" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            #
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-user mr-2"></i>Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-phone mr-2"></i>Contact Number
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-calendar mr-2"></i>Deleted At
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($trashedCustomers as $customer)
                        <tr class="bg-red-50/30">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                #{{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                                            <span class="text-lg font-medium leading-none text-red-700">
                                                {{ substr($customer->name, 0, 1) }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    {{ $customer->contact_number }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                {{ $customer->deleted_at->format('d M, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('customers.restore', $customer->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to restore this customer?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center text-green-600 hover:text-green-900 mr-3">
                                        <i class="fas fa-undo mr-1"></i> Restore
                                    </button>
                                </form>
                                <form action="{{ route('customers.force-delete', $customer->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt mr-1"></i> Delete Permanently
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable for active customers
        $('#customers-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [
                {
                    targets: -1,
                    orderable: false,
                    searchable: false
                }
            ],
            language: {
                search: "<i class='fas fa-search'></i> Search:",
                lengthMenu: "<i class='fas fa-list'></i> _MENU_ per page",
                info: "<i class='fas fa-info-circle'></i> Showing _START_ to _END_ of _TOTAL_ customers",
                paginate: {
                    first: "<i class='fas fa-angle-double-left'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    previous: "<i class='fas fa-angle-left'></i>"
                },
                emptyTable: "<div class='text-center p-4'><i class='fas fa-users text-gray-400 text-4xl mb-3'></i><br>No customers found</div>"
            },
            drawCallback: function() {
                $('.paginate_button').addClass('mx-1');
                $('.paginate_button.current').addClass('bg-blue-50 text-blue-600');
            },
            initComplete: function() {
                $('.dataTables_filter').append(
                    '<button class="ml-2 text-gray-400 hover:text-gray-600" onclick="$(\'#customers-table\').DataTable().search(\'\').draw();">' +
                    '<i class="fas fa-times"></i>' +
                    '</button>'
                );
            }
        });

        // Initialize DataTable for trashed customers
        if($('#trashed-customers-table').length) {
            $('#trashed-customers-table').DataTable({
                responsive: true,
                pageLength: 5,
                order: [[0, 'asc']],
                columnDefs: [
                    {
                        targets: -1,
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    search: "<i class='fas fa-search'></i> Search:",
                    lengthMenu: "<i class='fas fa-list'></i> _MENU_ per page",
                    info: "<i class='fas fa-info-circle'></i> Showing _START_ to _END_ of _TOTAL_ trashed customers",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    },
                    emptyTable: "<div class='text-center p-4'><i class='fas fa-trash-alt text-gray-400 text-4xl mb-3'></i><br>No trashed customers found</div>"
                },
                drawCallback: function() {
                    $('.paginate_button').addClass('mx-1');
                    $('.paginate_button.current').addClass('bg-blue-50 text-blue-600');
                }
            });
        }
    });
</script>
@endpush
@endsection 