team = [
    "Praca","Matej", "Ľuboš", "Igor", "Greg", "Andrej"
];

praca = [
    "Úloha 1 Pohľad študenta",
    "Úloha 1 Pohľad Administratora",
    "Úloha 2 Pohľad študenta",
    "Úloha 2 Pohľad Administratora",
    "Úloha 3 Generovanie hesiel",
    "Úloha 3 Rozposielanie emailov",
    "Úloha finalizácia",
    "Úloha Prihlasovanie",
    "Úloha multijazyčnosť"
];

log = [
    [false, false, false, true, false],
    [false, false, false, true, false],
    [true, false, false, false, false],
    [false, false, false, false, true],
    [false, false, true, false, false],
    [false, true, false, false, false],
    [true, false, false, false, false],
    [true, false, false, false, false],
    [true, false, false, false, false]
];

function createTable(){
    var  tabulka = document.createElement('table');

    let riad = 10;
    let stl = 6;
    for(let r = 0; r < riad; r++){
        let riadok = document.createElement('tr');
        riadok.style.border = "1px solid black";
        riadok.style.padding = "8px";
        for(let s = 0; s < stl; s++){
            let stlpec = document.createElement('th');
            stlpec.style.border = "1px solid black";
            stlpec.style.padding = "8px";
            if(r == 0){
                let text = document.createTextNode(team[s]);
                stlpec.appendChild(text);
            }
            else if(r > 0 && s == 0){
                let text = document.createTextNode(praca[r-1]);
                stlpec.appendChild(text);
            }
            else{
                let ch = document.createElement('input');
                ch.type = "checkbox";
                if(log[r-1][s-1] == true){
                    ch.checked = true;
                }
                ch.disabled = true;
                stlpec.appendChild(ch);
            }
            
            riadok.appendChild(stlpec);
        }
        tabulka.appendChild(riadok);
    }

    document.getElementById('table').appendChild(tabulka);
}