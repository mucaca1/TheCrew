$(document).ready(function(){
      $.MultiLanguage('./lang/index_language.json');
    })

function skLang(){
    $.MultiLanguage('./lang/index_language.json', 'sk');
}

function enLang(){
    $.MultiLanguage('./lang/index_language.json', 'en')
}