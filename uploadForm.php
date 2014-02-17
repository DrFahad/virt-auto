
<html>
    <head>
        <title>Image Upload Week 5</title>
        <script src="http://code.jquery.com/jquery-latest.min.js">
        </script>
        <script type="text/javascript">

            $(document).ready(function() {
                var cnt = 2;
                $("#anc_add").click(function() {
                    if ($('#tbl1 tr').length) {
                        $('#tbl1 tr').last().after('<tr><td>File [' + cnt + ']\n\
                        </td></tr><tr><td><input name="upfile[]" type="file" \n\
                        multiple="multiple" /></td></tr>');
                        cnt++;
                    } else {
                        cnt = 1;
                        $('#tbl1').append('<tr><td>File [' + cnt + ']</td></tr>\n\
                        <tr><td><input name="upfile[]" type="file" \n\
                        multiple="multiple" /></td></tr>');
                        cnt++;
                    }

                });

                $("#anc_rem").click(function() {
                    $('#tbl1 tr:last-child').remove();
                    $('#tbl1 tr:last-child').remove();
                    cnt--;
                });

                $('body').on('click', '#btnSubmit', function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form')[0]);

        $.ajax({
            url: 'upload2.php',
            type: 'POST',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            success: function (data) {
                $('#created').html(data);
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        });
        return false;
});
            });
        </script>

    </head>
    <?php
    ini_set('max_execution_time', 300);
    ?>
    <body>
        <form enctype="multipart/form-data" id="uploadForm" action="upload2.php" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="return_bytes(ini_get('upload_max_filesize'))" />
            <!-- Name of input element determines name in $_FILES array -->
            <!-- Upload this file: <input name="upfile[]" type="file" multiple="multiple" /> -->

            <br /><br />
            <table  id="tbl1" border="0">
                <tr><td>File [1]</td></tr>
                <tr><td><input name="upfile[]" type="file" multiple="multiple" /></td></tr>
            </table>
            <br/>
            <a href="javascript:void(0);" id='anc_add'>Add File</a>
            <a href="javascript:void(0);" id='anc_rem'>Remove File</a>
            <br/><br/><br/>
            <input type="submit" id="btnSubmit" value="Upload File" />

            
        </form>
        <div id="created"></div>
    </body>
</html>

