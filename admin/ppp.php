<?php
require $_SERVER['DOCUMENT_ROOT'].'/project/core/init.php';
if(!is_logged_in()){
  login_error_redirect();
}
if(!has_permission('admin')){
  permission_error_redirect('index.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
$dbpath = '';

if (isset($_GET['add']) ||isset($_GET['edit'])){
$parentQuery = $db->query("SELECT * FROM categories WHERE  parent = 0 ORDER BY category");
$title =((isset($_POST['title'])  && $_POST['title']!='')?sanitize($_POST['title']):'');
$parent =((isset($_POST['parent'])  && $_POST['parent']!='')?sanitize($_POST['parent']):'');
$category =((isset($_POST['child'])  && $_POST['child']!='')?sanitize($_POST['child']):'');
$price =((isset($_POST['price'])  && $_POST['price']!='')?sanitize($_POST['price']):'');
$last_price =((isset($_POST['last_price'])  && $_POST['last_price']!='')?sanitize($_POST['last_price']):'');
$description =((isset($_POST['description'])  && $_POST['description']!='')?sanitize($_POST['description']):'');
$sizes =((isset($_POST['sizes'])  && $_POST['sizes']!='')?sanitize($_POST['sizes']):'');
$sizes= rtrim($sizes,',');
$saved_image = '';

if(isset($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $productResults =$db->query("SELECT * FROM products WHERE id='$edit_id'");
  $product= mysqli_fetch_assoc($productResults);
  if(isset($_GET['delete_image'])){
    $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];echo $image_url;
    unlink($image_url);
    $db->query("UPDATE products SET image ='' WHERE id='$edit_id'");
    header('Location: products.php?edit='.$edit_id);
  }
  $category=((isset($_POST['child']) && $_POST['child']!='')?sanitize($_POST['child']):$product['categories']);
  $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']): $product['title']);
  $parentQ = $db->query("SELECT *FROM categories WHERE id='$category' ");
  $parentResult =mysqli_fetch_assoc($parentQ);
  $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']): $parentResult['parent']);
//  $child = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']): $product['child']);
  $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']): $product['price']);
  $last_price = ((isset($_POST['last_price']) && !empty($_POST['last_price']))?sanitize($_POST['last_price']): $product['last_price']);
  $description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']): $product['description']);
  $sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']): $product['sizes']);
  $saved_image = (($product['image'] != '')? $product['image']: '' );
  $dbpath = $saved_image;

}
//echo "dfercre";
if($_POST) {
//	$title =((isset())?sanitize($_POST['title']):'');
	$categories = sanitize($_POST['child']); // have to check file upload part..!!
	$price = sanitize($_POST['price']);
	$last_price = sanitize($_POST['last_price']);
	$sizes = sanitize($_POST['sizes']);
	$description = sanitize($_POST['description']);
	$dbpath = '';
	$errors = array();
	echo "dfercre";

	if(!empty($_POST['sizes'])) {
		$sizeString = sanitize($_POST['sizes']);
		$sizeString = rtrim($sizeString,',');
		$sizesArray= explode(',',$sizeString);
    $sizesArray = array();
		$sArray= array();
		$qArray = array();
		foreach($sizesArray as $ss){
			$s = explode(':',$ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
		}
	}else{$sizesArray= array();}
	$required = array('title','price','parent','child','sizes');
	/*foreach ($required as $field) {
		if($_POST[$field] == ''){
			$errors[]='All fields with and acstrisk are required.';
			break;

		}
	}*/
		 if($_POST["addp"]){
			var_dump($_FILES);
			//$photo= $_FILES['photo']['name'];
			$name = $_FILES['photo']['name'];
			//$nameArray = explode('.',$name);
			//$fileName = $nameArray[0];
			//$fileExt = $nameArray[1];
			//$mime= explode('/',$photo['type']);
			//$mimeType = $mime[0];
			//$mimeExt = $mime[1];
			$tmpLoc =$_FILES['photo']['tmp_name'];

		  $dbpath ='/project/images/products/'.$name;

/*
			$fileSize = $photo['size'];
			$allowed = array('png','jpg','jpge','gif');
			$uploadName =md5(microtime()).'.'.$fileExt;
			$uploadPath ='images/products/'.$uploadName;
			$dbpath ='images/products/'.$uploadName;
			echo $dbpath;
			if($mimeType != 'image'){
				$errors[] = 'the file must be a image.';
			}
			if(!in_array($fileExt, $allowed)){
				$errors[] ='The photo extention must be png ,jpg,jpge,if';
			}
		if($fileSize > 15000000){
			$errors[] ='File size must be under 15md';
		}
		if($fileExt != $mimeExt &&($mimeExt =='jpeg' && $fileExt !='jpg') ){
			$errors[]= 'file extention doesnot match the file';
		}
*/

	}
	if(!empty($errors)){
		echo display_errors($errors);
	}else{
		//upload file and insert into databae
	//	move_uploaded_file($tmpLoc,$dbpath);

		//echo $categories;

		$insertSql = "INSERT INTO products(title,price,last_price,categories,sizes,image,description)
				VALUES ('$title','$price','$last_price','$category','$sizes','$dbpath','$description')";
		//$insertSql ="INSERT INTO `products` (`id`, `title`, `price`, `last_price`, `categories`, `image`, `description`, `featured`, `sizes`, `deleted`)
		 //VALUES (NULL, 'sdf', '2.44', '2.33', '2', '', '', '0', '', '0')";
    if(isset($_GET['edit'])){
      $insertSql = "UPDATE products SET title = '$title',price = '$price',last_price ='$last_price',categories = '$category',
      sizes = '$sizes', image = '$dbpath', description = '$description' WHERE id = '$edit_id'";
    }

    $db->query($insertSql);


	}
}
?>
	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add a new') ;?>Product</h2><hr>
	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id : 'add=1');?>"method="POST" enctype="multipart/form-data">
	  <div class="form-group col-md-3">
	    <lable for="title">Title*:</lable>
		<input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
	</div>
	<div class="form-group col-md-3">
		    <lable for="parent">Parent Category*:</lable>
			<select class="form-control" id="parent" name="parent">
			<option value=""<?=(($parent == '')? 'selected':'');?>></option>
			<?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
				<option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?'selected':'');?>><?=$p['category'];?> </option>
			<?php endwhile; ?>
			</select>
			</div>
			<!-- For child categories -->
			<div class="form-group col-md-3">
				 <lable for="child">Child Category*:</lable>
				 <select id="child" name="child" class="form-control">
				 </select>
		</div>
		<!-- For Price -->
		<div class="form-group col-md-3">
	     <lable for="price">price*:</lable>
		   <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
	    </div>
<!-- For last Price -->
			<div class="form-group col-md-3">
				     <lable for="price">Last price:</lable>
					 <input type="text" id="last_price" name="last_price" class="form-control" value="<?=$last_price;?>">
				    </div>
						<!-- For sizes -->
		<div class="form-group col-md-3">
		 <lable>Quantity & Sizes*:</lable>
		 <button class="btn btn default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
		 </div>

		 <div class ="form-group col-md-3">
			 <label for ="sizes">Sizes & Qty Preview</lable>
				 <input type = "text" class="form-control" name="sizes" id="sizes" value="<?=$sizes;?>"readonly>
     </div>


		<div class="form-group col-md-6">
      <?php if($saved_image != ''): ?>
      <div class="$saved_image"><img src="<?=$saved_image;?>" alt="saved image"/><br/>
       <a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
      </div>
        <?php else: ?>
       <lable for="photo">Product Photo*:</lable>
       <input type="file" name="photo" id="photo" class="form-control"
       <?php endif; ?>
		</div>

		<div class="form-group col-md-6">
		  <lable for="description">Description:</lable>
		  <textarea id="description" name="description" class="form-control" rows="6"><?=$description;?> </textarea>
		  </div>

		  <div class="form-group col-right">
        <a href="products.php" class="btn btn-default">Cancel</a>
		  <input type="submit" name = "addp" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?> Product" class=" btn btn-success pull-right">
		   </div> <div class="clearfix"></div>
	</form>
	<!-- Modal -->
	<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
				<div class="modal-body">
			 <div class="container-fluid">
				 <?php for($i=1;$i<= 12;$i++): ?>
				 <div class="from-group col-md-4">
					 <label for="size<?=$i;?>">Size:</label>
				 <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
			 </div>
			 <div class="from-group col-md-2">
					 <label for="qty<?=$i;?>">Quantity:</label>
				 <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
			 </div>
			 <?php endfor; ?>
			 </div>
				</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick = "updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Savechanges</button>
      </div>
    </div>
  </div>
</div>


<?php }
else{
$sql = "SELECT * FROM products WHERE deleted =0";
$presults = $db->query($sql);
if (isset($_GET['featured'])) {
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $featuredsql = "UPDATE products SET featured ='$featured' WHERE id ='$id'";
  $db->query($featuredsql);
   header('Location:products.php');
}
?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>
<hr>
<table class ="table table-bordered table-condensed table-striped">
  <thead><th></th><th>Product</th><th>price</th><th>Categories</th><th>Featured</th><th>Sold</th></thead>
  <tbody>
    <?php while($product = mysqli_fetch_assoc($presults)):
      $childID = $product['categories'];
			$catSql="SELECT * FROM categories WHERE id = '$childID'";
			$result = $db->query($catSql);
			$child= mysqli_fetch_assoc($result);
			$parentID = $child['parent'];
			$pSql = "SELECT * FROM categories WHERE id = '$parentID'";
			$presult=$db->query($pSql);
			$parent = mysqli_fetch_assoc($presult);
			$category =$parent['category'].'-'.$child['category'];
			 ?>
      <tr>
        <td>
          <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon-remove"></span></a>
        </td>
        <td><?=$product['title'];?></td>
          <td><?=money($product['price']);?></td>

					<td><?=$category;?></td>

					<td><a href="products.php?featured=<?=(($product['featured']== 0)?'1':'0');?>&id=<?=$product['id'];?>" class= "btn btn-xs btn-default">
		  <span class="glyphicon glyphicon-<?=(($product['featured']==1)?'minus':'plus');?>"></span>

		  </a>&nbsp <?=(($product['featured']== 1)?'Featured Product':'');?></td>
          <td>0</td>

        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php } include 'includes/footer.php'; ?>
  <script>
  //annonymous function
  jQuery('document').ready(function(){
    get_child_options('<?=$category;?>');

  });
  </script>
