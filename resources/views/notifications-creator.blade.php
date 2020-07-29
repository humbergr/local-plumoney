<script type="text/javascript">
    $(document).ready(function () {
        toastr.options = {
            "timeOut": 0,
            "extendedTimeOut": 0,
            "positionClass": "toast-top-full-width __toast_k",
            "closeButton": true,
        };
        @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
        @elseif(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
        @endif
        @if ($errors->has('email') || $errors->has('password'))
        toastr.error("Incorrect email or password.");
        @endif
    });
</script>