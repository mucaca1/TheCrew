

/**
 * 
 * @param {*} element do ktoreho da text (getElementById)
 * @param {*} page page do database
 * @param {*} language jazyk
 * echo "<div id='tvojeID'><script>initText(document.getElementById('tvojeID'),'".$page_name[0].".username', '".$language."')</script></div>";
 */
function initText(element,page, language){
    console.log(element);
    console.log(page);
    console.log(language);
    $.ajax({
        type: 'GET',
        url: 'http://147.175.121.210:8159/SemestralneZadanie/api.php/text/' + page + "/" + language + "",
        success: function(msg){
        console.log(msg);
        var obj = JSON.parse(msg);
        var texty = [];
        for(i in obj){
                var text = [];
                var keys = Object.keys(obj[i]);
                for(ii in keys){
                    text.push(obj[i][keys[ii]]);
                }
                texty.push(text);
        }
        texty.forEach(el => {
            console.log(el);
            element.innerHTML = el;
        });
    }
});
}