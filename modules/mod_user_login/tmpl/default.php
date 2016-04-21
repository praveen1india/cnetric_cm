<?php
// No direct access
defined('_JEXEC') or die; ?>


<div class="User"><?php /*echo $hello; echo "dfdf"; */ ?></div>

<?php
		$User			= JFactory::getuser();
		$UserId			=$User->id;
		$userToken 		= JSession::getFormToken();


	if($UserId)
	{ ?>
		<style>
          p#register
         {
         	display:none !important;
         }
         </style>
<?php }
	else
	{ ?>
		<style>
          p#logout
         {
         	display:none !important;
         }
         </style>

	<?php }
?>


<div id="dd">

					<div style="border:0px solid;min-height:10px;float:right;position: relative;z-index: 999;">
						<div id="reg-right">
							<div id="reg-left">
								<div id="reg-mid">
									<div id="register">
									<p id="register">
									<span class="spce">
									<a href="index.php?option=com_users&view=login" class="s_in">Sign in</a></span><span class="spce">
									<a href="index.php?option=com_users&view=registration" class="reg">Register</a></span>
									</p>
									<p id="logout">
									<span class="spceNmae">
									<span>Hello,<?php echo $User->name ?><span></span></span>
									</span>
									<span class="spce">
									<a onclick="document.location.href='index.php?option=com_users&task=user.logout&<?php echo $userToken ?>=1&return=<?php echo base64_encode(JURI::Root()); ?>'" style="cursor:pointer">Sign out</a>
									</span>
									<span class="spce">
									<a onclick="document.location.href='index.php?option=com_users&view=profile'" style="cursor:pointer">My Account</a>
									</span>
									</p>

									</div>
								</div>
							</div>
						</div>
					 </div>

				</div>

