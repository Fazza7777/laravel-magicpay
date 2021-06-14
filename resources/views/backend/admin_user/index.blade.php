@extends('backend.layouts.app')
@section('title', 'Admin User Managment')
@section('admin-user-index', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> Admin Users List </div>

            </div>
        </div>
    </div>
    <div class="pt-3">
        <a href="{{ route('admin.admin-user.create') }}" class="btn btn-primary "><i class="fas fa-plus-circle"></i>
            Create Admin User</a>

    </div>
    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                <table class="table  Datatable table-bordered" style="width:100%">
                    <thead>
                        <tr class="bg-light">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>IP</th>
                            <th>USER AGENT</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            var dataTable = $('.Datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "/admin/admin-user/datatable/ssd",
                columns: [{
                        data: "name",
                        name: "name"
                    }, {
                        data: "email",
                        name: "email"
                    }, {
                        data: "phone",
                        name: "phone"
                    },
                    {
                        data: "ip",
                        name: "ip"
                    },
                    {
                        data: "user_agent",
                        name: "user_agent"
                    },
                    {

                        data: "action",
                        name: "action"
                    }
                ]
            });
            // ###########
            //this is first select parent (document) and select delete class
            $(document).on('click', '.delete', function(e) {
                e.preventDefault()
                var id = $(this).data('id')
                let token = document.head.querySelector("meta[name='csrf-token']")
                console.log(token.content)
                if (token) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF_TOKEN': token.content
                        }
                    })
                }
                // Sweat alert
                Swal.fire({
                    title: 'Account Delete',
                    text: 'Are you sure, you want to delete?',
                    showCancelButton: true,
                    confirmButtonText: `Confirm`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/admin-user/' + id,
                            type: 'DELETE',
                            success: function() {
                                dataTable.ajax.reload()
                            }
                        })
                    }
                })
            })
        });

    </script>
@stop
