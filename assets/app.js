//...............REGISTER..................>>>>>>>>>>>>>

$("#registerForm").submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);

    let name = $("input[name='name']").val().trim();
    let email = $("input[name='email']").val().trim();
    let password = $("input[name='password']").val();
    let confirm = $("input[name='confirm_password']").val();
    let mobile = $("input[name='mobile']").val().trim();

    $(".text-danger").html("");
    let valid = true;

    if (name === "") {
        $("#name_error").html("Name is required");
        valid = false;
    }
    if (email === "") {
        $("#email_error").html("Email is required");
        valid = false;
    }
    if (password === "") {
        $("#password_error").html("Password is required");
        valid = false;
    } else if (password.length < 6) {
        $("#password_error").html("Password must be at least 6 characters");
        valid = false;
    }
    if (confirm === "") {
        $("#confirm_error").html("Confirm password is required");
        valid = false;
    } else if (password !== confirm) {
        $("#confirm_error").html("Passwords do not match");
        valid = false;
    }
    if (mobile === "") {
        $("#mobile_error").html("Mobile number is required");
        valid = false;
    }
    if (mobile !== "" && mobile.length != 10) {
        $("#mobile_error").html("Mobile must be 10 digits");
        valid = false;
    }
    if (!valid) {
        return;
    }
    Swal.fire({
        title: "Confirm Registration?",
        text: "Do you really want to register?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Register"
    }).then((result) => {
        if (result.isConfirmed) {
            let button = $("#registerBtn");
            $.ajax({

                url: "backend_register.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,

                beforeSend:function(){
                    button.html('<span class="spinner-border spinner-border-sm"></span> Registering...');
                    button.prop("disabled",true);
                },

                success: function(data) {
                    setTimeout(() => {
                        button.html('<i class="bi bi-box-arrow-in-right"></i> Register');
                        button.prop("disabled",false);
                    }, 3000);

                    if (data.trim() == "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "Registration Successful",
                            timer: 1000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            window.location = "dashboard.php";
                        }, 4000);
                    } else if (data.trim() == "email Exist") {
                        Swal.fire("Error", "Email already exists", "error");
                    } else {
                        Swal.fire("Error", data, "error");
                    }
                },
                error: function() {
                    button.html('<i class="bi bi-box-arrow-in-right"></i> Register');
                    button.prop("disabled",false);
                    
                    Swal.fire("Error", "Server Error", "error");
                }
            });
        }
    });
});

$(document).on("click",".togglePassword",function(){
    let input = $(this).siblings(".form-floating").find("input");

    if(input.attr("type") === "password"){
        input.attr("type","text");
        $(this).removeClass("bi-eye").addClass("bi-eye-slash");
    }
    else{
        input.attr("type","password");
        $(this).removeClass("bi-eye-slash").addClass("bi-eye");
    }
});

//.......mobile validation
$("#mobile").on("input", function(){
    let value = $(this).val();

    value = value.replace(/[^0-9]/g,'');
    
    if(value.length == 1 && value < 6){
        value = "";
    }
    $(this).val(value);
});


//.................LOGIN..................................>>>>>>>>>>>>>>>>>>>>

$("#loginForm").submit(function(e) {
    e.preventDefault();
    let formData = $(this).serialize();

    let email = $("input[name='email']").val().trim();
    let password = $("input[name='password']").val();

    $(".text-danger").html("");
    let valid = true;

    if (email === "") {
        $("#email_error").html("Email is required");
        valid = false;
    }
    if (password === "") {
        $("#password_error").html("Password is required");
        valid = false;
    }
    if (!valid) {
        return;
    }

    $.ajax({
        url: "login_backend.php",
        type: "POST",
        data: formData,

        success:function(response){
            if(response.trim() == "admin"){
                Swal.fire({
                    icon:"success",
                    title:"Login Successful",
                    timer:1500,
                    showConfirmButton:false
                });
                setTimeout(()=>{
                    window.location="admin_dashboard.php";
                },1500);
            }
            else if(response.trim() == "user"){
                Swal.fire({
                    icon:"success",
                    title:"Login Successful",
                    timer:1500,
                    showConfirmButton:false
                });
                setTimeout(()=>{
                    window.location="dashboard.php";
                },1500);
            }
            else{
                Swal.fire({
                    icon:"error",
                    title:"Login Failed",
                    html:response
                });
            }
        }
    });
});

