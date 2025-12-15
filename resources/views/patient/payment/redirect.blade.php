<!DOCTYPE html>
<html>

<head>
    <title>{{ __('messages.redirectpayment') }}...</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>

<body>
    <script>
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route('patient.payment.finish') }}?order_id=' + result.order_id;
            },
            onPending: function(result) {
                window.location.href = '{{ route('patient.payment.finish') }}?order_id=' + result.order_id;
            },
            onError: function(result) {
                window.location.href = '{{ route('patient.payment.error') }}?order_id=' + result.order_id;
            },
            onClose: function() {
                window.location.href = '{{ route('patient.appointments.index') }}';
            }
        });
    </script>
</body>

</html>
