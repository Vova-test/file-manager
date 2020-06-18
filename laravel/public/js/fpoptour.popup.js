$(document).ready(function() {
    //Appearance PopUp
    $(".popup-show").click(function(){
        $("#modal").show();
    });
    //Hidden PopUp
    $(".close").click(function(){
        $(".modal").hide();
    });
    //Phone focus
    $("input[name=phone]").focus(function() {
        $("#phone-error").hide();
        $("#phone-error").html('');
    });
    //First name focus
    $("input[name=first-name]").focus(function() {
        $("#first-name-error").hide();
        $("#first-name-error").html('');
    });
    //Phone number mask
    $("#phone").mask("+38(999) 999-9999");  
    //Send
    $(".booking-send").click(function(){

        let errorIndicator = 1;
        let errorMessages = {
            "phone": "Phone Number is invalid",
            "first-name": "First-name is invalid",
        };

        if ($("input[name=phone]").val()<1) {
            $("#phone-error").html(errorMessages['phone']);
            $("#phone-error").show();
            errorIndicator = 0;
        }

        if ($("input[name=first-name]").val()<1) {
            $("#first-name-error").html(errorMessages['first-name']);
            $("#first-name-error").show();
            errorIndicator = 0;
        }

        if (errorIndicator != 1) {
            return;
        }

        let url = $("#tour").attr('data-value');
        let st = $('meta[name="csrf-token"]').attr('content');
        let credentials = {
            'first-name': $("input[name=first-name]").val(),
            'last-name': $("input[name=last-name]").val(),
            'phone': $("input[name=phone]").val()
        }
        let tour = $("#tour").attr('data-value');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': st
            },
            type: "POST",
            url: url,
            data: credentials,
            success: function(data)
            {
                $("#modal").hide();
                $("input[name=first-name]").val('');
                $("input[name=last-name]").val('');
                $("input[name=phone]").val('');
                $("#alert-text").text(data);
                $("#alert").show();
            },
            error: function(xhr)
            {
                console.log('err');
                if (xhr.responseJSON.message != "The given data was invalid.") {
                    $("#phone-error").html(xhr.responseJSON.message);
                } else {
                    errors = xhr.responseJSON.errors;
                    $.each(errors, function(key) {
                        $(`#${errorMessages[key]}-error`).html(errorMessages[key]);
                        $(`#${errorMessages[key]}-error`).show();
                    });
                }

                errorIndicator = 0;
            }
        });
        if (errorIndicator != 1) {
            
        }
    });
});