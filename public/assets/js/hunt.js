    function AjaxCall(url, ajaxMethod, callBack, data)
    {
        var requestData = (data != "undefined") ? data : {}; // set request data to data if exists
        var request = $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            method: ajaxMethod,
            data: requestData,
            dataType: "text",
            beforeSend: function() {
                $("#spinner-div").show(); //Load button clicked
            },
        });

        request.done(function (msg) {
            callBack(JSON.parse(msg));  //execute the callback function
            $("#spinner-div").hide(); //Load button clicked
        });

        request.fail(function (jqXHR, textStatus) {
            alert("Request failed: " + textStatus);
            $("#spinner-div").hide(); //Load button clicked
        });
    }

    function AlertCall(data, Promise = null)
    {
        toastr.success(data.msg);
        Promise
    }

    function DeleteButtonCall(url)
    {
        let deleteButton = {
            text: 'DELETE',
            url: url,
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert('No records selected')

                    return
                }

                if (confirm('Are you sure?')) {
                    $.ajax({
                        headers: {'x-csrf-token': _token},
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }})
                        .done(function () { location.reload() })
                }
            }
        }
        // return dtButtons.push(deleteButton)
        return deleteButton
    }

    function DataTableCall(tableClass, url, dtButton, data)
    {
        let dtOverrideGlobals = {
            buttons: dtButton,
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: url,
            columns: data,
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 100,
        };
        let table = $(tableClass).DataTable(dtOverrideGlobals);
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    }
