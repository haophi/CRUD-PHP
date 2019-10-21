$(document).ready(function(){
    $("#cancel-button").click(function(){
        window.location = "index.php";
    })

    $('#multy-delete').click(function(){
        var txt;
        if (confirm("Bạn có chắc muốn xoá hay không")) {
            $('#main-form').submit();
        } else {
            alert("Bạn đã chọn cancel");
        }
    });
    $('#check-all').change(function(){
        var checkStatus = this.checked;
        $('#main-form').find(':checkbox').each(function(){
            this.checked = checkStatus;
        })
    })
    $('.success, .notice, .error').click(function(){
        $(this).slideUp("slow");
    })

})
