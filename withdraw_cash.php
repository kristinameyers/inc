<?php
 $get_cuser = $db->get_row("select available_cash,email from ".tbl_user." where userId = '".$_SESSION['LOGINDATA']['USERID']."'",ARRAY_A);
 $available_cash = $get_cuser['available_cash'];
?>
<script type="text/javascript">
$(function () {
	$("#dcash_stash").on('click', function () {
		window.location = "<?php echo ru; ?>deposit_cash";
	})
	
	$("#checkout_bank_account,.extbank_account").on('click', function () {
		var checkout_method = this.value;
		if(checkout_method == 'bank_account') {
			document.getElementById('account_number').value='';
			document.getElementById('routing_number').value='';
			$('#test').show();
			$('#test1').hide();
			$("#deps_bank_account").slideDown("slow");
			$("#deps_cardit_card").slideUp("slow");
		} else {	
			$.ajax({
			url : "<?php echo ru;?>process/get_chechach.php?achid="+checkout_method,
			type: "POST",
			dataType:'html',
			success:function(response)	{
				var array = response.split("=");
				routnumber = array[0];
				var achnumber = array[1];
				//alert(routnumber);
				masked=achnumber;
				last_four_digits = masked.substr(6,4);
				card_number = "xx-xxxx-"+last_four_digits;
				//alert(card_number);
				document.getElementById('routing_number').value=routnumber;
				document.getElementById('routing_numbers').value=routnumber;
				document.getElementById('account_number').value=masked;
				document.getElementById('account_numbers').value=card_number;
				$('span[id^="error"]').remove();
				$('#test').hide();
				$('#test1').show();
				$("#deps_cardit_card").slideUp("slow");
				$("#deps_bank_account").slideDown("slow");
				}
			});	
		}
	});
	
	$("#checkout_credit_card,.extcheckout_credit_card").on('click', function () {
		var checkout_method = this.value;
		if(checkout_method == 'credit_card') {
			document.getElementById('cardnumber').value='';
			document.getElementById('cvv').value='';
			document.getElementById('month').value='';
			document.getElementById('year').value='';
			document.getElementById('fname').value='';
			document.getElementById('lname').value='';
			document.getElementById('address1').value='';
			document.getElementById('address2').value='';
			document.getElementById('state').value='';
			document.getElementById('city').value='';
			document.getElementById('zip').value='';
			$("#deps_bank_account").slideUp("slow");
			$("#deps_cardit_card").slideDown("slow");
			$('#original_val').show();
			$('#dumy_val').hide();
		} else {	
			$.ajax({
				url : "<?php echo ru;?>process/get_checkoutcreditcard.php?card_id="+checkout_method,
				type: "GET",
				dataType:'html',
				success:function(response) {
					var array = response.split("=");
						masked=array[0];
						last_four_digits = masked.substr(12,4);
						card_number = "xxxx-xxxx-xxxx-"+last_four_digits;
						pin=array[1];
						exp_month=array[2];
						exp_year=array[3];
						card_type=array[4];
						first_name=array[5];
						last_name=array[6];
						address1=array[7];
						address2=array[8];
						state=array[9];
						city=array[10];
						zip=array[11];
					//alert(card_number);
					document.getElementById('cardnumber').value=masked;
					document.getElementById('cardnumbers').value=card_number;
					document.getElementById('cvv').value=pin;
					document.getElementById('month').value=exp_month;
					document.getElementById('year').value=exp_year;
					document.getElementById('fname').value=first_name;
					document.getElementById('lname').value=last_name;
					document.getElementById('address1').value=address1;
					document.getElementById('address2').value=address2;
					document.getElementById('state').value=state;
					document.getElementById('city').value=city;
					document.getElementById('zip').value=zip;
					$('span[id^="error"]').remove();
					$('#original_val').hide();
					$('#dumy_val').show();
					$("#deps_cardit_card").slideDown("slow");
					$("#deps_bank_account").slideUp("slow");
				}
			});
		}
	});

});