// $(document).on("click",".togglePassword",function(){
//     let input = $(this).siblings(".form-floating").find("input");

//     if(input.attr("type") === "password"){
//         input.attr("type","text");
//         $(this).removeClass("bi-eye").addClass("bi-eye-slash");
//     }
//     else{
//         input.attr("type","password");
//         $(this).removeClass("bi-eye-slash").addClass("bi-eye");
//     }
// });


//.............ADMIN DASHBOARD..............>>>>>>>>>>>>>>>>>>>>>>>>>>>

$(document).ready(function(){

    //......delete
    $(document).on("click",".deleteUser",function(){
        let userId = $(this).data("id");
        let button = $(this);

        Swal.fire({
            title: "Delete User?",
            text: "Are you sure?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes Delete"
        }).then((result)=>{
            if(result.isConfirmed){
                button.html('<span class="spinner-border spinner-border-sm"></span> Deleting...');
                button.prop("disabled",true);

                setTimeout(function(){
                    $.ajax({
                        url:"delete_user.php",
                        type:"POST",
                        data:{id:userId},

                        success:function(response){
                            if(response.trim() === "success"){
                                Swal.fire({
                                    icon:"success",
                                    title:"User Deleted",
                                    timer:1500,
                                    showConfirmButton:false
                                });
                                button.closest("tr").remove();
                            }else{
                                Swal.fire("Error","Delete failed","error");
                            }
                        }
                    });
                },1000);
            }
        });
    });

    //...search

    $("#searchUser").on("input", function(){
        let value = $(this).val().toLowerCase();

        $("#userTable tbody tr").each(function(){
            let name = $(this).find("td:eq(2)").text().toLowerCase();
            let email = $(this).find("td:eq(3)").text().toLowerCase();
            let mobile = $(this).find("td:eq(4)").text().toLowerCase();

            if(name.includes(value) || email.includes(value) || mobile.includes(value)){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });

    // ........pagination

    let rowsPerPage = 5;
    let rows = $("#userTable tbody tr");
    let rowsCount = rows.length;
    let pageCount = Math.ceil(rowsCount / rowsPerPage);

    let pagination = $("#pagination");
    for(let i=1;i<=pageCount;i++){
        pagination.append('<li class="page-item"><a href="#" class="page-link">'+i+'</a></li>');
    }
    rows.hide();
    rows.slice(0,rowsPerPage).show();

    $("#pagination").on("click","a",function(e){
        e.preventDefault();

        let page = $(this).text();
        let start = (page-1)*rowsPerPage;
        let end = start + rowsPerPage;
        rows.hide().slice(start,end).show();
    });
});


//...........USER DASHBOARD...............>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

//......update profile
$("#editProfileForm").submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: 'update_profile.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,

        success: function(response) {
            $("#editMsg").html(response);

            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function() {
            $("#editMsg").html('<div class="alert alert-danger">Update Failed</div>');
        }
    });
});

//.....delete account
$("#confirmDelete").click(function () {
    let userid = $("#userid").val();
    let button = $(this);

    Swal.fire({
        title: "Delete Account?",
        text: "Are you sure you want to delete your account?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            button.html('<span class="spinner-border spinner-border-sm"></span> Deleting...');
            button.prop("disabled",true);

            setTimeout(function(){
                $.ajax({
                    url: "delete_account.php",
                    type: "POST",
                    data: { id: userid },

                    success: function (response) {
                        if (response.trim() == "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Account Deleted",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            setTimeout(function () {
                                window.location = "logout.php";
                            }, 2000);
                        } else {
                            Swal.fire("Error", "Delete failed", "error");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "Server error", "error");
                    }
                });
            },1000);
        }
    });
});

//........eye toggle

