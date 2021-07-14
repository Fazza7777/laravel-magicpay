@extends('fronted.layouts.app')
@section('title', 'Scan & Pay')
@section('content')
    <div class="scan">
        <div class="card wallet-card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/qr_scan.png') }}" alt="" style="width:220px">
                </div>
                <p class="mb-3">Click button, put QR code in the frame.</p>

                <button class="btn btn-theme btn-sm" data-toggle="modal" data-target="#scan">Scan</button>

                <!-- Modal -->
                <div class="modal fade" id="scan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title " id="exampleModalLabel"><i class="fa fa-qrcode mr-2"></i> Scan and
                                    Pay</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <video id="qr-video" width="100%" height="220px"></video>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
<script src="{{ asset('fronted/js/qr-scanner.umd.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js">
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
@section('scripts')
    <script>
        $(document).ready(function() {

            let scanner = new Instascan.Scanner({
                video: document.getElementById('qr-video')
            });
            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                }
            })
            scanner.addListener('scan', function(c) {
                if (c) {
                    scanner.stop();
                    $('#scan').modal('hide');
                    var to_phone = c;
                    window.location.replace(`/scan-and-pay-form?to_phone=${to_phone}`)

                }
                console.log(c);
            });
            // const scanner = new QrScanner(document.getElementById('qr-video'), function(result){
            //     console.log(result);
            // });
            $('#scan').on('show.bs.modal', function(e) {
                scanner.start();

            });
            $('#scan').on('hide.bs.modal', function(e) {
                scanner.stop();
            });
        });

    </script>
@endsection
