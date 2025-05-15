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
            <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
                <table id="customersTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
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
                </table>
            </div>
        </div>

        <!-- Trashed Customers Table -->
        @if($trashedCustomers->count() > 0)
        <div class="mt-8 border-t pt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-trash-alt text-gray-400 mr-2"></i>
                Trashed Customers
            </h2>
            <div class="shadow overflow-x-auto border-b border-gray-200 sm:rounded-lg">
                <table id="trashedTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
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
                </table>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const customersTable = $('#customersTable').DataTable({
            processing: true,
            serverSide: false,
            ajax: '{{ route('customers.index') }}',
            columns: [
                { 
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name' },
                { data: 'contact_number' },
                { 
                    data: 'created_at',
                    render: function(data) {
                        return moment(data).format('D MMM, YYYY');
                    }
                },
                {
                    data: 'id',
                    render: function(data) {
                        return `
                            <a href="{{ url('customers') }}/${data}/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <button onclick="deleteCustomer(${data})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i> Trash
                            </button>
                        `;
                    }
                }
            ],
            order: [[3, 'desc']]
        });

        // Make the customersTable variable available globally
        window.customersTable = customersTable;

        // Initialize DataTable for trashed customers if they exist
        @if($trashedCustomers->count() > 0)
        const trashedTable = $('#trashedTable').DataTable({
            data: @json($trashedCustomers),
            columns: [
                { 
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name' },
                { data: 'contact_number' },
                { 
                    data: 'deleted_at',
                    render: function(data) {
                        return moment(data).format('D MMM, YYYY');
                    }
                },
                {
                    data: 'id',
                    render: function(data) {
                        return `
                            <button onclick="restoreCustomer(${data})" class="text-green-600 hover:text-green-900 mr-3">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                            <button onclick="forceDeleteCustomer(${data})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash-alt"></i> Delete Permanently
                            </button>
                        `;
                    }
                }
            ],
            order: [[3, 'desc']]
        });
        window.trashedTable = trashedTable;
        @endif
    });

    function deleteCustomer(id) {
        if(!confirm('Are you sure you want to move this customer to trash?')) return;

        $.ajax({
            url: `{{ url('customers') }}/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(`button[onclick="deleteCustomer(${id})"]`).prop('disabled', true);
            },
            success: function(response) {
                window.customersTable.ajax.reload();
                if(window.trashedTable) {
                    window.trashedTable.ajax.reload();
                }
                showAlert('success', response.message);
            },
            error: function() {
                showAlert('error', 'An error occurred while deleting the customer.');
                $(`button[onclick="deleteCustomer(${id})"]`).prop('disabled', false);
            }
        });
    }

    function restoreCustomer(id) {
        if(!confirm('Are you sure you want to restore this customer?')) return;

        $.ajax({
            url: `{{ url('customers') }}/${id}/restore`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(`button[onclick="restoreCustomer(${id})"]`).prop('disabled', true);
            },
            success: function(response) {
                window.customersTable.ajax.reload();
                window.trashedTable.ajax.reload();
                showAlert('success', response.message);
            },
            error: function() {
                showAlert('error', 'An error occurred while restoring the customer.');
                $(`button[onclick="restoreCustomer(${id})"]`).prop('disabled', false);
            }
        });
    }

    function forceDeleteCustomer(id) {
        if(!confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.')) return;

        $.ajax({
            url: `{{ url('customers') }}/${id}/force-delete`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function() {
                $(`button[onclick="forceDeleteCustomer(${id})"]`).prop('disabled', true);
            },
            success: function(response) {
                window.trashedTable.ajax.reload();
                showAlert('success', response.message);
            },
            error: function() {
                showAlert('error', 'An error occurred while deleting the customer.');
                $(`button[onclick="forceDeleteCustomer(${id})"]`).prop('disabled', false);
            }
        });
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