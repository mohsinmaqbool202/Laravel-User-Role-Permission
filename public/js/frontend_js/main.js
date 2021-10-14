/*price range*/

if ($.fn.slider) {
    $('#sl2').slider();
}

var RGBChange = function () {
    $('#RGB').css('background', 'rgb(' + r.getValue() + ',' + g.getValue() + ',' + b.getValue() + ')')
};

/*scroll to top*/

$(document).ready(function () {
    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });
});


//add item to whislist
$(document).ready(function(){

    $(".addToWishList").click(function(){

        var form_data = $(this).closest(".wishlist_form").serializeArray();
        var user_email = form_data[0].value;
        var product_id = form_data[1].value

        if(form_data[0].value == '')
        {
            // alert('Please Log in First!');
            toastr.error('Please Log in First!','Error');
            return false;
        }   

        $.ajax({
            type:'get',
            url:'/add-to-wishlist',
            data:{user_email:user_email, product_id:product_id},
            success:function(resp){
                if(resp == 'false'){
                    alert('Product is already in your wishlist');
                    // toastr.info("Product is already in your wishlist.", "info");
                }else{
                    alert('Product added to your wishlist.');
                   // toastr.success("Product added to your wishlist.", "Success");
                }
            },
            error:function(){
                alert("Error Occured");
            }
        });
    });


    //copy billing address to shipping address
    $('#copyAddress').click(function(){
        if(this.checked){
            $('#shipping_country_id').val($("#country_id").val());
            $('#shipping_name').val($('#billing_name').val());
            $('#shipping_address').val($('#billing_address').val());
            $('#shipping_city').val($('#billing_city').val());
            $('#shipping_state').val($('#billing_state').val());
            $('#shipping_pincode').val($('#billing_pincode').val());
            $('#shipping_mobile').val($('#billing_mobile').val());
        }
        else
        {
            $('#shipping_country_id').val('');
            $('#shipping_name').val('');
            $('#shipping_address').val('');
            $('#shipping_city').val('');
            $('#shipping_state').val('');
            $('#shipping_pincode').val('');
            $('#shipping_mobile').val('');
        }
    });

    //check customer current pwd
    $('#new_pwd').keydown(function(){
        var current_pwd = $('#current_pwd').val();
        $.ajax({
            type:'get',
            url:'/check-customer-pwd',
            data:{current_pwd:current_pwd},
            success:function(resp){
                if(resp == "false"){
                    $('#chkPwd').html("<font color='red'>Current Password is Incorrect.</font>");
                }else if(resp == "true"){
                    $('#chkPwd').html("<font color='green'>Current Password is Correct.</font>");
                }
            },error:function()
            {
                alert("error");
            }
        });
    });

});
