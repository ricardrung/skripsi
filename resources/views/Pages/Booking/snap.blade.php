@extends('components.Layout.layout')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script type="text/javascript">
        window.onload = function() {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Pembayaran berhasil!',
                    }).then(() => {
                        window.location.href = "{{ route('booking.riwayat') }}";
                    });
                },
                onPending: function(result) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Menunggu Pembayaran',
                        text: 'Anda telah memilih metode pembayaran. Silakan selesaikan transaksinya.',
                        confirmButtonColor: '#8b5a2b'
                    }).then(() => {
                        window.location.href = "{{ route('booking.riwayat') }}";
                    });
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                        confirmButtonColor: '#8b5a2b'
                    }).then(() => {
                        window.location.href = "{{ route('booking.riwayat') }}";
                    });
                },
                onClose: function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Belum Selesai',
                        text: 'Anda menutup pop-up sebelum menyelesaikan pembayaran.',
                        confirmButtonColor: '#8b5a2b'
                    }).then(() => {
                        window.location.href = "{{ route('booking.riwayat') }}";
                    });
                }
            });
        }
    </script>
@endsection
