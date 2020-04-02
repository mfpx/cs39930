<div id="login-box">
    <form method="post" action="system/login.php" onsubmit="return do_login();">
        <div id="login">
            Login
        </div>
        Email:<br />
        <input type="email" name="email" class="field" id="email" required><br />
        Password:<br />
        <input type="password" name="password" class="field" id="password" required><br />
        <input type="submit" name="login" value="Submit" id="login_button">
        <p id="loading"><img src="media/5.gif"></p>
    </form>
    <br />
    <a href="reset">Forgotten password?</a>
</div>