$(document).ready(function(){
    $("#eyeIcon").click(function(){
        let password = $("#password");
        if(password.attr("type") === "password"){
            password.attr("type","text");
            $(this).removeClass("bi-eye").addClass("bi-eye-slash");
        }else{
            password.attr("type","password");
            $(this).removeClass("bi-eye-slash").addClass("bi-eye");
        }
    });
});

//......send mail
$("#sendMailForm").submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
        url: "send_user_mail.php",
        type:"POST",
        data:formData,
        contentType: false,
        processData: false,

        success:function(response){
            $("#mailMsg").html('<div class="alert alert-success">'+response+'</div>');
        }
    });
});


//..........FORGOT PASSWORD...............>>>>>>>>>>>>>>>>>>>>>>>>>

$("#forgotForm").submit(function(e){
    e.preventDefault();
    let email = $("input[name='email']").val().trim();
    $(".text-danger").html("");
    let valid = true;

    if(email === ""){
        $("#email_error").html("Email is required");
        valid = false;
    }
    if(!valid) return;

    $.ajax({
        url:"forgot.php",
        type:"POST",
        data:{email:email},

        success:function(data){
            data = $.trim(data);
            if(data.includes("OTP sent")){
                $("#msg").html(data);
                $("#otpBox").show();
                startTimer();
            }
            else{
                $("#msg").html(data);
            }
        }
    });
});

$("#verifyOtp").click(function(){
    let otp = $("#otp").val().trim();
    let email = $("input[name='email']").val().trim();

    $(".text-danger").html("");
    let valid = true;
    if(otp === ""){
        $("#otp_error").html("Please Enter OTP");
        valid = false;
    }
    if(!valid) return;
    $.ajax({
        url:"verify_otp.php",
        type:"POST",
        data:{otp:otp,email:email},

        success:function(data){
            data = $.trim(data);
            if(data === "success"){
                window.location="reset_password.php?email="+email;
            }
            else{
                $("#msg").html(data);
            }
        }
    });
});

let timer = 30;
let countdown;
$("#resendOtp").click(function(){
    let email = $("input[name='email']").val().trim();
    if(email === ""){
        $("#email_error").html("Enter email first");
        return;
    }

    $.ajax({
        url:"forgot.php",
        type:"POST",
        data:{email:email},

        success:function(){
            $("#msg").html("<div class='alert alert-info'>OTP resent successfully</div>");
            timer = 30;
            startTimer();
        }
    });
});

function startTimer(){
    clearInterval(countdown);
    $("#resendOtp").prop("disabled",true);

    countdown = setInterval(function(){
        timer--;

        $("#resendOtp").text("Resend OTP ("+timer+"s)");

        if(timer <= 0){
            clearInterval(countdown);
            $("#resendOtp").prop("disabled",false);
            $("#resendOtp").text("Resend OTP");

            timer = 30;
        }
    },1000);
}


//..................GET OTP...............>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$("#otpForm").submit(function(e){
    e.preventDefault();
    $.ajax({
        url: "send_otp.php",
        type: "POST",
        data: $(this).serialize(),

        success: function(response){
            $("#message").html(response);
        }
    });
});


//............RESET PASSWORD................>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

$("#resetPassword").submit(function(e) {

    let password = $("input[name='password']").val();
    let confirm = $("input[name='confirm_password']").val();

    $(".text-danger").html("");
    let valid = true;

    if (password === "") {
        $("#password_error").html("Password is required");
        valid = false;
    } else if (password.length < 6) {
        $("#password_error").html("Password must be at least 6 characters");
        valid = false;
    }
    if (confirm === "") {
        $("#confirm_error").html("Confirm password is required");
        valid = false;
    } else if (password !== confirm) {
        $("#confirm_error").html("Passwords do not match");
        valid = false;
    }
    if(!valid){
        e.preventDefault();

    }
});

// $(document).on("click",".togglePassword", function(){
//     let input = $(this).siblings(".form-floating").find("input");
//     let icon = $(this).find("i");

//     if(input.attr("type") == "password") {
//         input.attr("type", "text");
//         icon.removeClass("bi-eye").addClass("bi-eye-slash");
//     } else {
//         input.attr("type", "password");
//         icon.removeClass("bi-eye-slash").addClass("bi-eye");
//     }
// });
