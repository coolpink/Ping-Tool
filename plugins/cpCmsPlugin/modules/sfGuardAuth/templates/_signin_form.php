<?php use_helper('I18N') ?>


<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
	<fieldset>
		<label for="username" class="newline">Username:</label>
		<div class="textfield"><input type="text" id="username" name="signin[username]" /></div>

		<label for="password" class="newline">Password:</label>
		<div class="textfield"><input type="password" id="password" name="signin[password]" /></div>

		<div class="remember">
			<?php $routes = $sf_context->getRouting()->getRoutes() ?>
			<?php if (isset($routes['sf_guard_forgot_password'])): ?>
				<a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot password?', null, 'sf_guard') ?></a>
			<?php endif; ?>
			<input type="checkbox" id="rememberme" name="signin[remember]" /><label for="rememberme">Remember me</label>
		</div>
		<?php echo $form["_csrf_token"]->render(); ?>

		<div class="loginbutton">
			<button class="submit">Submit</button>
		</div>

	</fieldset>
</form>