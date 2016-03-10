<?php 
	class NewSession {
		private $user,$pass;
		
		function __construct($user="",$pass="") {
			$this->user = $user;
			$this->pass = $pass;

		}
		
		function getUsername() {
			return $this->user;
		}
		
		function getPassword() {
			return str_replace($this->pass,"******",$this->pass);
		}

		function getHeader() {
			include "includes/nav.inc";
		}

		function getFooter() {
			include "includes/footer.inc";
		}
		
	}

	class DatabaseResults {
		private $connect,$result_onsale,$result,$result_cart,$result_all;
		
		function __construct() {
			$this->connect = $connect;
			$this->result_onsale = $result_onsale;
			$this->result = $result;
			$this->result_cart = $result_cart;
			$this->result_all = $result_all;

		}

		function connectToDatabase() {
			$this->connect = mysqli_connect("localhost", "axb4898", "fr1end", "axb4898");
            //select items on sale
            $this->result_onsale = mysqli_query($this->connect, "select * from cookies where saleprice != 0");
            //select all items
            $this->result_all = mysqli_query($this->connect, "select * from cookies");

            $this->result = mysqli_query($this->connect, "select * from cookies where saleprice = 0");	

            $this->result_cart = mysqli_query($this->connect, "select * from cookies_cart");

		}

		function getItemsOnSale() {
			$data = array();

			echo '<div class="row">';
            while ($row = mysqli_fetch_row($this->result_onsale)) {
            	
            	$data = $row;
            	//print json_encode($row);
            	echo '<div class="col-sm-4"><div class="block">';
		        print "<h3 class='sale_header'>".$data[0]."</h3>";
		        print "<p class='sale_header'> REG PRICE: $".number_format((float)$data[2], 2, '.', '')."<br/>";
		        print "<b>SALE: $".number_format((float)$data[3], 2, '.', '')."</b>";
		        if($data[5]!=0) {
		        	echo '<br/><small class="center"> Only '.$data[5].' left! </small>';
		        }
		        echo '</p>';
		        print '<img class="sale_img" src="img/'.$data[4].'"/><br/>';

		        if($data[5]!=0) {
		        	echo "<div class='center'><a class='button button_home' type='button' value='Add to Cart' name='submit' href='cart.php?added=yes&data=".$data[0]."&desc=".$data[1]."&price=".$data[3]."'>Add to Cart</a></div>";
		    	} else {
		    		echo '<div class="red center"><p>Sold Out!</p></div>';
		    	}

		    	print "<br/><small>".$data[1]."</small>";
		        
            	echo "</div></div>";
            }
            echo "</div>";
		}

		function getAllItems() {
			$data = array();

            while ($row = mysqli_fetch_row($this->result)) {
            	$data = $row;
            	echo '<div class="z">';
			    print '<div class="row"><div class="col-sm-3"><img class="reg_img" src="img/'.$data[4].'"/><br/></div>';
			    
			    print '<div class="col-sm-5"><h3>'.$data[0]."</h3>";
			    print "<small>".$data[1]."</small><br/>";
			    print "<b>PRICE: $".number_format((float)$data[2], 2, '.', '')."</b>";
			    print "</div>";
			    
			    
			    echo '<div class="col-sm-4 center">';

            	if($data[5]!=0) {
            		echo "<br/><a class='button' type='button' value='Add to Cart' name='submit' href='cart.php?data=".$data[0]."&desc=".$data[1]."&price=".$data[2]."'>Add to Cart</a><br/><br/>";
            		echo 'Only '.$data[5].' left!';
            	} else {
            		echo '<span class="red">Sold Out!</span>';
            	}
            	echo "</div></div><hr/></div>";
            }
		}

		function getCartItems() {
			$data = array();
 			
            while ($row = mysqli_fetch_row($this->result_cart)) {
            	$data = $row;

			    print '<div class="row"><div class="col-xs-1">'.$data[3].'</div><div class="col-xs-11"><h4>'.$data[0]." </h4>".$data[1]." <br/>Price: ".number_format((float)$data[2], 2, '.', '').'<hr/></div></div>';
            }

            if($data[0] == null) {
            	print "<span class='red center'>No Cookies in Your Cart :-(</span>";
            }
		}

		function addToCart() {
			//cart item info
			echo $_GET['data'];
			echo $_GET['desc'];
			echo $_GET['price'];
			$item = $_GET['data'];
			$desc = $_GET['desc'];
			$price = $_GET['price'];
			
			if (isset($_GET['data'])) {
				$data = array();
				//put item in cart
				$query = "INSERT INTO cookies_cart (product, description, price, qty) VALUES ('".$item."', '".$desc."', '".$price."', 1);";
  				mysqli_query($this->connect, $query);
  				//update quantity
  				$query = "UPDATE cookies SET qty=qty-1 WHERE product='".$item."'";
  				mysqli_query($this->connect, $query);
  				header('Location: cart.php');
  			}
			return $item;
		}

		function getTotal() {
			$q = mysqli_query($this->connect, "select price from cookies_cart");;
  			$data = array();
 			$sub = 0.0;

            while ($row = mysqli_fetch_row($q)) {
            	$data = $row;
            	$sub += $data[0];
            }

			$tax = $sub*0.08;
			$total = $sub+$tax;
 
			echo '<div class="row"><div class="col-xs-6 right">';
			echo "Subtotal: <br/>";
			echo "Tax: <br/>";
			echo "Total:";
			echo '</div><div class="col-xs-6">';
			echo number_format((float)$sub, 2, '.', '')."<br/>";
			echo number_format((float)$tax, 2, '.', '')."<br/>";
			echo number_format((float)$total, 2, '.', '');
			echo "</div></div>";
			if($sub!=0.0) {
				echo "<br/><br/><a class='button' href='cart.php?empty=yes'>Complete Order</a><br/>";
				echo "<br/><br/><a class='button_red button_secondary' href='cart.php?empty=yes'>Empty Cart</a><br/><br/>";
			}
            return $total;
		}

		function emptyCart() {
			if(isset($_GET['empty'])) {
				$query = "DELETE FROM cookies_cart WHERE qty > 0";
  				mysqli_query($this->connect, $query);
  				header('Location: cart.php');
			}
		}

		function showNotification() {
			if(isset($_GET['add'])) {
				echo "<div class='container row notification'>Item Added</div>";
			} elseif(isset($_GET['edit'])) {
				echo "<div class='container row notification'>Item Updated</div>";
			} elseif(isset($_GET['added'])) {
 				echo "<div class='container row notification'>Item Added to Cart</div>";
 			} 
		}

		function getAllItemsSelect() {
			$dat = array();
			//put all products in a select
 			print '<div class="row"><div class="col-sm-4">';
 			print '<small>Select an item to edit:</small><br/><select onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
 			print "<option>-- Select --</option>";
 			//get data
            while ($row = mysqli_fetch_row($this->result_all)) {
            	$data = $row;
			    print '<option value="admin.php?data='.$data[0].'&desc='.$data[1].'&price='.$data[2].'&saleprice='.$data[3].'&img='.$data[4].'&qty='.$data[5].'">'.$data[0]." </a></option>";
            }
            print "</select>";
            print '</div><div class="col-sm-1"><br/><b>OR</b></div><div class="col-sm-4">';
            print "<br/><a class='button' href='admin.php'>Add New Item</a></div></div>";
            //Edit item
            if (isset($_GET['data'])) {
            	$itm = $_GET['data'];
            	$desc = $_GET['desc'];
            	$price = $_GET['price'];
            	$saleprice = $_GET['saleprice'];
            	$qty = $_GET['qty'];
            	$img = $_GET['img'];
            	
            	//inputs for edit item
            	echo "<h2>Edit Item</h2>";
            	echo '<form method="POST" action="admin.php?edit=yes">';
				echo '<label class="label">Item:</label><br/>';
				echo "<input type='text' class='input_width_full' value='".$itm."' name='edititm' required/><br/>";
            	echo '<label class="label">Description:</label><br/>';
            	echo "<input type='text' class='input_width_full' value='".$desc."' name='editdesc' required/><br/>";
            	echo '<label class="label">Reg Price:</label><br/>';
            	echo "<input type='text' class='input_width_full' value='".$price."' name='editprice' required/><br/>";
            	echo '<label class="label">Sale Price:</label><br/>';
            	echo "<input type='text' class='input_width_full' value='".$saleprice."' name='editsaleprice' required/><br/>";
            	echo '<label class="label">Image Path:</label><br/>';
            	echo "<input type='text' class='input_width_full' value='".$img."' name='editimg' required/><br/>";
            	echo '<label class="label">Quantity:</label><br/>';
            	echo "<input type='text' class='input_width_full' value='".$qty."' name='editqty' required/><br/><br/>";
            	echo "<input type='submit' class='button' value='Update Item'/>&nbsp;&nbsp;<a class='button_red' href='admin.php?delete=".$itm."'>Delete Item</a><br/><br/>";
            	echo "</form>";

            } elseif (isset($_GET['delete'])) {
            	//delete selected product
            	$query = "DELETE FROM cookies WHERE product='".$_GET['delete']."'";
  				mysqli_query($this->connect, $query);
            } elseif(isset($_GET['add'])) {
            	//add new product
            	$newitm = $_POST['newitem'];
            	$newdesc = $_POST['newdesc'];
            	$newprice = $_POST['newprice'];
            	$newsaleprice = $_POST['newsaleprice'];
            	$newqty = $_POST['newqty'];
            	$newimg = $_POST['newimg'];

            	$query = "INSERT INTO cookies (product, description, price, saleprice, img, qty) VALUES ('".$newitm."', '".$newdesc."', '".$newprice."', '".$newsaleprice."', '".$newimg."', '".$newqty."');";
  				mysqli_query($this->connect, $query);

            } elseif(isset($_GET['edit'])) {
            	//edit item
            	$edititm = $_POST['edititm'];
            	$editdesc = $_POST['editdesc'];
            	$editprice = $_POST['editprice'];
            	$editsaleprice = $_POST['editsaleprice'];
            	$editqty = $_POST['editqty'];
            	$editimg = $_POST['editimg'];

            	$query = "UPDATE cookies SET product='".$edititm."', description='".$editdesc."', price='".$editprice."', saleprice='".$editsaleprice."', img='".$editimg."', qty='".$editqty."' WHERE product='".$edititm."' ";
            	mysqli_query($this->connect, $query);
            } else {
            	//fields to add new product
            	echo "<h2>Add New Item</h2>";
            	echo '<form method="POST" action="admin.php?add=yes">';
            	echo '<label class="label">Item:</label><br/>';
				echo "<input type='text' class='input_width_full' name='newitem' placeholder='New Cookie' required/><br/>";
				echo '<label class="label">Description:</label><br/>';
            	echo "<input type='text' class='input_width_full' name='newdesc' required/><br/>";
            	echo '<label class="label">Reg Price:</label><br/>';
            	echo "<input type='text' class='input_width_full' name='newprice' placeholder='0.00' required/><br/>";
            	echo '<label class="label">Sale Price:</label><br/>';
            	echo "<input type='text' class='input_width_full' name='newsaleprice' placeholder='0.00' required/><br/>";
            	echo '<label class="label">Image Path:</label><br/>';
            	echo "<input type='text' class='input_width_full' name='newimg' value='ogcookie.png' required/><br/>";
            	echo '<label class="label">Quantity:</label><br/>';
            	echo "<input type='text' class='input_width_full' name='newqty' required/><br/><br/>";
            	echo "<input type='submit' class='button' value='Add Item'/>";
            	echo "</form>";
            	
            	//echo "<br/><br/><a class='button' href='admin.php?add=".$itm."'>Add Item</a><br/><br/>";

            }

		}
	}
?>

