# TheCrew
Zadanie webTech2

Pre vytvorenie novej stránky skopčiť defaultPage.php
Pre vytvorenie textu v dvojjazyčnej forme:

echo "<div id='idText'><script>initText(document.getElementById('idText'),'".$page_name[0]."', '".$language."')</script></div>";

$page_name[0] je nazov suboru. Čiže ak je v databaze odkaz ahoj.somCarovny tak subor v ktorom volate sa musí volať ahoj a treba dopísať .somCarovny čiže => ... '".$page_name[0].".somCarovny', '".$language."')</s ...


API => http://147.175.121.210:4159/SemestralneZadanie/api.php/text/page/language => return JSON textove retazce (reťazec)

Install:
To install https://github.com/PHPMailer/PHPMailer (required) do "composer require phpmailer/phpmailer"

https://datatables.net/ included in JS and CSS

add folder with 777 permissions /uploads
