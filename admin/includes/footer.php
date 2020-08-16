</div><br><br>
<div class ="col-md-12 text-center">&copy; Copyright 2019-2022 Hat Bazer </div>
<script>
function updateSizes(){
  var sizeString = '';
  for (var i=1; i<=12; i++){
    if(jQuery('#size'+i).val() != ''){
      sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
    }
  }
 jQuery('#sizes').val(sizeString);
}

//ajaxing
function get_child_options(selected){
  if(typeof selected === 'undefined'){
    var selected= '';
  }
  var parentID = jQuery('#parent').val();
  jQuery.ajax({
  url: '/project/admin/parsers/child_categories.php',
  type: 'POST',
  data:{parentID : parentID, selected: selected},
  success : function(data){
    jQuery('#child').html(data);
  },
  error: function(){alart("something went wrong with the chaild options")},

  });
}
jQuery('select[name="parent"]').change(function(){
get_child_options();
});
</script>
</body>
</html>
