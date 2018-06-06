<div class="wrap">
<h3>Agate Payments</h3>
<p style="text-align: left;">
	This Plugin requires you to set up a Agate merchant account.
</p>
<ul>
	<li>Navigate to the Agate <a href="https://agate.services/registration-form/">Sign-up page.</a></li>
</ul>
<br/>
<form action="<?php echo $this->scriptURL; ?>" method="post" id="agate-settings-form">
	<table class="form-table">
		<tr>
			<th>API Token</th>
			<td id='agate_api_token'>
				<label><input type="text" name="agateApiKey" value="<?php echo $this->frm->agateApiKey; ?>" /></label>
			</td>
		</tr>
		<tr valign="top">
      <th>Redirect URL</th>
			<td>
				<label><input type="text" name="agateRedirectURL" value="<?php echo $this->frm->agateRedirectURL; ?>" /></label>
				<p><font size='2'>Put the URL that you want the buyer to be redirected to after payment. This is usually a "Thanks for your order!" page.</font></p><br><br>
				<p><font size='2'><b>NOTE: <br>1. Please make sure you add a "Total" field in your form.</p>
			</td>
		</tr>

	</table>
	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
	<?php wp_nonce_field('save', $this->menuPage . '_wpnonce', false); ?>
	</p>
</form>

</div>
