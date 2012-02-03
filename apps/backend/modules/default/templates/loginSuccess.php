<div class="width24">
  <article class="box">
    <header>
        <h2>Login</h2>
    </header>
    <form method="post" action="<?php echo url_for('default/login') ?>" class="login">
      <p><label for="username">Username:</label><input type="text" id="username" name="username" /></p>
      <p><label for="password">Password:</label><input type="password" id="password" name="password" /></p>
      <input type="submit" value="Login" />
    </form>
  </article>
</div>