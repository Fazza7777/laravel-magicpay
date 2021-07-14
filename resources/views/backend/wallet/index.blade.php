@extends('backend.layouts.app')
@section('title', ' User Wallet')
@section('wallet-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>  Users Wallet </div>

            </div>
        </div>
    </div>

    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                <table class="table  Datatable table-bordered" style="width:100%">
                    <thead>
                        <tr class="bg-light">
                            <th>Account Number</th>
                            <th>Account Person</th>
                            <th>Amount(MMK)</th>
                            <th>Created_At</th>
                            <th>Update_At</th>
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
                "ajax": "/admin/wallet/datatable/ssd",
                columns: [{
                        data: "account_number",
                        name: "account_number",
                    }, {
                        data: "account_person",
                        name: "account_person",

                    }, {
                        data: "amount",
                        name: "amount"
                    },

                    {
                        data: "created_at",
                        name: "created_at"
                    },
                    {
                        data: "updated_at",
                        name: "updated_at"
                    },

                ],
                // order
                order: [[4, "desc" ]],
                columnDefs: [
                    {
                        targets: [0,1,2,3], // this is define index
                        sortable: false,
                    }
                ]

            });

        });

    </script>
@stop
