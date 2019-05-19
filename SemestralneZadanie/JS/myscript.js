function setPoints(data) {
    console.log(data);
    var a = data.id.split('_');
    console.log(a);

    var b = document.getElementById("points_"+a[1]).value;

    console.log(b);
    $.ajax({
        type: 'POST',
        url: 'https://147.175.121.210:4159/SemestralneZadanie/upload.php/admin/' + b + "/" + a[1],
        success: function(msg){
            console.log(msg);
        }
    });

}