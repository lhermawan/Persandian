{{-- RESPONSE MESSAGES --}}
@if($messages)
@foreach ($messages as $message)
<script>
    // Display a success toast, with a title
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "10000",
        "extendedTimeOut": "10000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr.{{ $message['level'] }}("{!! $message['message'] !!}");
</script>
@endforeach
@endif


{{-- ERROR VALIDATOR --}}
{{ $errors }}
@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
    // Display a success toast, with a title
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "preventDuplicates": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "10000",
        "extendedTimeOut": "10000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    toastr.warning("<li>{{ $error }}</li>");
</script>
@endforeach
@endif