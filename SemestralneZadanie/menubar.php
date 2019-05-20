<?php
echo "
        <nav id='nav'>
          <ul>
            <li><a href='./" . $page_name[0] . ".php?language=sk' onclick = 'skLang();'>SK</a></li>
            <li><a href='./" . $page_name[0] . ".php?language=en' onclick = 'enLang();'>EN</a></li>
            <li><a href='./welcomePage.php' id='login_user_name'></a></li>
            <li><a href='./authentification.php?logoff=true' id='logoffButton'></a></li>
          </ul>
        </nav>";
?>