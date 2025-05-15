@extends('layouts.app')

@section('content')
<!-- Customer Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-b pb-3">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Add Customer</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="customerForm" class="p-6">
                @csrf
                <input type="hidden" id="customerId">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <span class="text-red-600 text-sm mt-1" id="nameError"></span>
                    </div>
                    <div>
                        <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                        <input type="text" name="contact_number" id="contact_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <span class="text-red-600 text-sm mt-1" id="contactNumberError"></span>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-b pb-3">
                <h3 class="text-lg font-semibold text-gray-900">Confirm Delete</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700">Are you sure you want to move this customer to trash?</p>
                <div class="mt-6 flex justify-end">
                    <button type="button" class="mr-3 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" id="confirmDelete" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Move to Trash
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your customer database</p>
            </div>
            <button type="button" onclick="openCreateModal()" 
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Add Customer
            </button>
        </div>
    </div>

    <div id="alertMessage" style="display: none;" class="rounded-md p-4 mx-6 my-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle alert-icon"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium alert-text"></p>
            </div>
        </div>
    </div>

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
                                <button onclick="restoreCustomer({{ $customer->id }})" 
                                        class="inline-flex items-center text-green-600 hover:text-green-900 mr-3">
                                    <i class="fas fa-undo mr-1"></i> Restore
                                </button>
                                <button onclick="forceDeleteCustomer({{ $customer->id }})"
                                        class="inline-flex items-center text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete Permanently
                                </button>
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
    let customersTable;
    let trashedTable;
    let deleteCustomerId = null;

    $(document).ready(function() {
        // Initialize DataTable for active customers
        customersTable = $('#customers-table').DataTable({
            processing: true,
            responsive: true,
            ajax: {
                url: "{{ route('customers.index') }}",
                type: 'GET',
                dataSrc: 'customers'
            },
            columns: [
                { 
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { 
                    data: 'name',
                    render: function(data, type, row) {
                        return `
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                        <span class="text-lg font-medium leading-none text-blue-700">
                                            ${data.charAt(0)}
                                        </span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${data}</div>
                                </div>
                            </div>
                        `;
                    }
                },
                { 
                    data: 'contact_number',
                    render: function(data) {
                        return `
                            <div class="text-sm text-gray-900">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>${data}
                            </div>
                        `;
                    }
                },
                { 
                    data: 'created_at',
                    render: function(data) {
                        return `
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>${moment(data).format('D MMM, YYYY')}
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <button onclick="editCustomer(${data.id})" 
                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </button>
                            <button onclick="deleteCustomer(${data.id})"
                                    class="inline-flex items-center text-red-600 hover:text-red-900">
                                <i class="fas fa-trash mr-1"></i> Trash
                            </button>
                        `;
                    }
                }
            ],
            order: [[0, 'asc']],
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
            }
        });

        // Initialize DataTable for trashed customers if exists
        if($('#trashed-customers-table').length) {
            trashedTable = $('#trashed-customers-table').DataTable({
                responsive: true,
                pageLength: 5,
                order: [[0, 'asc']],
                language: {
                    search: "<i class='fas fa-search'></i> Search:",
                    lengthMenu: "<i class='fas fa-list'></i> _MENU_ per page",
                    info: "<i class='fas fa-info-circle'></i> Showing _START_ to _END_ of _TOTAL_ trashed customers",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    }
                }
            });
        }

        // Handle form submission
        $('#customerForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#customerId').val();
            const url = id ? `{{ url('customers') }}/${id}` : '{{ route('customers.store') }}';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: $(this).serialize(),
                success: function(response) {
                    $('#customerModal').modal('hide');
                    customersTable.ajax.reload();
                    showAlert('success', response.message);
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if(errors.name) {
                        $('#nameError').text(errors.name[0]);
                    }
                    if(errors.contact_number) {
                        $('#contactNumberError').text(errors.contact_number[0]);
                    }
                }
            });
        });

        // Handle delete confirmation
        $('#confirmDelete').click(function() {
            if(deleteCustomerId) {
                $.ajax({
                    url: `{{ url('customers') }}/${deleteCustomerId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide');
                        customersTable.ajax.reload();
                        if($('#trashed-customers-table').length) {
                            location.reload(); // Reload to show updated trashed items
                        }
                        showAlert('success', response.message);
                    }
                });
            }
        });
    });

    function openCreateModal() {
        $('#modalTitle').text('Add Customer');
        $('#customerForm')[0].reset();
        $('#customerId').val('');
        $('#nameError').text('');
        $('#contactNumberError').text('');
        $('#customerModal').modal('show');
    }

    function editCustomer(id) {
        $.get(`{{ url('customers') }}/${id}/edit`, function(customer) {
            $('#modalTitle').text('Edit Customer');
            $('#customerId').val(customer.id);
            $('#name').val(customer.name);
            $('#contact_number').val(customer.contact_number);
            $('#nameError').text('');
            $('#contactNumberError').text('');
            $('#customerModal').modal('show');
        });
    }

    function deleteCustomer(id) {
        deleteCustomerId = id;
        $('#deleteModal').modal('show');
    }

    function restoreCustomer(id) {
        if(confirm('Are you sure you want to restore this customer?')) {
            $.ajax({
                url: `{{ url('customers') }}/${id}/restore`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload(); // Reload to show updated tables
                }
            });
        }
    }

    function forceDeleteCustomer(id) {
        if(confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.')) {
            $.ajax({
                url: `{{ url('customers') }}/${id}/force-delete`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    location.reload(); // Reload to show updated tables
                }
            });
        }
    }

    function showAlert(type, message) {
        const alertDiv = $('#alertMessage');
        alertDiv.removeClass('bg-green-50 bg-red-50');
        alertDiv.find('.alert-icon').removeClass('text-green-400 text-red-400');
        alertDiv.find('.alert-text').removeClass('text-green-800 text-red-800');

        if(type === 'success') {
            alertDiv.addClass('bg-green-50');
            alertDiv.find('.alert-icon').addClass('text-green-400');
            alertDiv.find('.alert-text').addClass('text-green-800');
        } else {
            alertDiv.addClass('bg-red-50');
            alertDiv.find('.alert-icon').addClass('text-red-400');
            alertDiv.find('.alert-text').addClass('text-red-800');
        }

        alertDiv.find('.alert-text').text(message);
        alertDiv.fadeIn();
        setTimeout(() => alertDiv.fadeOut(), 3000);
    }
</script>
@endpush
@endsection 