$(document).ready(function(){
      $.MultiLanguage('./lang/welcomePage_language.json');
    })

function skLang(){
    $.MultiLanguage('./lang/welcomePage_language.json', 'sk');
}

function enLang(){
    $.MultiLanguage('./lang/welcomePage_language.json', 'en')
}