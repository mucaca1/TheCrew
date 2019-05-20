var mainId;
var interval;
var lang;

function setId(id){
    mainId = id;
}

function skLang(){
    lang = "sk";
}

function enLang(){
    lang = "en";
}

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
        url: 'https://147.175.121.210:4159/SemestralneZadanie/api.php/text/' + page + "/" + language + "",
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

function uploadChangesButton(obj){
    var cl = obj.className.split(' ');
    var status = cl[1].split('_')[0];
    var teamids = cl[1].split('_')[1];
    $.ajax({
        type: 'POST',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/uploads/button/' + status + "/" + mainId + "/" + teamids ,
        success: function(msg){
            console.log(msg);
    }
    });
}

function uploadChangesValue(obj){
    var cl = obj.className.split(' ');
    var status = obj.value;
    var st_id = obj.id.split("_");

    $.ajax({
        type: 'POST',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/uploads/value/' + status + "/" + st_id[1] + "/" + cl[1].substring(cl[1].length-1) + "/" + mainId,
        success: function(msg){
            console.log(msg);
    }
    });
}


function getChanges(studentid, updateOnly){
    console.log(studentid);

    $.ajax({
        type: 'GET',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/getChanges/' + studentid + "",
        success: function(msg){
        console.log(msg);
        var obj = JSON.parse(msg);
        if(!updateOnly){
            document.getElementById('content').innerHTML = "";
        }
        let i = 0;
        obj.forEach(element => {
            console.log(element['team_id']);
            console.log(element['subject_name']);
            console.log(element['year']);
            console.log(element['points']);
            console.log(element);
            if(updateOnly){
                updateTables(element['team_id'], element['subject_name'],element['year'], element['points'], element, element['admin_accept']);
            }
            else{
                createDynamicTable(element['team_id'], element['subject_name'],element['year'], element['points'], element, element['admin_accept']);
            }
            i++;
            
        });
    }
    });
}

function enableButton(){
    
    var cells = document.getElementsByClassName("enable_for_all"); 
    for (var i = 0; i < cells.length; i++) { 
        cells[i].disabled = false;
    }
}
function disableButton(){
    var cells = document.getElementsByClassName("disable_for_you"); 
    for (var i = 0; i < cells.length; i++) { 
        cells[i].disabled = true;
    }
}

function checkSum(obj){
    console.log(obj.className);
    var cl = obj.className.split(' ');
    var points = document.getElementsByClassName(cl[1]);
    var sum = 0;
    for(let index = 0; index < points.length; index++)
    {
        if(Number.isInteger(points[index].value)){
            sum += parseInt(points[index].value,10);
        }
        else{
            console.log("bad");
            obj.style.border = "thick solid #FF0000";
            return;
        }
        
    }
    var max = parseInt(document.getElementById("full_points_" + cl[1].split('_')[1]).textContent,10);
    if(sum <= max){
        console.log("ok");
        obj.style.border = "thick solid #00FF00";
        uploadChangesValue(obj);
    }
    else{
        console.log("bad");
        obj.style.border = "thick solid #FF0000";
    }
}

