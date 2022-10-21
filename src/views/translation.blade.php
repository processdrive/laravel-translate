<html>
    <head>
        <title>Translation</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    </head>
    <body>
        <div class="container mt-5">
            <div class="float-left">
                <button class="btn btn-primary btn-sm language">New Language</button>
                <button class="btn btn-primary btn-sm create">New Key</button>
            </div>
            <div class="float-right">
                <select class="form-select form-select-lg mt-2 select2" onchange="languageChange()" id="lang">
                    @foreach($language as $code => $lang )
                        <option value="{{ $code }}">{{ $lang }}</option>
                    @endforeach
                </select>
            </div>
            <h2 class="mb-4 text-center">Translation</h2>
            <table class="table table-bordered yajra-datatable">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Language</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <select class="form-control form-select-lg select2"  id="new_lang" style="width: 300px !important;">
                    @foreach($new_lang as $code => $lang )
                        <option value="{{ $code }}">{{ $lang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="modal_close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="newLanguage()">Update Language</button>
            </div>
            </div>
        </div>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter" style="display: none;"></button>
    </body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://malsup.github.io/jquery.blockUI.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2();
            $.fn.modal.Constructor.prototype._enforceFocus = function() {};
            loadDataTable();

        });
        function loadDataTable () {
            var lang = sessionStorage.getItem("translation_table") ?? "{{ array_keys($language)[0] }}"
            var table = $('.yajra-datatable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: { "url": "{{ route('translation.getdata') }}?lang="+lang, 
                                    "type": "POST" 
                                },
                            columns: [
                                {data: 'group', name: 'group'},
                                {data: 'key', name: 'key'},
                                {data: 'text', name: 'text'},
                                {
                                    data: 'action', 
                                    name: 'action', 
                                    orderable: false, 
                                    searchable: false
                                },
                            ]
                        });
        }
        function languageChange () {
            sessionStorage.setItem("translation_table", document.getElementById('lang').value);
            location.reload()
        }
        document.getElementById('lang').value = sessionStorage.getItem("translation_table") ?? "{{ array_keys($language)[0] }}"

        $(document).on('click', '.update', function() {
            var store_data = {'edit_id' : $(this).attr('data-attr'), 'lang' : sessionStorage.getItem("translation_table") ?? "{{ array_keys($language)[0] }}"}
            var column = ['group', 'key', 'text']
            $(this).parent().siblings('td').each(function(index) {
                store_data[column[index]] = $("#"+$($(this).html()).attr('id')).val()
            });
            $.ajax({
                url: "{{ route('translation.update') }}",
                type: "POST",
                data: store_data
            }).done((data) =>{
                if (data) {
                    swal("Your data updated successfully!", {
                    icon: "success",
                    });
                    $('.yajra-datatable').DataTable().ajax.reload();
                }
            });
            $(this).siblings('.edit').show();  
            $(this).siblings('.cancel').hide();  
            $(this).siblings('.delete').show();  
            $(this).hide();  
        });

        $(document).on('click', '.edit', function() {  
            var column = ['group', 'key', 'text']
            var id = $(this).attr('data-attr')
            $(this).parent().siblings('td').each(function(index) {  
                var content = $(this).html();
                $(this).html('<input class="form-control" id="'+column[index]+'_'+id+'" value="' + content + '" />');  
            });  
            $(this).siblings('.update').show();  
            $(this).siblings('.cancel').show();  
            $(this).siblings('.delete').hide();  
            $(this).hide();                   
        });

        $(document).on('click', '.delete', function() {  
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ route('translation.delete') }}",
                        type: "POST",
                        data: {'id' : $(this).attr('data-attr')}
                    }).done((data) =>{
                        if (data) {
                            swal("Your data Deleted successfully!", {
                            icon: "success",
                            });
                            $('.yajra-datatable').DataTable().ajax.reload();
                        }
                    });
                }
            });
        });

        $(document).on('click', '.cancel', function() {
            $(this).parent().siblings('td').each(function() {  
                $(this).html($($(this).html()).val());  
            }); 
            $(this).siblings('.edit').show();  
            $(this).siblings('.delete').show();  
            $(this).siblings('.update').hide();  
            $(this).hide();
        });

        $(document).on('click', '.create', () => {
            $(".create").attr('disabled', true)
            $(".yajra-datatable").find("tbody tr:first").before(`<tr>
                <td><input class="form-control" id="group_new"/></td>
                <td><input class="form-control" id="key_new"/></td>
                <td><input class="form-control" id="text_new"/></td>
                <td>
                    <a href="javascript:void(0)" class="save btn btn-primary btn-sm"><i class="fa fa-save"></i></a>
                    <a href="javascript:void(0)" class="save_cancel btn btn-danger btn-sm"><i class="fa fa-close"></i></a>
                </td>
            </tr>`); 
        })

        $(document).on('click', '.save_cancel', () => {
            $('.yajra-datatable').DataTable().ajax.reload()
            $(".create").attr('disabled', false)
        });

        $(document).on('click', '.save', () => {
            var store_data = { lang : sessionStorage.getItem("translation_table") ?? "{{ array_keys($language)[0] }}", group: $('#group_new').val(),
                                key: $('#key_new').val(), text: $('#text_new').val() }
            $.ajax({
                url: "{{ route('translation.store') }}",
                type: "POST",
                data: store_data
            }).done((data) =>{
                if (data) {
                    swal("Your data saved successfully!", {
                    icon: "success",
                    });
                    $('.yajra-datatable').DataTable().ajax.reload();
                    $(".create").attr('disabled', false)
                }
            });
        });
        
        $(document).on('click', '.language', () => {
            $('#exampleModalCenter').modal('show');
        });
        function newLanguage () {
            $('#modal_close').click()
            $.blockUI({ message: '<span>Please wait a min. your language is convert to google translate.<span>'});
            $.ajax({
                url: "{{ route('translation.newlanguage') }}",
                type: "POST",
                data: {to_lang : document.getElementById('new_lang').value, from_lang : sessionStorage.getItem("translation_table") ?? "{{ array_keys($language)[0] }}"}
            }).done((data) =>{
                if (data) {
                    $.unblockUI(); 
                    location.reload()
                }
            });
        }
    </script>
</html>