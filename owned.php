<?php
$userId = $_SESSION['LOGINDATA']['USERID'];
?>
<div class="mid_contant">
	<h2 class="title">Control: Your Owned Items</h2>
	<?php include("common/controls_leftmenu.php");?>
	<div class="cont_bar outbox_left outbox_right inbox_right retailer">
		<div class="sort_by">
			<div class="flied search_keyword">
				<label>Sort By:</label>
				<input type="text" name="keyword" id="keyword" placeholder="Enter Keywords">
			</div>
			<div class="your_redeem date_frm">
				<div class="date_flieds">
					<div class="flied">
						<label>From:</label>
						<input type="text" name="day" id="day" placeholder="DD">
						<span>/</span>
						<input type="text" name="month" id="month" placeholder="MM">
						<span>/</span>
						<input type="text" name="year" id="year" placeholder="YY">
						<img src="<?php echo ru_resource;?>images/date_icon_b.jpg" id="buttonHere" alt="Calender Icon" />
					</div>
					<div id="datepicker"></div>
					<div class="flied">
						<label>To:</label>
						<input type="text" name="days" id="days" placeholder="DD">
						<span>/</span>
						<input type="text" name="months" id="months" placeholder="MM">
						<span>/</span>
						<input type="text" name="years" id="years" placeholder="YY">
						<img src="<?php echo ru_resource;?>images/date_icon_b.jpg" id="buttonHeres" alt="Calender Icon" />
					</div>
					<div id="datepickers"></div>
				</div>
				<div class="flied date_submit">
					<input type="submit" name="search_rec" id="search_rec" value="Search" />
				</div>
			</div>
		</div>
		<?php
			if (isset($_SESSION['sort_by'])) {	
				if($_SESSION['sort_by']['by'] == 'dated') {
					$by = "ORDER BY o.".$_SESSION['sort_by']['by'];
				} else {
					$by = "ORDER BY p.".$_SESSION['sort_by']['by'];
				}
				$ad = $_SESSION['sort_by']['ad'];
			}	
		?>
		<ul class="unwrp_list wishlist">
			<li class="title_bar">
				<div class="from"></div>
				<div class="occas items">Item <img src="<?php echo ru_resource;?>images/arrow_j.png" alt="Down Arrow" class="items" />
					<div style="display:none" class="expirat_down" id="item_down">
						<a href="javascript:;" class="expt<?php echo $by == 'p.pro_name' ? ' '.strtolower($ad) : null; ?>" id="pro_name">A - Z</a>
						<a href="javascript:;" class="latest<?php echo $by == 'p.pro_name' ? ' '.strtolower($ad) : null; ?>" id="pro_name">Z - A</a>
					</div>
				</div>
				<div class="occas">Price <img src="<?php echo ru_resource;?>images/arrow_j.png" alt="Down Arrow" class="expirat" />
					<div style="display:none" class="expirat_down" id="expirat_down">
						<a href="javascript:;" class="expt<?php echo $by == 'p.price' ? ' '.strtolower($ad) : null; ?>" id="price">Lowest</a>
						<a href="javascript:;" class="latest<?php echo $by == 'p.price' ? ' '.strtolower($ad) : null; ?>" id="price">Highest</a>
					</div>
				</div>
				<div class="notft">Date Added <img src="<?php echo ru_resource;?>images/arrow_j.png" alt="Down Arrow" class="amount" />
					<div style="display:none" class="expirat_down" id="amount_down">
						<a href="javascript:;" class="expt<?php echo $by == 'o.dated' ? ' '.strtolower($ad) : null; ?>" id="dated">Earliest</a>
						<a href="javascript:;" class="latest<?php echo $by == 'o.dated' ? ' '.strtolower($ad) : null; ?>" id="dated">Latest</a>
					</div>
				</div>
				<div class="resp_btn"></div>
			</li>
			<div id="default_rec">
				<?php
					$love_pro = $db->get_results("select o.own_id,o.proid,o.userId,o.dated,p.proid,p.pro_name,p.price,p.image_code from ".tbl_own." as o, ".tbl_product." as p where p.proid = o.proid and o.userId = '".$userId."' {$by} {$ad}",ARRAY_A);
					if($love_pro) {
						foreach($love_pro as $product) { 
							//$product = $db->get_row("select * from ".tbl_product." where proid = '".$love['proid']."'",ARRAY_A);
							$get_l = "SELECT count( own_it ) AS cnt FROM ".tbl_own." WHERE proid = '".$product['proid']."' GROUP BY proid HAVING Count( own_it )";
							$own_count = $db->get_row($get_l,ARRAY_A);
				?>
				<li class="record">
					<div class="from">
						<div class="counter owned">
							<div class="counter_inner">
								<img src="<?php echo ru_resource;?>images/icon_j.jpg" alt="Owned Icon" />
							</div>
							<span><?php echo $own_count{'cnt'}; ?> People Own It</span>
						</div> 
						<div class="list_img">
							<img src="<?php  get_image($product['image_code']);?>" width="105" height="105" alt="<?php echo ucfirst($product['pro_name']); ?>" />
						</div>
					</div>
					<div class="occas"><?php echo substr(ucfirst($product['pro_name']),0,10); ?></div>
					<div class="occas">$<?php echo number_format($product['price'],2); ?></div>
					<div class="notft">
						<?php 
							$created_timestamp = strtotime($product['dated']);
							$child1 = date('m/d/Y', $created_timestamp);
							echo $child1; 
						?>
					</div>
					<div class="resp_btn">
						<img src="<?php echo ru_resource;?>images/email_icon.jpg" alt="Email Icon" class="hiden email_icon" />
						<a href="javascript:;" onclick="del_ownedlist('<?php echo $product['proid']; ?>','<?php echo $product['own_id']; ?>');" class="orange unwraped">Remove</a>
					</div>
				</li>
				<?php } } ?>
			</div>
			<div id="serach_rec"></div>
		</ul>
	</div>
</div>
<?php 
unset($_SESSION['sort_by']);
unset($_SESSION['sort']);
?>			
<script type="text/javascript">
$(function () {
	$("#search_rec").click(function () {
		var key = $("#keyword").val();
		var day = $("#day").val();
		var month = $("#month").val();
		var year = $("#year").val();
		var days = $("#days").val();
		var months = $("#months").val();
		var years = $("#years").val();
		var myData = "key="+key+"&day="+day+"&month="+month+"&year="+year+"&days="+days+"&months="+months+"&years="+years;
		$.ajax({
			url:"<?php echo ru; ?>process/process_searchowned.php",
			type: "GET",
			data: myData,
			success:function (response) {
				$("#serach_rec").html(response);
				$("#default_rec").hide();
			}
		});
		
	});
});

function del_ownedlist(id,ownid)
{
	var dId = id;
	var uId = '<?php echo $userId; ?>';
	var ownid = ownid;
	$.ajax({
	url: '<?php echo ru;?>process/process_itemselect.php?dID='+dId+'&uId='+uId+'&ownid='+ownid,
	type: 'get', 
	success: function(output) {
	if(output == 'Success')
	{
		window.location = "<?php echo ru?>owned";
	}
	}
	});
}
</script>		