function createDynamicTable(team_id, subject_name, year, points, row, ink){
    
    var h = document.createElement('h3');
    h.innerHTML = subject_name;
    document.getElementById('content').appendChild(h);
    var y = document.createElement('p');
    y.innerHTML = year;
    document.getElementById('content').appendChild(y);
    var p = document.createElement('p');
    p.setAttribute("id", "full_points_"+team_id);
    p.innerHTML = points;
    document.getElementById('content').appendChild(p);
    if(ink != null){
        var p2 = document.createElement('p');
        if(ink == true){
            p2.innerHTML = "Admin suhlasi";
        }
        else{
            p2.innerHTML = "Admin nesúhlasí";
        }
        document.getElementById('content').appendChild(p2);
    }
    
    var table = document.createElement('table');
    table.setAttribute("id", "predmet_table");
    var head = document.createElement('thead');
    head.innerHTML = "<tr><th>Meno</th><th>Email</th><th>Body</th><th>Odsúhlasenie bodov</th></tr>";

    
    table.appendChild(head);
    var body = document.createElement('tbody');

    //document.write("<table border='1' width='200'>")
    //document.write("<tr><th>ID #</th><th>Datum</th><th>Hodnota</th></tr>");
    //Dynamic content --------------------------------------------------------
    var i = 0;
    while(row[i] != null)
    {
        let t = document.createElement('tr');

        var text = "<input type='number' name='points' onchange = 'checkSum(this);'";
        if(row[i]['point'] != null){
            //ak ma body
            text = text + " value='" + row[i]['point'] + "'";
        }

        //nastavenie lass parametrov
        text = text + " id = 'input_" + row[i]['id'] + "_" + team_id + "'"; // input_username_team pre refresh id
        text = text + " class = '" + "input_" + team_id; //zakladny pre id inputu. input_teamID pre checksum.


        if(row[i]['enable'] == true){
            if(ink != null){
                if(ink == true){
                    text = text + " disable_for_you";
                }
                else{
                    text = text + " enable_for_all";
                }
            }
            else{
                text = text + " enable_for_all";
            }
        }
        else{
            text = text + " disable_for_you";
        }


            
        
        text = text + "'>"; //uzavretie class nastaveni a uzavretie input.

        var text2 = "<small>-</small>";

        if(row[i]['button'] != null){
            if(row[i]['button'] == true){
                if(ink != null){
                    //aak admin uzotvoril neviem klikat
                    if(ink == true){
                        text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
                    }
                }
                //je to moj button a mozem nan klikat.
                else{
                    text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
                }
            }
            else{
                //nie je to moj button a mozem sa nan len pozerat.
                text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
            }
        }
        if(row[i]['agree'] != null){
            if(row[i]['agree'] == true){
                    text2 = "<small>Suhlasim</small>"; 
            }
            else{
                text2 = "<small>Nesuhlasim</small>";
            }
        }
        if(row[i]["full_name"] != null){
            if(row[i]["full_name"] != ""){
                t.innerHTML = "<td>" + row[i]["full_name"] + "</td><td>" + row[i]["email"] + "</td><td>" + text +"</td><td id = '"+row[i]["username"]+ team_id + "'>" + text2 + "</td>";
            }
            else{
                t.innerHTML = "<td>" + row[i]["username"] + "</td><td>" + row[i]["email"] + "</td><td>" + text +"</td><td id = '"+row[i]["username"]+ team_id + "'>" + text2 + "</td>";
            }
        }
        else{
            t.innerHTML = "<td>" + row[i]["username"] + "</td><td>" + row[i]["email"] + "</td><td>" + text +"</td><td id = '"+row[i]["username"]+ team_id + "'>" + text2 + "</td>";
        }
        
        body.appendChild(t);
        //document.write("<tr><td>" + (i+1) + "</td><td>" + row[i][0] + "</td><td>" + row[i][1] +"</td></tr>");
        i++;
    }
    table.appendChild(body);
    //Static content  --------------------------------------------------------
    //document.write("</table>")
    document.getElementById('content').appendChild(table);

    enableButton();
    disableButton();
}

function updateTables(team_id, subject_name, year, points, row, ink){
    var i = 0;
    document.getElementById("full_points_"+team_id).innerHTML = points;
    while(row[i] != null)
    {
        //nahraj input.
        var input = document.getElementById("input_" + row[i]['id'] + "_" + team_id); //konkretny input konkretneho cloveka.
        if(row[i]['point'] != null){
            //ak ma body nastav.
            input.setAttribute('value', row[i]['point']);
        }

        //mozem editovat ?
        if(row[i]['enable'] != null){
            if(row[i]['enable'] == true){
                //mozem
                if(ink != null){
                    if(ink == true){
                        input.setAttribute('class', 'disable_for_you input_' + team_id);
                    }
                    else{
                        input.setAttribute('class', 'enable_for_all input_' + team_id);
                    }
                }
                else{
                    input.setAttribute('class', 'enable_for_all input_' + team_id);
                }
            }
            else{
                //nemozem
                input.setAttribute('class', 'disable_for_you input_' + team_id);
            }
        }

        //Tlacidlo alebo text;
        //chcem kolonku kde ma byt tlacidlo alebo text.
        var updatedTd = document.getElementById(row[i]["username"] + team_id);
        updatedTd.innerHTML = "";

        //nanovo nahram info z vytvarania tabulky
        /* ####################################################################### */
        var text2 = "<small>-</small>";

        if(row[i]['button'] != null){
            if(row[i]['button'] == true){
                //je to moj button a mozem nan klikat.
                if(ink != null){
                    if(ink == true){
                        text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
                    }
                    else{
                        text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
                    }
                }
                else{
                    text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'enable_for_all negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
                }
            }
            else{
                //nie je to moj button a mozem sa nan len pozerat.
                text2 = "<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you positive_"+team_id+"' onclick='uploadChangesButton(this)'>Súhlasím</button>/<button id = 'button_"+ row[i]['username'] + "_" + team_id +"' class = 'disable_for_you negative_"+team_id+"' onclick='uploadChangesButton(this)'>Nesúhlasím</button>";
            }
        }
        if(row[i]['agree'] != null){
            if(row[i]['agree'] == true){
                text2 = "<small>Suhlasim</small>"; 
            }
            else{
                text2 = "<small>Nesuhlasim</small>";
            }
        }
        /* ####################################################################### */

        updatedTd.innerHTML = text2;
        i++;
    }
    enableButton();
    disableButton();
}
//pre credentialMgmt
var emailElements = document.getElementsByClassName("emailForm");
function initRadioHandler()
{
    for(var j = 0; j < emailElements.length; j++)
        emailElements[j].style.display = "none";
    var btns = document.getElementsByName("action");
    for(var i = 0; i < btns.length; i++)
    {
        btns[i].onclick = function(){
            if(this.value == "email")
                for(var j = 0; j < emailElements.length; j++)
                    emailElements[j].style.display = "initial";
            else
                for(var j = 0; j < emailElements.length; j++)
                    emailElements[j].style.display = "none";
        };
    }
}