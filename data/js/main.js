function mode() {
    var a = localStorage.a;
    if (a == 0) {
        $("#av").prop("checked", 1);
    } else if (a == 1) {
        $("#bv").prop("checked", 1);
    }
}

$("#av").click(function() {
    window.localStorage.a = 0;
})

$("#bv").click(function() {
    window.localStorage.a = 1;
})

$("#btn").click(function() {
    document.getElementById("bilidata").innerHTML = "请稍等...";
    document.getElementById("spinner").style.display = "inline-block";
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bilidata").innerHTML = this.responseText;
            document.getElementById("spinner").style.display = "none";
        } else if (this.readyState == 4) {
            document.getElementById("bilidata").innerHTML = "请求失败，状态码：" + this.status;
            document.getElementById("spinner").style.display = "none";
        }
    };
    if ($('#av').prop('checked')) {
        xhttp.open("GET", "./data/bilidata.php?aid=" + $('#hao').val(), true);
    } else {
        xhttp.open("GET", "./data/bilidata.php?bvid=" + $('#hao').val(), true);
    }
    xhttp.send();
})

function key() {
    if (event.keyCode == 13)
        document.getElementById("btn").click();
}

mode();