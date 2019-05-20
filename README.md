# TheCrew
Zadanie webTech2

Pre vytvorenie novej stránky skopčiť defaultPage.php
Pre vytvorenie textu v dvojjazyčnej forme:

echo "<div id='idText'><script>initText(document.getElementById('idText'),'".$page_name[0]."', '".$language."')</script></div>";

$page_name[0] je nazov suboru. Čiže ak je v databaze odkaz ahoj.somCarovny tak subor v ktorom volate sa musí volať ahoj a treba dopísať .somCarovny čiže => ... '".$page_name[0].".somCarovny', '".$language."')</s ...


API => http://147.175.121.210:4159/SemestralneZadanie/api.php/text/page/language => return JSON textove retazce (reťazec)

When sending emails:

Beyond the headers of the CSV file as {{head tag}} you can use {{sender_name}} and {{sender_email}}. Those will be relpaced in the template.

CSV file has to include table headers exactly 'meno' and 'Email' (so we know who to send email to) as was part of the provided template.

Install:

To install https://github.com/PHPMailer/PHPMailer (required) do "composer require phpmailer/phpmailer"

https://datatables.net/ included in JS and CSS

https://github.com/ezhmd/PHP-easy-multilanguage included

add folder with 777 permissions /uploads

Import included .sql (in /sql_exports) to database as tables of the same name as filenames.

emailCredentials.php actaualy sends them

