<nav id='nav'>
  <ul>
	<li><a href="<?php $lang->printLanguageToggle("sk") ?>">SK</a></li>
	<li><a href="<?php $lang->printLanguageToggle("en") ?>">EN</a></li>
	<li><a href='./welcomePage.php' id='login_user_name'></a></li>
	<li><a href='./authentification.php?logoff=true' id='logoffButton'><?php $lang->printLabel(['Odhlásiť sa', 'Log off']);?></a></li>
  </ul>
</nav>