function copy_amount() {
	var amount =  document.getElementById('amount').value;
	if(amount != '') {
	var calculate_tax = Number(amount) / 100 * 6.00;
	document.getElementById('calculate_tax').value="$"+calculate_tax.toFixed(2);
	var add_tax_amount = Number(amount) + Number(calculate_tax);
	var total_amount = add_tax_amount.toFixed(2);
	document.getElementById('total_amount').value="$"+total_amount;
	}
}
</script>
<div class="mid_contant">
	<h2 class="title">Cash Stash<sup>SM:</sup> Transfer Funds</h2>
	<div class="cont_bar" onclick="copy_amount();">
		<div class="cont_bar_inner cont_bar_inner_d cash_stach">
			<?php if(isset($_SESSION['biz_withdraw_err']['withdrawcashstash'])) { ?>
				<script type="text/javascript">
					$(function () {
						$('.overlay').show();
						$('#withdraw_success').toggle( "slow" );
					});
			
					function redirect_func() {
						window.location = "<?php echo ru;?>withdraw_cash";
					}
				</script>
				<div class="overlay" style="display:none"></div>
				<div class="modal" id="withdraw_success" style="display:none">
					<a style="cursor:pointer" onClick="close_div();">
						<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
					</a>
					<img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  />
					<div class="valid_msg">$<?php echo number_format($_SESSION['withdraw']['amount'],'2'); ?> will be withdraw<br />from your cash stash.<br />You have $<?php echo $available_cash; ?> remaining.
						<button class="orange" onclick="redirect_func()" type="submit">Continue</button>
					</div>
				</div>
			<?php } ?>
			<img src="<?php echo ru_resource;?>images/jester_ab.jpg" alt="Jester Image" />
			<div class="regs_form">
				<div class="sugget_left">
					<div class="cash_blnce">
						<span>Cash Stash <br/>Balance</span>
						<div class="cash_amount"><span>$<?php echo $available_cash;?></span></div>
					</div>
				</div>
				<div class="sugget_left">
					<div class="terms">
						<div class="terms_inner_b">
							<div class="squaredFour left">
								<input type="radio" name="cash_stash" id="wcash_stash" value="withdraw" checked="checked" />
								<label class="draw_fund" for="wcash_stash"></label>
							</div>
							<label class="title">Withdraw Funds <span>FROM</span> Cash Stash...</label>
						</div>
					</div>
					<form id="payment-form" class="form-horizontal" method="post" action="<?php echo ru;?>process/process_withdrawcash.php">
						<input name="userId" id="userId" value="<?php echo $_SESSION['LOGINDATA']['USERID'];?>" type="hidden">
						<input name="calculate_tax" id="calculate_tax" value="" type="hidden">
						<input name="WithdrawCash" id="WithdrawCash" value="1" type="hidden">
						<div class="time_flied with_draw" id="doposit_fund">
							<div class="flied">
								<label>Enter Amount</label>
								<input type="text" name="amount" id="amount" value="<?php echo $_SESSION['biz_withdraw']['amount'];?>" placeholder="Amount" onClick="event.stopPropagation()" <?php if($_SESSION['biz_withdraw_err']['amount']) { ?> style="border: 1px solid #ea4e18 !important;" <?php } ?> class="value" />
								<label class="usd">USD</label>
								<?php if($_SESSION['biz_withdraw_err']['amount']) { ?>	
									<div class="modal" id="model_cash">
										<a style="cursor:pointer" onClick="close_div();">
										<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
										</a>
										<img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  />
										<div class="valid_msg"><?php echo $_SESSION['biz_withdraw_err']['amount']; ?></div>
									</div>
								<?php } ?>
							</div>
							<?php $payment_setting = mysql_fetch_array(mysql_query("select * from ".tbl_payment_setting.""));
								if($payment_setting['payment_option'] == '1') { ?>	
									<div class="terms terms_c">
										<?php /*?><span>...AND send to your bank account</span><?php */?>
										<?php if($_SESSION['biz_withdraw_err']['checkout_method']) { ?>	
											<div class="modal" id="model_cash">
												<a style="cursor:pointer" onClick="close_div();">
													<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
												</a>
												<img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  />
												<div class="valid_msg"><?php echo $_SESSION['biz_withdraw_err']['checkout_method']; ?></div>
											</div>
										<?php } ?>
										<?php
											$get_ach = $db->get_results("select ach_number,acchID from ".tbl_achnumber." where userId = '".$_SESSION['LOGINDATA']['USERID']."'",ARRAY_A);
											if($get_ach) {
												foreach($get_ach as $achbnk) {
													$ach_num = decrypt($achbnk['ach_number']);
													$last_four_digits = substr("$ach_num", -4);
													$masked = "xx-xxxx-".$last_four_digits;
										?>
										<div class="terms">
											<div class="terms_inner_b">
												<div class="squaredFour left">
													<input type="radio" name="checkout_method" class="extbank_account" id="extbank_account_<?php echo $achbnk['acchID']; ?>" value="<?php echo $achbnk['acchID']; ?>">
													<label class="deps_account" for="extbank_account_<?php echo $achbnk['acchID']; ?>"></label>
												</div>	
												<label class="title">Use account <?php  echo $masked; ?></label>
											</div>
										</div>
									<?php } } ?>
									<?php /*?><div class="terms_inner_b">
										<div class="squaredFour left">
											<input type="radio" name="checkout_method" id="checkout_bank_account" value="bank_account" <?php if($_SESSION['biz_withdraw']['checkout_method'] == 'bank_account') echo 'checked="checked"'; ?>>
											<label class="deps_account" for="checkout_bank_account"></label>
										</div>	
										<label class="title">Bank Account</label>
									</div><?php */?>
								</div>
								<div class="time_flied" id="deps_bank_account" <?php if($_SESSION['biz_withdraw']['checkout_method'] == 'bank_account') { } else { ?> style="display:none"<?php } ?>>
									<div id="test">
										<div class="flied flied_e">
											<input name="routing_number" id="routing_number" autocomplete="off" maxlength="9" placeholder="Routing Number" <?php if($_SESSION['biz_withdraw_err']['routing_number']) { ?> class="hightlight" <?php } ?> type="text" value="<?php echo $_SESSION['biz_withdraw']['routing_number']; ?>">
											<?php if($_SESSION['biz_withdraw_err']['routing_number']) { ?>	
												<div class="modal" id="model_cash">
													<a style="cursor:pointer" onClick="close_div();">
														<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
													</a>
													<img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  />
													<div class="valid_msg"><?php echo $_SESSION['biz_withdraw_err']['routing_number']; ?></div>
												</div>
											<?php } ?>									
											<input name="account_number" id="account_number" autocomplete="off" maxlength="10" placeholder="Account Number" <?php if($_SESSION['biz_withdraw_err']['account_number']) { ?> class="hightlight" <?php } ?> type="text" value="<?php echo $_SESSION['biz_withdraw']['account_number']; ?>">
											<?php if($_SESSION['biz_withdraw_err']['account_number']) { ?>	
												<div class="modal" id="model_cash">
													<a style="cursor:pointer" onClick="close_div();">
														<img src="<?php echo ru_resource; ?>images/close_icon.png" alt="Closed Icon" />
													</a>
													<img src="<?php echo ru_resource; ?>images/jester_icon_validation.png" alt="Validation Icon"  />
													<div class="valid_msg"><?php echo $_SESSION['biz_withdraw_err']['account_number']; ?></div>
												</div>
											<?php } ?>
										</div>
									</div>
									
								</div>
							<?php } ?>
							<div class="flied">
								<?php /*?><label>Total Deposit</label><?php */?>
								<input type="hidden" name="total_amount" id="total_amount" value="<?php echo $_SESSION['biz_withdraw']['total_amount']; ?>" class="value calcut" placeholder="[calculate total]">
								<?php /*?><label class="usd fee">Plus 6.0% fee</label><?php */?>
							</div>
							<?php 
							  if($payment_setting['payment_option'] == '1')
							  {
							?>
							<div class="flied flied_e">
								<button class="orange" id="credit" type="submit">COMPLETE TRANSACTION</button>
								<?php /*?><input type="submit" value="Complete Transaction" class="orange" /><?php */?>
							</div>
							<?php } ?>
						</div>
					</form>
				</div>
				<div class="sugget_left">
					<div class="terms">
						<div class="terms_inner_b">
							<div class="squaredFour left">
								<input type="radio" name="cash_stash" id="dcash_stash" value="deposit" />
								<label class="doposit_fund" for="dcash_stash"></label>
							</div>
							<label class="title">Deposit Funds <span class="into">INTO</span> Cash Stash</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="http://digitalbush.com/wp-content/uploads/2013/01/jquery.maskedinput-1.3.1.min_.js"></script>											
<script>
jQuery(function($) {
  $("#routing_number").mask("999-999-999");
  $("#routing_numbers").mask("999-999-999");
  $("#account_number").mask("99-9999-9999");
  $("#account_numbers").mask("99-9999-9999");
  $("#cardnumber").mask("9999-9999-9999-9999");
  $("#cardnumbers").mask("9999-9999-9999-9999");
});
</script>		
<?php if($_SESSION['biz_withdraw_err']) { ?>	
<div class="overlay"></div>
<?php } ?>
<?php
unset($_SESSION['biz_withdraw_err']);
unset($_SESSION['biz_withdraw']);
unset($_SESSION['withdraw']['amount']);
?>	