<script type="text/javascript">
    $(document).ready(function () {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "positionClass": "toast-bottom-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        @if($errors->any())
            let msg = '';
            @foreach ($errors->all() as $error)
                msg = msg + "- {{ $error }} <br/>";
            @endforeach
            toastr.error(msg, "Error");
        @endif
        @if(Session::has('success'))
        toastr.success("{{ Session::has('success') }}", "Success");
        @endif
        @if(Session::has('error'))
        toastr.error("{{ Session::has('error') }}", "Error");
        @endif
        @if(Session::has('info'))
        toastr.error("{{ Session::has('info') }}", "Info");
        @endif
        @if(Session::has('warning'))
        toastr.warning("{{ Session::has('warning') }}", "Warning");
        @endif
    });
</script>