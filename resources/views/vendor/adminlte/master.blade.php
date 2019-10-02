<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 2'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">

    @include('adminlte::plugins', ['type' => 'css'])

    @if(config('adminlte.pace.active'))
    <!-- Pace -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/{{config('adminlte.pace.color', 'blue')}}/pace-theme-{{config('adminlte.pace.type', 'center-radar')}}.min.css">
    @endif

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 style -->
    <link rel="stylesheet" href="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css">
    @endif
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet" type='text/css'>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    @yield('adminlte_css')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition @yield('body_class')">

    @yield('body')
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="//malsup.github.com/jquery.form.js"></script>

    @if (Auth::check())
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        var table;
        axios.defaults.headers.common = {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': "Bearer {{ session('token') }}"
        }
        $.ajaxSetup({
            headers: {
                'Authorization': "Bearer {{ session('token') }}",
            }
        });
        @endif
    </script>

    @if (Auth::check())

    <!-- Axios -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8.17.1/dist/sweetalert2.all.min.js" integrity="sha256-VxbmCDobjdVsaHYJOlEcQcc/p89LW76+b4x3r7l5Ikg=" crossorigin="anonymous"></script>
    <script>
        $('body').on('click', '.btn-action', function(elemen) {
            elemen.preventDefault();
            $(".btn-action").attr("disabled", true);

            if ($(this).hasClass('btn-create')) {
                showModal($(this));

            } else if ($(this).hasClass('btn-detail')) {
                showModal($(this));
            } else if ($(this).hasClass('btn-edit')) {
                showModal($(this));
            } else if ($(this).hasClass('delete')) {
                const urlsdelete = $(this).attr('link');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to delete this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios({
                            url: urlsdelete,
                            credentials: true,
                            method: "DELETE",
                        }).then(response => {
                            console.log(response);
                            table.draw();
                            swal2(data.status, data.message);

                        }).catch(error => {
                            console.log(error.response);
                        });
                    }
                })
            }
            $(".btn-action").attr("disabled", false);

        });
        $(document).ajaxStart(function() {
            Pace.restart();
        });



        function showModal(el) {
            var urls = el.attr('link'),
                title = el.attr('title');

            $('.modal-title').text(title);

            axios({
                url: urls,
                credentials: true,
                method: "GET",
            }).then(response => {
                // // console.log(response);
                $('.modal-content').html(response.data);
                // // initElem();
                $('#modal-button').text(el.hasClass('edit') ? 'Edit' : 'Simpan');
                if (el.hasClass('btn-addlink')) {
                    $('#invisible_id').val('1');

                }
                $('.modal').modal('show');

            }).catch(error => {
                console.log(error.response);
            });
        }
        $('body').on('click', '#saveBtn', function(e) {
            e.preventDefault();
            $(this).html('Sending..');
            $("#btnSubmit").attr("disabled", true);

            $.ajax({
                data: $('#my_form').serialize(),
                url: $('#my_form').attr("action"),
                type: $('#my_form').attr("method"),
                dataType: 'json',
                success: function(data) {

                    $('#my_form').trigger("reset");
                    $('.modal').modal('hide');
                    swal2(data.status, data.message);
                    table.draw();

                },
                error: function(data) {
                    swal2(data.status, data.message);

                    console.log('Error:', data);
                    $('#saveBtn').html('Save Changes');
                }
            });
            $("#btnSubmit").attr("disabled", false);

        });

        function swal2(types, titles) {
            Swal.fire({
                position: 'top-end',
                type: types,
                title: titles,
                showConfirmButton: false,
                timer: 2000
            })
        }
    </script>
    @endif
    @include('adminlte::plugins', ['type' => 'js'])
    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
    <script src="//cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
    <!-- Config language DataTables -->
    <script>
        (function($, dataTable) {
            $.extend(true, $.fn.dataTable.defaults, {
                pageLength: 25,
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: true,
                aoColumnDefs: [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                language: {
                    "emptyTable": "Data Kosong",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ Data",
                    "zeroRecords": "Tidak Ada Data yang Ditampilkan",
                    "processing": "Silahkan Tunggu...",
                    "oPaginate": {
                        "sFirst": "Awal",
                        "sLast": "Akhir",
                        "sNext": "Selanjutnya",
                        "sPrevious": "Sebelumnya"
                    },
                },


            });
        })(jQuery, jQuery.fn.dataTable);
    </script>
    @endif

    @if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
    @endif
    @yield('adminlte_js')



</body>

</html>