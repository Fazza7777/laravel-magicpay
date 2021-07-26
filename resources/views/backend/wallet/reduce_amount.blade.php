@extends('backend.layouts.app')
@section('title', ' User Wallet')
@section('wallet-active', 'mm-active')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> Reduce Amount </div>

            </div>
        </div>
    </div>

    <div class="content py-3">
        <div class="card">
            <div class="card-body">

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
                order: [
                    [4, "desc"]
                ],
                columnDefs: [{
                    targets: [0, 1, 2, 3], // this is define index
                    sortable: false,
                }]

            });

        });
    </script>
@stop
