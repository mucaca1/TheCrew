var mainId;
var interval;



function setId(id){
    mainId = id;
}

/**
 * 
 * @param {*} element do ktoreho da text (getElementById)
 * @param {*} page page do database
 * @param {*} language jazyk
 * echo "<div id='tvojeID'><script>initText(document.getElementById('tvojeID'),'".$page_name[0].".username', '".$language."')</script></div>";
 */
function initText(element,page, language){
    /*console.log(element);
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
});*/
}

function uploadChangesButton(obj){
    var cl = obj.className.split(' ');
    var status = obj.id;
    $.ajax({
        type: 'POST',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/uploads/button/' + status + "/" + mainId + "/" + cl[1] ,
        success: function(msg){
            console.log(msg);
    }
    });
}

function uploadChangesValue(obj){
    var cl = obj.className.split(' ');
    var status = obj.value;
    $.ajax({
        type: 'POST',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/uploads/value/' + status + "/" + mainId + "/" + cl[3] ,
        success: function(msg){
            console.log(msg);
    }
    });
}


function getChanges(team, updateOnly){
    console.log(team);

    $.ajax({
        type: 'GET',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/getChanges/' + team + "",
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
                updateTables(element['team_id'], element['subject_name'],element['year'], element['points'], element, i);
            }
            else{
                createDynamicTable(element['team_id'], element['subject_name'],element['year'], element['points'], element, i);
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
        sum += parseInt(points[index].value,10);
    }
    if(sum <= parseInt(document.getElementById(cl[2]).textContent,10)){
        console.log("ok");
        obj.style.border = "thick solid #00FF00";
        uploadChangesValue(obj);
    }
    else{
        console.log("bad");
        obj.style.border = "thick solid #FF0000";
    }
}

function createDynamicTable(team, subject_name, year, points, row, ink){
    
    var h = document.createElement('h3');
    h.innerHTML = subject_name;
    document.getElementById('content').appendChild(h);
    var y = document.createElement('p');
    y.innerHTML = year;
    document.getElementById('content').appendChild(y);
    var p = document.createElement('p');
    p.setAttribute("id", subject_name+team);
    p.innerHTML = points;
    document.getElementById('content').appendChild(p);
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

        //button
        var text2 = "<small></small>";
        if(row[i]["enable"] == true){
            if(row[i]["point"] != null){
                text = "<input type='number' name='' id='" + row[i]["username"] + ink + "i" + "' class = 'enable_for_all member_points_t" + team + ink + " "+subject_name+team+" "+ team + "' value = " + row[i]["point"] + " onchange = 'checkSum(this);'>";
            }
            else{
                text = "<input type='number' name='' id='" + row[i]["username"] + ink + "i" + "' class = 'enable_for_all member_points_t" + team + ink + " "+subject_name+team+" "+ team + "' onchange = 'checkSum(this);'>";
            }
            text2 = "-";
            if(row[i]["agree"] != null){
                if(row[i]["agree"] == false){
                    text2 = "<small >Nesúhlasí</small>";
                    }
                    else{
                        text2 = "<small >Súhlasí<small>";
                    }
            }
            
        }
        else{
            if(row[i]["point"] != null){
                text = "<input type='number' name='points' id='" + row[i]["username"] + ink + "i" + "' class = 'disable_for_you member_points_t" + ink + team + " "+subject_name+team+" "+ team + "' value = " + row[i]["point"] + " onchange = 'checkSum(this);'>";
            }
            else{
                text = "<input type='number' name='points' id='" + row[i]["username"] + ink + "i" + "' class = 'disable_for_you member_points_t" + ink + team + " "+subject_name+team+" "+ team + "' onchange = 'checkSum(this);'>";
            }

            
            
            if(row[i]["button"] != null){
                if(row[i]["button"] == true){
                text2 = "<button id='positive' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Suhlas</button> / <button id='negative' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Nesuhlas</button>";
                }
                else{
                    text2 = "<button id='positive' class = 'disable_for_you "+ team +"'>Suhlas</button> / <button id='negative' class = 'disable_for_you "+ team +"'>Nesuhlas</button>";
                }
            }
            if(row[i]["agree"] != null){
                if(row[i]["agree"] == false){
                    text2 = "<small >Nesúhlasí</small>";
                    }
                    else{
                        text2 = "<small >Súhlasí<small>";
                    }
            }
        }
        t.innerHTML = "<td>" + row[i]["username"] + "</td><td>" + row[i]["email"] + "</td><td>" + text +"</td><td id = '"+row[i]["username"]+ ink + "'>" + text2 + "</td>";
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

function updateTables(team, subject_name, year, points, row, ink){
    var i = 0;
    document.getElementById(subject_name+team).innerHTML = points;
    while(row[i] != null)
    {
        if(row[i]["enable"] == true){
            if(row[i]["point"] != null){
                var input = document.getElementById(row[i]["username"] + ink + "i");
                if(input != null){
                    input.setAttribute("class", "enable_for_all member_points_t" + team + ink + " "+subject_name+team+" "+ team);
                    if(row[i]["point"] != null){
                        input.setAttribute("value", row[i]["point"]);
                    }
                }
            }
            else{
                var input = document.getElementById(row[i]["username"] + ink + "i");
                if(input != null){
                    input.setAttribute("class", "enable_for_all member_points_t" + team + ink + " "+subject_name+team+" "+ team);
                }
            }
            var td = document.getElementById(row[i]["username"] + ink);
            td.innerHTML = "";
            var text2 = "-";
            if(row[i]["button"] != null){
                if(row[i]["button"] == true){
                    text2 = "<button id='positive' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Suhlas</button> / <button id='negative' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Nesuhlas</button>";
                }
                else{
                    text2 = "<button id='positive' class = 'disable_for_you "+ team +"'>Suhlas</button> / <button id='negative' class = 'disable_for_you "+ team +"'>Nesuhlas</button>";
                }
            }
            if(row[i]["agree"] != null){
                if(row[i]["agree"] == false){
                    text2 = "<small >Nesúhlasí</small>";
                    }
                    else{
                        text2 = "<small >Súhlasí<small>";
                    }
            }
            td.innerHTML = text2;
        }
        else{
            var input = document.getElementById(row[i]["username"] + ink + "i");
            if(input != null){
                input.setAttribute("class", "disable_for_you member_points_t" + team + ink + " "+subject_name+team+" "+ team);
                if(row[i]["point"] != null){
                    input.setAttribute("value", row[i]["point"]);
                }
            }
            var td = document.getElementById(row[i]["username"] + ink);
            td.innerHTML = "";

            var text2 = "-";
            if(row[i]["button"] != null){
                if(row[i]["button"] == true){
                    text2 = "<button id='positive' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Suhlas</button> / <button id='negative' class = 'enable_for_all "+ team +"' onclick = 'uploadChangesButton(this);'>Nesuhlas</button>";
                }
                else{
                    text2 = "<button id='positive' class = 'disable_for_you "+ team +"'>Suhlas</button> / <button id='negative' class = 'disable_for_you "+ team +"'>Nesuhlas</button>";
                }
            }
            if(row[i]["agree"] != null){
                if(row[i]["agree"] == false){
                    text2 = "<small >Nesúhlasí</small>";
                    }
                    else{
                        text2 = "<small >Súhlasí<small>";
                    }
            }
            td.innerHTML = text2;
        }
        i++;
    }
    enableButton();
    disableButton();
}