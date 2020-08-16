</div><br><br>
<div class ="text-center" id="footer">&copy; Copyright 2019-2022 Hat Bazer </footer>

<script>
jQuery(window).scroll(function(){
var vscroll=jQuery(this).scrollTop();
jQuery('#logotext').css({
"transform" : "translate(0px,"+vscroll/2+"px)"
});
var vscroll=jQuery(this).scrollTop();
jQuery('#back-flower').css({
"transform" : "translate(0px,"+vscroll/5+"px, -"+vscroll/12+"px)"
});
var vscroll=jQuery(this).scrollTop();
jQuery('#fore-flower').css({
"transform" : "translate(0px,"+vscroll/2+"px)"
});
});
function detailsmodal(id){
  var data = {"id" : id};
  jQuery.ajax({
    url :'/project/includes/detailsmodal.php',
    method : "post",
    data : data,
    success : function(data){
      jQuery('body').append(data);
      jQuery('#details-modal').modal('toggle');
    },
    error : function(){
      alert("Somthing is wrong..!!")
    }
  });
}



function add_to_cart(){
  jQuery('#modal_errors').html("");
  var size = jQuery('#size').val();
  var quantity = jQuery('#quantity').val();
  var available = jQuery('#available').val();
  var error = '';
  var data = jQuery('#add_product_form').serialize();
  if(size == '' || quantity == '' || quantity == 0){
    error += '<p class="text-danger text-center">You must choose a size and quantity</p>';
    jQuery('#modal_errors').html(error);
    return;
  }else if (quantity > available) {
    error += '<p class="text-danger text-center">There are only '+available+' available.</p>';
    jQuery('#modal_errors').html(error);
    return;
  }else{
    jQuery.ajax({
      url : '/project/admin/parsers/add_cart.php',
      method : 'post',
      data : data,
      success : function(){
        location.reload();
      },
      error : function(){alert("something went wrong");}
    });
  }
}

</script>
</body>
</html>
